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
 * @copyright  Copyright (c) 2013 TinyBrick Inc. LLC
 * @license    http://store.opencommercellc.com/commercial-license
 */

class TinyBrick_Warp_Block_Page_Html extends Mage_Page_Block_Html
{
	
	protected function _construct()
	{
		if (isset($_GET['debug_back']) && $_GET['debug_back'] == '1') {
			$this->setIsDebugMode(true);
		}
		return parent::_construct();
	}
	
	public function cachePage($expires = false, $disqualifiers='', $disqualifiedContentPath='')
	{
		$this->setCachePage(true);
		$this->setExpires(($expires)? $expires : false);
		$this->setDisqualifiers($disqualifiers);
		$this->setDisqualifiedContentPath($disqualifiedContentPath);
		return $this;
	}
	
	public function buildDisqualifiers($disqualifiers)
	{
		// GET ENABLED HOLE PUNCHES
		$hpEnabled = Mage::getStoreConfig('punch/hpconfiguration');
		
		$dis = array();
		
		// CHECKS TO SEE IF IT IS ENABLED
		foreach($hpEnabled as $key => $hp){
			//TOPLINKS = loggedin, CART = cart, COMPARE = compare
			if($hp == 0){
				array_push($dis, $key); 
			}
			
		}
		
		// CONVERT THE ARRAY BACK TO WHAT IT NEEDS TO BE FOR WARP
		return implode(',',$dis);
	}
	
	protected function _aggregateTags()
	{
	    $aggregateTags = $this->getAggregateTags();
	    $tag = self::_requestTag();
	    if(!@in_array($tag, $aggregateTags)){
	        $aggregateTags[] = strtoupper($tag);
	    }
	    $this->setAggregateTags($aggregateTags);
	}
	
	/*
	 *
	 * Sets the requested tag for category/product/page 
	 * 
	*/
	
	protected function _requestTag()
	{
	    $name = Mage::app()->getFrontController()->getRequest()->getControllerName();
	    switch($name){
	        case 'category':
	            $id = Mage::registry('current_category')->getId();
	            $_tag = Mage_Catalog_Model_Category::CACHE_TAG.'_'.$id;
	            break;
	        case 'product':
	            $id = Mage::registry('current_product')->getId();
	            $_tag = Mage_Catalog_Model_Product::CACHE_TAG.'_'.$id;
	            break;
	        case 'page':
	            $id = Mage::getSingleton('cms/page')->getId();
	            $_tag = Mage_Cms_Model_Page::CACHE_TAG.'_'.$id;
	            break;
	        default:
	            $_tag = '';
	    }
        return $_tag;
	}
	
	/*
	 * 
	 * Actually saves the cached page
	 * Can see notes further down into the method 
	 * 
	*/
	
	protected function _afterToHtml($html)
	{
		if ($this->getCachePage()) {
			if (Mage::app()->useCache('warp')) {
				if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
					if ($this->_isRegistered()) {
						if (Mage::getSingleton('checkout/cart')->getItemsCount() < 1) {
							if(!$this->_comparing()) {
								if(Mage::app()->getRequest()->getActionName() != 'noRoute') {
       								    $doNotCache = false;

                                                                    
									$key = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
									if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
										$key = 'SECURE_' . $key;
									}
									//remove ids that should not be cached
									//get params from config to exclude from url key
									$excludeParams = Mage::getStoreConfig('dfv/cache/params');
									$excludeArr = explode(",", $excludeParams);
									$excludes = array();
							    	foreach($excludeArr as $param) {
							    		$excludes[] = $param;
							    	}
							    	
							    	// unset arrays here - memory management
							    	unset($excludeParams);
							    	unset($excludeArr);
							    	
							    	//see if we even need to remove some params
							    	if(count($excludes)) {
							    		//if we do, blow it up and remake it
							    		$keyPart = explode("?", $key);
							    		if(isset($keyPart[1])) {
                                            $keyParse = parse_url($key);
											$currentParams = explode("&", $keyParse['query']);
											$newParams = array();
											foreach($currentParams as $iteration=>$currentParam) {
												$paramArr = explode("=", $currentParam);
												if(in_array($paramArr[0], $paramsNocacheArr)) {
												    // There is a parameter in the URL which should prevent caching the page
												    $doNotCache = true;
												    break;
												}else{
													if(!in_array($paramArr[0], $excludes)) {
                                                    	$newParams[] = (isset($paramArr[1]) ? $paramArr[0] . '=' . $paramArr[1] : $paramArr[0] . '=');
                                                    }
												}
											}
											if (!$doNotCache) {
    											$key = $keyPart[0];
    											if (count($newParams)) {
    											    // remove 'debug_back=1'
    											    $removeParams = array('debug_back=1');
    											    $newParams = array_diff($newParams,$removeParams);

    											    $newParamsStr = implode('&',$newParams);
    											    $key .= '?' . $newParamsStr;
    											}
    											//$key = preg_replace('/(\?|&|&&)debug_back=1/s', '', $key);
											}
                                                                        }
							    	} else {
							    		$key = preg_replace('/(\?|&|&&)debug_back=1/s', '', $key);
							    	}
                                                                
                                    if (!$doNotCache) {
    									if($this->_useMultiCurrency()){
    										$key .= '_' . Mage::app()->getStore()->getCurrentCurrencyCode();
    									}
    									if($this->_useMultiStoreView()){
    										$key .= '_store_' . Mage::app()->getStore()->getCode();
    									}
    									/* Add an extra Key if an sort order is defined in the session */
    									if (Mage::getSingleton('catalog/session')->getSortOrder()) {
    									    $key .= '_order_' . Mage::getSingleton('catalog/session')->getSortOrder();
    									}
    									/* Add an extra Key if an sort direction is defined in the session */
    									if (Mage::getSingleton('catalog/session')->getSortDirection()) {
    									    $key .= '_dir_' . Mage::getSingleton('catalog/session')->getSortDirection();
    									}
    									// remove pesky session id from url
    									Mage::app()->setUseSessionVar(false);
    										
    									(Mage::getStoreConfig('dfv/oej/version') != 0 && Mage::getStoreConfig('punch/hpconfiguration/hpenabled') == 1) ? $ajaxHolePunch = "<div id='ajaxholepunchEnabled'></div>" : $ajaxHolePunch = "<div id='ajaxholepunchDisabled'></div>";								
    								
    									$html = Mage::getSingleton('core/url')->sessionUrlVar($html);
    									
    									// THIS CHECKS WHERE THE BODY IS FOUND IN THE STRING
    									$length = strlen($ajaxHolePunch);
    									$strposBody = strpos($html, '<body');
    									
    									//LENGTH OF HTML
    									$lengthHtml = strlen($html);
    									
    									//HTML BEFORE THE STRPOS
    									$temp1 = substr($html, 0, $strposBody);
    									//HTML AFTER STRPOS
    									$temp2 = substr($html, $strposBody, $lengthHtml);
    									//COMBINE INTO ONE    									
										$html = $temp1 . $ajaxHolePunch . $temp2;
    									
    									//$html .= $ajaxHolePunch;
    									$data = array((string)$html, $this->getDisqualifiers(), $this->getDisqualifiedContentPath());	
    									
    									$this->_aggregateTags();
    									$this->report("saving page with key: $key", true);
    									Mage::getSingleton('warp/server')->save($key, $data, $this->getExpires(), $this->getAggregateTags(), Mage::app()->getFrontController()->getRequest()->getControllerName());
							    	} else {
							    	    $this->report("No Cache Parameter Found ($paramArr[0])", true);
							    	}
								} else {
									$this->report("404 page", true);
								}
							} else {
								$this->report("found items in the compare", true);
							}
						} else {
							$this->report("found items in the cart", true);
						}
					} else {
						$this->report("invalid registration", true);
					}
				} else {
					$this->report("customer is logged in", true);
				}
			} else {
				$this->report("please enable the 'whole pages' cache checkbox in the cache management panel", true);
			}
		} else {
			$this->report("this page is not set to be cached according to the layout", true);
		}
		// remove any NOCACHE tags
		$html = preg_replace('/\<!\-\- +nocache.+?\-\-\>/si', "", $html);
		$html = preg_replace('/\<!\-\- endnocache \-\-\>/si', "", $html);
		return parent::_afterToHtml($html);
	}
        
	
	protected function _comparing()
	{
		$comparing = false;
		if(Mage::getSingleton('catalog/session')->getCatalogCompareItemsCount()){
			if(Mage::getSingleton('catalog/session')->getCatalogCompareItemsCount() > 0){
				$comparing = true;
			}
		}
		return $comparing;
	}
	
	protected function _getConfig($key)
	{
		return Mage::getStoreConfig($key);
	}
	
	protected function _useMultiCurrency()
	{
		if($useCurrency = Mage::getStoreConfig('dfv/cache/multicurrency')){
			if($useCurrency == '1'){
				return true;
			}
		}
		return false;
	}
        
	protected function _useMultiStoreView()
	{
		if($useStoreView = Mage::getStoreConfig('dfv/cache/multistore')){
			if($useStoreView == '1'){
				return true;
			}
		}
		return false;
	}
	
	private function _isRegistered()
	{
		
		$baseUrl = Mage::getBaseUrl();
		if(preg_match('/127.0.0.1|localhost|192.168/', $baseUrl)){
			return true;
		}
		
		if($registeredDomain = $this->_getConfig('dfv/oej/nfg')){
			if(preg_match("/$registeredDomain/i", $baseUrl)){
				if(($serial = $this->_getConfig('dfv/oej/wdf')) && $key = $this->_getConfig('dfv/oej/ntr')){
					if(md5($registeredDomain.$serial) == $key || md5($registeredDomain.$serial.'advanced') == $key){
						return true;
					} 
				}
			}
		}
		return false;
	}
	
	private function report($message, $term=false)
	{
		if ($this->getIsDebugMode()) {
			echo "$message<br />";
			if ($term) {
				exit;
			}
		}
	}
	
}

?>
