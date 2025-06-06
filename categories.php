<?php
  require_once("includes/common.php");

  $path = (isset($_GET["path"])?$_GET["path"]:"");

  if ($config_useCategoryHierarchy)
  {
    $banner["breadcrumbs"] = array();

    $banner["breadcrumbs"][] = array("title"=>translate("Category"),"href"=>tapestry_indexHREF("category"));

    $nodeInfo = tapestry_categoryHierarchyNodeInfo(str_replace("-"," ",$path));

    foreach($nodeInfo["hierarchy"] as $category)
    {
      $banner["breadcrumbs"][] = array("title" => $category["name"], "href" => tapestry_indexHREF("category",$category["path"]));
    }

    if ($path)
    {
      $banner["breadcrumbs"][] = array("title" => $nodeInfo["name"], "href" => tapestry_indexHREF("category",$nodeInfo["path"]));
    }

    $sql = "SELECT id,name FROM `".$config_databaseTablePrefix."categories_hierarchy` WHERE parent='".$nodeInfo["id"]."' ORDER BY name";

    if (database_querySelect($sql,$categories))
    {
      $atoz["items"] = array();

      foreach($categories as $category)
      {
        $item["name"] = $category["name"];

        $item["href"] = tapestry_indexHREF("category",tapestry_hyphenate(($path?$path."/":"").$category["name"]));

        $atoz["items"][] = $item;
      }
    }
    elseif($path)
    {
      $_GET["q"] = "category:".$path;

      if ($config_useRewrite) $_GET["rewrite"] = 1;

      require("search.php");

      exit();
    }
  }
  else
  {
    if ($path)
    {
      $_GET["q"] = "category:".$path;

      if ($config_useRewrite) $_GET["rewrite"] = 1;

      require("search.php");

      exit();
    }
    else
    {
      $banner["breadcrumbs"] = array();

      $banner["breadcrumbs"][] = array("title"=>translate("Category")." A-Z","href"=>tapestry_indexHREF("category"));

      $atoz["items"] = array();

      $sql = "SELECT DISTINCT(category) as category FROM `".$config_databaseTablePrefix."products` ORDER BY category";

      if (database_querySelect($sql,$rows))
      {
        foreach($rows as $product)
        {
          if ($product["category"])
          {
            $item = array();

            $item["name"] = $product["category"];

            $item["href"] = tapestry_indexHREF("category",$product["category"]);

            $atoz["items"][] = $item;
          }
        }
      }
    }
  }

  $header["title"] = translate("Category")." A-Z";

  $banner["h2"] = "<strong>".translate("Category")." A-Z</strong>";

  require("html/header.php");

  require("html/searchform.php");     

  require("html/menu.php");

  require("html/banner.php");

  if (isset($atoz)) require("html/atoz.php");

  require("html/footer.php");
?>