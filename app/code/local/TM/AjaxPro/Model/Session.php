<?php

class TM_AjaxPro_Model_Session {

    /**
     *
     * @var bool
     */
    protected $_status = true;

    /**
     *
     * @var array
     */
    protected $_messages = array();

    public function __construct()
    {
        $storages = array(
            Mage::getSingleton('checkout/session'),
            Mage::getSingleton('wishlist/session'),
            Mage::getSingleton('customer/session'),
            Mage::getSingleton('catalog/session')
        );
        $clear = Mage::getStoreConfig('ajax_pro/general/enabledNoticeForm');
        foreach ($storages as $storage) {
            $messageCollection = $storage->getMessages($clear);
            foreach ($messageCollection->getItems() as $message) {
                $this->addMessage($message->getText(), $message->getType());
            }
        }

        if (empty($this->_messages[Mage_Core_Model_Message::SUCCESS])
            && $this->getStatus()) {

            $this->addMessage(
                $this->__('Action was complete')
            );
        }
    }

    /**
     * Translate a phrase
     *
     * @return string
     */
    public function __()
    {
        $args = func_get_args();
        $expr = new Mage_Core_Model_Translate_Expr(array_shift($args), 'TM_AjaxPro');
        array_unshift($args, $expr);
        return Mage::app()->getTranslator()->translate($args);
    }

    /**
     *
     * @param string $message
     * @param string $type
     * @return \TM_AjaxPro_Model_Session
     */
    public function addMessage($message, $type = Mage_Core_Model_Message::SUCCESS)
    {
        $this->_messages[$type][] = $message;
        return $this;
    }

    /**
     *
     * @return string
     */
    public function getMessages()
    {
        return $this->_messages;
    }

    /**
     *
     * @return bool
     */
    public function getStatus()
    {
        $notSuccessStatusTypes = array(
            Mage_Core_Model_Message::ERROR,
            Mage_Core_Model_Message::WARNING,
            Mage_Core_Model_Message::NOTICE
        );
        $messages = $this->_messages;
        foreach ($notSuccessStatusTypes as $_type) {
            if (isset($messages[$_type]) && count($messages[$_type])) {
                return false;
            }
        }
        return true;
    }
}
