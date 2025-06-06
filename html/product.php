<?php
  $product_main = $product["products"][0];

  $product_bestPriceText = (count($product["products"])>1?translate("Best Price"):translate("Best Price"));

  $product_bestPriceMerchants = array();

  foreach($product["products"] as $p)
  {
    if ($p["price"] == $product_main["price"])
    {
      $html = "<a target='_BLANK' href='".tapestry_buyURL($p)."'>".$p["merchant"]."</a>";

      if ($p["voucher_code"])
      {
        $html .= " (".translate("using voucher code")." <span class='pt_pr_vouchercode'>".$p["voucher_code"]."</span>)";
      }

      $product_bestPriceMerchants[] = $html;
    }
  }

  $product_bestPriceMerchants = implode(", ",$product_bestPriceMerchants);

  if ($config_useShoppingList)
  {
    $product_shoppingList = tapestry_shoppingList();

    if (isset($product_shoppingList[$product_main["name"]]))
    {
      $product_shoppingListHTML = "<p><a href='".tapestry_shoppingListHREF()."'><i class='fi-check'></i> ".translate("This item is in your Shopping List")."</a></p>";
    }
    else
    {
      $product_shoppingListHTML = "<p><a href='".tapestry_shoppingListHREF()."?add=".urlencode($product_main["name"])."'><i class='fi-shopping-cart'></i> ".translate("Add to Shopping List")."</a></p>";
    }
  }
?>

<div class='row pt_p'>

  <?php if ($product_main["image_url"]): ?>

    <div class='small-2 columns show-for-small-only'>&nbsp;</div>

    <div class='small-8 medium-6 columns'><img alt='<?php print translate("Image of"); ?> <?php print htmlspecialchars($product_main["name"],ENT_QUOTES,$config_charset); ?>' src='<?php print $config_baseHREF."imageCache.php?src=".base64_encode($product_main["image_url"]); ?>' /></div>

    <div class='small-2 columns show-for-small-only'>&nbsp;</div>

  <?php endif; ?>

  <div class='<?php print ($product_main["image_url"]?"medium-6":"small-12"); ?> columns'>

    <h1><?php print $product_main["name"]; ?></h1>
	
	

    <h3><?php if($product_main["brand"]): ?> <?php print "<a href='".tapestry_indexHREF("brand",$product_main["brand"])."'>".$product_main["brand"]."</a>"; ?><?php endif; ?></h3>

    
	
	<h3><?php if($product_main["category"]): ?> <?php print "<a href='".tapestry_indexHREF("category",$product_main["category"])."'>".$product_main["category"]."</a>"; ?><?php endif; ?></h3>

    

    
	 
	
    

    <p>

      <strong><?php print $product_bestPriceText; ?>:

      <?php print tapestry_price($product_main["price"]); ?></strong></br> <?php print translate("from"); ?>

      <?php print $product_bestPriceMerchants; ?>
	  
	  
    </p>
	
     <?php if (isset($product_shoppingListHTML)) print $product_shoppingListHTML; ?>

     <?php if (isset($product_main["extraHTML"])) print $product_main["extraHTML"]; ?>
    
	

  </div>

</div>