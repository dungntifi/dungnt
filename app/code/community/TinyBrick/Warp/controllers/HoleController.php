<?php

class TinyBrick_Warp_HoleController extends Mage_Core_Controller_Front_Action 
{
        public function indexAction()
        {
             echo Mage::getSingleton('checkout/session')->getQuote()->getItemsCount();            
        }
        
        public function testAction(){
        	echo Mage::getStoreConfig('dfv/cron/prod');
        }
        
        public function nameAction(){
            
            $firstName = Mage::getSingleton('customer/session')->getCustomer()->getFirstname();
            $lastName = Mage::getSingleton('customer/session')->getCustomer()->getLastname();
            
            if(!$firstName || !$lastName){
                echo Mage::getStoreConfig('design/header/welcome');
            }else{
                echo 'Welcome, ' . $firstName . ' ' . $lastName . '!';
            }
        }
        
        public function checkAction(){
            $loggedIn = Mage::getSingleton('customer/session')->getCustomer()->getEntityId();
            if($loggedIn){
                echo true;
            }else{
                echo false;
            }
        }
        
        public function compare(){
            $product = array();
            
            $compare = Mage::helper('catalog/product_compare');
                       
            // gets products
            $compareProducts = $compare->getItemCollection();
            
            foreach($compareProducts as $products){
                // grabs the product image
                
                $helper = Mage::helper('catalog/product');
                
                $productUrl = $helper->getProductUrl($products->getData('entity_id'));          

                $removeUrl = $compare->getRemoveUrl($products);
                
                $product[$products->getData('sku')] = array(
                        'Sku' => $products->getData('sku'),
                        'requestPath' => $products->getData('name'),
                        'productUrl' => $productUrl,
                        'removeUrl' => $removeUrl,
                        );
            }
            

            $productArray = array('products' => $product, 'compareTotal' => $compare->getItemCount(), 'clearUrl' => $compare->getClearListUrl(), 'compareUrl' => $compare->getListUrl());
            return $productArray;
        }
        
        public function cart(){
            
            $cartHelper = Mage::helper('checkout/cart');
            $cart = $cartHelper->getQuote()->getData();
            $cartQty = (int)$cart['items_qty'];
            $cartSubTotal = round($cart['base_subtotal'],2);
            $cartUrl = $cartHelper->getCartUrl();
            
            // uncomment if you want to get all the quote items
            //$cartItems = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
            
            return $newObject = array(
                       "cartQty" => $cartQty,
                       "cartSubtotal" => $cartSubTotal,
                       "cartUrl" => $cartUrl,
                );
           
        }
        
        public function poll(){
            
        }
                
        public function topLinks(){
            // get total items in cart
            $cart = Mage::helper('checkout/cart')->getQuote()->getData();
            $cartTotal = (int)$cart['items_qty'];
            
            $loggedin = 0;
            
            // check if logged in
            if(Mage::helper('customer')->isLoggedIn() == 1){
                
                $loggedin = 1;
                
                $firstName = Mage::getSingleton('customer/session')->getCustomer()->getFirstname();
                $lastName = Mage::getSingleton('customer/session')->getCustomer()->getLastname();

                if(!$firstName || !$lastName){
                    $welcome = Mage::getStoreConfig('design/header/welcome');
                }else{
                    $welcome = 'Welcome, ' . $firstName . ' ' . $lastName . '!';
                }
            }else{
                $welcome = Mage::getStoreConfig('design/header/welcome');
            }
            
            // This will grab all of the css info from backend
            $configTopLinks = Mage::getStoreConfig('punch/toplinks');
            
            return $newObject = array(
                        "cartTotal" => $cartTotal,
                        "welcome" => $welcome,
                        "loggedin" => $loggedin,
                        "topLinksCss" => $configTopLinks,
            );
        }
        
        public function enabledAction(){
            
            $enabledObject = Mage::getStoreConfig('punch/hpconfiguration');
            
            $object = array();
            
            foreach($enabledObject as $key => $value){
                $object[$key] = $value;
                $object[$key.'hp'] = $this->$key();
            }
            
            echo json_encode($object);
            
        }
}