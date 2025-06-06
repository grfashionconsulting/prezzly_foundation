<?php
  require_once("includes/common.php");

  require("includes/stopwords.php");

  $q = (isset($_GET["q"])?tapestry_normalise($_GET["q"],":\.\/"):"");

  $sql = "SELECT * FROM `".$config_databaseTablePrefix."products` WHERE normalised_name='".database_safe($q)."'";
  
  if (database_querySelect($sql,$rows))
  {
    header("Location: ".tapestry_productHREF($rows[0]));
  
    exit();
  }

  $page = (isset($_GET["page"])?intval($_GET["page"]):1);

  $sort = (isset($_GET["sort"])?$_GET["sort"]:"relevance");

  $rewrite = isset($_GET["rewrite"]);

  $merchantFilter = (isset($_GET["merchantFilter"])?$_GET["merchantFilter"]:"");

  $categoryFilter = (isset($_GET["categoryFilter"])?$_GET["categoryFilter"]:"");

  $brandFilter = (isset($_GET["brandFilter"])?$_GET["brandFilter"]:"");

  $priceWhere = "";

  if (isset($_GET["minPrice"]) && $_GET["minPrice"])
  {
    $minPrice = sprintf("%.2f",$_GET["minPrice"]);
  }
  else
  {
    $minPrice = "";
  }
  if (isset($_GET["maxPrice"]) && $_GET["maxPrice"])
  {
    $maxPrice = sprintf("%.2f",$_GET["maxPrice"]);
  }
  else
  {
    $maxPrice = "";
  }

  if ($minPrice || $maxPrice)
  {
    if ($minPrice && $maxPrice)
    {
      $priceWhere = " AND price BETWEEN '".$minPrice."' AND '".$maxPrice."' ";
    }
    elseif ($minPrice)
    {
      $priceWhere = " AND price > '".$minPrice."' ";
    }
    elseif ($maxPrice)
    {
      $priceWhere = " AND price < '".$maxPrice."' ";
    }
  }

  if ($merchantFilter)
  {
    $priceWhere .= " AND merchant = '".database_safe($merchantFilter)."' ";
  }

  if ($categoryFilter)
  {
    if ($config_useCategoryHierarchy)
    {
      $priceWhere .= " AND categoryid = '".database_safe($categoryFilter)."' ";
    }
    else
    {
      $priceWhere .= " AND category = '".database_safe($categoryFilter)."' ";
    }
  }

  if ($brandFilter)
  {
    $priceWhere .= " AND brand = '".database_safe($brandFilter)."' ";
  }


   if ($q)
  {
    if (isset($_GET["log"]) && $_GET["log"])
    {
      $sql = "INSERT INTO pt_querylog (query, count) VALUES ('".database_safe($q)."', 1) ON DUPLICATE KEY UPDATE count = count + 1";
      database_queryModify($sql,$result);
    }
	
    $orderByDefault = array();

    $orderByDefault["rating"] = "rating DESC";

    $orderByDefault["priceAsc"] = "minPrice ASC";

    $orderByDefault["priceDesc"] = "minPrice DESC";

    $orderByFullText = array();

    $orderByFullText["relevance"] = "relevance DESC";

    $orderByFullText["rating"] = "rating DESC";

    $orderByFullText["priceAsc"] = "minPrice ASC";

    $orderByFullText["priceDesc"] = "minPrice DESC";

    $parts = explode(":",$q);

    switch($parts[0])
    {
      case "category":

        if ($config_useCategoryHierarchy)
        {
          $nodeInfo = tapestry_categoryHierarchyNodeInfo($parts[1]);

          $ins = array();

          foreach($nodeInfo["lowerarchy"] as $id)
          {
            $ins[] = "'".$id."'";
          }

          $in = implode(",",$ins);

          $where = "categoryid IN (".$in.")";

          $sql = "SELECT SQL_CALC_FOUND_ROWS id,COUNT(id) AS numMerchants,MIN(price) as minPrice FROM `".$config_databaseTablePrefix."products` WHERE ".$where.$priceWhere." GROUP BY search_name";

          $orderBySelection = $orderByDefault;

          break;
        }
        // pass through to merchant
      case "merchant":
        // pass through to brand
      case "brand":

        if ($config_useCategoryHierarchy)
        {
          $fields = array("merchant","brand");
        }
        else
        {
          $fields = array("merchant","category","brand");
        }

        $first = TRUE;

        $where = "";

        $j = count($parts);

        for($i=0;$i<$j;$i++)
        {
          if (!$parts[$i]) continue;

          if (in_array($parts[$i],$fields))
          {
            $field = $parts[$i];

            continue;
          }

          if (!$first)
          {
            $where .= " AND ";
          }

          $where .= " ".$field." = '".database_safe($parts[$i])."' ";

          $first = FALSE;
        }

        $sql = "SELECT SQL_CALC_FOUND_ROWS id,COUNT(id) AS numMerchants,MIN(price) as minPrice FROM `".$config_databaseTablePrefix."products`  WHERE ".$where.$priceWhere." GROUP BY search_name";

        $orderBySelection = $orderByDefault;

        break;

      case "voucher":

        $now = time();

        $sql = "SELECT * FROM `".$config_databaseTablePrefix."vouchers` WHERE merchant='".database_safe($parts[1])."' AND code='".database_safe($parts[2])."' AND ( (valid_from < '".$now."' AND valid_to = '0') OR (valid_from < '".$now."' AND valid_to > '".$now."') ) ";

        if (database_querySelect($sql,$vouchers))
        {
          $voucher = $vouchers[0];

          $where = " merchant = '".database_safe($voucher["merchant"])."' ";

          if ($config_useVoucherCodes == 2)
          {
            $where .= " AND voucher_code = '".database_safe($voucher["code"]).($voucher["discount_text"]?" (".$voucher["discount_text"].")":"")."' ";
          }
          else
          {
            if ($voucher["match_value"])
            {

              $match_values = explode(",",$voucher["match_value"]);

              $match_wheres = array();

              foreach($match_values as $match_value)
              {
                if ($voucher["match_type"]=="exact")
                {
                  $match_wheres[] = " ".$voucher["match_field"]." = '".database_safe($match_value)."' ";
                }
                else
                {
                  $match_wheres[] = " ".$voucher["match_field"]." LIKE '%".database_safe($match_value)."%' ";
                }
              }

              $where .= " AND (".implode(" OR ",$match_wheres).") ";
            }

            if ($voucher["min_price"])
            {
              $where .= " AND price >= '".database_safe($voucher["min_price"])."' ";
            }
          }

          $sql = "SELECT SQL_CALC_FOUND_ROWS id,COUNT(id) AS numMerchants,MIN(price) as minPrice FROM `".$config_databaseTablePrefix."products` WHERE ".$where.$priceWhere." GROUP BY search_name";

          $orderBySelection = $orderByDefault;
        }
        else
        {
          $sql = "SELECT SQL_CALC_FOUND_ROWS id,COUNT(id) AS numMerchants,MIN(price) as minPrice FROM `".$config_databaseTablePrefix."products` WHERE 0 GROUP BY search_name";

          $orderBySelection = $orderByDefault;
        }

        break;

      case "bw":
        $where = "search_name LIKE '".database_safe($parts[1])."%'";

        $sql = "SELECT SQL_CALC_FOUND_ROWS id,COUNT(id) AS numMerchants,MIN(price) as minPrice FROM `".$config_databaseTablePrefix."products` WHERE ".$where.$priceWhere." GROUP BY search_name";

        $orderBySelection = $orderByDefault;

        break;

      default:
        $words = explode(" ",$parts[0]);

        if ($config_useFullText)
        {
          foreach($words as $word)
          {
            if (strlen($word) <= 3 || in_array(strtolower($word),$stopwords))
            {
              $config_useFullText = FALSE;

              break;
            }
          }
        }

        if ($config_useFullText)
        {
          $words = explode(" ",$parts[0]);

          $newWords = array();

          foreach($words as $word)
          {
            if (substr($word,-1)=="s")
            {
              $newWords[] = substr($word,0,-1);
            }
          }

          $allWords = array_merge($words,$newWords);

          $parts[0] = implode(" ",$allWords);

          if ($config_searchDescription)
          {
            $matchFields = "name,description";
          }
          else
          {
            $matchFields = "name";
          }

          $where = "MATCH ".$matchFields." AGAINST ('".database_safe($parts[0])."')";

          $sql = "SELECT SQL_CALC_FOUND_ROWS id,COUNT(id) AS numMerchants,MIN(price) as minPrice, MATCH ".$matchFields." AGAINST ('".database_safe($parts[0])."') AS relevance FROM `".$config_databaseTablePrefix."products` WHERE ".$where.$priceWhere." GROUP BY search_name";

          $orderBySelection = $orderByFullText;
        }
        else
        {
          $wheres = array();

          foreach($words as $word)
          {
            $where  = "(";

            $where .= "search_name LIKE '%".database_safe($word)."%'";

            if ($config_searchDescription)
            {
              $where .= " OR description LIKE '%".database_safe($word)."%'";
            }

            if (substr($word,-1)=="s")
            {
              $where .= " OR search_name LIKE '%".database_safe(substr($word,0,-1))."%'";

              if ($config_searchDescription)
              {
                $where .= " OR description LIKE '%".database_safe(substr($word,0,-1))."%'";
              }
            }

            $where .= ")";

            $wheres[] = $where;
         }

          $where = implode(" AND ",$wheres);

          $sql = "SELECT SQL_CALC_FOUND_ROWS id,COUNT(id) AS numMerchants,MIN(price) as minPrice FROM `".$config_databaseTablePrefix."products` WHERE ".$where.$priceWhere." GROUP BY search_name";

          $orderBySelection = $orderByDefault;
        }
        break;
    }

    if (isset($orderBySelection[$sort]))
    {
      $sql .= " ORDER BY ".$orderBySelection[$sort];
    }

    $offset = ($page-1) * $config_resultsPerPage;

    $sql .= " LIMIT ".$offset.",".$config_resultsPerPage;

    $numRows = database_querySelect($sql,$rows);

    $sqlResultCount = "SELECT FOUND_ROWS() as resultcount";

    database_querySelect($sqlResultCount,$rowsResultCount);

    $resultCount = $rowsResultCount[0]["resultcount"];

    $banner["breadcrumbs"] = array();

    if (isset($parts[1]) && $rewrite)
    {
      switch($parts[0])
      {
        case "category":

          if ($config_useCategoryHierarchy)
          {
            $banner["h2"] = "<strong>".translate("Category")."</strong>";

            $banner["breadcrumbs"][] = array("title" => translate("Category"), "href" => tapestry_indexHREF("category"));

            foreach($nodeInfo["hierarchy"] as $category)
            {
              $banner["breadcrumbs"][] = array("title" => $category["name"], "href" => tapestry_indexHREF("category",$category["path"]));

              $banner["h2"] .= " / <a href='".tapestry_indexHREF("category",$category["path"])."'>".$category["name"]."</a>";
            }

            $banner["h2"] .= " / ".$nodeInfo["name"];

            $lastBreadcrumb = $nodeInfo["name"];
          }
          else
          {
            $banner["h2"] = "<strong>".translate("Category")." A-Z</strong>";

            $banner["h2"] .= " / ".$parts[1];

            $banner["breadcrumbs"][] = array("title"=>translate("Category")." A-Z","href"=>tapestry_indexHREF("category"));

            $lastBreadcrumb = $parts[1];
          }

          break;

        case "merchant":

          $banner["breadcrumbs"][] = array("title"=>translate("Merchant")." A-Z","href"=>tapestry_indexHREF("merchant"));

          $banner["h2"] = "<strong>".translate("Merchant")." A-Z</strong>";

          $banner["h2"] .= " / ".$parts[1];

          $lastBreadcrumb = $parts[1];

          break;

        case "brand":

          $banner["breadcrumbs"][] = array("title"=>translate("Brand")." A-Z","href"=>tapestry_indexHREF("brand"));

          $banner["h2"] = "<strong>".translate("Brand")." A-Z</strong>";

          $banner["h2"] .= " / ".$parts[1];

          $lastBreadcrumb = $parts[1];

          break;
      }
    }
    else
    {
      $banner["h2"] = translate("Product search results for")." <strong>".htmlspecialchars($q,ENT_QUOTES,$config_charset)."</strong>&nbsp;";

      $lastBreadcrumb = $q;
    }

    if ($resultCount)
    {
      $resultFrom = ($offset+1);

      $resultTo = ($offset+$config_resultsPerPage);

      if ($resultTo > $resultCount) $resultTo = $resultCount;

      $sortHREF = $config_baseHREF."search.php?q=".urlencode($q)."&amp;page=1&amp;";

      if ($minPrice || $maxPrice)
      {
        $sortHREF .= "minPrice=".$minPrice."&amp;maxPrice=".$maxPrice."&amp;";
      }

      if ($merchantFilter)
      {
        $sortHREF .= "merchantFilter=".urlencode($merchantFilter)."&amp;";
      }

      if ($categoryFilter)
      {
        $sortHREF .= "categoryFilter=".urlencode($categoryFilter)."&amp;";
      }

      if ($brandFilter)
      {
        $sortHREF .= "brandFilter=".urlencode($brandFilter)."&amp;";
      }

      $sortHREF .= "sort=";

      $sortRelevance = ($sort=="relevance"?"<strong>".translate("Relevance")."</strong>":"<a href='".$sortHREF."relevance'>".translate("Relevance")."</a>");

      if ($config_useInteraction)
      {
        $sortRating = ", ".($sort=="rating"?"<strong>".translate("Product Rating")."</strong>":"<a href='".$sortHREF."rating'>".translate("Product Rating")."</a>");
      }
      else
      {
        $sortRating = "";
      }

      $sortPriceAsc = ($sort=="priceAsc"?"<strong>".translate("Low to High")."</strong>":"<a href='".$sortHREF."priceAsc'>".translate("Low to High")."</a>");

      $sortPriceDesc = ($sort=="priceDesc"?"<strong>".translate("High to Low")."</strong>":"<a href='".$sortHREF."priceDesc'>".translate("High to Low")."</a>");

      $banner["h3"] = translate("Order by").": ".$sortRelevance.$sortRating." | ".translate("Price").": ".$sortPriceAsc.", ".$sortPriceDesc;

      $navigation["resultCount"] = $resultCount;

      $searchresults["numRows"] = $numRows;

      $ids = array();

      foreach($rows as $row)
      {
        $ids[] = $row["id"];
      }

      $in = implode(",",$ids);

      $sql2 = "SELECT id,buy_url,name,normalised_name,image_url,description,price,rating FROM `".$config_databaseTablePrefix."products` WHERE id IN (".$in.")";

      database_querySelect($sql2,$rows2);

      $rows3 = array();

      foreach($rows2 as $row2)
      {
        $rows3[$row2["id"]] = $row2;
      }

      $searchresults["products"] = $rows;

      foreach($searchresults["products"] as $k => $product)
      {
        $searchresults["products"][$k] = array_merge($searchresults["products"][$k],$rows3[$product["id"]]);

        if ($product["numMerchants"]==1)
{
  $searchresults["products"][$k]["productHREF"] = tapestry_buyURL($searchresults["products"][$k]);
}
else
{
  $searchresults["products"][$k]["productHREF"] = tapestry_productHREF($searchresults["products"][$k]);
}
      }

      $showing = " (<strong>".$resultFrom."</strong> ".translate("to")." <strong>".$resultTo."</strong> ".translate("of")." <strong>".$resultCount."</strong>)";
    }
    else
    {
      $showing = " (".translate("no results found").")";
    }

    $header["title"] = $q;

    $banner["h2"] .= $showing;

    $banner["breadcrumbs"][] = array("title"=>$lastBreadcrumb." ".$showing,"href"=>"#");

    if ($sort <> "relevance")
    {
      $titleSort = array("relevance" => translate("Relevance"),"rating" => translate("Rating"),"priceAsc"  => translate("Price: Low to High"),"priceDesc" => translate("Price: High to Low"));

      $header["title"] .= " | ".translate("By")." ".$titleSort[$sort];
    }

    if ($page > 1)
    {
      $header["title"] .= " | ".translate("Page")." ".$page;
    }
  }

  require("html/header.php");

  

  require("html/searchform.php");
  
  require("html/menu.php");

  
  
  if ($parts[0] == "merchant")
{
  $filename = "negozio/".$parts[1].".html";
  if (file_exists($filename))
  {
    require($filename);
  }
}  
  
 if ($parts[0] == "brand")
{
  $filename = "marchio/".$parts[1].".html";
  if (file_exists($filename))
  {
    require($filename);
  }
}  
 
  
  if ($parts[0] == "category")
{
  $filename = "categoria/".$parts[1].".html";
  if (file_exists($filename))
  {
    require($filename);
  }
}
require("html/banner.php");

  if (isset($searchresults))
  {
    if ($config_enableSearchFilters)
    {
      require("html/searchfilters.php");
    }

    require("html/searchresults.php");
  }
  else
  {
    require("html/noresults.php");
  }

  if (isset($navigation))
  {
    if ($minPrice || $maxPrice)
    {
      $sort .= "&amp;minPrice=".$minPrice."&amp;maxPrice=".$maxPrice;
    }

    if ($merchantFilter)
    {
      $sort .= "&amp;merchantFilter=".urlencode($merchantFilter);
    }

    if ($categoryFilter)
    {
      $sort .= "&amp;categoryFilter=".urlencode($categoryFilter);
    }

    if ($brandFilter)
    {
      $sort .= "&amp;brandFilter=".urlencode($brandFilter);
    }

    require("html/navigation.php");
  }

  require("html/footer.php");
?>