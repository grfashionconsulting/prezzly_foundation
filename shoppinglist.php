<?php
  require("includes/common.php");

  $shoppingList = tapestry_shoppingList();

  $shoppingListCount = count($shoppingList);

  if (isset($_GET["add"]) && ($shoppingListCount >= 10))
  {
    $shoppinglistitems["full"] = TRUE;
  }
  elseif (isset($_GET["add"]))
  {
    tapestry_shoppingListAdd($_GET["add"]);

    header("Location: ".tapestry_shoppingListHREF());

    exit();
  }

  if (isset($_GET["remove"]))
  {
    tapestry_shoppingListRemove($_GET["remove"]);

    header("Location: ".tapestry_shoppingListHREF());

    exit();
  }

  $header["title"] = translate("My Shopping List");

  $header["meta"]["robots"] = "noindex,nofollow";

  require("html/header.php");

  require("html/searchform.php");     require("html/menu.php");

  $banner["breadcrumbs"] = array();

  $banner["breadcrumbs"][] = array("title"=>$header["title"],"href"=>$config_baseHREF."shoppingList.php");

  $banner["h2"] = "<strong>".translate("My Shopping List")."</strong>";

  $banner["h3"] = ($shoppingListCount?$shoppingListCount." ".($shoppingListCount > 1?translate("Items"):translate("Item"))." | <a href='?remove=@ALL'><i class='fi-x'></i> ".translate("Remove All")."</a>":translate("Your shopping list is empty."));

  require("html/banner.php");

  if ($shoppingListCount)
  {
    $shoppinglistitems["available"] = array();

    $shoppinglistitems["unavailable"] = array();

    foreach($shoppingList as $name => $item)
    {
      if ($rows = tapestry_shoppingListRows($name))
      {
        $item["rows"] = $rows;

        $shoppinglistitems["available"][] = $item;
      }
      else
      {
        $item["rows"][0]["name"] = $name;

        $shoppinglistitems["unavailable"][] = $item;
      }
    }
  }

  if (isset($shoppinglistitems)) require("html/shoppinglistitems.php");

  require("html/footer.php");
?>