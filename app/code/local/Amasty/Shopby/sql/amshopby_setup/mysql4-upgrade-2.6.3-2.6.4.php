<?php
/**
 * Created by JetBrains PhpStorm.
 * User: NhimXu
 * Date: 11/13/14
 * Time: 2:15 PM
 * To change this template use File | Settings | File Templates.
 */
$this->startSetup();

$this->run("
    ALTER TABLE `{$this->getTable('amshopby/value')}` ADD COLUMN `size_chart_us` TEXT;
    ALTER TABLE `{$this->getTable('amshopby/value')}` ADD COLUMN `size_chart_uk` TEXT;
    ALTER TABLE `{$this->getTable('amshopby/value')}` ADD COLUMN `size_chart_eu` TEXT;
");

$this->endSetup();