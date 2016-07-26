<?php

class Widgento_Login_Helper_Data extends Mage_Core_Helper_Abstract
{
    const ACL_CUSTOMER_WIDGENTOLOGIN = 'customer/widgentologin';

    /**
     * @param int $customerId
     * @return int
     */
    public function getCustomerStoreId($customerId)
    {
        if (!$customerId) {
            return false;
        }
    
        $customer = $this->getCustomerModel()->load($customerId);

        if ($customer->getStoreId()) {
            $customerStore = $this->getStoreById($customer->getStoreId());

            if ($customerStore->getId() && $customerStore->getIsActive()) {
                return $customer->getStoreId();
            }
        }
    
        if ($customer->getWebsiteId()) {
            $customerWebsite = $this->getWebsiteById($customer->getWebsiteId());

            /* @var $websiteStore Mage_Core_Model_Store */
            foreach ($customerWebsite->getStores() as $websiteStore) {
                if ($websiteStore->getIsActive()) {
                    return $websiteStore->getId();
                }
            }
        }

        if ($this->getCustomerConfigShare()->isGlobalScope()) {
            return $this->getDefaultStoreView()->getId();
        }
    }

    /**
     * @return Mage_Customer_Model_Config_Share
     */
    protected function getCustomerConfigShare() {
        return Mage::getSingleton('customer/config_share');
    }

    /**
     * @return Mage_Core_Model_Store
     */
    protected function getDefaultStoreView() {
        return Mage::app()->getDefaultStoreView();
    }

    /**
     * @param int $storeId
     * @return Mage_Core_Model_Store
     */
    protected function getStoreById($storeId) {
        return Mage::app()->getStore($storeId);
    }

    /**
     * @param int $websiteId
     * @return Mage_Core_Model_Website
     */
    protected function getWebsiteById($websiteId) {
        return Mage::app()->getWebsite($websiteId);
    }

    /**
     * @return Mage_Customer_Model_Customer
     */
    protected function getCustomerModel() {
        return Mage::getModel('customer/customer');
    }

    /**
     * @return Mage_Admin_Model_Session
     */
    protected function getAdminSession() {
        return Mage::getSingleton('admin/session');
    }

    /**
     * @param string $event
     * @param array $data
     * @return Mage_Core_Model_App
     */
    protected function dispatchEvent($event, array $data) {
        return Mage::dispatchEvent($event, $data);
    }

    /**
     * @return Varien_Object
     */
    protected function getEventTransport() {
        return new Varien_Object(array(
            'disable' => false,
        ));
    }

    /**
     * @param int $customerId
     * @return bool
     */
    public function isLoginAllowed($customerId) {
        $transport = $this->getEventTransport();
        $this->dispatchEvent('widgentologin_disable', array(
            'transport'   => $transport,
            'customer_id' => $customerId,
        ));

        return ($this->getAdminSession()->isAllowed(self::ACL_CUSTOMER_WIDGENTOLOGIN) || $transport->getDisable());
    }
}
