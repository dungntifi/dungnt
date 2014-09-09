<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2008-2012 Amasty (http://www.amasty.com)
* @package Amasty_Feed
*/ 
class Amasty_Feed_Block_Adminhtml_Profile_Grid_Renderer_Generate extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract 
{
	public function render(Varien_Object $row)
	{
		if (in_array($row->getStatus(), array('0','3'))) {
			return sprintf('<a href="#" onclick="am_feed_object.request(%d);">%s</a>', $row->getFeedId(), Mage::helper('amfeed')->__('Generate'));
		} else {
			return '';
		}
	}
}