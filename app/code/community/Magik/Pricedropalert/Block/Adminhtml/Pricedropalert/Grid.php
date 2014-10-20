<?php

class Magik_Pricedropalert_Block_Adminhtml_Pricedropalert_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("pricedropalertGrid");
				$this->setDefaultSort("id");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("pricedropalert/pricedropalert")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
			// $this->addColumn("id", array(
			// 	"header" => Mage::helper("pricedropalert")->__("ID"),
			// 	"align" =>"right",
			// 	"width" => "50px",
			// 	"type" => "number",
			// 	"index" => "id",
			// ));

		      $this->addColumn('email', array(
					'header'    => Mage::helper('pricedropalert')->__('Email'),
					'width'     => '150px',
					'index'     => 'email',
		      ));

		      $this->addColumn('product_name', array(
		          'header'    => Mage::helper('pricedropalert')->__('Product Name'),
		          'align'     =>'left',
			  'width'     => '200px',
		          'index'     => 'product_name',
		      ));

			  
		      $this->addColumn('product_price', array(
					'header'    => Mage::helper('pricedropalert')->__('Price'),
					'width'     => '100px',
					'type'  => 'price',  
					'currency_code' =>(string)Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
					'index'     => 'product_price',
		      ));  
			
		      $this->addColumn('status', array(
					'header'    => Mage::helper('pricedropalert')->__('Email Status'),
					'width'     => '100px',
					'index'     => 'status',
					'type'      => 'options',
					'options'   => array(
					    0 => 'Email not yet sent',
					    1 => 'Email Sent',
					),
		      ));	
		      $this->addColumn('active_status', array(
		          'header'    => Mage::helper('pricedropalert')->__('Subscription Status'),
		          'align'     => 'left',
		          'width'     => '80px',
		          'index'     => 'active_status',
		          'type'      => 'options',
		          'options'   => array(
		              0 => 'Active',
		              1 => 'Inactive',
		          ),
		      ));
	  		
                
			// $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			// $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('id');
			$this->getMassactionBlock()->setFormFieldName('ids');
			//$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('delete', array(
					 'label'=> Mage::helper('pricedropalert')->__('Delete'),
					 'url'  => $this->getUrl('*/adminhtml_pricedropalert/massRemove'),
					 'confirm' => Mage::helper('pricedropalert')->__('Are you sure?')
				));
			return $this;
		}
			

}