<?php
/**
 * Created by PhpStorm.
 * User: testuser
 * Date: 31/07/2025
 * Time: 11:43
 */

namespace MyCompany\PersonalizedBundles\Model\ResourceModel\Accessory;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            \MyCompany\PersonalizedBundles\Model\Accessory::class,
            \MyCompany\PersonalizedBundles\Model\ResourceModel\Accessory::class
        );
    }
}