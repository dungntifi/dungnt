<?php
class Magik_Pricedropalert_IndexController extends Mage_Core_Controller_Front_Action
{
 public function indexAction()
 {     
    $data=$this->getRequest()->getPost();
    $productId=$data['product'];

    $product = Mage::getModel('catalog/product')->load($productId);     
    $arr = Mage::getModel('pricedropalert/pricedropalert')->chkifExists($productId,$data['email']);
    
    if(count($arr) > 0)
    {
      $existData=Mage::getModel('pricedropalert/pricedropalert')->load($arr[0]['id']);
      if($existData->getActiveStatus()==1) {
        if($product->getSpecialPrice()!='')
          $pr=$product->getSpecialPrice();
        else
          $pr=$product->getPrice();

        $existData->SetProductPrice($pr);
        $existData->setActiveStatus(0);
        $existData->save();        
        //$url=$product->getProductUrl();     
        echo "<div class='thanx_message'>".Mage::getStoreConfig('pricedrop_section/pricedrop_general/pricedrop_thanksmsg',Mage::app()->getStore())."</div>" ;

      } else {
        echo "<div class='thanx_message'>".Mage::getStoreConfig('pricedrop_section/pricedrop_general/pricedrop_exists_thanksmsg',Mage::app()->getStore())."</div>";     
      }
    }   
    else 
    {           
      if($product->getSpecialPrice()!='')
        $pr=$product->getSpecialPrice();
      else
        $pr=$product->getPrice();
      
      $pricedropModel=Mage::getModel('pricedropalert/pricedropalert');

      $pricedropModel->setEmail($data['email']);
      $pricedropModel->setProductid($productId);
      $pricedropModel->setProductName($product->getName());
      $pricedropModel->setProductPrice($pr);
      $pricedropModel->setStatus(0);
      $pricedropModel->setCreatedTime(date('Y-m-d H:i:s'));
      $pricedropModel->save();

      
      //$url=$product->getProductUrl();     
      echo "<div class='thanx_message'>".Mage::getStoreConfig('pricedrop_section/pricedrop_general/pricedrop_thanksmsg',Mage::app()->getStore())."</div>" ;       
    }   
  }  

  public function unsubscribeAction()
  {
    $id=$this->getRequest()->getParam('id');
   
    $pricedropModel=Mage::getModel('pricedropalert/pricedropalert')->load($id);
    $pricedropModel->setActiveStatus(1);
    $pricedropModel->save();
    $this->loadLayout();     

    $block = $this->getLayout()->createBlock(
      'Mage_Core_Block_Template',
      'followprice_unsubscribe_block',
      array('template' => 'pricedropalert/unsubscribe.phtml')
      );

    $this->getLayout()->getBlock('content')->append($block);

      //Release layout stream
    $this->renderLayout();

  }

  public function subscribeAction()
  {
    $this->loadLayout();     
    $this->renderLayout();

  }

  public function unfollowAction()
  {
    $id=$this->getRequest()->getParam('id');
    $pricedropModel=Mage::getModel('pricedropalert/pricedropalert')->load($id);
    $pricedropModel->setActiveStatus(1);
    $pricedropModel->save();   
    $this->loadLayout(); 
    echo "<div class='thanx_message'>".Mage::getStoreConfig('pricedrop_section/pricedrop_general/pricedrop_unsubscribe',Mage::app()->getStore())."</div>";

  }

  public function followAction()
  {
    $id=$this->getRequest()->getParam('id');
    $pricedropModel=Mage::getModel('pricedropalert/pricedropalert')->load($id);
    $pricedropModel->setActiveStatus(0);
    $pricedropModel->save();   
    $this->loadLayout(); 
    echo "<div class='thanx_message'>".Mage::getStoreConfig('pricedrop_section/pricedrop_general/pricedrop_subscribe',Mage::app()->getStore())."</div>";

  }
}