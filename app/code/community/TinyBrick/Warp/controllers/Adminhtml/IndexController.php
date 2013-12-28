<?php
/**
 * TinyBrick Commercial Extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the TinyBrick Commercial Extension License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://store.delorumcommerce.com/license/commercial-extension
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@tinybrick.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this package to newer
 * versions in the future. 
 *
 * @category   TinyBrick
 * @package    TinyBrick_Warp
 * @copyright  Copyright (c) 2010 TinyBrick
 * @license    http://www.tinybrick.com/license/commercial-extension
 */


class TinyBrick_Warp_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{

        protected function _initAction() {
                $this->loadLayout()
                        ->_setActiveMenu('tinybrick/warp')
                        ->_addBreadcrumb(Mage::helper('adminhtml')->__('Warp Cache Management'), Mage::helper('adminhtml')->__('Warp Cache Management'));

                return $this;
        }

        public function indexAction() {
			$this->_initAction()->renderLayout();
        }
        
        public function htaccessAction(){
        	$this->loadLayout()->renderLayout();
        }
        
        public function clearAction(){
            
            if($this->getRequest()->getParam('id')){
                $id = $this->getRequest()->getParam('id');
            }
            // This gets the filename from the parameters
            if( $id > 0 ) {
                try {
                    $warpModel = Mage::getModel('warp/warp');
                    
                    $fileName = Mage::helper('warp')->getFileName($id);
                    
                    $warpModel->setId($id)->delete();
                    
                    // Need to place the delete cache piece here
                    
                    $this->singledeleteAction($fileName);
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted from Database'));
                    $this->_redirect('*/*/');
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirect('*/*/');
                }
            }
            
        }
        
        public function getMultipleFiles(){
            // This will loop through all the files
            $warpModel = Mage::getModel('warp/warp');
            $collection = $warpModel->getCollection()->addFieldToFilter('id',$id);
            foreach($collection as $fileName){
                Mage::log($fileName->getData('filename'));
            }
            die();
        }
        
        public function singledeleteAction($fileName){
            $fileName = Mage::helper('warp')->getServer() . '$fileName';
            // This deletes a single page from cache
            if (is_file($fileName)){
                    unlink($fileName);
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('and Item was successfully deleted from cache'));
                }
            
        }
        
        public function flushAction(){
        	
        	$type = $this->getRequest()->getParam('type');
            // This will delete all cache and remove all records from database
			if($type != all){
        		Mage::helper('warp')->clearCacheByType($type);
        		$this->_redirect('*/*/');
			}else{
				
				/**
				 * Deletes all
				 */
				$dir = Mage::helper('warp')->getServer();
				
				$files = array_diff(scandir($dir), array('.','..'));
				foreach ($files as $file) {
					(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
				}
				if(is_dir($dir)){
					try{
						rmdir($dir);
				
						Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Cache has been deleted'));
						$this->_redirect('*/*/');
					} catch (Exception $e) {
						Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
						$this->_redirect('*/*/');
					}
				}
				/**
				 * Deletes everything from database
				 */
				$collection =  Mage::helper('warp')->collection()->getCollection();
				foreach($collection as $row){
					$id = $row->getData('id');
					if($id > 0){
						try{
							Mage::getModel('warp/warp')->load($id)->delete();
						}catch (Exception $e) {
							Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
							$this->_redirect('*/*/');
						}
							
				
					}
				}
				
			}

        }
        
        public function massdeleteAction(){
            // This grabs all the ID's that need to be deleted
            $id = $this->getRequest()->getPost('id');
            if(!$id){
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('adminhtml')->__('Please select pages you want to delete!'));

            }else{
                try{
                    foreach($id as $i){
                        Mage::helper('warp')->clearCacheAllById($i);
                    }
                    Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__(
                    '%d pages were removed from Cache', count($id)));
                    $this->_redirect('*/*/');
                }catch (Exception $e){
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirect('*/*/');
                }
            }
            
            
        }
        
    public function warmAction(){
    	
    	$timecreated   = strftime("%Y-%m-%d %H:%M:%S",  mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));
    	$timescheduled = strftime("%Y-%m-%d %H:%M:%S", mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")));
    	$jobCode = 'warp_warmcache';
    	try {
    		$schedule = Mage::getModel('cron/schedule');
    		$schedule->setJobCode($jobCode)
    		->setCreatedAt($timecreated)
    		->setScheduledAt($timescheduled)
    		->setStatus(Mage_Cron_Model_Schedule::STATUS_PENDING)
    		->save();
    		Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Your site has been setup to cache. It will begin shortly.'));
    		$this->_redirect('*/*/');
    		
    	} catch (Exception $e) {
    		throw new Exception(Mage::helper('cron')->__('Unable to save Cron expression. Error has occured'));
    	}

    	
    }    
        
    public function warmAction1() {

    	// This sets the max execution time - need to allow it to be set in the admin section
    	// Also need to allow this to ONLY run if turned on in the admin section
    	set_time_limit(200);
    	
        $baseUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
        
        try{
            $this->urls = array();

            //cms urls
            $collection = Mage::getModel('cms/page')->getCollection()->addFieldToSelect('identifier');
            $collection->getSelect()->WHERE('is_active = 1');

            foreach($collection as $row){
                
                ($row['identifier'] == 'compare' || $row['identifier'] == 'cart' ? false : $this->urls[] = $row['identifier']); 
                
                }
            
            $urls = array_chunk($this->urls, 10, true);
            foreach($urls as $url) {
                Mage::helper('warp')->getCache($url, $baseUrl);
            }        
            
            
            
            $this->urls = array();
            
            //category urls
            $collection = Mage::getModel('core/url_rewrite')->getCollection()->addFieldToSelect('request_path');
            $collection->getSelect()->WHERE('category_id IS NOT NULL AND product_id IS NULL')->group('category_id')->order(new Zend_Db_Expr('request_path'));
            
            
            foreach($collection as $row){
                $this->urls[] = $row['request_path'];        
            }
            
            $urls = array_chunk($this->urls, 10, true);

            foreach($urls as $url){
                Mage::helper('warp')->getCache($url, $baseUrl);
            }
             
            
            $this->urls = array();

            //product urls
            $collection = Mage::getModel('core/url_rewrite')->getCollection()->addFieldToSelect('request_path');
            $test = $collection->getSelect()->WHERE('product_id IS NOT NULL')->order(new Zend_Db_Expr('category_id, request_path'))->group('product_id')->__toString();
            
            
            foreach($collection as $row) $this->urls[] = $row['request_path'];

            $urls = array_chunk($this->urls, 10, true);

            foreach($urls as $url) {
                Mage::helper('warp')->getCache($url, $baseUrl);
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__(
            'Your pages were added to Cache'));
             $this->_redirect('*/*/');
        }catch (Exception $e){
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());

        }
    }
    
    public function savehtAction()
    {
    	/**
    	 * This will save to the htaccess file
    	 */
    	$basedir = Mage::getBaseDir('base');
    	$newData = $this->getRequest()->getParam('htaccess');
    	$test = file_put_contents($basedir . '/.htaccess', $newData);

    }
        
}