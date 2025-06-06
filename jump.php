<?php
  require("includes/common.php");

  $id = (isset($_GET["id"])?intval($_GET["id"]):"");

  if (!$id) exit();

  $sql = "SELECT filename,buy_url FROM `".$config_databaseTablePrefix."products` WHERE id='".database_safe($id)."'";

  if (database_querySelect($sql,$rows))
  {
    $product = $rows[0];

    $sql = "UPDATE `".$config_databaseTablePrefix."feeds` SET clicks=clicks+1 WHERE filename = '".database_safe($product["filename"])."'";

    database_queryModify($sql,$insertID);

    header("Location: ".$product["buy_url"]);
  }

  exit();
?>