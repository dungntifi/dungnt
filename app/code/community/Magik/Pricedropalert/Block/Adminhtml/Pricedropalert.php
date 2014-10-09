<?php


class Magik_Pricedropalert_Block_Adminhtml_Pricedropalert extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_pricedropalert";
	$this->_blockGroup = "pricedropalert";
	$this->_headerText = Mage::helper("pricedropalert")->__("Pricedropalert Manager");
	//$this->_addButtonLabel = Mage::helper("pricedropalert")->__("Add New Item");
	parent::__construct();
	$this->_removeButton('add');
	}

}