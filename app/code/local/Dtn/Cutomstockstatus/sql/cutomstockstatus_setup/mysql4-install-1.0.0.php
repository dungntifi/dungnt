<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 11/27/14
 * Time: 9:44 AM
 * To change this template use File | Settings | File Templates.
 */
$installer = $this;

$installer->startSetup();

/*--------------------------------------Enable Quantity InStock---------------------------------------*/

$installer->addAttribute('catalog_product', 'enable_instock', array(
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Enable In Stock',
    'input'             => 'select',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_boolean',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '1',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'enable_instock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Quantity In Stocl---------------------------------------------*/

$installer->addAttribute('catalog_product', 'qty_in_stock', array(
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Qty In Stock',
    'input'             => 'text',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_table',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '0',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'qty_in_stock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Status In Stocl---------------------------------------------*/

$installer->addAttribute('catalog_product', 'status_instock', array(
    'type'              => 'varchar',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Status In Stock',
    'input'             => 'text',
    'class'             => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '0',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'status_instock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Enable vendor stock---------------------------------------------*/

$installer->addAttribute('catalog_product', 'enable_vendorstock', array(
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Enable Vendor Stock',
    'input'             => 'select',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_boolean',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '1',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'enable_vendorstock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Quantity Vendor Stock---------------------------------------------*/

$installer->addAttribute('catalog_product', 'qty_vendorstock', array(
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Qty Vendor Stock',
    'input'             => 'text',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_table',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '0',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'qty_vendorstock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Status vendor stock---------------------------------------------*/

$installer->addAttribute('catalog_product', 'status_vendorstock', array(
    'type'              => 'varchar',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Status Vendor Stock',
    'input'             => 'text',
    'class'             => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '0',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'status_vendorstock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Enable Vendor Pre-order Stock---------------------------------------------*/

$installer->addAttribute('catalog_product', 'enable_preorderstock', array(
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Enable Pre-Order Stock',
    'input'             => 'select',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_boolean',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '1',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'enable_preorderstock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Quantity Vendor Pre-order Stock---------------------------------------------*/

$installer->addAttribute('catalog_product', 'qty_preorderstock', array(
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Qty Pre-order Stock',
    'input'             => 'text',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_table',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '0',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'qty_preorderstock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Date Pre-Order Stock---------------------------------------------*/

$installer->addAttribute('catalog_product', 'date_preorderstock', array(
    'type'              => 'datetime',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Date Pre Order Stock',
    'input'             => 'date',
    'class'             => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'date_preorderstock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Status Vendor Pre-Order Stock---------------------------------------------*/

$installer->addAttribute('catalog_product', 'status_preorderstock', array(
    'type'              => 'varchar',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Status Pre-Order Stock',
    'input'             => 'text',
    'class'             => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '0',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'status_preorderstock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Enable Vendor Pre-order Stock---------------------------------------------*/

$installer->addAttribute('catalog_product', 'enable_vendor_preorderstock', array(
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Enable Vendor Pre-Order Stock',
    'input'             => 'select',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_boolean',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '1',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'enable_vendor_preorderstock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Quantity Vendor Pre-order Stock---------------------------------------------*/

$installer->addAttribute('catalog_product', 'qty_vendor_preorderstock', array(
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Qty Vendor Pre-order Stock',
    'input'             => 'text',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_table',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '0',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'qty_vendor_preorderstock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Date Vendor Pre-Order Stock---------------------------------------------*/

$installer->addAttribute('catalog_product', 'date_vendor_preorderstock', array(
    'type'              => 'datetime',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Date Vendor Pre Order Stock',
    'input'             => 'date',
    'class'             => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'date_vendor_preorderstock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Status Vendor Pre-Order Stock---------------------------------------------*/

$installer->addAttribute('catalog_product', 'status_vendor_preorderstock', array(
    'type'              => 'varchar',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Status Vendor Pre Order Stock',
    'input'             => 'text',
    'class'             => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '0',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'status_vendor_preorderstock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Enable Special order Stock---------------------------------------------*/

$installer->addAttribute('catalog_product', 'enable_specialorderstock', array(
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Enable Special order Stock',
    'input'             => 'select',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_boolean',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '1',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'enable_specialorderstock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Quantity Vendor Pre-order Stock---------------------------------------------*/

$installer->addAttribute('catalog_product', 'qty_specialorderstock', array(
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Qty Special Order Stock',
    'input'             => 'text',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_table',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '0',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'qty_specialorderstock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Date Vendor Pre-Order Stock---------------------------------------------*/

$installer->addAttribute('catalog_product', 'date_specialorderstock', array(
    'type'              => 'datetime',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Date Special Order Stock',
    'input'             => 'date',
    'class'             => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'date_specialorderstock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Status Vendor Pre-Order Stock---------------------------------------------*/

$installer->addAttribute('catalog_product', 'status_specialorderstock', array(
    'type'              => 'varchar',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Status Special Order Stock',
    'input'             => 'text',
    'class'             => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '0',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'status_specialorderstock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Number Special-Order Stock---------------------------------------------*/

$installer->addAttribute('catalog_product', 'numberofdays_stock', array(
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Number Of Days Stock',
    'input'             => 'text',
    'class'             => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '0',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'numberofdays_stock');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Enable Quantity Unknown---------------------------------------*/

$installer->addAttribute('catalog_product', 'enable_unknown', array(
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Enable Unknown',
    'input'             => 'select',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_boolean',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '1',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'enable_unknown');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Quantity Unknown--------------------------------------------*/

$installer->addAttribute('catalog_product', 'qty_unknown', array(
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Qty Unknown',
    'input'             => 'text',
    'class'             => '',
    'source'            => 'eav/entity_attribute_source_table',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '0',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'qty_unknown');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

/*--------------------------------------Status In Stocl---------------------------------------------*/

$installer->addAttribute('catalog_product', 'status_unknown', array(
    'type'              => 'varchar',
    'backend'           => '',
    'frontend'          => '',
    'label'             => 'Status Unknown Stock',
    'input'             => 'text',
    'class'             => '',
    'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'default'           => '0',
    'searchable'        => false,
    'filterable'        => false,
    'comparable'        => false,
    'visible_on_front'  => false,
    'unique'            => false,
    'apply_to'          => '',
    'is_configurable'   => false
));
$attributeId = $installer->getAttributeId('catalog_product', 'status_unknown');

foreach ($installer->getAllAttributeSetIds('catalog_product') as $attributeSetId)
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId('catalog_product', $attributeSetId, 'Custom Stock Status');
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId('catalog_product', $attributeSetId);
    }
    $installer->addAttributeToSet('catalog_product', $attributeSetId, $attributeGroupId, $attributeId);
}

$installer->endSetup();