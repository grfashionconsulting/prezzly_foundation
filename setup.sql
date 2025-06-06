CREATE TABLE jobs (
  `id` int(11) NOT NULL auto_increment,
  `directory` varchar(255) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `url` text NOT NULL,
  `unzip` int(11) NOT NULL default '0',
  `minsize` varchar(255) NOT NULL default '',
  `lastrun` int(11) NOT NULL default '0',
  `status` varchar(255) NOT NULL default '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE feeds (
  `id` int(11) NOT NULL auto_increment,
  `filename` varchar(255) NOT NULL default '',
  `registered` int(11) NOT NULL default '0',
  `imported` int(11) NOT NULL default '0',
  `products` int(11) NOT NULL default '0',
  `format` varchar(255) NOT NULL default '',
  `merchant` varchar(64) NOT NULL default '',
  `field_name` varchar(255) NOT NULL default '',
  `field_description` varchar(255) NOT NULL default '',
  `field_image_url` varchar(255) NOT NULL default '',
  `field_buy_url` varchar(255) NOT NULL default '',
  `field_price` varchar(255) NOT NULL default '',
  `field_category` varchar(255) NOT NULL default '',
  `user_category` varchar(64) NOT NULL default '',
  `field_brand` varchar(255) NOT NULL default '',
  `user_brand` varchar(64) NOT NULL default '',
  `clicks` int(11) NOT NULL default '0',
  `field_merchant` varchar(255) NOT NULL default '',
  PRIMARY KEY (`id`),
  KEY `merchant` (`merchant`)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE filters (
  `id` int(11) NOT NULL auto_increment,
  `filename` varchar(255) NOT NULL default '',
  `field` varchar(255) NOT NULL default '',
  `name` varchar(30) NOT NULL default '',
  `created` int(11) NOT NULL default '0',
  `data` blob,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE products (
  `id` int(11) NOT NULL auto_increment,
  `merchant` varchar(64) NOT NULL default '',
  `filename` varchar(255) NOT NULL default '',
  `name` varchar(255) NOT NULL default '',
  `description` text NOT NULL,
  `image_url` varchar(255) NOT NULL default '',
  `buy_url` text NOT NULL,
  `price` decimal(10,2) NOT NULL default '0.00',
  `category` varchar(64) NOT NULL default '',
  `brand` varchar(64) NOT NULL default '',
  `rating` int(11) NOT NULL default '0',
  `reviews` int(11) NOT NULL default '0',
  `search_name` varchar(255) NOT NULL default '',
  `normalised_name` varchar(255) NOT NULL default '',
  `original_name` varchar(255) NOT NULL default '',
  `voucher_code` varchar(255) NOT NULL default '',
  `categoryid` int(11) NOT NULL default '0',
  `dupe_hash` varchar(32) NOT NULL default '',
  PRIMARY KEY (`id`),
  KEY `filename` (`filename`),
  KEY `merchant` (`merchant`),
  KEY `category` (`category`),
  KEY `brand` (`brand`),
  KEY `categoryid` (`categoryid`),
  FULLTEXT KEY `name_2` (`name`),
  KEY `normalised_name` (`normalised_name`),
  KEY `search_name_price_id` (`search_name`,`price`,`id`),
  KEY `search_name_merchant_price_id` (`search_name`,`merchant`,`price`,`id`),
  KEY `search_name_category_price_id` (`search_name`,`category`,`price`,`id`),
  KEY `search_name_brand_price_id` (`search_name`,`brand`,`price`,`id`),
  UNIQUE KEY `dupe_filter` (`dupe_hash`)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE reviews (
  `id` int(11) NOT NULL auto_increment,
  `created` int(11) NOT NULL default '0',
  `approved` int(11) NOT NULL default '0',
  `product_name` varchar(255) NOT NULL default '',
  `rating` int(11) NOT NULL default '0',
  `comments` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_name` (`product_name`)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE brands (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `alternates` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE categories (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `alternates` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE categories_hierarchy (
  `id` int(11) NOT NULL auto_increment,
  `parent` int(11) NOT NULL default '0',
  `name` varchar(64) NOT NULL default '',
  `alternates` text,
  PRIMARY KEY  (id),
  UNIQUE KEY dupe_filter (parent,name)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE productsmap (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `alternates` text,
  `category` varchar(255) NOT NULL default '',
  `brand` varchar(255) NOT NULL default '',
  `image_url` varchar(255) NOT NULL default '',
  `description` text,
  `meta` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE productsmap_regexp (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `trigger_merchant` varchar(255) NOT NULL default '',
  `trigger_category` varchar(255) NOT NULL default '',
  `trigger_brand` varchar(255) NOT NULL default '',
  `regexp` varchar(255) NOT NULL default '',
  `product_name` varchar(255) NOT NULL default '',
  `category` varchar(255) NOT NULL default '',
  `brand` varchar(255) NOT NULL default '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE featured (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `sequence` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE vouchers (
  `id` int(11) NOT NULL auto_increment,
  `merchant` varchar(255) NOT NULL default '',
  `code` varchar(255) NOT NULL default '',
  `match_type` varchar(255) NOT NULL default '',
  `match_field` varchar(255) NOT NULL default '',
  `match_value` varchar(255) NOT NULL default '',
  `discount_type` char(1) NOT NULL default '',
  `discount_value` decimal(10,2) NOT NULL default '0.00',
  `discount_text` varchar(255) NOT NULL default '',
  `min_price` decimal(10,2) NOT NULL default '0.00',
  `max_price` decimal(10,2) NOT NULL default '0.00',
  `valid_from` int(11) NOT NULL default '0',
  `valid_to` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;


CREATE TABLE voucherfeeds (
  `id` int(11) NOT NULL auto_increment,
  `filename` varchar(255) NOT NULL default '',
  `registered` int(11) NOT NULL default '0',
  `format` varchar(255) NOT NULL default '',
  `field_merchant` varchar(255) NOT NULL default '',
  `field_code` varchar(255) NOT NULL default '',
  `field_valid_from` varchar(255) NOT NULL default '',
  `field_valid_to` varchar(255) NOT NULL default '',
  `field_description` varchar(255) NOT NULL default '',
  `merchant_mappings` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  KEY `filename` (`filename`)
) ENGINE=MyISAM DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
