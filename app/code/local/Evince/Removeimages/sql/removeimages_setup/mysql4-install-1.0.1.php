<?php

$installer = $this;

$installer->startSetup();

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('removeimages')};
CREATE TABLE {$this->getTable('removeimages')} (
 `removeimages_id` int(11) unsigned NOT NULL auto_increment,
 `filename` varchar(255) NOT NULL default '',
 PRIMARY KEY  (`removeimages_id`),
 UNIQUE KEY `filename` (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8; 
    ");

$installer->endSetup(); 