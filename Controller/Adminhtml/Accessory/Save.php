<?php
/**
 * Created by PhpStorm.
 * User: testuser
 * Date: 31/07/2025
 * Time: 11:48
 */

namespace MagentoServices\PersonalizedBundles\Controller\Adminhtml\Accessory;

use Magento\Backend\App\Action;
use MagentoServices\PersonalizedBundles\Api\AccessoryRepositoryInterface;
use MagentoServices\PersonalizedBundles\Api\Data\AccessoryInterfaceFactory;

class Save extends Action
{
    protected $accessoryRepository;
    protected $accessoryFactory;

    public function __construct(
        Action\Context $context,
        AccessoryRepositoryInterface $accessoryRepository,
        AccessoryInterfaceFactory $accessoryFactory
    ) {
        parent::__construct($context);
        $this->accessoryRepository = $accessoryRepository;
        $this->accessoryFactory = $accessoryFactory;
    }

    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        $resultRedirect = $this->resultRedirectFactory->create();

        try {
            $accessory = $this->accessoryFactory->create();
            if (isset($data['id']) && $data['id']) {
                $accessory = $this->accessoryRepository->getById($data['id']);
            }
            $accessory->setProductId($data['product_id']);
            $accessory->setAccessoryId($data['accessory_id']);
            $this->accessoryRepository->save($accessory);
            $this->messageManager->addSuccessMessage(__('Accessory saved successfully.'));
            return $resultRedirect->setPath('mycompany_personalizedbundles/accessory/index');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect->setPath('mycompany_personalizedbundles/accessory/edit', ['id' => $data['id'] ?? null]);
        }
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MagentoServices_PersonalizedBundles::accessory');
    }
}