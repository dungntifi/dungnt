<?php
/**
 * Automatic Configurables Extension
 *
 * @category   QS
 * @package    QS_Quickconfigurables
 * @author     Quart-soft Magento Team <magento@quart-soft.com> 
 * @copyright  Copyright (c) 2010 Quart-soft Ltd http://quart-soft.com
 */
class QS_Quickconfigurables_Block_Adminhtml_Catalog_Product_Edit_Tab_Super_Config extends Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Super_Config implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Initialize block
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('quickconfigurables/catalog/product/edit/super/config.phtml');
    }    
	
	/**
     * Prepare Layout data
     *
     * @return Mage_Adminhtml_Block_Catalog_Product_Edit_Tab_Super_Config
     */
    protected function _prepareLayout()
    {
        if ($this->_getProduct()->getId()) {
            $this->setChild('combination',
                $this->getLayout()->createBlock('quickconfigurables/adminhtml_catalog_product_edit_tab_super_config_combination')
            );
		}
        return parent::_prepareLayout();
    }
	
    /**
     * Retrieve Create New Empty Product URL
     *
     * @return string
     */
	public function getCombinationsUrl()
	{
		return $this->getUrl(
            'quickconfigurables/adminhtml_product/combination',
            array(
                'set'      => $this->_getProduct()->getAttributeSetId(),
				'product'      => $this->_getProduct()->getId(),
                'required' => $this->_getRequiredAttributesIds(),
            )
        );
	}
	
    /**
     * Retrieve Create New Empty Product URL
     *
     * @return string
     */
	public function getCreateSimpleProductUrl()
	{
		return $this->getUrl('quickconfigurables/adminhtml_product/simple');
	}	
}
