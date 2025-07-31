<?php
/**
 * Created by PhpStorm.
 * User: testuser
 * Date: 31/07/2025
 * Time: 11:46
 */

namespace MagentoServices\PersonalizedBundles\Block\Adminhtml\Accessory;

use Magento\Backend\Block\Widget\Grid\Extended;
use Magento\Backend\Block\Template\Context;
use MagentoServices\PersonalizedBundles\Model\ResourceModel\Accessory\CollectionFactory;

class Grid extends Extended
{
    private $collectionFactory;

    public function __construct(
        Context $context,
        \Magento\Backend\Block\Widget\Grid\ColumnSet $columnSet,
        CollectionFactory $collectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $columnSet, $data);
        $this->collectionFactory = $collectionFactory;
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setId('accessoryGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
    }

    protected function _prepareCollection()
    {
        $collection = $this->collectionFactory->create();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn(
            'id',
            [
                'header' => __('ID'),
                'index' => 'id',
            ]
        );
        $this->addColumn(
            'product_id',
            [
                'header' => __('Product ID'),
                'index' => 'product_id',
            ]
        );
        $this->addColumn(
            'accessory_id',
            [
                'header' => __('Accessory ID'),
                'index' => 'accessory_id',
            ]
        );
        return parent::_prepareColumns();
    }
}