<?php
/**
 * Created by PhpStorm.
 * User: testuser
 * Date: 31/07/2025
 * Time: 11:43
 */

<?php
namespace MagentoServices\PersonalizedBundles\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Accessory extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('mycompany_personalizedbundles_accessory', 'id');
    }
}