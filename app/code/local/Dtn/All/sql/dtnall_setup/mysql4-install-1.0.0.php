<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 10/14/14
 * Time: 3:20 PM
 * To change this template use File | Settings | File Templates.
 */
/** @var Mage_Sales_Model_Mysql4_Setup $installer  */
$installer = $this;

$installer->startSetup();



//$installer->run("
//    ALTER TABLE sales_flat_order_item ADD COLUMN IF NOT EXISTS event_date DATETIME NULL;
//    ALTER TABLE sales_flat_invoice_item ADD COLUMN IF NOT EXISTS event_date DATETIME NULL;
//    ALTER TABLE sales_flat_shipment_item ADD COLUMN IF NOT EXISTS event_date DATETIME NULL;
//");

$installer->addAttribute('order_item','event_date',array(
    'type' => 'DATETIME',
    'nullable' => true,
    'comment' => 'Event Date'
));
$installer->addAttribute('invoice_item','event_date',array(
    'type' => 'DATETIME',
    'nullable' => true,
    'comment' => 'Event Date'
));
$installer->addAttribute('shipment_item','event_date',array(
    'type' => 'DATETIME',
    'nullable' => true,
    'comment' => 'Event Date'
));

$installer->updateAttribute('catalog_product', 'created_at', 'frontend_label', 'Date Added');
$installer->updateAttribute('catalog_product', 'created_at', 'used_for_sort_by', '1');
$installer->endSetup();
?>