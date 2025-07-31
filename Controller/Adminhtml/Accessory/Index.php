<?php
/**
 * Created by PhpStorm.
 * User: testuser
 * Date: 31/07/2025
 * Time: 11:46
 */

namespace MagentoServices\PersonalizedBundles\Controller\Adminhtml\Accessory;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected $resultPageFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('MagentoServices_PersonalizedBundles::accessory');
        $resultPage->getConfig()->getTitle()->prepend(__('Manage Accessories'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MagentoServices_PersonalizedBundles::accessory');
    }
}