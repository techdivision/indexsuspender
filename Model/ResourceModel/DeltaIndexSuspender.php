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

namespace TechDivision\IndexSuspender\Model\ResourceModel;

use TechDivision\IndexSuspender\Api\Constants;
use TechDivision\IndexSuspender\Api\IndexSuspenderInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * @category   TechDivision
 * @package    IndexSuspender
 * @subpackage Model
 * @copyright  Copyright (c) 2017 TechDivision GmbH <info@techdivision.com> - TechDivision GmbH
 * @link       http://www.techdivision.com/
 * @author     David Führ <d.fuehr@techdivision.com>
 */
class DeltaIndexSuspender extends AbstractDb
{
    protected function _construct()
    {
        $this->_init(Constants::DB_TABLE_NAME, IndexSuspenderInterface::INDEX_SUSPENDER_ID);
    }
}
