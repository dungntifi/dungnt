<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 11/14/14
 * Time: 3:49 PM
 * To change this template use File | Settings | File Templates.
 */
$installer = $this;

$installer->startSetup();
$installer->run("ALTER TABLE review_detail ADD COLUMN email VARCHAR(255) NULL");
$installer->endSetup();
?>