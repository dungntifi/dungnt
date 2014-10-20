<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 10/14/14
 * Time: 3:20 PM
 * To change this template use File | Settings | File Templates.
 */
$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE sales_flat_order_item ADD COLUMN event_date DATETIME NULL;
    ALTER TABLE sales_flat_invoice_item ADD COLUMN event_date DATETIME NULL;
    ALTER TABLE sales_flat_shipment_item ADD COLUMN event_date DATETIME NULL;
");
$installer->endSetup();
?>