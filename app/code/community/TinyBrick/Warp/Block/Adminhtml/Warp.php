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
 * @copyright  Copyright (c) 2010 TinyBrick Inc. LLC
 * @license    http://store.delorumcommerce.com/license/commercial-extension
 */
class TinyBrick_Warp_Block_Adminhtml_Warp extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
        $this->_controller = 'adminhtml_warp';
        $this->_blockGroup = 'warp';
        $this->_headerText = Mage::helper('warp')->__('Warp Full Page Cache');  
        $this->_addButton('flush_magentocms', array(
        		'label'     => Mage::helper('warp')->__('Flush CMS Pages'),
        		'onclick'   => 'setLocation(\'' . $this->getUrl('*/adminhtml_index/flush', array('type' => 'page')) .'\')',
        		'class'     => 'delete',
        ));
        $this->_addButton('flush_magentocat', array(
        		'label'     => Mage::helper('warp')->__('Flush Catalog Pages'),
        		'onclick'   => 'setLocation(\'' . $this->getUrl('*/adminhtml_index/flush', array('type' => 'category')) .'\')',
        		'class'     => 'delete',
        ));
        $this->_addButton('flush_magentopro', array(
        		'label'     => Mage::helper('warp')->__('Flush Product Pages'),
        		'onclick'   => 'setLocation(\'' . $this->getUrl('*/adminhtml_index/flush', array('type' => 'product')) .'\')',
        		'class'     => 'delete',
        ));
        $this->_addButton('flush_magento', array(
                'label'     => Mage::helper('warp')->__('Flush Warp Cache for Entire Site'),
                'onclick'   => 'setLocation(\'' . $this->getUrl('*/adminhtml_index/flush', array('type' => 'all')) .'\')',
                'class'     => 'delete',
            ));
        
        if(Mage::getStoreConfig('dfv/crawler/crawlerenabled') == 1){
	        $this->_addButton('cache_magento', array(
	                'label'     => Mage::helper('warp')->__('Recache Entire Site'),
	                'onclick'   => 'setLocation(\'' . $this->getUrl('*/adminhtml_index/warm') .'\')',
	        ));
        }
        
        parent::__construct();
        
        $this->_removeButton('add');
  }
}