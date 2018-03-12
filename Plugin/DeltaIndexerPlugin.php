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

namespace TechDivision\IndexSuspender\Plugin;

use TechDivision\IndexSuspender\Helper\Config;
use TechDivision\IndexSuspender\Exception\PreventJobExecutionException;
use Magento\Cron\Model\Schedule;
use TechDivision\IndexSuspender\Model\DeltaIndexSuspender;
use TechDivision\IndexSuspender\Model\SuspendManager;

/**
 * @category   TechDivision
 * @package    IndexSuspender
 * @subpackage Plugin
 * @copyright  Copyright (c) 2018 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link       http://www.techdivision.com/
 * @author     David Führ <d.fuehr@techdivision.com>
 */
class DeltaIndexerPlugin
{
    /** @var SuspendManager */
    private $suspendManager;

    /** @var Config  */
    private $config;

    /** @var DeltaIndexSuspender  */
    private $deltaIndexSuspender;

    /**
     * @param DeltaIndexSuspender $deltaIndexSuspender
     * @param SuspendManager $suspendManager
     * @param Config $config
     */
    public function __construct(
        DeltaIndexSuspender $deltaIndexSuspender,
        SuspendManager $suspendManager,
        Config $config
    ) {
        $this->deltaIndexSuspender = $deltaIndexSuspender;
        $this->suspendManager = $suspendManager;
        $this->config = $config;
    }

    /**
     * @param Schedule $subject
     * @throws PreventJobExecutionException
     */
    public function beforeTryLockJob(Schedule $subject)
    {
        if ($this->shouldSuspendCronJob($subject)) {
            $subject->setStatus(Schedule::STATUS_MISSED);
            throw new PreventJobExecutionException(__('TechDivision IndexSuspender cancelled job execution.'));
        }
    }

    /**
     * @param Schedule $subject
     * @return bool
     */
    private function shouldSuspendCronJob(Schedule $subject)
    {
        $featureActive = $this->config->getDeltaIndexerEnabled();
        $suspended = $this->suspendManager->isDeltaIndexerSuspended();
        $deltaIndexerJob = in_array($subject->getJobCode(), $this->deltaIndexSuspender->getJobCodesToSuspend(), false);

        return $featureActive && $suspended && $deltaIndexerJob;
    }
}