<?php 
class TinyBrick_Warp_Model_Cron extends Mage_Core_Model_Abstract
{
	
	public $urls;
	
	public function warmcache() {
		// This sets the max execution time - need to allow it to be set in the admin section
		// Also need to allow this to ONLY run if turned on in the admin section
		set_time_limit(Mage::getStoreConfig('dfv/crawler/timeout'));
	
		try{
			$this->urls = array();
	
			//cms urls
			$collection = Mage::getModel('cms/page')->getCollection()->addFieldToSelect('identifier');
			$collection->getSelect()->WHERE('is_active = 1');
	
			foreach($collection as $row) ($row['identifier'] == 'compare' || $row['identifier'] == 'cart' ? false : $this->urls[] = $row['identifier']);
			
			$urls = array_chunk($this->urls, 10, true);
			foreach($urls as $url) Mage::helper('warp')->getCache($url);

			$this->urls = array();
	
			//category urls
			$collection = Mage::getModel('core/url_rewrite')->getCollection()->addFieldToSelect('request_path');
			$collection->getSelect()->WHERE('category_id IS NOT NULL AND product_id IS NULL')->group('category_id')->order(new Zend_Db_Expr('request_path'));
	
			foreach($collection as $row) $this->urls[] = $row['request_path'];

			$urls = array_chunk($this->urls, 10, true);
	
			foreach($urls as $url) Mage::helper('warp')->getCache($url);
			 
			$this->urls = array();
	
			//product urls
			$collection = Mage::getModel('core/url_rewrite')->getCollection()->addFieldToSelect('request_path');
			$test = $collection->getSelect()->WHERE('product_id IS NOT NULL')->order(new Zend_Db_Expr('category_id, request_path'))->group('product_id')->__toString();
	
	
			foreach($collection as $row) $this->urls[] = $row['request_path'];
	
			$urls = array_chunk($this->urls, 10, true);
	
			foreach($urls as $url) Mage::helper('warp')->getCache($url);
	
			Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__(
			'Your pages were added to Cache'));
			//$this->_redirect('*/*/');
		}catch (Exception $e){
			Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
	
		}
	}
	
	public function flush($type)
	{
		Mage::helper('warp')->clearCacheByType($type);
	}

	public function cms()
	{
		if(Mage::getStoreConfig('dfv/cron/cms') == 1){
			$this->flush('cms');
		}
	}
	
	public function catalog()
	{
		if(Mage::getStoreConfig('dfv/cron/cat') == 1){
			$this->flush('catalog');
		}
	}
	
	public function product()
	{
		if(Mage::getStoreConfig('dfv/cron/prod') == 1){
			$this->flush('product');
		}
	}
	
}