<?php
  require("includes/common.php");

  $atoz["items"] = array();

  $sql = "SELECT DISTINCT(merchant) FROM `".$config_databaseTablePrefix."products` ORDER BY merchant";

  if (database_querySelect($sql,$rows))
  {
    foreach($rows as $product)
    {
      $item = array();

      $item["name"] = $product["merchant"];

      if (file_exists("logos/".$product["merchant"].$config_logoExtension))
      {
        $item["logo"] = $config_baseHREF."logos/".str_replace(" ","%20",$product["merchant"]).$config_logoExtension;
      }

      $item["href"] = tapestry_indexHREF("merchant",$product["merchant"]);

      $atoz["items"][] = $item;
    }
  }

  $header["title"] = translate("Merchant")." A-Z";

  $banner["breadcrumbs"] = array();

  $banner["breadcrumbs"][] = array("title"=>translate("Merchant")." A-Z","href"=>tapestry_indexHREF("merchant"));

  $banner["h2"] = "<strong>".translate("Merchant")." A-Z</strong>";

  require("html/header.php");

  require("html/searchform.php");
  require("html/menu.php");
  require("html/banner.php");

  require("html/atoz.php");

  require("html/footer.php");
?>