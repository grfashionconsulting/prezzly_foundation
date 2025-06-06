<?php 

include("includes/common.php"); 

$sql = "CREATE TABLE IF NOT EXISTS `". $config_databaseTablePrefix ."price_alerts` (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `product` varchar(255) NOT NULL,
	  `hash` varchar(32) NOT NULL,
	  `subscribed_price` decimal(10,2) NOT NULL,
	  `target_price` decimal(10,2) NOT NULL,
	  `price` decimal(10,2) NOT NULL,
	  `email` varchar(100) NOT NULL,
	  `last_checked` INT(11),
	  PRIMARY KEY (`id`)
	) ENGINE=MyISAM";
	
	database_queryModify($sql,$result);
	
	print "Price Alert table created";

?>