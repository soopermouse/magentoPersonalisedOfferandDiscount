<?php
/**
 * Created by PhpStorm.
 * User: testuser
 * Date: 31/07/2025
 * Time: 11:40
 */

namespace MyCompany\PersonalizedBundles\Api\Data;

interface AccessoryInterface
{
    public function getId();
    public function setId($id);
    public function getProductId();
    public function setProductId($productId);
    public function getAccessoryId();
    public function setAccessoryId($accessoryId);
}