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

namespace TechDivision\IndexSuspender\Setup;

use TechDivision\IndexSuspender\Api\IndexSuspenderInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use TechDivision\IndexSuspender\Api\Constants;

/**
 * Class InstallSchema
 *
 * @package     TechDivision\IndexSuspender\Setup
 * @file        InstallSchema.php
 * @copyright   Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @site        https://www.techdivision.com/
 * @author      David Führ <d.fuehr@techdivision.com>
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $table = $setup->getConnection()->newTable($setup->getTable(Constants::DB_TABLE_NAME))->addColumn(
            IndexSuspenderInterface::INDEX_SUSPENDER_ID,
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'identity' => true, 'primary' => true, 'unsigned' => true],
            'Index Suspender ID'
        )->setComment('All active suspenders.');

        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}
