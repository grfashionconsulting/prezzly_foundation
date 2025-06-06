<?php
  require("includes/common.php");

  $now = time();

  $sql = "SELECT * FROM `".$config_databaseTablePrefix."vouchers` WHERE ( (valid_from < '".$now."' AND valid_to = '0') OR (valid_from <= '".$now."' AND valid_to > '".$now."') ) ORDER BY merchant";

  if (database_querySelect($sql,$vouchers))
  {
    foreach($vouchers as $k => $voucher)
    {
      if (file_exists("logos/".$voucher["merchant"].$config_logoExtension))
      {
        $vouchers[$k]["logo"] = $config_baseHREF."logos/".str_replace(" ","%20",$voucher["merchant"]).$config_logoExtension;
      }

      $vouchers[$k]["href"] = $config_baseHREF."search.php?q=voucher:".urlencode($voucher["merchant"]).":".urlencode($voucher["code"]);

      switch($voucher["discount_type"])
      {
        case "#":

          $vouchers[$k]["text"] = translate("Save")." ".tapestry_price($voucher["discount_value"]);

          break;

        case "%":

          $parts = explode(".",$voucher["discount_value"]);

          $i = $parts[0];

          $d = (intval($parts[1])?".".rtrim($parts[1],"0"):"");

          $vouchers[$k]["text"] = translate("Save")." ".$i.$d."%";

          break;

        case "S":

          $vouchers[$k]["text"] = $voucher["discount_text"];

          break;
      }

      if (($voucher["min_price"] > 0) && ($voucher["max_price"] > 0))
      {
        $vouchers[$k]["text"] .= " ".translate("when you spend between")." ".tapestry_price($voucher["min_price"])." ".translate("and")." ".tapestry_price($voucher["max_price"]);
      }
      elseif($voucher["min_price"] > 0)
      {
        $vouchers[$k]["text"] .= " ".translate("when you spend")." ".tapestry_price($voucher["min_price"]);
      }
      elseif($voucher["max_price"] > 0)
      {
        $vouchers[$k]["text"] .= " ".translate("when you spend up to")." ".tapestry_price($voucher["max_price"]);
      }

      if ($voucher["match_value"])
      {
        $vouchers[$k]["text"] .= " ".translate("on selected products");
      }

      $vouchers[$k]["text"] .= " ".translate("using voucher code")." <strong>".$voucher["code"]."</strong>";
    }
    $coupons["vouchers"] = $vouchers;
  }

  $header["title"] = translate("Voucher Codes");

  $banner["breadcrumbs"] = array();

  $banner["breadcrumbs"][] = array("title"=>translate("Voucher Codes"),"href"=>$config_baseHREF."vouchers.php");

  $banner["h2"] = "<strong>".translate("Voucher Codes")."</strong>";

  require("html/header.php");

  require("html/searchform.php");  
  require("html/menu.php");  
  require("html/banner.php");

  require("html/coupons.php");

  require("html/footer.php");
?>