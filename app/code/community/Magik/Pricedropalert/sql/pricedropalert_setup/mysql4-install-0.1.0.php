<?php
$installer = $this;
$installer->startSetup();
$installer->run("
  DROP TABLE IF EXISTS {$this->getTable('magik_pricedropalert')};
  CREATE TABLE {$this->getTable('magik_pricedropalert')} (
    `id` int(11) unsigned NOT NULL auto_increment,  
    `email` varchar(255) NOT NULL default '',  
    `productid` int(11) NOT NULL default '0',    
    `product_name` text NOT NULL default '',  
    `product_price` Float NOT NULL default '0',  
    `status` smallint(6) NOT NULL default '0',
    `active_status` int(6) NOT NULL default '0' COMMENT  '0-subscribe/1-unsubscribe',
    `created_time` datetime NULL,
    `update_time` datetime NULL, 
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"

);

$installer->endSetup();
	 