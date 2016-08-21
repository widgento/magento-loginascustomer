<?php

class Widgento_Login_Helper_Button extends Mage_Core_Helper_Abstract
{
    const BUTTONAREA_HIDDEN = 'hidden';
    const BUTTONAREA_HEADER = 'header';

    /**
     * @return array
     */
    public function getButtonData()
    {
        return array(
            'label'   => $this->getHelper()->__('Log in customer'),
            'onclick' => 'window.open(\''.$this->getUrlModel()->getUrl(
                'adminhtml/widgentologin_index/',
                array('id' => $this->getCustomerId())
                ).'\', \'customer\');',
            );
    }

    /**
     * @return Widgento_Login_Helper_Data
     */
    protected function getHelper()
    {
        return Mage::helper('widgentologin');
    }

    /**
     * @return Mage_Adminhtml_Model_Url
     */
    protected function getUrlModel()
    {
        return Mage::getModel('adminhtml/url');
    }

    /**
     * @return Mage_Customer_Model_Customer
     */
    protected function getCurrentCustomer()
    {
        $currentCustomer = Mage::registry('current_customer');
        if (!$currentCustomer instanceof Mage_Customer_Model_Customer) {
            $currentCustomer = Mage::getModel('customer/customer');
        }

        return $currentCustomer;
    }

    /**
     * @return Mage_Sales_Model_Order
     */
    protected function getCurrentOrder()
    {
        $currentOrder = Mage::registry('current_order');
        if (!$currentOrder instanceof Mage_Sales_Model_Order) {
            $currentOrder = Mage::getModel('sales/order');
        }

        return $currentOrder;
    }

    /**
     * @return int
     */
    protected function getCustomerId()
    {
        if ($this->getCurrentOrder()->getCustomerId()) {
            return $this->getCurrentOrder()->getCustomerId();
        }

        if ($this->getCurrentCustomer()->getId()) {
            return $this->getCurrentCustomer()->getId();
        }

        return 0;
    }

    /**
     * @return string
     */
    public function getButtonArea()
    {
        if (!$this->getHelper()->isLoginAllowed($this->getCustomerId())
            || !$this->getHelper()->getCustomerStoreId($this->getCustomerId())) {
            return static::BUTTONAREA_HIDDEN;
        }

        return static::BUTTONAREA_HEADER;
    }
}