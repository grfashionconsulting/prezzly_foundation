<div class='row'>

  <div class='small-12 columns'>

    <ul class='breadcrumbs'>

      <li><a href='<?php print $config_baseHREF; ?>'><?php print translate("Home"); ?></a></li>

      <?php
        if (isset($banner["breadcrumbs"]))
        {
          $i = 0;

          $c = count($banner["breadcrumbs"]);

          foreach($banner["breadcrumbs"] as $breadcrumb)
          {
            $i++;

            print "<li".($i==$c?" class='current'":"")."><a href='".$breadcrumb["href"]."'>".$breadcrumb["title"]."</a></li>";
          }
        }
        elseif(isset($banner["h2"]))
        {
          $banner["h2"] = strip_tags($banner["h2"]);

          $parts = explode("/",$banner["h2"]);

          foreach($parts as $part)
          {
            print "<li class='current'>".$part."</li>";
          }
        }
      ?>

    </ul>

  </div>

</div>

<?php
  if (strpos($_SERVER["PHP_SELF"],"search.php") || strpos($_SERVER["PHP_SELF"],"categories.php"))
  {
    if (isset($banner["h3"])) $banner["h3"] = str_replace(" | ".translate("Price")," <br class='show-for-small-only' />".translate("Price"),$banner["h3"]);
  }
?>

<div class='pt_ba row'>

  <div class='small-12 columns'>

    <?php print (isset($banner["h3"])?"<h3>".$banner["h3"]."</h3>":"");  ?>

  </div>

</div>