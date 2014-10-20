<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 10/9/14
 * Time: 2:32 PM
 * To change this template use File | Settings | File Templates.
 */
class Dtn_Saveforlater_IndexController extends Mage_Core_Controller_Front_Action{

    public function indexAction(){
    }

    public function saveAction(){
        if (!$this->_validateFormKey()) {
            $this->_redirectReferer();
            return;
        }
        $request = $this->getRequest()->getParams();
        $_modelMain = Mage::getModel('saveforlater/savemain');
        $_modelItem = Mage::getModel('saveforlater/saveitem');
        $helper = Mage::helper("saveforlater/saveforlater");
        $isLogin = Mage::getSingleton('customer/session')->isLoggedIn();
        if($request["product"]){
            if($isLogin){
                try{
                    $customer = Mage::getSingleton('customer/session')->getCustomer();
                    $data_main = array();
                    if($helper->checkExitsCustomerInSaveMain($_modelMain->getCollection(),$customer->getId())){
                        $id = $helper->getCurrentIdSaveMain($_modelMain->getCollection(),$customer->getId());
                        $_modelMain->load($id);
                        $data_main["updated_at"] = "2014-10-10 00:00:00.000000";
                        $_modelMain->setUpdatedAt($data_main["updated_at"]);
                    }else{
                        $data_main["customer_id"]= $customer->getId();
                        $data_main["updated_at"] = "2014-10-09 00:00:00.000000";
                        $_modelMain->setData($data_main);
                    }
                    $_modelMain->save();

                    $data_item = array();
                    if($helper->checkExitsProductInSaveItem($_modelItem->getCollection(),$helper->getCurrentIdSaveMain($_modelMain->getCollection(),$customer->getId()),$request["product"],json_encode($request["super_attribute"]))==true){
                        Mage::getSingleton('core/session')->addNotice('Update success');
                    }else{
                        $data_item["main_id"] = $helper->getCurrentIdSaveMain($_modelMain->getCollection(),$customer->getId());
                        $data_item["store_id"] = Mage::app()->getStore()->getId();
                        $data_item["product_id"] = $request["product"];
                        $data_item["option"] = json_encode($request["super_attribute"]);
                        $data_item["qty"] = $request["qty"];
                        $_modelItem->setData($data_item);
                        Mage::getSingleton('core/session')->addSuccess('Add new success');
                    }
                    $_modelItem->save();
                }catch (Exception $e){
                    Mage::getSingleton('core/session')->addError('Error add to database');
                }
            }else{
                $cookie = Mage::getSingleton('core/cookie');
                $cookieSaveLater = Mage::getSingleton('core/cookie')->get("saveforlater");
                if($cookieSaveLater){
                    $count = count($cookieSaveLater);
                    foreach($cookieSaveLater as $data){
                        $saveLater = unserialize($data);
                        if($request["product"] == $saveLater["product"] && json_encode($request["super_attribute"]) == json_encode($saveLater["super_attribute"])){
                            Mage::getSingleton('core/session')->addSuccess('Product existed');
                            continue;
                        }else{
                            $cookie->set('saveforlater['.$count.']', serialize($request), time()+864000000, '/');
                            Mage::getSingleton('core/session')->addSuccess('Add Product to save later success');
                        }
                    }
                }else{
                    $cookie->set('saveforlater[0]', serialize($request), time()+864000000, '/');
                    Mage::getSingleton('core/session')->addSuccess('Add Product to save later success');
                }
            }
        }
        Mage::app()->cleanCache();
        $this->_redirectReferer();
    }

    public function listAction(){
        if( !Mage::getSingleton('customer/session')->isLoggedIn() ) {
            Mage::getSingleton('customer/session')->authenticate($this);
            return;
        }

        $this->loadLayout();
        $navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');
        if ($navigationBlock) {
            $navigationBlock->setActive('saveforlater/index/list');
        }

        $this->renderLayout();
    }

    public function addproductAction(){
        $request = $this->getRequest()->getParam("id");
        if($request){
            $model = Mage::getModel('saveforlater/saveitem')->load($request);
            $product = Mage::getModel('catalog/product')->load($model->getProductId());
            $cart = Mage::getModel('checkout/cart');
            $options = array("product"=>$model->getProductId(),"super_attribute"=>json_decode($model->getOption(),true),"qty"=>$model->getQty());
            $cart->addProduct($product, $options);
            $cart->save();

            Mage::getSingleton('core/session')->addSuccess('Add product "'.$product->getName().'" to cart success');
            $this->_redirectReferer();
        }
    }

    public function removeitemAction(){
        $request = $this->getRequest()->getParam("id");
        if($request){
            $model = Mage::getModel('saveforlater/saveitem')->load($request);
            $product = Mage::getModel('catalog/product')->load($model->getProductId());
            $model->delete();
            Mage::getSingleton('core/session')->addSuccess('Remove item with product "'.$product->getName().'" success');
            Mage::app()->cleanCache();
            $this->_redirectReferer();
        }
    }
}