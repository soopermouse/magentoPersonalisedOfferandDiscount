<?php
/**
 * Created by PhpStorm.
 * User: testuser
 * Date: 31/07/2025
 * Time: 11:50
 */

namespace MagentoServices\PersonalizedBundles\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $table = $setup->getConnection()->newTable(
            $setup->getTable('mycompany_personalizedbundles_accessory')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'product_id',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Product ID'
        )->addColumn(
            'accessory_id',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Accessory ID'
        )->setComment('Accessory Relationships');
        $setup->getConnection()->createTable($table);
        $setup->endSetup();
    }
}