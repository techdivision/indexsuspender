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

namespace TechDivision\IndexSuspender\Plugin;

use Closure;
use Magento\Framework\Indexer\ActionInterface as IndexAction;
use Magento\Framework\Mview\ActionInterface as MviewAction;
use TechDivision\IndexSuspender\Helper\Config;
use TechDivision\IndexSuspender\Model\SuspendManager;

/**
 * @category   TechDivision
 * @package    IndexSuspender
 * @subpackage Model
 * @copyright  Copyright (c) 2017 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link       http://www.techdivision.com/
 * @author     David Führ <d.fuehr@techdivision.com>
 */
class ActionPlugin
{
    /** @var SuspendManager */
    private $suspendManager;
    /** @var Config */
    private $config;

    /**
     * @param SuspendManager $suspendManager
     */
    public function __construct(
        SuspendManager $suspendManager,
        Config $config
    ) {
        $this->suspendManager = $suspendManager;
        $this->config = $config;
    }

    /**
     * @param MviewAction $subject
     * @param Closure $proceed
     * @param array $ids
     */
    public function aroundExecute(
        MviewAction $subject,
        Closure $proceed,
        array $ids
    ) {
        if (!$this->isSuspended($subject)) {
            $indexSuspender = $this->suspendManager->getNewIndexSuspender();
            $indexSuspender->setType($subject)->suspend();

            $proceed($ids);

            $indexSuspender->resume();
        }
    }

    /**
     * @param IndexAction $subject
     * @param Closure $proceed
     * @param int $id
     */
    public function aroundExecuteRow(
        IndexAction $subject,
        Closure $proceed,
        int $id
    ) {
        if (!$this->isSuspended($subject)) {
            $indexSuspender = $this->suspendManager->getNewIndexSuspender();
            $indexSuspender->setType($subject)->suspend();

            $proceed($id);

            $indexSuspender->resume();
        }
    }

    /**
     * @param IndexAction $subject
     * @param Closure $proceed
     * @param array $ids
     */
    public function aroundExecuteList(
        IndexAction $subject,
        Closure $proceed,
        array $ids
    ) {
        if (!$this->isSuspended($subject)) {
            $indexSuspender = $this->suspendManager->getNewIndexSuspender();
            $indexSuspender->setType($subject)->suspend();

            $proceed($ids);

            $indexSuspender->resume();
        }
    }

    /**
     * @param IndexAction $subject
     * @param Closure $proceed
     */
    public function aroundExecuteFull(
        IndexAction $subject,
        Closure $proceed
    ) {
        if (!$this->isSuspended($subject)) {
            $indexSuspender = $this->suspendManager->getNewIndexSuspender();
            $indexSuspender->setType($subject)->suspend();

            $proceed();

            $indexSuspender->resume();
        }
    }

    private function isSuspended(object $object)
    {
        return $this->config->getIndexerEnabled() && $this->isLocked($object);
    }

    private function isLocked(object $object)
    {
        $indexSuspender = $this->suspendManager->getNewIndexSuspender();
        return $indexSuspender->setTypeByObjectClass($object)->isLocked();
    }
}
