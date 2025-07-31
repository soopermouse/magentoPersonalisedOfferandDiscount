<?php
/**
 * Created by PhpStorm.
 * User: testuser
 * Date: 31/07/2025
 * Time: 11:47
 */

namespace MagentoServices\PersonalizedBundles\Controller\Adminhtml\Accessory;

use Magento\Backend\App\Action;
use Magento\Framework\View\Result\PageFactory;
use MagentoServices\PersonalizedBundles\Api\AccessoryRepositoryInterface;

class Edit extends Action
{
    protected $resultPageFactory;
    protected $accessoryRepository;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        AccessoryRepositoryInterface $accessoryRepository
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->accessoryRepository = $accessoryRepository;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('MagentoServices_PersonalizedBundles::accessory');
        $resultPage->getConfig()->getTitle()->prepend($id ? __('Edit Accessory') : __('Add Accessory'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MagentoServices_PersonalizedBundles::accessory');
    }
}