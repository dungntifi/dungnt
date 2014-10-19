<?php
$installer = new Mage_Eav_Model_Entity_Setup($this->_resourceName);
$installer->startSetup();

$attrCode = 'color_position';
$attrLabel = 'Color position';
$attrNote = 'Sort order of color attribute';
$attrGroupName = 'Dresses';

$installer->addAttribute(Mage_Catalog_Model_Product::ENTITY, $attrCode, array(
    'type'              => 'int',
    'backend'           => '',
    'frontend'          => '',
    'label'             => $attrLabel,
    'note'              => $attrNote,
    'input'             => 'text',
    'class'             => '',
    'source'            => '',
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
    'apply_to'          => 'configurable',
    'is_configurable'   => false,
));
$attributeId = $installer->getAttributeId(Mage_Catalog_Model_Product::ENTITY, $attrCode);

foreach ($installer->getAllAttributeSetIds(Mage_Catalog_Model_Product::ENTITY) as $attributeSetId) 
{
    try {
        $attributeGroupId = $installer->getAttributeGroupId(Mage_Catalog_Model_Product::ENTITY, $attributeSetId, $attrGroupName);
    } catch (Exception $e) {
        $attributeGroupId = $installer->getDefaultAttributeGroupId(Mage_Catalog_Model_Product::ENTITY, $attributeSetId);
    }
    $installer->addAttributeToSet(Mage_Catalog_Model_Product::ENTITY, $attributeSetId, $attributeGroupId, $attributeId);
}

$installer->endSetup();