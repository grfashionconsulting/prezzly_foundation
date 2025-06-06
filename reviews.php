<?php
  require("includes/common.php");

  $q = (isset($_GET["q"])?tapestry_normalise($_GET["q"],":\."):"");

  $rewrite = isset($_GET["rewrite"]);

  if (isset($_POST["submit"]))
  {
    if (!isset($_POST["comments"])) $_POST["comments"] = "";

    if ($_POST["rating"] && ($_POST["rating"] == $_POST["confirm"]))
    {
      $sql = "INSERT INTO `".$config_databaseTablePrefix."reviews` SET created='".time()."',approved='0',product_name='".database_safe($q)."',rating='".database_safe($_POST["rating"])."',comments='".database_safe(stripslashes(trim($_POST["comments"])))."'";

      database_queryModify($sql,$insertId);
    }

    if ($config_useRewrite)
    {
      header("Location: ".$_SERVER["REQUEST_URI"]."?pending=1");
    }
    else
    {
      header("Location: ".$_SERVER["REQUEST_URI"]."&pending=1");
    }

    exit();
  }

  if ($q)
  {
    $banner["h2"] = translate("Product reviews for")." <strong>".htmlspecialchars($q,ENT_QUOTES,$config_charset)."</strong>&nbsp;";

    $sql = "SELECT * FROM `".$config_databaseTablePrefix."products` WHERE normalised_name = '".database_safe($q)."'";

    $numRows = database_querySelect($sql,$rows);

    if ($numRows)
    {
      if ($config_useVoucherCodes === 1)
      {
        $rows = tapestry_applyVoucherCodes($rows);
      }

      foreach($rows as $k => $row)
      {
        $rows[$k]["productHREF"] = tapestry_productHREF($row);

        $rows[$k]["extraHTML"] = "<p><a href='".$rows[$k]["productHREF"]."'>".translate("Show All Prices")."</a></p>";
      }

      function cmp($a, $b)
      {
        if ($a["price"] == $b["price"])
        {
            return 0;
        }
        return ($a["price"] < $b["price"]) ? -1 : 1;
      }

      usort($rows,"cmp");

      $product["products"] = $rows;

      $sql = "SELECT * FROM `".$config_databaseTablePrefix."reviews` WHERE product_name = '".database_safe($q)."' AND approved <> '0' ORDER BY created";

      if (database_querySelect($sql,$rows))
      {
        $ratings["reviews"] = $rows;
      }

      $header["title"] = $product["products"][0]["name"]." ".translate("Reviews");

      $banner["breadcrumbs"] = array();

      $banner["breadcrumbs"][] = array("title"=>$product["products"][0]["name"]." ".translate("Reviews"),"href"=>"#");

      $header["meta"]["description"] = translate("Product reviews for")." ".$product["products"][0]["name"];

      $header["meta"]["keywords"] = $product["products"][0]["name"]." ".translate("Reviews");
    }
    else
    {
      $header["title"] = $q." ".translate("Reviews");

      $banner["h2"] .= "(".translate("product no longer available").")";
    }
  }

  require("html/header.php");
  
   require("html/searchform.php");

  require("html/menu.php"); 

  require("html/banner.php");

  if (isset($product))
  {
    require("html/product.php");

    require("html/ratings.php");
  }

  require("html/footer.php");
?>