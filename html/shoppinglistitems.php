<div class='pt_sl'>

  <?php foreach($shoppinglistitems["available"] as $item): ?>

    <?php
      $product = $item["rows"][0];

      $product["productHREF"] = tapestry_productHREF($product);

      $product["numMerchants"] = count($item["rows"]);

      $product["minPrice"] = $product["price"];

      if ($product["price"] == $item["price"])
      {
        $product["deltaHTML"] = "<span class='pt_sl_nc'><i class='fi-arrow-right'></i> <i class='fi-arrow-left'></i> ".translate("no price change")."</span>";
      }
      elseif($product["price"] > $item["price"])
      {
        $product["deltaHTML"] = "<span class='pt_sl_up'><i class='fi-arrow-up'></i> ".tapestry_price(tapestry_decimalise($product["price"] - $item["price"]))."</span>";
      }
      else
      {
        $product["deltaHTML"] = "<span class='pt_sl_dn'><i class='fi-arrow-down'></i> ".tapestry_price(tapestry_decimalise($item["price"] - $product["price"]))."</span>";
      }
    ?>

    <div class='row pt_sl_each'>

      <div class='small-2 columns show-for-small-only'>&nbsp;</div>

      <div class='small-8 medium-3 columns'>

        <div class='pt_sl_image_container'>

          <?php if ($product["image_url"]): ?>

            <a href='<?php print $product["productHREF"]; ?>'><img alt='<?php print translate("Image of"); ?> <?php print htmlspecialchars($product["name"],ENT_QUOTES,$config_charset); ?>' class='pt_sl_image' src='<?php print htmlspecialchars($product["image_url"],ENT_QUOTES,$config_charset); ?>' /></a>

          <?php else: ?>

            &nbsp;

          <?php endif; ?>

        </div>

      </div>

      <div class='small-2 columns show-for-small-only'>&nbsp;</div>

      <div class='medium-6 columns'>

        <h4><a href='<?php print $product["productHREF"]; ?>'><?php print $product["name"]; ?></a></h4>

        <?php if ($product["description"]): ?>

          <p><?php print tapestry_substr($product["description"],250,"..."); ?></p>

        <?php endif; ?>

      </div>

      <div class='medium-3 columns pt_sl_each_price'>

        <span class='pt_sl_from'><?php print ($product["numMerchants"]>1?translate("now from")."&nbsp;":translate("now")."&nbsp;"); ?></span>

        <span class='pt_sl_price'><?php print tapestry_price($product["minPrice"]); ?></span>

        <br />

        <a class='button tiny radius secondary pt_sl_button' href='<?php print $product["productHREF"]; ?>'><?php print ($product["numMerchants"]>1?translate("Compare")." ".$product["numMerchants"]." ".translate("Prices"):translate("More Information")); ?></a>

        <br />

        <?php print $product["deltaHTML"]; ?>

        <br />

        <a href='?remove=<?php print urlencode($product["name"]); ?>'><i class='fi-x'></i> <?php print translate("Remove"); ?></a>

      </div>

      <div class='small-12 columns show-for-small-only'><hr /></div>

    </div>

  <?php endforeach; ?>

  <?php foreach($shoppinglistitems["unavailable"] as $item): ?>

    <?php $product = $item["rows"][0]; ?>

    <div class='row pt_sl_each'>

      <div class='small-2 columns show-for-small-only'>&nbsp;</div>

      <div class='small-8 medium-2 columns'>

        <div class='pt_sl_image_container'>

            &nbsp;

        </div>

      </div>

      <div class='small-2 columns show-for-small-only'>&nbsp;</div>

      <div class='medium-8 columns'>

        <h4><?php print $product["name"]; ?></h4>

        <p><?php print translate("Item not currently available."); ?></p>

      </div>

      <div class='medium-2 columns pt_sl_each_price'>

        <a href='?remove=<?php print urlencode($product["name"]); ?>'><i class='fi-x'></i> <?php print translate("Remove"); ?></a>

      </div>

      <div class='small-12 columns show-for-small-only'><hr /></div>

    </div>

  <?php endforeach; ?>

  <?php if (isset($shoppinglistitems["full"])): ?>

    <script type='text/JavaScript'>

      alert('<?php print translate("Sorry, your Shopping List is full."); ?>');

    </script>

  <?php endif; ?>

</div>