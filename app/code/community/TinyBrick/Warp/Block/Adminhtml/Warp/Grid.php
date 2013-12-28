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

class TinyBrick_Warp_Block_Adminhtml_Warp_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    
  protected $_userMode;
    
  public function __construct()
  {
      parent::__construct();
      $this->setId('warp');
      $this->setDefaultSort('url');
      $this->setDefaultDir('DESC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('warp/warp')->getCollection();

      $this->setCollection($collection);
 
      parent::_prepareCollection();
      return $this;
  }

  protected function _prepareColumns()
  {  

      $this->addColumn('url', array(
          	'header'    => Mage::helper('warp')->__('URL'),
          	'align'     =>'left',
          	'index'     => 'url',
      ));
      
      $this->addColumn('filename', array(
                'header'    => Mage::helper('warp')->__('File Name'),
                'align'     => 'left',
                'index'     => 'filename',
          
      ));
      
      $this->addColumn('server', array(
                'header'    => Mage::helper('warp')->__('Server'),
                'align'     => 'left',
                'index'     => 'server',
                'width'     => '150px',
          
      ));
      
      $this->addColumn('type', array(
      		'header'    => Mage::helper('warp')->__('Type'),
      		'align'     => 'left',
      		'index'     => 'type',
      		'width'     => '150px',
      
      ));

      $this->addColumn('action',  array(
                'header'    => Mage::helper('warp')->__('Action'),
                'width'     => '60px',
                'type'      => 'action',
                'getter'     => 'getId',
          		'align'     =>'center',
                'actions'   => array(
                    array(
                        'caption' => Mage::helper('sales')->__('Delete'),
                        'url'     => array('base'=>'*/adminhtml_index/clear'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'id',
                'is_system' => true,
      ));
      
      return parent::_prepareColumns();

  }

  protected function _prepareMassaction() {
  	if(!$this->_userMode){
        	$this->setMassactionIdField('main_table.id');
                $this->getMassactionBlock()->setFormFieldName('id');

                $this->getMassactionBlock()->addItem('delete', array(
                	'label'    => $this->__('Delete'),
                	'url'      => $this->getUrl('*/adminhtml_index/massdelete'),
                	'confirm'  => $this->__('Are you sure?')
        	));
	}
	return $this;
  }

  public function getRowUrl($row)
  {
	//return 'http' . ($row->getIsSecure() > 0 ? 's' : '') . '://' . $row->getServer() .  $row->getUri();
  }
 
}
