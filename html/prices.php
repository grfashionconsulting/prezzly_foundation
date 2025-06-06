<?php
  $prices_showVoucherCodes = FALSE;

  foreach($prices["products"] as $product)
  {
    if ($product["voucher_code"])
    {
      $prices_showVoucherCodes = TRUE;
    }
  }
?>

<div class='row pt_pr'>

  <div class='small-12 columns'>

    <table>

      <thead>

        <tr>

          <th><?php print translate("Stockist"); ?></th>

          <th class='hide-for-small-only'><?php print translate("Catalogue Product Name"); ?></th>

          <th><?php print translate("Price"); ?></th>

          <?php if ($prices_showVoucherCodes): ?>

            <th><?php print translate("Voucher Code"); ?></th>

          <?php endif; ?>

          <th>&nbsp;</th>

        </tr>

      </thead>

      <tbody>

        <?php foreach($prices["products"] as $product): ?>

          <tr>

            <?php if (file_exists("logos/".$product["merchant"].$config_logoExtension)): ?>

              <td class='pt_pr_mlogo'><a href='<?php print tapestry_buyURL($product); ?>'><img alt='<?php print htmlspecialchars($product["merchant"],ENT_QUOTES,$config_charset); ?> <?php print translate("Logo"); ?>' src='<?php print $config_baseHREF."logos/".str_replace(" ","%20",$product["merchant"]).$config_logoExtension; ?>' /></a></td>

            <?php else: ?>

              <td class='pt_pr_mtext'><a href='<?php print tapestry_buyURL($product); ?>'><?php print $product["merchant"]; ?></a></td>

            <?php endif; ?>

            <td class='hide-for-small-only'><?php print $product["original_name"]; ?></td>

            <td class='pt_pr_price'><?php if ($product["price"] == 0.00) { print "Chiama per il prezzo"; } else { print tapestry_price($product["price"]); } ?></td>

            <?php if ($prices_showVoucherCodes): ?>

              <td class='pt_pr_vouchercode'><?php print $product["voucher_code"]; ?></td>

            <?php endif; ?>

            <td class='pt_pr_visit'><a class='button tiny radius success' href='<?php print tapestry_buyURL($product); ?>'><?php print translate("Visit Store"); ?></a></td>

          </tr>

        <?php endforeach; ?>

      </tbody>

    </table>

  </div>

</div>