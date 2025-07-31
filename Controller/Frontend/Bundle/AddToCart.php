<?php
/**
 * Created by PhpStorm.
 * User: testuser
 * Date: 31/07/2025
 * Time: 11:49
 */

namespace MagentoServices\PersonalizedBundles\Controller\Frontend\Bundle;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Checkout\Model\Cart;
use Magento\Catalog\Api\ProductRepositoryInterface;

class AddToCart extends Action
{
    protected $cart;
    protected $productRepository;

    public function __construct(
        Context $context,
        Cart $cart,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct($context);
        $this->cart = $cart;
        $this->productRepository = $productRepository;
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $params = $this->getRequest()->getParams();

        try {
            foreach ($params['products'] as $productId => $options) {
                $product = $this->productRepository->getById($productId);
                $addParams = ['qty' => 1];
                if (!empty($options['super_attribute'])) {
                    $addParams['super_attribute'] = $options['super_attribute'];
                }
                $this->cart->addProduct($product, $addParams);
            }
            $this->cart->save();
            $this->messageManager->addSuccessMessage(__('Bundle added to cart.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('checkout/cart');
    }
}