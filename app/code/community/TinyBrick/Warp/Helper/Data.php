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
 * @license    http://store.opencommercellc.com/commercial-license
 */

class TinyBrick_Warp_Helper_Data extends Mage_Core_Helper_Abstract
{
    
    protected $_server;
    
    public function getServer()
    {
            if (!isset($this->_server)){
                    //verify that the file system exists and that we have rights to it.	
                    $folderPath = Mage::getStoreConfig('dfv/files');
                    $folder = $folderPath['path'];
                    if (!isset($folder) || $folder == '')
                            $folder = '/var/warp';
                    rtrim($folder, '/');
                    if (isset($_SERVER['ROOT_DIRECTORY'])) {
                        $rootDirectory = rtrim($_SERVER['ROOT_DIRECTORY'],'/').'/';
                        $folder = $rootDirectory.ltrim($folder,'/');
                    }
                    if (!is_dir($folder)){
                            mkdir($folder, 0777);
                    }
                    $this->_server = $folder . '/';
            }
            return $this->_server;
    }    
    
    public function saveCache($fileName, $url, $server, $type){
        
        // This will save all cache file names into the DB for viewing purposes
        $warpModule = Mage::getModel('warp/warp'); 
        
        // This checks to see if the file name is already in database
        $collection = $this->collection()->getCollection()->addFieldToSelect('filename');
        $collection->getSelect()->WHERE('filename LIKE "'.$fileName.'"');
        
        foreach($collection as $file) $file = 1;
        
        if(@!$file){
	        $warpModule->setUrl($url)
	                ->setServer($server)
	                ->setFilename($fileName)
	                ->setType($type)
	                ->save();
        }elseif($file == 1){
        	return;
        }
        
    }
    
    public function clearCache($fileName){  
        
            $server = $this->getServer();
            if (is_file($server . $fileName)){
                    unlink($server . $fileName);
            }
        
    }
    
    public function clearCacheAllById($id){
        $this->clearCacheById($id);
        $this->clearCacheDbById($id);
    }
    
    public function clearCacheDbById($id){
        $warp = $this->collection()->setId($id);
        $warp->delete();
    }
    
    public function clearCacheById($id){
        $fileName = $this->getFileName($id);
        $this->clearCache($fileName);
    }
    
    public function getServerType(){
        
    }
    
    public function collection(){

        return Mage::getModel('warp/warp');

    }
    
    public function _isStandard(){

    	if(Mage::getStoreConfig('dfv/oej/ntr') != md5(Mage::getStoreConfig('dfv/oej/nfg').Mage::getStoreConfig('dfv/oej/wdf').'advanced')){
    		return true;
    	}else{
    		return false;
    	}
    }
    
    public function getFileName($id){
    	$file = Mage::getModel('warp/warp')->load($id);
        return $file->getFilename();
   }
   
   public function clearDbItemOnSave($filename){
       $filename = str_replace(' ', '', $filename);
       $ids = $this->getId($filename);

       foreach($ids as $id){
            $this->clearCacheDbById($id);
            $this->clearCacheById($id);
       }
   }
   
   public function getId($filename){
       //$resource = Mage::getSingleton('core/resource')->getConnection('core_read');
       //$query = "SELECT id FROM tinybrick_warp_cached_pages WHERE filename = '".trim($filename)."'";
       //return $resource->fetchAll($query);
       $collection = $this->collection()->getCollection()->addFieldToSelect('id');
       return $collection->getSelect()->WHERE('filename='.$filename);
   }
        
    public function getCache($url) {
    	set_time_limit(Mage::getStoreConfig('dfv/crawler/timeout'));
    	$baseUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB);
    	
        $mh = curl_multi_init();
        $time = microtime(true);
        
        foreach($url as $URL) {
            $url = $baseUrl . $URL;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate'); 
            curl_multi_add_handle($mh, $ch);
        }
        
        $running = 0;
        do { curl_multi_exec($mh, $running); } while ($running > 0);
        curl_multi_close($mh);
        unset($mh);
        
        $time = microtime(true) - $time;
        $this->totalTime += $time;
    }
    
    public function clearCacheDbAll(){
             
        
        $collection =  $this->collection()->getCollection();
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
    
    public function cleanCacheByProductId($id){
        $tag[] = Mage_Catalog_Model_Product::CACHE_TAG.'_'.$id;
        Mage::getSingleton('warp/server')->clean($tag);
    }
    
    public function cleanCacheByCategoryId($id){
        $tag[] = Mage_Catalog_Model_Category::CACHE_TAG.'_'.$id;
        Mage::getSingleton('warp/server')->clean($tag);
   }
   
   public function clearCacheByType($type){
   	   	$pagesId = $this->getByType($type);
   	   	foreach($pagesId as $page){
	   	   	$this->clearCacheById($page['id']);
	   	   	$this->clearCacheDbById($page['id']);
   	   	}
   }
   
   public function getByType($type)
   {
		$collection = $this->collection()->getCollection()->addFieldToFilter('type', $type);
		$file = array();
		foreach($collection as $c){
			$file[$c->getId()] = ($c->getData());
		}
		return $file;
		
   }
   
}