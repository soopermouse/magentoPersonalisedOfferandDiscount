<?php
/**
 * Created by PhpStorm.
 * User: testuser
 * Date: 31/07/2025
 * Time: 11:41
 */

namespace MagentoServices\PersonalizedBundles\Model;

use Magento\Framework\Model\AbstractModel;
use MagentoServices\PersonalizedBundles\Api\Data\AccessoryInterface;

class Accessory extends AbstractModel implements AccessoryInterface
{
    protected function _construct()
    {
        $this->_init(\MagentoServices\PersonalizedBundles\Model\ResourceModel\Accessory::class);
    }

    public function getId()
    {
        return $this->getData('id');
    }

    public function setId($id)
    {
        return $this->setData('id', $id);
    }

    public function getProductId()
    {
        return $this->getData('product_id');
    }

    public function setProductId($productId)
    {
        return $this->setData('product_id', $productId);
    }

    public function getAccessoryId()
    {
        return $this->getData('accessory_id');
    }

    public function setAccessoryId($accessoryId)
    {
        return $this->setData('accessory_id', $accessoryId);
    }
}