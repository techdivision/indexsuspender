<?php
/**
 * Copyright (c) 2018 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
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
use TechDivision\IndexSuspender\Model\ResourceModel\DeltaIndexSuspender\Collection;
use TechDivision\IndexSuspender\Model\ResourceModel\DeltaIndexSuspender\CollectionFactory;
use Magento\Framework\Mview\View\CollectionFactory as ViewCollectionFactory;
use Magento\Framework\Mview\View\Collection as ViewCollection;
use Magento\Framework\Mview\ViewInterface;

/**
 * @category   TechDivision
 * @package    IndexSuspender
 * @subpackage Model
 * @copyright  Copyright (c) 2018 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link       http://www.techdivision.com/
 * @author     David Führ <d.fuehr@techdivision.com>
 */
class SuspendManager
{
    /**
     * Holds the max sql int value possible
     */
    const SQL_INT_MAX = 4294967295;

    /** @var  Collection */
    private $deltaIndexSuspenderCollection;

    /** @var  ViewCollection */
    private $viewCollection;

    /** @var  DeltaIndexSuspenderFactory */
    private $deltaIndexSuspenderFactory;

    /**
     * @param CollectionFactory $collectionFactory
     * @param ViewCollectionFactory $viewCollectionFactory
     * @param DeltaIndexSuspenderFactory $deltaIndexSuspenderFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ViewCollectionFactory $viewCollectionFactory,
        DeltaIndexSuspenderFactory $deltaIndexSuspenderFactory
    ) {
        $this->deltaIndexSuspenderCollection = $collectionFactory->create();
        $this->viewCollection = $viewCollectionFactory->create();
        $this->deltaIndexSuspenderFactory = $deltaIndexSuspenderFactory;
    }

    /**
     * @return bool
     */
    public function isDeltaIndexerSuspended()
    {
        $collection = $this->deltaIndexSuspenderCollection->load();

        return (bool)count($collection);
    }

    /**
     * @return DeltaIndexSuspender
     */
    public function getNewDeltaIndexSuspender()
    {
        return $this->deltaIndexSuspenderFactory->create();
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
    
    /**
     * Clears all suspenders from all processes.
     */
    public function resumeAll()
    {
        $this->deltaIndexSuspenderCollection->deleteAll();
    }
}