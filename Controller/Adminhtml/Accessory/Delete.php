<?php
/**
 * Created by PhpStorm.
 * User: testuser
 * Date: 31/07/2025
 * Time: 11:49
 */
namespace MagentoServices\PersonalizedBundles\Controller\Adminhtml\Accessory;

use Magento\Backend\App\Action;
use MagentoServices\PersonalizedBundles\Api\AccessoryRepositoryInterface;

class Delete extends Action
{
    protected $accessoryRepository;

    public function __construct(
        Action\Context $context,
        AccessoryRepositoryInterface $accessoryRepository
    ) {
        parent::__construct($context);
        $this->accessoryRepository = $accessoryRepository;
    }

    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $accessory = $this->accessoryRepository->getById($id);
            $this->accessoryRepository->delete($accessory);
            $this->messageManager->addSuccessMessage(__('Accessory deleted successfully.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $resultRedirect->setPath('mycompany_personalizedbundles/accessory/index');
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MagentoServices_PersonalizedBundles::accessory');
    }
}