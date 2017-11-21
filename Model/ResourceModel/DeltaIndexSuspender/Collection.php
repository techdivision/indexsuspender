<?php
/**
 * Copyright (c) 2017 TechDivision GmbH
 * All rights reserved
 *
 * This product includes proprietary software developed at TechDivision GmbH, Germany
 * For more information see http://www.techdivision.com/
 *
 * To obtain a valid license for using this software please contact us at
 * license@techdivision.com
 */

namespace TechDivision\IndexSuspender\Model\ResourceModel\DeltaIndexSuspender;

use TechDivision\IndexSuspender\Api\IndexSuspenderInterface;
use TechDivision\IndexSuspender\Model\DeltaIndexSuspender;
use TechDivision\IndexSuspender\Model\ResourceModel\DeltaIndexSuspender as DeltaIndexSuspenderResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * @category   TechDivision
 * @package    IndexSuspender
 * @subpackage Model
 * @copyright  Copyright (c) 2017 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link       http://www.techdivision.com/
 * @author     David Führ <d.fuehr@techdivision.com>
 */
class Collection extends AbstractCollection
{
    /** @var string */
    protected $_idFieldName = IndexSuspenderInterface::INDEX_SUSPENDER_ID;

    protected function _construct()
    {
        $this->_init(DeltaIndexSuspender::class, DeltaIndexSuspenderResource::class);
    }

    /**
     * Delete all entities from database.
     */
    public function deleteAll()
    {
        $this->getConnection()->delete($this->getMainTable());
    }
}
