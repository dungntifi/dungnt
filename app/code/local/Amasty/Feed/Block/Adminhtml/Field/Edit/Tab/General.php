<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2008-2012 Amasty (http://www.amasty.com)
* @package Amasty_Feed
*/ 
class Amasty_Feed_Block_Adminhtml_Field_Edit_Tab_General extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $feed = Mage::registry('amfeed_field');
        
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        /* @var $hlp Amasty_Feed_Helper_Data */
        $hlp = Mage::helper('amfeed');
    
        $fldInfo = $form->addFieldset('general', array('legend'=> $hlp->__('General')));
        $fldInfo->addField('title', 'text', array(
            'label'     => $hlp->__('Name'),
            'class'     => 'required-entry',
            'required'  => true,
            'name'      => 'title',
        ));
        $fldInfo->addField('code', 'text', array(
            'label'     => $hlp->__('Code'),
            'required'  => true,
            'name'      => 'code',
            'class'		=> 'validate-code',
            'note'		=> $hlp->__('For internal use. Must be unique with no spaces'),
        ));
        
        $attributes = $hlp->getAttributes();
        
        $attributes = array_merge(array(
            '' => ''
        ), $attributes);
        
        
        $fldInfo->addField('base_attr', 'select', array(
            'label'     => $hlp->__('Base Attribute'),
            'name'      => 'base_attr',
            'values'    => $attributes,
//            'onchange' => "!this.value ? $('transform').setAttribute('disabled', true) : $('transform').removeAttribute('disabled')"
        ));         
//        
           
        $fldInfo->addField('transform', 'text', array(
//            'disabled' => $feed->getBaseAttr() ? false : true,            
            'label'     => $hlp->__('Modification'),
            'name'      => 'transform',
            'note'		=> $this->__('Use percentage (like +15%), or fixed value (like -20) to modify numerical values'),
        ));

        $fldInfo->addField('default_value', 'textarea', array(
            'label'     => $hlp->__('Default value'),
            'rows'      => 1,
            'cols'      => 1,
            'name'      => 'default_value',
            //'note'		=> $this->__('Will be used if the base attribute value is empty. You can create advanced templates like:<br/> `Buy {name} for {price}`'),
        ));               
    
        //set form values
        $form->setValues($feed->getData()); 
        
        return parent::_prepareForm();
    }
}