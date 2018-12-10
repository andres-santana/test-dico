<?php
$installer = $this;

$installer->startSetup();
//$dbConfig = Mage::getSingleton('core/resource')->getConnection()->getConfig();
//$dbName = $dbConfig['dbname'];

//$config = Mage::getResourceModel('sales/order')->getConnection()->getConfig();
// $config is an array
//$dbname = $config['dbname'];

$dbName = (string)Mage::getConfig()->getNode('global/resources/default_setup/connection/dbname');


$dbConnection 	= Mage::getSingleton('core/resource')->getConnection('core_write');

/********* DROP `user_role_type` COLUMN FROM `admin_role` TABLE IF EXISTS  *********/
$admin_role_table						= $dbConnection->query("
															SELECT *
															FROM information_schema.COLUMNS
															WHERE
																TABLE_SCHEMA = '".$dbName."'
															AND TABLE_NAME = '".$this->getTable('admin_role')."'	
															AND COLUMN_NAME = 'user_role_type'
														"); 
$admin_role_table_col 					= $admin_role_table->fetch(PDO::FETCH_ASSOC);
if($admin_role_table_col){ $dbConnection->query("ALTER TABLE ".$this->getTable('admin_role')." DROP COLUMN `user_role_type`"); }

/********* DROP `usertype` COLUMN FROM `admin_user` TABLE IF EXISTS  *********/
$admin_user_table						= $dbConnection->query("
																SELECT *
																FROM information_schema.COLUMNS
																WHERE
																	TABLE_SCHEMA = '".$dbName."'
																AND TABLE_NAME = '".$this->getTable('admin_user')."'	
																AND COLUMN_NAME = 'usertype'
															"); 
$admin_user_table_col 					= $admin_user_table->fetch(PDO::FETCH_ASSOC);
if($admin_user_table_col){ $dbConnection->query("ALTER TABLE ".$this->getTable('admin_user')." DROP COLUMN `usertype`"); }

/********* DROP `vender_id` COLUMN FROM `catalog_product_entity` TABLE IF EXISTS  *********/
$catalog_product_entity_table			= $dbConnection->query("
																SELECT *
																FROM information_schema.COLUMNS
																WHERE
																	TABLE_SCHEMA = '".$dbName."'
																AND TABLE_NAME = '".$this->getTable('catalog_product_entity')."'
																AND COLUMN_NAME = 'vender_id'
															"); 
$catalog_product_entity_table_col 		= $catalog_product_entity_table->fetch(PDO::FETCH_ASSOC);
if($catalog_product_entity_table_col){ $dbConnection->query("ALTER TABLE ".$this->getTable('catalog_product_entity')." DROP COLUMN `vender_id`"); }

/********* DROP `vender_ids` COLUMN FROM `sales_flat_order` TABLE IF EXISTS  *********/
$sales_flat_order_table					= $dbConnection->query("
																SELECT * 
																FROM information_schema.COLUMNS
																WHERE
																	TABLE_SCHEMA = '".$dbName."'
																AND TABLE_NAME = '".$this->getTable('sales_flat_order')."'	
																AND COLUMN_NAME = 'vender_ids'
															"); 
$sales_flat_order_table_col 			= $sales_flat_order_table->fetch(PDO::FETCH_ASSOC);
if($sales_flat_order_table_col){ $dbConnection->query("ALTER TABLE ".$this->getTable('sales_flat_order')." DROP COLUMN `vender_ids`"); }

$installer->run("

DROP TABLE IF EXISTS {$this->getTable('user_website')};

CREATE TABLE {$this->getTable('user_website')} (
  `id` int(11) unsigned NOT NULL auto_increment,
  `userid` int(11) NULL,
  `websiteid` int(11) NULL,
  `store_ids` varchar(255) NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE {$this->getTable('admin_role')} ADD COLUMN `user_role_type` varchar(255);

ALTER TABLE {$this->getTable('admin_user')} ADD COLUMN `usertype` varchar(255);

ALTER TABLE {$this->getTable('catalog_product_entity')} ADD COLUMN `vender_id` int(11);

ALTER TABLE {$this->getTable('sales_flat_order')} ADD COLUMN `vender_ids` text;

INSERT INTO {$this->getTable('admin_role')} (`parent_id`, `tree_level`, `sort_order`, `role_type`,  `user_id`, `role_name`, `user_role_type`) VALUES(0, 1, 0, 'G',  0, 'Vendor user', 'store_role');

");

$result								= $dbConnection->query("SELECT role_id FROM ".$this->getTable('admin_role')." WHERE user_role_type='store_role'");
$rows								= $result->fetch(PDO::FETCH_ASSOC);

$lastAdminRoleId 					= $rows['role_id'];

$installer->run("
	INSERT INTO {$this->getTable('admin_rule')}(`role_id`, `resource_id` , `privileges`, `assert_id`, `role_type`, `permission`) 
	VALUES	({$lastAdminRoleId}, 'admin/catalog', '', '0', 'G', 'allow'),
			({$lastAdminRoleId}, 'admin/catalog/categories', '', '0', 'G', 'allow'),
			({$lastAdminRoleId}, 'admin/catalog/products', '', '0', 'G', 'allow'),
			({$lastAdminRoleId}, 'admin/catalog/attributes', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/catalog/attributes/attributes', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/catalog/attributes/sets', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/catalog/update_attributes', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/catalog/urlrewrite', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/catalog/search', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/catalog/reviews_ratings', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/catalog/reviews_ratings/reviews', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/catalog/reviews_ratings/reviews/all', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/catalog/reviews_ratings/ratings', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/catalog/tag', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/catalog/tag/all', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/catalog/tag/pending', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/catalog/sitemap', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales', '', '0', 'G', 'allow'),
			({$lastAdminRoleId}, 'admin/sales/order', '', '0', 'G', 'allow'),
			({$lastAdminRoleId}, 'admin/sales/order/actions', '', '0', 'G', 'allow'),
			({$lastAdminRoleId}, 'admin/sales/order/actions/view', '', '0', 'G', 'allow'),
			({$lastAdminRoleId}, 'admin/sales/order/actions/create', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/order/actions/email', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/order/actions/reorder', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/order/actions/edit', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/order/actions/cancel', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/order/actions/review_payment', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/order/actions/capture', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/order/actions/invoice', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/order/actions/creditmemo', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/order/actions/hold', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/order/actions/unhold', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/order/actions/ship', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/order/actions/comment', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/order/actions/emails', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/invoice', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/shipment', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/creditmemo', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/transactions', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/transactions/fetch', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/recurring_profile', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/billing_agreement', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/billing_agreement/actions', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/billing_agreement/actions/view', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/billing_agreement/actions/manage', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/billing_agreement/actions/use', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/checkoutagreement', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/tax', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/tax/classes_customer', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/tax/classes_product', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/tax/import_export', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/tax/rates', '', '0', 'G', 'deny'),
			({$lastAdminRoleId}, 'admin/sales/tax/rules', '', '0', 'G', 'deny')
	");

$installer->endSetup();
?>