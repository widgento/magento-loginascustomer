<?php
 
class Widgento_Login_IndexController extends Mage_Core_Controller_Front_Action
{
    const REDIRECT_PATH = 'customer/account';
    const REQUEST_HASH  = 'id';

    /**
     * @return Widgento_Login_Helper_Config
     */
    protected function getConfigHelper()
    {
        return Mage::helper('widgentologin/config');
    }

    /**
     * @return Widgento_Login_Model_Login
     */
    protected function getLoginModel()
    {
        return Mage::getModel('widgentologin/login');
    }

    /**
     * @return Mage_Customer_Model_Session
     */
    protected function getCustomerSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * @return Mage_Persistent_Model_Session
     */
    protected function getPersistentSession()
    {
        return Mage::getSingleton('persistent/session');
    }

    /**
     * @return Mage_Core_Model_Store
     */
    protected function getCurrentStore()
    {
        return Mage::app()->getStore();
    }

    /**
     * @return array
     */
    protected function getClearSingletonsList()
    {
        return array(
            Mage::getSingleton('catalog/session'), 
            Mage::getSingleton('core/session'), 
            Mage::getSingleton('customer/session'), 
            Mage::getSingleton('newsletter/session'), 
            Mage::getSingleton('paypal/session'), 
            Mage::getSingleton('paypal/session'), 
            Mage::getSingleton('reports/session'), 
            Mage::getSingleton('review/session'), 
            Mage::getSingleton('wishlist/session'), 
            Mage::getSingleton('catalogsearch/session'), 
            Mage::getSingleton('paypaluk/session'), 
        );
    }

    public function indexAction()
    {
        $hash     = $this->getRequest()->getParam(self::REQUEST_HASH);
        $login    = $this->getLoginModel()->load($hash, 'login_hash');
        $isActive = $login->getIsActive();

        if (!$this->getConfigHelper()->isSaveLogs()) {
            $login->truncate();
        }
        else {
            $login
                ->setStoreId($this->getCurrentStore()->getId())
                ->setLoginHash('')
                ->setIsActive(0)
                ->save();
        }

        if ($isActive && $login->getCustomerId()) {
            Mage::dispatchEvent('widgentologin_before_login', array(
                'customer_id' => $login->getCustomerId(),
            ));

            foreach ($this->getClearSingletonsList() as $singleton) {
                /* @var $singleton Mage_Core_Session_Abstract */
                $singleton->clear();
            }

            if ($this->getCustomerSession()->getCustomerId()) {
                if ($this->getPersistentSession()) {
                    $this->getPersistentSession()
                        ->clear()
                        ->deleteByCustomerId($this->getCustomerSession()->getCustomerId());
                }
            }

            if (method_exists($this->getCustomerSession(), 'renewSession')) {
                $this->getCustomerSession()->renewSession();
            }
            // for 1.4
            else {
                $this->getCustomerSession()->logout();
            }

            $this->getCustomerSession()->loginById($login->getCustomerId());

            Mage::dispatchEvent('widgentologin_after_login', array(
                'customer_id' => $login->getCustomerId(),
            ));

            return $this->_redirect(static::REDIRECT_PATH);
        }

        return $this->_redirect('');
    }
}
