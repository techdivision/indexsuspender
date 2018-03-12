<?php
/**
 * Copyright (c) 2018 TechDivision GmbH
 * All rights reserved
 *
 * This product includes proprietary software developed at TechDivision GmbH, Germany
 * For more information see http://www.techdivision.com/
 *
 * To obtain a valid license for using this software please contact us at
 * license@techdivision.com
 */

namespace TechDivision\IndexSuspender\Cron;

use Magento\Framework\App\Config\ScopeConfigInterface;
use TechDivision\IndexSuspender\Model\ResourceModel\DeltaIndexSuspender\Collection;
use TechDivision\IndexSuspender\Model\ResourceModel\DeltaIndexSuspender\CollectionFactory;

/**
 * @category   TechDivision
 * @package    IndexSuspender
 * @subpackage Cron
 * @copyright  Copyright (c) 2018 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link       http://www.techdivision.com/
 * @author     David Führ <d.fuehr@techdivision.com>
 */
class ReleaseSuspenders
{
    const CONFIG_RELEASE_PERIOD = 'techdivision_indexsuspender/general/release_suspender_period';

    /** @var ScopeConfigInterface */
    private $scopeConfig;
    /** @var Collection  */
    private $suspenderCollection;

    /**
     * ReleaseSuspenders constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        CollectionFactory $collectionFactory
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->suspenderCollection = $collectionFactory->create();
    }

    /**
     * Releases suspenders that are older than configured period
     *
     * @return void
     */
    public function execute()
    {
        $period = $this->scopeConfig->getValue(self::CONFIG_RELEASE_PERIOD);
        $this->suspenderCollection->deleteBefore($period);
    }
}
