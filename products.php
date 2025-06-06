<?php
  require("includes/common.php");

  $q = (isset($_GET["q"])?tapestry_normalise($_GET["q"],":\."):"");

  $rewrite = isset($_GET["rewrite"]);

  if ($q)
  {
    $banner["h2"] = translate("Price search results for")." <strong>".htmlspecialchars($q,ENT_QUOTES,$config_charset)."</strong>&nbsp;";

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
        $rows[$k]["reviewHREF"] = tapestry_reviewHREF($row);

        if ($config_useRewrite)
        {
          $rows[$k]["merchantHREF"] = $config_baseHREF."merchant/".urlencode(tapestry_hyphenate($row["merchant"]))."/";
        }
        else
        {
          $rows[$k]["merchantHREF"] = $config_baseHREF."search.php?q=merchant:".urlencode($row["merchant"]).":";
        }

        if ($config_useInteraction)
        {
          if ($rows[$k]["reviews"])
          {
            $rows[$k]["extraHTML"] = "<p>".tapestry_stars($rows[$k]["rating"],"")."&nbsp;<a href='".$rows[$k]["reviewHREF"]."'>".$rows[$k]["reviews"]." ".translate("Reviews")."</a></p>";
          }
          else
          {
            $rows[$k]["extraHTML"] = "<p><a href='".$rows[$k]["reviewHREF"]."'>".translate("Review This Product")."</a></p>";
          }
        }
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

      $prices["products"] = $rows;

      $header["title"] = $product["products"][0]["name"];

      $banner["breadcrumbs"] = array();

      $banner["breadcrumbs"][] = array("title"=>$product["products"][0]["name"],"href"=>"#");

      $header["meta"]["description"] = translate("Price search results for")." ".$product["products"][0]["name"];

      $header["meta"]["keywords"] = $product["products"][0]["name"];
    }
    else
    {
      $header["title"] = $q;

      $banner["h2"] .= "(".translate("product no longer available").")";
    }
  }

  if ($config_useRelated || (!isset($product)))
  {
    $ignoreFullText = FALSE;

    $wheres = array();

    if (isset($product))
    {
      $wheres[] = "name <> '".database_safe($product["products"][0]["name"])."'";

      if ($product["products"][0]["category"])
      {
        $wheres[] = "category = '".database_safe($product["products"][0]["category"])."'";

        $ignoreFullText = TRUE;
      }
      if ($product["products"][0]["brand"])
      {
        $wheres[] = "brand = '".database_safe($product["products"][0]["brand"])."'";

        $ignoreFullText = TRUE;
      }
    }

    if (!$ignoreFullText)
    {
      $wheres[] = "MATCH name AGAINST ('".database_safe($q)."')";
    }

    $where = implode(" AND ",$wheres);

    $sql = "SELECT DISTINCT(normalised_name),MATCH name AGAINST ('".database_safe($q)."') AS relevance FROM `".$config_databaseTablePrefix."products` ".($ignoreFullText?"IGNORE INDEX (name_2)":"USE INDEX (name_2)")." WHERE ".$where." ORDER BY relevance DESC LIMIT 3";

    $searchresults["numRows"] = database_querySelect($sql,$rows);

    $searchresults["products"] = $rows;

    if ($searchresults["numRows"])
    {
      $ins = array();

      foreach($rows as $row)
      {
        $ins[] = "'".database_safe($row["normalised_name"])."'";
      }

      $in = implode(",",$ins);

      $sql2 = "SELECT *,MIN(price) AS minPrice, COUNT(id) AS numMerchants FROM `".$config_databaseTablePrefix."products` WHERE normalised_name IN (".$in.") GROUP BY normalised_name ORDER BY NULL";

      database_querySelect($sql2,$rows2);

      $rows3 = array();

      foreach($rows2 as $row2)
      {
        $rows3[strtolower($row2["normalised_name"])] = $row2;
      }

      foreach($searchresults["products"] as $k => $p)
      {
        $searchresults["products"][$k] = array_merge($searchresults["products"][$k],$rows3[strtolower($p["normalised_name"])]);

        $searchresults["products"][$k]["productHREF"] = tapestry_productHREF($searchresults["products"][$k]);
      }

      $related = true;
    }
  }

  require("html/header.php");

  require("html/searchform.php");
   require("html/menu.php");
  require("html/banner.php");

  if (isset($product)) require("html/product.php");

  if (isset($prices)) require("html/prices.php");

  if (isset($related)) require("html/related.php");

  require("html/footer.php");
?>