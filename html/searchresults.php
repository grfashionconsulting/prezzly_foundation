<?php
  $shoppingListCookie = "shoppingList".bin2hex($config_baseHREF);
  $shoppingList = (isset($_COOKIE[$shoppingListCookie])?unserialize($_COOKIE[$shoppingListCookie]):array());
?> <?php
  if (file_exists("html/user_searchresults_before.php")) require("html/user_searchresults_before.php");
?>

<?php if(strpos($_SERVER["PHP_SELF"],"products.php")===FALSE): ?>

  <div class='row'>

  <div class='small-12 medium-2 columns'>

  <?php require("html/searchfilters_sidebar.php"); ?>

  </div>

  <div class='small-12 medium-10 columns'>

<?php else: ?>

  <div class='row'>

  <div class='small-12 columns'>

<?php endif; ?>

<div class='pt_sr'>

    <?php foreach($searchresults["products"] as $product): ?>

      <div class='row pt_sr_each'> <hr class="divider" />

        <div class='small-2 columns show-for-small-only'>&nbsp;</div></hr>

        <div class='small-8 medium-2 columns'>

          <div class='pt_sr_image_container'>

            <?php if ($product["image_url"]): ?>

              <a href='<?php print $product["productHREF"]; ?>'><img alt='<?php print translate("Image of"); ?> <?php print htmlspecialchars($product["name"],ENT_QUOTES,$config_charset); ?>' class='pt_sr_image' src='<?php print htmlspecialchars($product["image_url"],ENT_QUOTES,$config_charset); ?>' /></a>

            <?php else: ?>

              &nbsp;

            <?php endif; ?>

          </div>

        </div>

        <div class='small-2 columns show-for-small-only'>&nbsp;</div>

        <div class='medium-7 columns'>

          <h4>

          <a href='<?php print $product["productHREF"]; ?>'><?php print $product["name"]; ?></a>

          <?php if ($config_useInteraction): ?>

            <?php if ($product["rating"]) print tapestry_stars($product["rating"],""); ?>

          <?php endif; ?>

          </h4>

          <?php if ($product["description"]): ?>

            <p><?php print tapestry_substr($product["description"],250,"..."); ?></p>

          <?php endif; ?>

        </div>

        <div class='medium-3 columns pt_sr_each_price'>

          <span class='pt_sr_from'><?php print ($product["numMerchants"]>1?translate("from")."&nbsp;":""); ?></span>

          <span class='pt_sr_price'>
  <?php 
    if ($product["minPrice"] == 0.00) {
      print "Chiama per il prezzo";
    } else {
      print tapestry_price($product["minPrice"]);
    }
  ?>
</span>


          <br />

          <a class='button tiny radius secondary' href='<?php print $product["productHREF"]; ?>'><?php print ($product["numMerchants"]>1?translate("Compare")." ".$product["numMerchants"]." ".translate("Prices"):translate("More Information")); ?></a>
           <?php
  if (isset($shoppingList[$product["name"]]))
  {
    print "<br /><a href='".tapestry_shoppingListHREF()."'><i class='fi-check'></i> ".translate("In Shopping List")."</a>";
  }
  else
  {
    print "<br /><a href='".tapestry_shoppingListHREF()."?add=".urlencode($product["name"])."'><i class='fi-shopping-cart'></i> ".translate("Add to Shopping List")."</a>";
  }
?> 
        </div>

        <div class='small-12 columns show-for-small-only'><hr /></div>

      </div>

    <?php endforeach; ?>

</div>

</div>
</div>

<?php
  if (file_exists("html/user_searchresults_after.php")) require("html/user_searchresults_after.php");
?>