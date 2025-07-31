<?php
/**
 * Created by PhpStorm.
 * User: testuser
 * Date: 31/07/2025
 * Time: 11:44
 */

namespace MagentoServices\PersonalizedBundles\Model;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Reports\Model\ResourceModel\Product\CollectionFactory as ViewedCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory as OrderItemCollectionFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use MagentoServices\PersonalizedBundles\Model\ResourceModel\Accessory\CollectionFactory as AccessoryCollectionFactory;

class BundleManager
{
    private $customerSession;
    private $productRepository;
    private $viewedCollectionFactory;
    private $orderItemCollectionFactory;
    private $scopeConfig;
    private $accessoryCollectionFactory;

    public function __construct(
        Session $customerSession,
        ProductRepositoryInterface $productRepository,
        ViewedCollectionFactory $viewedCollectionFactory,
        OrderItemCollectionFactory $orderItemCollectionFactory,
        ScopeConfigInterface $scopeConfig,
        AccessoryCollectionFactory $accessoryCollectionFactory
    ) {
        $this->customerSession = $customerSession;
        $this->productRepository = $productRepository;
        $this->viewedCollectionFactory = $viewedCollectionFactory;
        $this->orderItemCollectionFactory = $orderItemCollectionFactory;
        $this->scopeConfig = $scopeConfig;
        $this->accessoryCollectionFactory = $accessoryCollectionFactory;
    }

    public function getBundleOffers()
    {
        $bundles = [];
        if (!$this->customerSession->isLoggedIn()) {
            return $bundles;
        }

        $customerId = $this->customerSession->getCustomerId();
        $viewedProducts = $this->getMostViewedProducts($customerId);
        $purchasedProducts = $this->getPurchasedProducts($customerId);
        $accessories = $this->getAccessoriesForProducts($purchasedProducts);

        // Bundle Type 1: Three most-viewed products (not purchased)
        if ($this->scopeConfig->getValue('personalized_bundles/general/bundle_type_1_enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
            $mostViewedNotPurchased = array_diff(array_keys($viewedProducts), array_keys($purchasedProducts));
            if (count($mostViewedNotPurchased) >= 3) {
                $bundleProducts = array_slice($mostViewedNotPurchased, 0, 3);
                $bundles[] = $this->createBundle($bundleProducts, 'Most Viewed Products Bundle', 0.8);
            }
        }

        // Bundle Type 2: Three accessories for a purchased product
        if ($this->scopeConfig->getValue('personalized_bundles/general/bundle_type_2_enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
            foreach ($purchasedProducts as $productId => $product) {
                if (isset($accessories[$productId]) && count($accessories[$productId]) >= 3) {
                    $bundleProducts = array_slice($accessories[$productId], 0, 3);
                    $bundles[] = $this->createBundle($bundleProducts, 'Accessories for ' . $product->getName(), 0.8, true);
                }
            }
        }

        // Bundle Type 3: One most-viewed product + two accessories
        if ($this->scopeConfig->getValue('personalized_bundles/general/bundle_type_3_enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE)) {
            $mostViewedNotPurchased = array_diff(array_keys($viewedProducts), array_keys($purchasedProducts));
            if (!empty($mostViewedNotPurchased)) {
                $mainProductId = reset($mostViewedNotPurchased);
                $mainProduct = $this->productRepository->getById($mainProductId);
                if (isset($accessories[$mainProductId]) && count($accessories[$mainProductId]) >= 2) {
                    $bundleProducts = [$mainProductId => $mainProduct] + array_slice($accessories[$mainProductId], 0, 2);
                    $bundles[] = $this->createBundle($bundleProducts, 'Most Viewed + Accessories Bundle', 0.8, true);
                }
            }
        }

        return $bundles;
    }

    private function getMostViewedProducts($customerId)
    {
        $collection = $this->viewedCollectionFactory->create()
            ->addViewsCount()
            ->addFieldToFilter('customer_id', $customerId)
            ->setOrder('views_count', 'DESC')
            ->setPageSize(10);
        $products = [];
        foreach ($collection as $item) {
            $products[$item->getProductId()] = $this->productRepository->getById($item->getProductId());
        }
        return $products;
    }

    private function getPurchasedProducts($customerId)
    {
        $collection = $this->orderItemCollectionFactory->create()
            ->addFieldToFilter('customer_id', $customerId);
        $products = [];
        foreach ($collection as $item) {
            $products[$item->getProductId()] = $this->productRepository->getById($item->getProductId());
        }
        return $products;
    }

    private function getAccessoriesForProducts($products)
    {
        $accessoryCollection = $this->accessoryCollectionFactory->create()
            ->addFieldToFilter('product_id', ['in' => array_keys($products)]);
        $accessories = [];
        foreach ($accessoryCollection as $accessory) {
            $accessoryId = $accessory->getAccessoryId();
            $productId = $accessory->getProductId();
            $accessories[$productId][] = $this->productRepository->getById($accessoryId);
        }
        return $accessories;
    }

    private function createBundle($productIds, $name, $discountFactor, $allowVariants = false)
    {
        $totalPrice = 0;
        $products = [];
        foreach ($productIds as $productId => $product) {
            $products[$productId] = [
                'product' => $product,
                'is_configurable' => $product->getTypeId() === 'configurable',
            ];
            $totalPrice += $product->getPrice();
        }
        return [
            'name' => $name,
            'products' => $products,
            'total_price' => $totalPrice,
            'bundle_price' => $totalPrice * $discountFactor,
            'allow_variants' => $allowVariants
        ];
    }
}