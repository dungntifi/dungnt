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
 * @package    TinyBrick_LightSpeed
 * @copyright  Copyright (c) 2010 TinyBrick
 * @license    http://www.tinybrick.com/license/commercial-extension
 */

class TinyBrick_Warp_Model_Server_Redis
{
	protected $_server;
        protected $_enabled = false;
        
        public function getServer() {
            if (!$this->_server) {
                require_once(Mage::getBaseDir('lib') . '/Credis/Client.php');
                require_once(Mage::getBaseDir('lib') . '/Zend/Cache/Backend.php');
                require_once(Mage::getBaseDir('lib') . '/Zend/Cache/Backend/Interface.php');
                require_once(Mage::getBaseDir('lib') . '/Zend/Cache/Backend/ExtendedInterface.php');
                require_once(Mage::getBaseDir('lib') . '/Zend/Cache/Backend/Redis.php');
                
                $location = Mage::getStoreConfig('dfv/redis');
                                         
                $options = array();
                $options['server'] = (string)$location['host'];
                $options['port'] = (int)$location['port'];
                $options['database'] = (int)$location['database'];
                $options['timeout'] = (int)$location['timeout'];
                $options['force_standalone'] = (int)$location['force_standalone'];
                $options['automatic_cleaning_factor'] = (int)$location['cleaning'];
                
                $this->_enabled = true;
                $this->_server = new Zend_Cache_Backend_Redis($options);
            }
            return $this->_server;
        }
        
        public function save($key, $data, $expires = 0, array $tags=array()) {
            $server = $this->getServer();
            if ($server && $this->_enabled) {
                $server->save(serialize($data), $key, $tags, $expires);
            }
        }
        
        public function cleanByTag($tag) {
            
            if ($this->_server) {
                $this->_server->_removeByMatchingTags($tag);
            }
            
        }

        public function clean($tags=array()) {
            
            if ($this->_server) {
                if (count($tags) && !in_array('WARP', $tags)) {
                    $this->_server->_removeByMatchingTags($tags);
                }else {
                    $this->_server->clean(Zend_Cache::CLEANING_MODE_ALL, $tags);
                }
            }
            
        }

}