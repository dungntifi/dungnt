<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 10/10/14
 * Time: 4:37 PM
 * To change this template use File | Settings | File Templates.
 */
class Dtn_Saveforlater_Block_Drashboad_Saveforlater extends Mage_Catalog_Block_Product_Abstract{

    public function productCollection(){
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $_modelItem = Mage::getModel('saveforlater/saveitem')->getCollection();
        $_modelItem->getSelect()->join(array('save_main' => Mage::getSingleton('core/resource')->getTableName('saveforlater/savemain')),
            '`main_table`.`main_id` = `save_main`.`id`')->where("`customer_id`='".$customer->getId()."'");
        return $_modelItem->getData();
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