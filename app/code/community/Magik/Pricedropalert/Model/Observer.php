<?php
class Magik_Pricedropalert_Model_Observer
{

	public function sendAlert()
	{

		$emailTemplateId=Mage::getStoreConfig('pricedrop_section/pricedrop_general/pricedrop_template',Mage::app()->getStore());
		$senderEmail=Mage::getStoreConfig('pricedrop_section/pricedrop_general/pricedrop_emailreply',Mage::app()->getStore());
		$senderEmailName=Mage::getStoreConfig('pricedrop_section/pricedrop_general/pricedrop_emailreplyname',Mage::app()->getStore());
		$data = Mage::getModel('pricedropalert/pricedropalert')->getList1();

		$prodIds = Mage::getModel('pricedropalert/pricedropalert')->getProductId();
		foreach($prodIds as $productId) 
		{			
		    foreach($data as  $key=>$val)
		    {			
			    if($val['productid']==$productId)
			    {		

					  $org = number_format(Mage::getModel('pricedropalert/pricedropalert')->getPrice($productId),2);
					  
					  $sym = Mage::app()->getLocale()->currency(Mage::app()->getStore()->getCurrentCurrencyCode())->getSymbol(); 	    
					  if( $val['product_price'] > $org )
					  {						  		
							$templateId = $emailTemplateId;        							
							$mailSubject = 'Price Drop for - '.$val['product_name'];
							$sender = Array('name' => $senderEmail,'email' => $senderEmailName);
							$email = $val['email'];
							$storeId = Mage::app()->getStore()->getId();
							/* set default store id instead of 0 */
							if($storeId==0){
							  $storeId = Mage::app()->getWebsite(true)->getDefaultGroup()->getDefaultStoreId();
							}
							$vars = Array('product_name' => $val['product_name'],'follow_price' => Mage::helper('core')->currency($val['product_price'],true,false), 'current_price'=>Mage::helper('core')->currency($org,true,false), 'unsubscribe_link'=>Mage::getUrl('pricedropalert/index/unsubscribe/id/'.$val['id']));							
							$etranslate = Mage::getSingleton('core/translate');
								      Mage::getModel('core/email_template')
									  ->setTemplateSubject($mailSubject)
									  ->sendTransactional($templateId, $sender, $email, null, $vars, $storeId);
							$etranslate->setTranslateInline(true);						
							Mage::getModel('pricedropalert/pricedropalert')->updateDB($val['id'],$org);
						
					  }
			    }
		    }
		}
	}

	
}