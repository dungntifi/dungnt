<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 10/9/14
 * Time: 5:22 PM
 * To change this template use File | Settings | File Templates.
 */
class Dtn_Saveforlater_Block_Head_Head extends Mage_Core_Block_Template{
    public function itemCookie(){
        $result = array();
        $cookieSaveLater = Mage::getModel('core/cookie')->get("saveforlater");
        foreach($cookieSaveLater as $items){
            $item = unserialize($items);
            $result[] = array("product_id"=>$item["product"],"option"=>json_encode($item["super_attribute"]),"qty"=>$item["qty"]);
        }
        return $result;
    }

    public function itemAfterLogin(){
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $_modelItem = Mage::getModel('saveforlater/saveitem')->getCollection();
        $_modelItem->getSelect()->join(array('save_main' => Mage::getSingleton('core/resource')->getTableName('saveforlater/savemain')),
            '`main_table`.`main_id` = `save_main`.`id`')->where("`customer_id`='".$customer->getId()."'");
        return $_modelItem->getData();
    }

    public function getProductCollectionForLater(){
        $isLogin = Mage::getSingleton('customer/session')->isLoggedIn();
        if($isLogin){
            $collection = $this->itemAfterLogin();
        }else{
            $collection = $this->itemCookie();
        }
        return $collection;
    }

    public function getProductCollection($productId){
        $product = Mage::getModel('catalog/product')->load($productId);
        return $product;
    }

    public function getTextAttributeAndOptionById($attributeId,$optionId){
        $_model = Mage::getModel('eav/entity_attribute')->load($attributeId);
        $_productModel = Mage::getModel('catalog/product');
        $_optionModel = $_productModel->getResource()->getAttribute($_model->getAttributeCode());
        $_option = $_optionModel->getSource()->getOptionText($optionId);
        $result = array($_model->getFrontendLabel(),$_option);
        return $result;
    }
}