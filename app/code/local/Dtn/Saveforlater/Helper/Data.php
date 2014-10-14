<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 10/9/14
 * Time: 2:32 PM
 * To change this template use File | Settings | File Templates.
 */
class Dtn_Saveforlater_Helper_Data extends Mage_Core_Helper_Url{

    public function isAllow(){
        return true;
    }

    public function getAddUrl($product){
        return $this->_getUrl('saveforlater/index/save', $this->_getUrlParams($product));
    }

    protected function _getUrlParams($product){
        return array(
            'product' => $product->getId(),
            Mage_Core_Controller_Front_Action::PARAM_NAME_URL_ENCODED => $this->getEncodedUrl(),
            Mage_Core_Model_Url::FORM_KEY => $this->_getSingletonModel('core/session')->getFormKey()
        );
    }


}