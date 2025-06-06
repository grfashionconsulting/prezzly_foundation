<?php
  require("includes/common.php");

  $atoz["items"] = array();

  $sql = "SELECT DISTINCT(brand) as brand FROM `".$config_databaseTablePrefix."products` ORDER BY brand";

  if (database_querySelect($sql,$rows))
  {
    foreach($rows as $product)
    {
      if ($product["brand"])
      {
        $item = array();

        $item["name"] = $product["brand"];

        $item["href"] = tapestry_indexHREF("brand",$product["brand"]);

        $atoz["items"][] = $item;
      }
    }
  }

  $header["title"] = translate("Brand")." A-Z";

  $banner["breadcrumbs"] = array();

  $banner["breadcrumbs"][] = array("title"=>translate("Brand")." A-Z","href"=>tapestry_indexHREF("brand"));

  $banner["h2"] = "<strong>".translate("Brand")." A-Z</strong>";

  require("html/header.php");

  require("html/searchform.php");  
  require("html/menu.php");  
  require("html/banner.php");

  require("html/atoz.php");

  require("html/footer.php");
?>