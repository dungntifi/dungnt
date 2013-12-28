<?php

$installer = $this;

$installer->startSetup();

$installer->run("

ALTER TABLE {$this->getTable('tinybrick_warp_cached_pages')} ADD `type` text(20) NULL DEFAULT NULL;

")->endSetup();