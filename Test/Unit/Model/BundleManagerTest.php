<?php
/**
 * Created by PhpStorm.
 * User: testuser
 * Date: 31/07/2025
 * Time: 11:51
 */

namespace MagentoServices\PersonalizedBundles\Test\Unit\Model;

use PHPUnit\Framework\TestCase;
use MagentoServices\PersonalizedBundles\Model\BundleManager;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class BundleManagerTest extends TestCase
{
    private $bundleManager;
    private $customerSessionMock;
    private $productRepositoryMock;
    private $viewedCollectionFactoryMock;
    private $orderItemCollectionFactoryMock;
    private $scopeConfigMock;
    private $accessoryCollectionFactoryMock;

    protected function setUp(): void
    {
        $objectManager = new ObjectManager($this);
        $this->customerSessionMock = $this->createMock(\Magento\Customer\Model\Session::class);
        $this->productRepositoryMock = $this->createMock(\Magento\Catalog\Api\ProductRepositoryInterface::class);
        $this->viewedCollectionFactoryMock = $this->createMock(\Magento\Reports\Model\ResourceModel\Product\CollectionFactory::class);
        $this->orderItemCollectionFactoryMock = $this->createMock(\Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory::class);
        $this->scopeConfigMock = $this->createMock(\Magento\Framework\App\Config\ScopeConfigInterface::class);
        $this->accessoryCollectionFactoryMock = $this->createMock(\MagentoServices\PersonalizedBundles\Model\ResourceModel\Accessory\CollectionFactory::class);

        $this->bundleManager = $objectManager->getObject(
            BundleManager::class,
            [
                'customerSession' => $this->customerSessionMock,
                'productRepository' => $this->productRepositoryMock,
                'viewedCollectionFactory' => $this->viewedCollectionFactoryMock,
                'orderItemCollectionFactory' => $this->orderItemCollectionFactoryMock,
                'scopeConfig' => $this->scopeConfigMock,
                'accessoryCollectionFactory' => $this->accessoryCollectionFactoryMock
            ]
        );
    }

    public function testGetBundleOffersNotLoggedIn()
    {
        $this->customerSessionMock->expects($this->once())
            ->method('isLoggedIn')
            ->willReturn(false);
        $this->assertEmpty($this->bundleManager->getBundleOffers());
    }
}