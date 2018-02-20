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

namespace TechDivision\IndexSuspender\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Registry;
use TechDivision\IndexSuspender\Api\IndexSuspenderInterface;

/**
 * @category   Hallhuber
 * @package    Custom
 * @copyright  Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Vadim Justus <v.justus@techdivision.com>
 */
class DeltaIndexSuspender extends AbstractModel implements IndexSuspenderInterface
{
    /**
     * @var [] Holds the indexer job codes which have to be suspended
     */
    private $jobCodesToSuspend = [
        'indexer_reindex_all_invalid',
        'indexer_update_all_views',
        'indexer_clean_all_changelogs',
    ];

    /**
     * DeltaIndexSuspender constructor.
     * @param Context $context
     * @param Registry $registry
     * @param ResourceModel\DeltaIndexSuspender $resource
     * @param ResourceModel\DeltaIndexSuspender\Collection $resourceCollection
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ResourceModel\DeltaIndexSuspender $resource,
        ResourceModel\DeltaIndexSuspender\Collection $resourceCollection
    ) {
        parent::__construct($context, $registry, $resource, $resourceCollection);
    }

    /**
     * In any case of destruction, resume all indexers
     */
    public function __destruct()
    {
        if ($this->getData('createdByCurrentProcess')) {
            $this->resume();
        }
    }

    /**
     * Returns the job codes to be suspended
     *
     * @return []
     */
    public function getJobCodesToSuspend()
    {
        return $this->jobCodesToSuspend;
    }

    /**
     * Suspends the job code
     */
    public function suspend()
    {
        $this->setData('active', true);
        $this->setData('createdByCurrentProcess', true);
        $this->getResource()->save($this);
    }

    /**
     * Resumes the job code
     */
    public function resume()
    {
        if (!$this->isDeleted()) {
            $this->getResource()->delete($this);
        }
    }
}