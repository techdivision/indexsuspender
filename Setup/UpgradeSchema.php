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

namespace TechDivision\IndexSuspender\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use TechDivision\IndexSuspender\Api\Constants;

/**
 * Class UpgradeSchema
 *
 * @package     TechDivision\IndexSuspender\Setup
 * @file        InstallSchema.php
 * @copyright   Copyright (c) 2018 TechDivision GmbH (http://www.techdivision.com)
 * @site        https://www.techdivision.com/
 * @author      Martin Eisenführer <m.eisenfuehrer@techdivision.com>
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.3.0', '<')) {
            $this->addDatetimeColumnToSuspenderTable($setup);
        }

        if (version_compare($context->getVersion(), '0.4.0', '<')) {
            $this->addExternalKeyColumnToSuspenderTable($setup);
        }

        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @return void
     */
    protected function addDatetimeColumnToSuspenderTable(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable(Constants::DB_TABLE_NAME),
            'created_at',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                'nullable' => false,
                'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT,
                'comment' => 'Created At'
            ]
        );
    }

    /**
     * @param SchemaSetupInterface $setup
     * @return void
     */
    protected function addExternalKeyColumnToSuspenderTable(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable(Constants::DB_TABLE_NAME),
            'externalkey',
            [
                'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'length' => 100,
                'comment' => 'Key or ID for external use (eg. Pipelines)'
            ]
        );
    }
}