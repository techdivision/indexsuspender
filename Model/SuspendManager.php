<?php
/**
 * Copyright (c) 2017 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * All rights reserved
 *
 * This product includes proprietary software developed at TechDivision GmbH, Germany
 * For more information see http://www.techdivision.com/
 *
 * To obtain a valid license for using this software please contact us at
 * license@techdivision.com
 */

namespace TechDivision\IndexSuspender\Model;

use Magento\Framework\Mview\View\StateInterface;
use TechDivision\IndexSuspender\Model\IndexSuspenderFactory;
use Magento\Framework\Mview\View\CollectionFactory as ViewCollectionFactory;
use Magento\Framework\Mview\View\Collection as ViewCollection;
use Magento\Framework\Mview\ViewInterface;

/**
 * @category   TechDivision
 * @package    IndexSuspender
 * @subpackage Model
 * @copyright  Copyright (c) 2017 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link       http://www.techdivision.com/
 * @author     David Führ <d.fuehr@techdivision.com>
 */
class SuspendManager
{
    /** @var  ViewCollection */
    private $viewCollection;
    /** @var  IndexSuspenderFactory */
    private $indexSuspenderFactory;

    /**
     * @param ViewCollectionFactory $viewCollectionFactory
     * @param IndexSuspenderFactory $indexSuspenderFactory
     */
    public function __construct(
        ViewCollectionFactory $viewCollectionFactory,
        IndexSuspenderFactory $indexSuspenderFactory
    ) {
        $this->viewCollection = $viewCollectionFactory->create();
        $this->indexSuspenderFactory = $indexSuspenderFactory;
    }

    /**
     * @return IndexSuspender
     */
    public function getNewIndexSuspender()
    {
        return $this->indexSuspenderFactory->create();
    }

    /**
     * @param $type
     * @return bool
     */
    public function isIndexSuspended($type)
    {
        return $this->getNewIndexSuspender()->setType($type)->isLocked();
    }

    /**
     * Flush all active changelog tables and reset according mview status.
     */
    public function flushAll()
    {
        $this->flush([]);
    }

    /**
     * Flush given active changelog tables and reset according mview status.
     *
     * @param string|string[] $viewCodes
     */
    public function flush($viewCodes = [])
    {
        if (!is_array($viewCodes)) {
            $viewCodes = [$viewCodes];
        }

        foreach ($this->getMViews($viewCodes) as $view) {

            if ($view->getState()->getMode() == StateInterface::MODE_ENABLED) {

                $state = $view->getState();
                $changelog = $view->getChangelog();
                $state->setVersionId($changelog->getVersion());
                $state->save();
            }
        }
    }

    /**
     * @param string[] $viewCodes
     *
     * @return ViewInterface[]
     */
    private function getMViews($viewCodes)
    {
        $allViews = $this->viewCollection->getItemsByColumnValue('group', 'indexer');

        return $this->filterMViews($allViews, $viewCodes);
    }

    /**
     * @param ViewInterface[] $allViews
     * @param string[] $viewCodes
     * @return ViewInterface[]
     */
    private function filterMViews($allViews, $viewCodes)
    {
        if (!$viewCodes) {
            return $allViews;
        }

        $viewCodesFlipped = array_flip($viewCodes);

        foreach ($allViews as $key => $view) {
            if (!isset($viewCodesFlipped[$view->getId()])) {
                unset($allViews[$key]);
            }
        }

        return $allViews;
    }
}
