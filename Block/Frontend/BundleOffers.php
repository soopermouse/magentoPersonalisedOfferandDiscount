<?php
/**
 * Created by PhpStorm.
 * User: testuser
 * Date: 31/07/2025
 * Time: 11:45
 */

namespace MagentoServices\PersonalizedBundles\Block\Frontend;

use Magento\Framework\View\Element\Template;
use MagentoServices\PersonalizedBundles\Model\BundleManager;
use Magento\Customer\Model\Session;
use Magento\Reports\Model\ResourceModel\Product\CollectionFactory as ViewedCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory as OrderItemCollectionFactory;

class BundleOffers extends Template
{
    private $bundleManager;
    private $customerSession;
    private $viewedCollectionFactory;
    private $orderItemCollectionFactory;

    public function __construct(
        Template\Context $context,
        BundleManager $bundleManager,
        Session $customerSession,
        ViewedCollectionFactory $viewedCollectionFactory,
        OrderItemCollectionFactory $orderItemCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->bundleManager = $bundleManager;
        $this->customerSession = $customerSession;
        $this->viewedCollectionFactory = $viewedCollectionFactory;
        $this->orderItemCollectionFactory = $orderItemCollectionFactory;
    }

    public function getBundleOffers()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return [];
        }
        $bundles = $this->bundleManager->getBundleOffers();
        $hasPurchasedBundles = $this->hasPurchasedBundles();
        $hasVisitedCategories = $this->hasVisitedCategories();

        // Show bundles in cart only if no category visits or no bundle purchases
        if ($this->getRequest()->getFullActionName() === 'checkout_cart_index' && ($hasPurchasedBundles || $hasVisitedCategories)) {
            return [];
        }

        return $bundles;
    }

    private function hasPurchasedBundles()
    {
        $orders = $this->orderItemCollectionFactory->create()
            ->addFieldToFilter('customer_id', $this->customerSession->getCustomerId());
        foreach ($orders as $order) {
            if (strpos($order->getProduct()->getName(), 'Bundle') !== false) {
                return true;
            }
        }
        return false;
    }

    private function hasVisitedCategories()
    {
        $viewed = $this->viewedCollectionFactory->create()
            ->addFieldToFilter('customer_id', $this->customerSession->getCustomerId());
        return $viewed->getSize() > 0;
    }
}