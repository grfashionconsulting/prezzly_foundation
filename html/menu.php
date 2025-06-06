<!-- Menu Desktop: visibile solo su desktop -->
<div class="contain-to-grid hide-for-small-only">
  <nav class='top-bar' data-topbar role='navigation'>
    <ul class='title-area'>
      <li class='name'>
        <h1><a href='<?php print $config_baseHREF; ?>'><i class='fi-home'></i></a></h1>
      </li>
      <li class='toggle-topbar'><a href='#'>&#x2630;</a></li>
    </ul>
    <section class='top-bar-section'>
      <ul class='left'>
        <?php if (isset($config_useCategoryHierarchy) && $config_useCategoryHierarchy): ?>
          <li class='has-dropdown' onClick='JavaScript:menu_loadCat(0);'>
            <a href='#'><?php print translate("Category"); ?></a>
            <ul id='menu_cat0' class='dropdown'>
              <li><a href='#'>&nbsp;<div class="preloader"></div></a></li>
            </ul>
          </li>
        <?php else: ?>
          <li><a href='<?php print tapestry_indexHREF("category"); ?>'><?php print translate("Category"); ?></a></li>
        <?php endif; ?>
        <li><a href='<?php print tapestry_indexHREF("brand"); ?>'><?php print translate("Brand"); ?></a></li>
        <li><a href='<?php print tapestry_indexHREF("merchant"); ?>'><?php print translate("Merchant"); ?></a></li>
      </ul>
    </section>
    <?php if ($config_useVoucherCodes || $config_useShoppingList): ?>
      <section class='top-bar-section'>
        <ul class='right'>
          <?php if (isset($config_useVoucherCodes) && $config_useVoucherCodes): ?>
            <li><a href='<?php print $config_baseHREF; ?>vouchers.php'><i class='fi-ticket'></i> <?php print translate("Voucher Codes"); ?></a></li>
          <?php endif; ?>
          <?php if (isset($config_useShoppingList) && $config_useShoppingList): ?>
            <li><a href='<?php print tapestry_shoppingListHREF(); ?>'><i class='fi-shopping-cart'></i> <?php print translate("My Shopping List"); ?> (<?php $menu_shoppingList = tapestry_shoppingList(); print count($menu_shoppingList); ?>)</a></li>
          <?php endif; ?>
        </ul>
      </section>
    <?php endif; ?>
  </nav>
  <script type='text/JavaScript'>
    var menu_loadCatDone = [];
    function menu_loadCat(id)
    {
      if (menu_loadCatDone[id]) return;
      $("#menu_cat"+id).load("<?php print $config_baseHREF; ?>html/menu_categories.php?parent="+id, function() {});
      menu_loadCatDone[id] = true;
    }
  </script>
</div>

<!-- Menu Mobile: visibile solo su dispositivi mobili -->
<div class="show-for-small-only" style="background: #fff; padding: 0.5rem 1rem;">
  <ul class="mobile-menu" style="display: flex; justify-content: space-between; align-items: center; margin: 0; padding: 0; list-style: none; white-space: nowrap; overflow-x: auto;">
    <li style="flex: 1; text-align: center;">
      <a style="color: #1E293B; font-size: 12px; text-decoration: none;" href='<?php print tapestry_indexHREF("category"); ?>'>
        <?php print translate("Categorie"); ?>
      </a>
    </li>
    <li style="flex: 1; text-align: center;">
      <a style="color: #1E293B; font-size: 12px; text-decoration: none;" href='<?php print tapestry_indexHREF("brand"); ?>'>
        <?php print translate("Marchi"); ?>
      </a>
    </li>
    <li style="flex: 1; text-align: center;">
      <a style="color: #1E293B; font-size: 12px; text-decoration: none;" href='<?php print tapestry_indexHREF("merchant"); ?>'>
        <?php print translate("Negozi"); ?>
      </a>
    </li>
    <li style="flex: 1; text-align: center;">
      <a style="color: #1E293B; font-size: 12px; text-decoration: none;" href='<?php print $config_baseHREF; ?>offers.php'>
        <?php print translate("Offerte"); ?>
      </a>
    </li>
    <?php if (isset($config_useShoppingList) && $config_useShoppingList): ?>
      <li style="flex: 1; text-align: center;">
        <a style="color: #1E293B; font-size: 12px; text-decoration: none;" href='<?php print tapestry_shoppingListHREF(); ?>'>
          <i class="fi-shopping-cart" style="font-size: 11px; vertical-align: middle; margin-right: 3px;"></i>
          <?php print translate("Lista della spesa"); ?>
          (<?php $menu_shoppingList = tapestry_shoppingList(); print count($menu_shoppingList); ?>)
        </a>
      </li>
    <?php endif; ?>
  </ul>
</div>
