<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('rfq')};
CREATE TABLE {$this->getTable('rfq')} (
  `rfq_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `website_name` varchar(255) NOT NULL,
  `avg_order` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `cutomer_email_status` smallint(6) NOT NULL DEFAULT '2',
  `status` smallint(6) NOT NULL DEFAULT '2',
  `created_time` datetime DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,  
  PRIMARY KEY (`rfq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 
