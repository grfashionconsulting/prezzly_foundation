<?php
  require("includes/common.php");

  header("Content-Type: application/json;");

  if ($_GET["q"])
  {
    $sql = "SELECT DISTINCT(normalised_name) AS name FROM `".$config_databaseTablePrefix."products` WHERE name LIKE '".database_safe($_GET["q"])."%' LIMIT 5";
    
    if (!database_querySelect($sql,$products))
    {
      $sql = "SELECT DISTINCT(normalised_name) AS name FROM `".$config_databaseTablePrefix."products` WHERE name LIKE '%".database_safe($_GET["q"])."%' LIMIT 5";
      
      database_querySelect($sql,$products);
    }

    print json_encode(array("totalResultsCount"=>count($products),"products"=>$products));
  }
  
  exit();
?>