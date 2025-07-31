<?php
/**
 * Created by PhpStorm.
 * User: testuser
 * Date: 31/07/2025
 * Time: 11:42
 */

namespace MagentoServices\PersonalizedBundles\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use MagentoServices\PersonalizedBundles\Api\AccessoryRepositoryInterface;
use MagentoServices\PersonalizedBundles\Model\ResourceModel\Accessory\CollectionFactory;

class AccessoryRepository implements AccessoryRepositoryInterface
{
    private $collectionFactory;
    private $searchResultsFactory;

    public function __construct(
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory
    ) {
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    public function getById($id)
    {
        $accessory = $this->collectionFactory->create()->getItemById($id);
        if (!$accessory) {
            throw new \Magento\Framework\Exception\NoSuchEntityException(__('Accessory with ID %1 does not exist.', $id));
        }
        return $accessory;
    }

    public function save(\MagentoServices\PersonalizedBundles\Api\Data\AccessoryInterface $accessory)
    {
        $accessory->save();
        return $accessory;
    }

    public function delete(\MagentoServices\PersonalizedBundles\Api\Data\AccessoryInterface $accessory)
    {
        $accessory->delete();
    }

    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->collectionFactory->create();
        // Apply search criteria (e.g., filters, sorting) if needed
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }
}