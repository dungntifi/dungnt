<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 10/9/14
 * Time: 2:27 PM
 * To change this template use File | Settings | File Templates.
 */
$installer = $this;
$installer->startSetup();

    /**
     * Creating extensions tables and filling it with initial data
     */
try {
    $installer->run("
        CREATE TABLE IF NOT EXISTS `{$this->getTable('saveforlater/savemain')}` (
            `id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
            `customer_id` INT( 10 ) NOT NULL ,
            `updated_at` DATETIME NOT NULL ,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

        CREATE TABLE IF NOT EXISTS `{$this->getTable('saveforlater/saveitem')}` (
            `item_id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
            `main_id` INT( 10 ) NOT NULL ,
            `product_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'Product ID',
            `store_id` smallint(5) unsigned DEFAULT NULL COMMENT 'Store ID',
            `created_at` DATETIME NOT NULL ,
            `option` text COMMENT 'Value',
            `qty` decimal(12,4) NOT NULL COMMENT 'Qty',
            PRIMARY KEY (`item_id`)
        ) ENGINE = InnoDB DEFAULT CHARSET=utf8;
    ");
} catch (Exception $ex) {
    Mage::logException($ex);
}
$installer->endSetup();