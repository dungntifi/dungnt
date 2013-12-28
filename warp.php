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


// To enable debugging, uncomment the following
// error_reporting(E_ALL | E_STRICT);
// ini_set('display_errors', 1);
require_once 'app/Mage.php';
// Mage::setIsDeveloperMode(true);
//ini_set('display_errors', 1);

if(!PageCache::doYourThing()){
	include_once('index.php');
}

class PageCache
{
	static private $rootDirectory			= './';
	static private $localXml				= 'app/etc/local.xml';
	static private $isCookieNew				= true;
	static private $sessionType				= '';
	static private $rawSession				= '';
	static private $session					= '';
	static private $sessionConfig			= array();
	static private $cacheEngine				= '';
	static private $cacheData				= array();
	static private $mysqlidatabase			= array();
	static private $pdodatabase				= array();
	static private $conditions				= array();	// loggedin, cart
	static private $initConditions			= false;
	static private $holeContent				= array();
	static private $request_path 			= '';
	static private $debugMode				= false;
	static private $multiCurrency			= false;
	static private $multiStoreView			= false;
	static private $adminURI 				= '';
	static private $excludeURI 				= '';
	static private $storeCode 				= '';
	static private $runCode 				= '';
	static private $runType					= '';
	static private $storeId					= null;
	static private $websiteId				= null;
	static private $defaultCurrencyCode		= '';
	static private $config					= '';
	static private $tablePrefix				= '';
	static private $uriNoQuery				= '';
	static private $uriQueryOnly			= '';
	static private $multiHostname			= false;
	static private $multiHostnameConfig		= array();
	
	public static function doYourThing()
	{
		try{
                    
			$uriParts = explode("?", $_SERVER['REQUEST_URI']);
			self::$uriNoQuery = $uriParts[0];
			if (isset($uriParts[1])) {
				self::$uriQueryOnly = $uriParts[1];
			}
			if (isset($_SERVER['ROOT_DIRECTORY'])) {
			    self::$rootDirectory = $_SERVER['ROOT_DIRECTORY'];
			    self::$rootDirectory = rtrim(self::$rootDirectory,'/').'/';
			    self::$localXml = self::$rootDirectory.self::$localXml;
			}
                        
			self::prepareDebugger();
			self::verifyConfigurationExists();
			self::loadConfiguration();
			self::checkSagepay();
			self::redirectAdmin();
            self::redirectMobile();
            self::redirectURI();
            self::setCompilation();
			self::initCookie();
			self::renderCachedPage();
            self::closeDbConnection();
			return true;
		}catch(Exception $e){
			self::report("Error: {$e->getMessage()}", true);
            self::closeDbConnection();
			return false;
		}
	}
        
        // This will set compilation to work with Lightspeed
        public static function setCompilation() {
            require_once 'includes/config.php';
        }

    public static function checkSagepay()
    {
    	// This will check if you have Sagepay enabled. If so, it will keep it from caching
    	if(Mage::getStoreConfig('dfv/cache/sagepay') == 1){
    		
    		if (preg_match('/\/sgps(\/|$)/', $_SERVER['REQUEST_URI'])) {
    		
    			throw new Exception("sage pay suite frontend");
    		
    		}
    		
    		if (preg_match('/\/sgpsSecure(\/|$)/', $_SERVER['REQUEST_URI'])) {
    		
    			throw new Exception("sage pay suite backend");
    		
    		}
    	}
    }
        
	public static function redirectAdmin()
	{
		// detect existance of the admin URI and redirect immediately to index.php
		if (self::$adminURI == '') {
			self::$adminURI = 'admin';
		}
		$pattern = '/\/'.self::$adminURI.'(\/|$)/';
		if (preg_match($pattern, self::$uriNoQuery)) {
			throw new Exception("admin interface detected");
		}
	}
        
	public static function redirectURI()
	{
		// detect existance of keyword in the first part of the URI and redirect immediately to index.php
		if (self::$excludeURI != '' || self::$excludeURI != NULL) {
			// List of keywords are needed in a pipe delimted format for the regular expression
                        $_excludeURI = preg_quote(self::$excludeURI, '/');
			$keywords = explode(",", $_excludeURI);
			$pipeDelimitedKeywords = implode('|', $keywords);
			$pattern = '/\/('.$pipeDelimitedKeywords.')(\/|$)/';
			if (preg_match($pattern, self::$uriNoQuery)) {
				throw new Exception("URI bypasses warp");
			}
		}
		
		/*
		 * Magento specific parameters requiring going through mage
		 */
		if (self::$uriQueryOnly !== '') {
			/*
			 * When setting a storeview via URL parameter go through Mage.)
			 */
			if (preg_match('/___store=/', self::$uriQueryOnly)) {
				throw new Exception("___store is being set and bypasses warp");
			}
		}
	}
        
	public static function redirectMobile()
	{
            
            $useragent = @$_SERVER['HTTP_USER_AGENT'];
            if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
            {	
                    throw new Exception("mobile interface detected");
            }


	}
                
        public static function closeDbConnection()
        {
            if(self::useMySqli()){
                if(isset(self::$mysqlidatabase)) {
                    mysqli_close(self::$mysqlidatabase);
                }
                if(isset(self::$sessionConfig['connection'])) {
                    mysqli_close(self::$sessionConfig['connection']);
                }
            }
            else {
                self::$pdodatabase = null;
            }
        }

	public static function initCookie()
	{
		if(!isset($_COOKIE['frontend'])){
			self::report("first time visitor, I will be creating a cookie from here");
			// create the cookie so Magento doesn't fail
			self::buildCookie();
		}else{
			self::report("not a new visitor, using old cookie");
			self::$isCookieNew = false;
		}
	}
	
	public static function buildCookie()
	{
		require_once 'app/Mage.php';
		$request = new Zend_Controller_Request_Http();
		session_set_cookie_params(
			 self::getCookieLifetime()
			,self::getDefaultCookiePath()
			//,$request->getHttpHost()
                        ,Mage::getModel('core/cookie')->getDomain()
			,false
			,true
		);
		session_name('frontend');
		session_start();
	}
	
	public static function messageExists()
	{
		$message = false;
		if(!self::$isCookieNew){
			self::$rawSession = self::getRawSession();
			if(preg_match('/_messages.*?{[^}]*?Mage_Core_Model_Message_(Success|Error|Notice).*?}/s', self::$rawSession) > 0){
				$message = true;
			}
		}
		return $message;
	}

	public static function initConditions()
	{
            if(self::$initConditions){
		return;
            }
		// get the session_id from the cookie : $_COOKIE['frontend']
            if(!self::$isCookieNew){
                    $session = self::getSession();
                    // see if they are a logged in customer
                    if(isset($session['customer_base']) || isset($session['customer'])){
                            if(isset($session['customer_base']['id']) || isset($session['customer']['id'])){
                                    // ensure they haven't logged out
                                    if((int)$session['customer_base']['id'] >= 1 || (int)$session['customer']['id'] >= 1){
                                            self::$conditions[] = 'loggedin';
                                    }
                            }
                    }
			// see if they have started a cart
            if(isset($session['checkout'])){
                if(isset($session['core']['visitor_data']['quote_id']) && ($quoteId = $session['core']['visitor_data']['quote_id'])){
                    $sql = "SELECT COUNT(*) FROM ". self::getTableName('sales_flat_quote_item') ." WHERE quote_id = $quoteId";
					if(self::useMySqli()){
						//mysqli
						$rresult = mysqli_query(self::$mysqlidatabase, $sql);

						while($rrow = mysqli_fetch_array($rresult)){
							if((int)$rrow[0] >= 1){
								self::$conditions[] = 'cart';
							}
							break;
						}
					}else{
                                            console.log('test1');
						//PDO
						foreach(self::$pdodatabase->query($sql) as $rrow) {
					        if((int)$rrow[0] >= 1){
								self::$conditions[] = 'cart';
							}
							break;
					    }
					}
                }
            }
			//See if they have added items to a compare
			if(isset($session['catalog'])){
				if(isset($session['catalog']['catalog_compare_items_count'])){
					if($session['catalog']['catalog_compare_items_count'] > 0){
						self::$conditions[] = 'compare';
					}
				}
			}
			
		}
		self::$initConditions = true;
	}
	
	public static function prepareSession()
	{
		if(!self::$session){
			self::$session = @self::unserializeSession(self::getRawSession());
			if (!self::$session) {
				self::report("unable to parse the session, generally this is because the session has expired");
			}
		}
	}
	
	public static function get($key)
	{
		switch(self::$cacheEngine){
			case 'memcached':
				return self::$cacheData['server']->get($key);
				break;
			case 'files':
				if($data = @file_get_contents(self::$cacheData['path'] . "/" . md5($key))){                                    
                                    // check if gzip is enabled
                                    $gzip = Mage::getStoreConfig('dfv/files');
                                    if($gzip['gzip'] == 1){
                                        $data = unserialize(gzuncompress($data));
                                        return $data;
                                    }else{
					return unserialize($data);
                                    }
				}
				break;
                        case 'redis':
                                $data = self::$cacheData['server']->load($key);
                                return unserialize($data);
                                break;
		}
		return false;
	}
	
	public static function getCachedPage()
	{
		$key = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		if((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ||
                  (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https'))
                    {
			$key = 'SECURE_' . $key;
		}
		
		//get params from config to exclude from url key
                $params = Mage::getStoreConfig('dfv/cache/params');
		$excludes = array();
		$excludeParams = $params;//$config->lightspeed->global->params;
		$excludeArr = explode(",", $excludeParams);
    	foreach($excludeArr as $param) {
    		$excludes[] = $param;
    	}

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
					if(!in_array($paramArr[0], $excludes)) {
					    if (isset($paramArr[1])) {
					        $newParams[] = $paramArr[0] . "=" . $paramArr[1];
					    }
					    else {
					        $newParams[] = $paramArr[0] . "=";
					    }
					}
				}
				$key = $keyPart[0];
				if (count($newParams)) {
				    // remove 'debug_back=1'
				    $removeParams = array('debug_front=1');
				    $newParams = array_diff($newParams,$removeParams);

				    $newParamsStr = implode('&',$newParams);
				    if ($newParamsStr) {
				        $key .= '?' . $newParamsStr;
				    }
				}
			}
    	} else {
    		$key = preg_replace('/(\?|&|&&)debug_front=1/s', '', $key);
    	}

		if(self::$multiCurrency){
			self::report("configuration set to use multi_currency");
			$key .= '_' . self::getCurrencyCode();
		}
                
		if(self::$multiStoreView){
			self::report("configuration set to use multi_storeview");
			$key .= '_' . self::getStoreCode();
		}
                
		self::report("attempting to fetch url: $key");
		if($data = self::get($key)){
                    $disqualified = false;
			if(self::messageExists()){
				self::report("a global message exists, we must not allow a cached page", true);
				return false;
			}
			if(isset($data[1]) && $data[1]){
				if($data[1] == '*'){ // auto disqualify when messages exist in the session
					self::report("disqualified because the disqualifier is *");
					$disqualified = true;
				}else{
					self::initConditions();
					$disqualifiers = explode(",", $data[1]);
					if($count = count($disqualifiers)){
						for($i=0; $i<$count; $i++){
							if(in_array($disqualifiers[$i], self::$conditions)){
								self::report("disqualified with {$disqualifiers[$i]}");
								$disqualified = true;
								break 1;
							}
						}
					}
				}
				if($disqualified == true){
					// handle dynamic content retrieval here
					if(isset($data[2]) && $data[2]){
						self::report("attempting to retrieve hole punched content from {$data[2]}");

						$_SERVER['REQUEST_URI'] = self::$request_path . "/" . $data[2];
						self::closeDbConnection();
						require_once 'app/Mage.php';
						ob_start();
						self::setRunCode_RunType();
						if (self::$runCode == '' && self::$runType == '') {
							Mage::run();
						}
						else {
							/*
							 * Customise to your unique requirements if needed
							 */
							Mage::run(self::$runCode, self::$runType);
						}
						$content = ob_get_clean();
					}else{
						self::report("valid disqualifiers without hole punch content... bummer", true);
						return false;
					}
				}else{
					return $data[0];
				}
			}else{
				return $data[0];
			}
		}else{
			self::report("No match found in the cache store", true);
			return false;
		}
	}

	public static function getDefaultCookiePath()
	{
		$path = "/";
		try{
			/*
			* The original query:
			* $sql = "SELECT value FROM ". self::getTableName('core_config_data') ." WHERE path = 'web/cookie/cookie_path' AND scope = 'default' AND scope_id = 0";
			* did not take into account that 'web/cookie/cookie_path' can be defined at website or storeview level.
			*/
			list($store_id, $website_id) = self::getStoreId_WebsiteId(self::getStoreCode());
			/*
			 * Take the config parameter in order of scope priority: storeview level, website level, default level.
			*/
			$sql = "SELECT value FROM ". self::getTableName('core_config_data') ." WHERE path = 'web/cookie/cookie_path' AND
				( ( scope = 'default' AND scope_id = 0 ) OR ( scope = 'websites' AND scope_id = $website_id ) OR ( scope = 'store' AND scope_id = $store_id ) )
				ORDER BY CASE
					WHEN scope='stores' THEN 1
					WHEN scope='websites' THEN 2
					WHEN scope='default' THEN 3
				END ASC LIMIT 1";
			if(self::useMySqli()){
				//mysqli
				$result = mysqli_query(self::$mysqlidatabase, $sql);
				while($row = mysqli_fetch_array($result)){
					if(isset($row[0])){
						$path = $row[0];
					}
				}
			}else{
				//PDO
				foreach(self::$pdodatabase->query($sql) as $row) {
			        if(isset($row[0])){
						$path = $row[0];
					}
			    }
			}
		}catch(Exception $e){}
		
		return $path;
	}

	public static function getCurrencyCode()
	{
		$currencyCode = '';
		$session = self::getSession();
		if($session && isset($session[self::getStoreCode()])){
			self::report("found the session data for store code: " . self::getStoreCode());
			if(isset($session[self::getStoreCode()]['currency_code'])){
				self::report("found a currency code in the session: " + $session[self::getStoreCode()]['currency_code']);
				$currencyCode = $session[self::getStoreCode()]['currency_code'];
			}
		}
		if(!$currencyCode){
			self::report("defaulting to default currency code: " . self::getDefaultCurrencyCode());
			$currencyCode = self::getDefaultCurrencyCode();
		}
		return $currencyCode;
	}

	public static function getSession()
	{
		if (!self::$session) {
			self::prepareSession();
		}
		return self::$session;
	}

	public static function getRawSession()
	{
		if (!self::$rawSession) {
			switch(self::$sessionType){
				case 'db':
					$sql = "SELECT session_data FROM ". self::getTableName('core_session') ." WHERE session_id = '{$_COOKIE['frontend']}'";
					if(self::useMySqli()){
						//mysqli
						$result = mysqli_query(self::$mysqlidatabase, $sql);
						while($row = mysqli_fetch_array($result)){
							if(isset($row[0])){
								$path = $row[0];
							}
						}
						$result = mysqli_query(self::$sessionConfig['connection'], $sql);
						if(count($result)){
							while($row = mysqli_fetch_array($result)){
								return $row[0];	
							}
						}
					}else{
						//PDO
						foreach(self::$pdodatabase->query($sql) as $row) {
					        if(isset($row[0])){
								$path = $row[0];
							}
					    }
						$result = mysqli_query(self::$sessionConfig['connection'], $sql);
						foreach($result = self::$pdodatabase->query($sql) as $row) {
							if(count($result)){
								return $row[0];
							}
						}
					}
					break;
				case 'memcached':
					return self::$sessionConfig['server']->get($_COOKIE['frontend']);
					break;
				case 'files':
				default:
					return @file_get_contents(self::$sessionConfig['path'] . "/" . "sess_" . $_COOKIE['frontend']);
					break;
			}
		}
		return self::$rawSession;
	}
	
	public static function unserializeSession($data)
	{
		$result = false;
		if($data){
		    $vars = preg_split('/([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff^|]*)\|/', $data,-1,PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		    $numElements = count($vars);
		    for($i=0; $numElements > $i && $vars[$i]; $i++) {
		        $result[$vars[$i]]=unserialize($vars[++$i]);
		    }
		}
	    return $result;
	}

	public static function fillNoCacheHoles($html)
	{
		return preg_replace_callback('/(\<!\-\- +nocache.+?\-\-\>).*?(\<!\-\- endnocache \-\-\>)/si', 'PageCache::replaceNoCacheBlocks', $html); 
	}
	
	public static function replaceNoCacheBlocks($matches)
	{
		// $matches[0] is the whole block
		// $matches[1] is the <!-- nocache -->
		// $matches[2] is the <!-- endnocache -->
		// print_r($matches);
		$key = self::getAttributeValue('key', $matches[1]);
		if(isset(self::$holeContent[$key])){
			return self::$holeContent[$key]; 
		}else{
			return $matches[0];
		}
	}
	
	public static function getAttributeValue($attribute, $html)
	{
		preg_match('/(\s*'.$attribute.'=\s*".*?")|(\s*'.$attribute.'=\s*\'.*?\')/', $html, $matches);
		
		if(count($matches)){
			$match = $matches[0];
			$match = preg_replace('/ +/', "", $match);
			$match = str_replace($attribute."=", "", $match);
			$match = str_replace('"', "", $match);
			return $match;
		}else{
			return false;
		}
	}
	
	public static function sanitizePage($page)
	{
		$page = preg_replace('/\<!\-\- +nocache.+?\-\-\>/si', "", $page);
		$page = preg_replace('/\<!\-\- endnocache \-\-\>/si', "", $page);
		return $page;
	}
        
	public static function getCookieLifetime()
	{
		$lifetime = 3600;
		try{
			/*
			* The original query:
			* $sql = "SELECT value FROM ". self::getTableName('core_config_data') ." WHERE path = 'web/cookie/cookie_lifetime' AND scope = 'default' AND scope_id = 0";
			* did not take into account that 'web/cookie/cookie_lifetime' can be defined at website or storeview level.
			*/
			list($store_id, $website_id) = self::getStoreId_WebsiteId(self::getStoreCode());
			/*
			 * Take the config parameter in order of scope priority: storeview level, website level, default level.
			*/
			$sql = "SELECT value FROM ". self::getTableName('core_config_data') ." WHERE path = 'web/cookie/cookie_lifetime' AND
				( ( scope = 'default' AND scope_id = 0 ) OR ( scope = 'websites' AND scope_id = $website_id ) OR ( scope = 'store' AND scope_id = $store_id ) )
				ORDER BY CASE
					WHEN scope='stores' THEN 1
					WHEN scope='websites' THEN 2
					WHEN scope='default' THEN 3
				END ASC LIMIT 1";
			if(self::useMySqli()){
				//mysqli
				$result = mysqli_query(self::$mysqlidatabase, $sql);
				while($row = mysqli_fetch_array($result)){
					if(isset($row[0])){
						$lifetime = (int) $row[0];
					}
				}
			}else{
				//PDO
				foreach(self::$pdodatabase->query($sql) as $row) {
			        if(isset($row[0])){
						$lifetime = (int) $row[0];
					}
			    }
			}
		}catch(Exception $e){}
		
		return $lifetime;
	}
	
	public static function report($message, $term=false)
	{
		if (self::$debugMode) {
			echo "$message<br />";
			if ($term) {
				exit;
			}
		}
	}
	
	public static function prepareDebugger()
	{
		if (isset($_GET['debug_front']) && $_GET['debug_front'] == '1') {
			self::$debugMode = true;
		}
	}
	
	public static function verifyConfigurationExists()
	{            
		if(!file_exists(self::$localXml)){
			throw new Exception('cannot find local.xml at '.self::$localXml);
		}
	}
	
	public static function loadConfiguration()
	{
            // this will load the local.xml file and grab the global configuration information about the DB
            $config = self::$config = simplexml_load_file(self::$localXml);
            $connection = $config->global->resources->default_setup->connection;
            $username   = (string)$connection->username;
            $password   = (string)$connection->password;
            $db_name    = (string)$connection->dbname;
            $host       = (string)$connection->host;
            self::report('Found the global DB node');

            if(self::useMySqli()){
                self::report('Using MySQLI');
                //mysqli
                self::$mysqlidatabase = mysqli_connect($host, $username, $password) or die(mysqli_error());
                if(self::$mysqlidatabase->connect_error){
                    die('Connect Error (' . self::$mysqlidatabase->connect_error . ')');
                }
                mysqli_select_db(self::$mysqlidatabase, $db_name);
            }else{
                //pdo
                try {
                    self::report('Using PDO');
                    self::$pdodatabase = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
                } catch (PDOException $e) {}
            }
             
            // This checks for multicurrency
            self::$multiCurrency = Mage::getStoreConfig('dfv/cache/multicurrency');
            self::$multiStoreView = Mage::getStoreConfig('dfv/cache/multistore');
            self::$adminURI = (string)Mage::getConfig()->getNode('admin/routers/adminhtml/args/frontName');
            
            if(Mage::app()->getStore()->getCode() == 'default'){
                $rt = 'store';
            }else{
                $rt = 'website';
            }
            
            // If mulistoreview is set-up, we need to get the host info
            if(self::$multiStoreView != 0){
                self::$multiHostname = 1;
                self::$multiHostnameConfig = array (
                        'run_code' => Mage::app()->getStore()->getCode(),
                        'run_type' => $rt,
                        'host_name' => Mage::getBaseUrl()
                );
            }
            // Exlude URI's
            self::$excludeURI = Mage::getStoreConfig('dfv/cache/excludeuri');
                 
                 
            // this will load cache configuration
            $cacheType = array(Mage::getStoreConfig('dfv/cache/type'));
                // this is if it is equal to files
                if($cacheType[0] == 'files'){
                    $location = Mage::getStoreConfig('dfv/files');
                    

                    
                    self::$cacheEngine = 'files';
                    self::$cacheData['path'] = self::$rootDirectory.$location['path'];

                    self::report('Files are stored in ' . self::$cacheData['path']);
                    
                    }
                    // this is for memcached
                if($cacheType[0] =='memcached'){
                    $location = Mage::getStoreConfig('dfv/memcached');
                    
                    self::report('Files are stored in Memcached');
                    
                    if(!class_exists('Memcache')){
                            throw new Exception('Memcache extension not installed, but configured for use in admin section');
                    }
                    self::$cacheEngine = 'memcached';
                    self::$cacheData['server'] = new Memcache();
                    self::$cacheData['server']->addServer(
                                    (string)$location['host']
                                    ,(int)$location['port']
                                    ,(bool)$location['persistence']
                            );
                }
                
                if($cacheType[0] == 'redis'){
                    try {
                        
                        $location = Mage::getStoreConfig('dfv/redis');
                        
                        require_once(Mage::getBaseDir('lib') . '/Credis/Client.php');
                        require_once(Mage::getBaseDir('lib') . '/Zend/Cache/Backend.php');
                        require_once(Mage::getBaseDir('lib') . '/Zend/Cache/Backend/Interface.php');
                        require_once(Mage::getBaseDir('lib') . '/Zend/Cache/Backend/ExtendedInterface.php');
                        require_once(Mage::getBaseDir('lib') . '/Zend/Cache/Backend/Redis.php');
                        
                        self::$cacheEngine = 'redis';
                        self::report('Cache is being stored in Redis');
                        

                        
                            $options = array();
                            $options['server'] = (string)$location['host'];
                            $options['port'] = (int)$location['port'];
                            $options['database'] = (int)$location['database'];
                            $options['timeout'] = 180;//(int)$location['timeout'];
                            $options['force_standalone'] = 0;//(int)$location['force_standalone'];
                            $options['automatic_cleaning_factor'] = (int)$location['cleaning'];
                        
                            self::$cacheData['server'] = new Zend_Cache_Backend_Redis($options);
                    }
                    catch (Exception $e) {
                        die('Redis can not be used as the backend for Lightspeed. Check local.xml for proper configuration.');
                    }
                    
                }
            
            
            // this will load session configuration
            $sessionType = array(Mage::getStoreConfig('dfv/session'));
            foreach($sessionType as $type){
                if($type['files'] == 1){
                    
                    self::report('Sessions are stored in files');
                    
                    $location = Mage::getStoreConfig('dfv/dbfiles');
                    self::$sessionType = 'files';
		    self::$sessionConfig['path'] = self::$rootDirectory.$location['path'];
                }elseif($type['database'] == 1){
                    
                    // This will grab the DB information from the nodes in the local.xml file
                    
                    self::report('Sessions are stored in the database');
                    
                    self::$sessionType = 'db';
                    
                    if(self::useMySqli()){
                        
                    self::$sessionConfig['connection'] = self::$mysqlidatabase;
                    
                    }else{
                        try{
                            self::$sessionConfig['connection'] = self::$pdodatabase;
                            } catch (PDOException $e) {}
                        }
                }elseif($type['memcached'] == 1){
                    
                    // This will grab the DB info from memcached
                    self::report('Sessions are stored in memcached');
                    
                    $locationSession = Mage::getStoreConfig('dfv/dbmemcached');
                    
                    self::$sessionType = 'memcached';
                    if(!class_exists('Memcache')){
                        throw new Exception('Memcache extension not installed, but configured for use in local.xml');
                    }
                    self::$sessionConfig['server'] = new Memcache();
                    self::$sessionConfig['server']->addServer(
                             (string)$locationSession['host']
                            ,(int)$locationSession['port']
                            ,(bool)$locationSession['persistence']
                    );
                    
                }
            }
            
            /*
		$config = self::$config = simplexml_load_file('app/etc/local.xml');
		$nodeFound = false;
		foreach($config->children() as $child){
			if($child->getName() == 'lightspeed'){
				$nodeFound = true;
				foreach($child->children() as $child2){
					switch($child2->getName()){
						case 'global':
							self::report("found the global db node");
							if(self::useMySqli()){
								//mysqli
								self::$mysqlidatabase = mysqli_connect((string)$child2->connection->host, (string)$child2->connection->username, (string)$child2->connection->password);
								mysqli_select_db(self::$mysqlidatabase, (string)$child2->connection->dbname);
							}else{
								//pdo
								try {
								    self::$pdodatabase = new PDO('mysql:host='.(string)$child2->connection->host.';dbname='.(string)$child2->connection->dbname, (string)$child2->connection->username, (string)$child2->connection->password);
								} catch (PDOException $e) {}
							}
							
							self::$request_path = (string)$child2->request_path;
							self::$request_path = rtrim(trim(self::$request_path), '/');
							if($child2->multi_currency){
								self::$multiCurrency = (int) $child2->multi_currency;
							} 	
						break;
						case 'session':
							switch((string)$child2->type){
								case 'memcached':
									// self::report("Session store is memcached");
									if(!class_exists('Memcache')){
										throw new Exception('Memcache extension not installed, but configured for use in local.xml');
									}
									self::$sessionType = 'memcached';
									self::$sessionConfig['server'] = new Memcache();
									foreach($child2->servers->children() as $server){
										self::$sessionConfig['server']->addServer(
											 (string)$server->host
											,(int)$server->port
											,(bool)$server->persistant
										);
									}
									break;
								case 'db':
									// self::report("session store is db");
									self::$sessionType = 'db';
									self::$sessionConfig['connection'] = mysqli_connect((string)$child2->connection->host, (string)$child2->connection->username, (string)$child2->connection->password);
									mysqli_select_db(self::$sessionConfig['connection'], (string)$child2->connection->dbname);
									break;
								case 'files':
								default:
									// self::report("session store is files");
									self::$sessionType = 'files';
									self::$sessionConfig['path'] = (string) $child2->path;
									if(!self::$sessionConfig['path']){
										self::$sessionConfig['path'] = 'var/session';
									}
									break;
							}
							break;
						case 'cache':
							switch((string)$child2->type){
								case 'memcached':
									// self::report("cache engine is memcached");
									if(!class_exists('Memcache')){
										throw new Exception('Memcache extension not installed, but configured for use in local.xml');
									}
									self::$cacheEngine = 'memcached';
									self::$cacheData['server'] = new Memcache();
									foreach($child2->servers->children() as $server){
										self::$cacheData['server']->addServer(
											 (string)$server->host
											,(int)$server->port
											,(bool)$server->persistant
										);
									}
									break;
								case 'files':
								default:
									// self::report("cache engine is files");
									self::$cacheEngine = 'files';
									self::$cacheData['path'] = (string)$child2->path;
									if(!self::$cacheData['path']){
										self::$cacheData['path'] = 'var/lightspeed';
									}
									break;
							}
							break;
					}
				}
			}
		}
		
		if(!$nodeFound){
            //node not found, install for them
            self::report("local.xml does not contain <lightspeed> node, here let us setup lightspeed for you...", true);
			self::createLocalConfig();
			self::loadConfiguration();
		}
             
             */
	}
	/*
	public static function createLocalConfig()
	{
            
        $config = simplexml_load_file('app/etc/local.xml');
        $host = $config->global->resources->default_setup->connection->host;
        $username = $config->global->resources->default_setup->connection->username;
        $password = $config->global->resources->default_setup->connection->password;
        $db = $config->global->resources->default_setup->connection->dbname;
        $config->addChild('lightspeed');
        
        //create global section under lightspeed
        $config->lightspeed->addChild('global');
        $connection = $config->lightspeed->global->addChild('connection');
        $connection->addChild('host', '<![CDATA[' . $host . ']]>');
        $connection->addChild('username', '<![CDATA[' . $username . ']]>');
        $connection->addChild('password', '<![CDATA[' . $password . ']]>');
        $connection->addChild('dbname', '<![CDATA[' . $db . ']]>');
        $config->lightspeed->global->addChild('multi_currency', '0');
        $config->lightspeed->global->addChild('request_path', "<![CDATA[]]>");
	            
        //create session section under lightspeed
        $config->lightspeed->addChild('session');
        $config->lightspeed->session->addChild('type');
        $config->lightspeed->session->addChild('path');
        $config->lightspeed->session->addChild('servers');
        $config->lightspeed->session->servers->addChild('localhost');
        $config->lightspeed->session->servers->localhost->addChild('host', '<![CDATA[127.0.0.1]]>');
        $config->lightspeed->session->servers->localhost->addChild('port', '<![CDATA[11211]]>');
        $config->lightspeed->session->servers->localhost->addChild('persistent', '<![CDATA[1]]>');
        $config->lightspeed->session->addChild('connection');
        $config->lightspeed->session->connection->addChild('host', $host);
        $config->lightspeed->session->connection->addChild('username', $username);
        $config->lightspeed->session->connection->addChild('password', $password);
        $config->lightspeed->session->connection->addChild('dbname', $db);

        //create cache section under lightspeed
        $config->lightspeed->addChild('cache');
        $config->lightspeed->cache->addChild('type');
        $config->lightspeed->cache->addChild('path');
        $config->lightspeed->cache->addChild('servers');
        $config->lightspeed->cache->servers->addChild('localhost');
        $config->lightspeed->cache->servers->localhost->addChild('host', '<![CDATA[127.0.0.1]]>');
        $config->lightspeed->cache->servers->localhost->addChild('port', '<![CDATA[11211]]>');
        $config->lightspeed->cache->servers->localhost->addChild('persistent', '<![CDATA[1]]>');
        
        $newConfig = html_entity_decode($config->asXML());
        file_put_contents('app/etc/local.xml', $newConfig);
        self::report("local.xml setup and configured...", true);
        
        //setup htaccess file for lightspeed.php use
        $htaccess = file_get_contents('.htaccess');
        $htaccess = str_replace('index.php', 'lightspeed.php', $htaccess);
        file_put_contents('.htaccess', $htaccess);
        self::report(".htaccess setup and configured...", true);
	}
	*/
	public static function renderCachedPage()
	{
		self::setCompilation();
		if($page = self::getCachedPage()){
			self::report("success!, I'm about to spit out a cached page, look out.", true);
			self::prepareHeaders();
			echo self::sanitizePage($page);
		}else{
			throw new Exception("no cache matches at this url.");
		}
	}
	
	public static function prepareHeaders()
	{
		header("Pragma: no-cache");
		header("Cache-Control: no-cache, must-revalidate, no-store, post-check=0, pre-check=0");
	}
	
	public static function getStoreCode() 
	{
		// if this is already set, let's just use it
		if (self::$storeCode !== '') return self::$storeCode;
		
		self::setRunCode_RunType();
		/*
		 * First attempt: tries to get the store code from the HOST, if it is defined to run at store level
		*/
		if (self::$runType == 'store') {
			self::$storeCode = 'store_' . self::$runCode;
			self::report("found a match in the multi_hostname configuration, setting store code to: " . self::$storeCode);
		}
		
		if (self::$storeCode == '' && isset($_COOKIE['store'])) {
			/*
			 * Second attempt: tries to get the store code from  the 'store' cookie, which is set for non-default storeviews of a multi-storeview  site
			*/
			self::$storeCode = 'store_' . $_COOKIE['store'];
			self::report("found a match in the cookie for store code, setting store code to: " . self::$storeCode);
		}
		else {
			/*
			 * Third attempt: looks for the default View of the default Shop for a given Website.
			 * This is needed because for the default View the 'store' cookie is not set.
			 * 
			 * >>> TO TEST:
			 *     scenario with a Website with two or more Shops
			 *     multi-website set-up under the same Host
			 * 
			 * The original code looked at the session to find the store, but that was unreliable in
			 * a multi-store and multi-site set-up.
			 * It was not working when the session was shared across websites via the SID url parameter
			 *     System > Configuration > Web > Session Validation Settings > Use SID on Frontend = YES
			 */
			$sql = "SELECT view.code
				FROM " . self::getTableName('core_website') . " as website
				INNER JOIN " . self::getTableName('core_store_group') . " as store ON website.default_group_id = store.group_id
				INNER JOIN " . self::getTableName('core_store') . " as view ON store.default_store_id = view.store_id
				WHERE view.is_active = 1";
			if (self::$runType == 'website') {
				/*
				 * In a multi-site set-up, takes tha website core from the configuration
				 */
				$website_code = self::$runCode;
				$sql .= " AND website.code = '$website_code'";
				$msg = "looking for a match in the DB for the website ($website_code), setting store code to: ";
			}
			else {
				/*
				 * Assumes it is not a multi-site set-up and it looks for the default website.
				 */
				$sql .= " AND website.is_default = 1";
				$msg = "looking for a match in the DB for the default website, setting store code to: ";
			}
			if(self::useMySqli()){
				//mysqli
				$result = mysqli_query(self::$mysqlidatabase, $sql);
				if(count($result)){
					while($row = mysqli_fetch_array($result)){
						self::$storeCode = $row[0];
					}
				}
			}else{
				//PDO
				if(count($result)){
					foreach(self::$pdodatabase->query($sql) as $row) {
						while($row = mysqli_fetch_array($result)){
							self::$storeCode = $row[0];
						}
					}
				}
			}
			self::$storeCode = 'store_' . self::$storeCode;
			self::report($msg . self::$storeCode);
		}
		
		if (self::$storeCode == '') {
			self::$storeCode = 'store_default';
			self::report("setting store code to: store_default");
		}
		
		return self::$storeCode;
	}
	
        public static function getStoreId_WebsiteId($storeCode) 
        {
		if (!(self::$storeId === null) && !(self::$websiteId === null)) return array(self::$storeId, self::$websiteId);
		
		$storeCode = preg_replace( "|store\_|i", "", $storeCode );
		
		$sql = "SELECT store_id, website_id FROM ". self::getTableName('core_store') ." WHERE code = '$storeCode'";
		if(self::useMySqli()){
			//mysqli
			$result = mysqli_query(self::$mysqlidatabase, $sql);
			if(count($result)){
				while($row = mysqli_fetch_array($result)){
					self::$storeId = $row[0];
					self::$websiteId = $row[1];
				}
			}
		}else{
			//PDO
			if(count($result)){
				foreach(self::$pdodatabase->query($sql) as $row) {
					while($row = mysqli_fetch_array($result)){
						self::$storeId = $row[0];
						self::$websiteId = $row[1];
					}
				}
			}
		}
		return array(self::$storeId, self::$websiteId);
	}
        
	public static function getDefaultCurrencyCode() 
	{
		if (!self::$defaultCurrencyCode){
			/*
			* $sql = "SELECT value FROM ". self::getTableName('core_config_data') ." WHERE path = 'currency/options/default'";
			* In order to retrieve the correct default currency the scope has to be defined.
			* If no currency is defined at specific store level (scope_id) then uses the "default" scope.
			*/
			if (self::$storeCode == "store_default" || self::$storeCode == '') {
				$sql = "SELECT value FROM ". self::getTableName('core_config_data') ." WHERE path = 'currency/options/default' AND scope = 'default' AND scope_id = 0";
			} else {
				list($store_id, $website_id) = self::getStoreId_WebsiteId(self::getStoreCode());
				/*
				 * Take the config parameter in order of scope priority: storeview level, website level, default level.
				 */
				$sql = "SELECT value FROM ". self::getTableName('core_config_data') ." WHERE path = 'currency/options/default' AND
					( ( scope = 'default' AND scope_id = 0 ) OR ( scope = 'websites' AND scope_id = $website_id ) OR ( scope = 'store' AND scope_id = $store_id ) )
					ORDER BY CASE
						WHEN scope='stores' THEN 1
						WHEN scope='websites' THEN 2
						WHEN scope='default' THEN 3
					END ASC LIMIT 1";
			}

			if(self::useMySqli()){
				//mysqli
				$result = mysqli_query(self::$mysqlidatabase, $sql);
		        if(count($result)){
		            while($row = mysqli_fetch_array($result)){
						self::$defaultCurrencyCode = $row[0];
		            }
				}
			}else{
				//PDO
				if(count($result)){
					foreach(self::$pdodatabase->query($sql) as $row) {
				        while($row = mysqli_fetch_array($result)){
							self::$defaultCurrencyCode = $row[0];
			            }
				    }
				}	
			}
		}
		return self::$defaultCurrencyCode;
	}
	
	public static function useMySqli()
	{
		if (function_exists('mysqli_connect')) {
			//mysqli is installed
			return true;
		}else{
			//Try PDO
			return false;
		}
	}
	
	public static function getDBPrefix()
	{
		//Use alone for higher performance table prefix fetch
		$prefix = '';
		
		//Use for standard table prefix fetch (Lower performance, but no configuration required)
		if(self::$tablePrefix == ''){
			if(self::$config != ''){
				try{
					$config = self::$config;
					$prefix = $config->global->resources->db->table_prefix;
				}catch(Exception $e){}
			}else{
				if(file_exists(self::$localXml)){
					try{
						$config = self::$config = simplexml_load_file(self::$localXml);
						$prefix = $config->global->resources->db->table_prefix;
					}catch(Exception $e){}
				}
			}
			if($prefix != ''){
				$prefix = $prefix."_";
			}
			self::$tablePrefix = $prefix;
			return $prefix;
		}else{
			return self::$tablePrefix;
		}
	}

	public static function getTableName($tableName)
	{
		return self::getDBPrefix() . $tableName;
	}
        
        public static function setRunCode_RunType()
	{
		if (self::$multiHostname) {
			if (self::$runCode == '' && self::$runType == '') {
				/*
				* Check if the environmental variables, which are used by Magento if set.
				* They can be set in a VirtualHost:
				*     SetEnv MAGE_RUN_CODE "base"
				*     SetEnv MAGE_RUN_TYPE "website"
				* or in .htaccess solution:
				*     SetEnvIf Host .*yourhost.* MAGE_RUN_CODE="base";
				*     SetEnvIf Host .*yourhost.* MAGE_RUN_TYPE="website";
				* Where .*yourhost.* is an regex expression matching the domain for which you want to set environmental variable.
				*/
				self::$runCode = isset($_SERVER['MAGE_RUN_CODE']) ? $_SERVER['MAGE_RUN_CODE'] : ''; // Store or website code
				self::$runType = isset($_SERVER['MAGE_RUN_TYPE']) ? $_SERVER['MAGE_RUN_TYPE'] : 'store'; // Run store or run website
				if(self::$multiHostnameConfig) {
						self::$runCode = self::$multiHostnameConfig['run_code'];
						self::$runType = self::$multiHostnameConfig['run_type'];
				}
			}
		}
	}       
}
