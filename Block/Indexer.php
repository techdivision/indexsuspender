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

namespace TechDivision\IndexSuspender\Block;

use Magento\Framework\View\Element\Template;
use TechDivision\IndexSuspender\Model\ResourceModel\DeltaIndexSuspender\CollectionFactory;

/**
 * @category   TechDivision
 * @package    IndexSuspender
 * @subpackage Block
 * @copyright  Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @link       http://www.techdivision.com/
 * @author     Martin Eisenführer <m.eisenfuehrer@techdivision.com>
 */
class Indexer extends Template
{
    /** @var  CollectionFactory */
    private $deltaIndexSuspenderCollection;

    /**
     * Indexer constructor.
     *
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        array $data = [])
    {
        parent::__construct($context, $data);
        $this->deltaIndexSuspenderCollection = $collectionFactory->create();
    }

    /**
     * @return int
     */
    public function countSuspender()
    {
        $collection = $this->deltaIndexSuspenderCollection->load();
        return count($collection);
    }

    /**
     * @return mixed
     */
    public function getLastSuspender()
    {
        $this->deltaIndexSuspenderCollection->setOrder('created_at');
        $this->deltaIndexSuspenderCollection->setPageSize(1);
        $last = $this->deltaIndexSuspenderCollection->getFirstItem();
        return $last->getData('created_at');
    }

    /**
     * @return string
     */
    public function getCleanSuspenderUrl()
    {
        return $this->getUrl('indexsuspender/cache/cleanSuspender');
    }
}