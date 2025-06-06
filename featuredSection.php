<?php
  $sql = "SELECT * FROM `".$config_databaseTablePrefix."featured` WHERE name LIKE '".database_safe($featuredSection["section"])."/%' ORDER BY sequence";

  if (database_querySelect($sql,$rows))
  {
    $sqlNames = array();

    $sqlCase = "CASE normalised_name";

    foreach($rows as $featured)
    {
      $featured["name"] = tapestry_normalise(str_replace($featuredSection["section"]."/","",$featured["name"]));

      $sqlNames[] = "'".$featured["name"]."'";

      $sqlCase .= " WHEN '".database_safe($featured["name"])."' THEN ".$featured["sequence"];
    }

    $sqlCase .= " END AS sequence";

    $sqlIn = implode(",",$sqlNames);

    $sql = "SELECT * , MIN( price ) AS minPrice, MAX( price ) AS maxPrice, COUNT( id ) AS numMerchants, ".$sqlCase." FROM `".$config_databaseTablePrefix."products` WHERE normalised_name IN (".$sqlIn.") GROUP BY normalised_name ORDER BY sequence";

    database_querySelect($sql,$rows);

    $featured["products"] = $rows;

    foreach($featured["products"] as $k => $product)
    {
      $featured["products"][$k]["productHREF"] = tapestry_productHREF($product);

      $featured["products"][$k]["reviewHREF"] = tapestry_reviewHREF($product);
    }
  }

  if (isset($featured)) require("html/featured.php");
?>