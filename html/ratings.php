<div class='row pt_ra'>

  <div class='small-12 columns'>

    <?php if(isset($ratings["reviews"])): ?>

      <?php foreach($ratings["reviews"] as $review): ?>

        <blockquote>

          <?php if ($review["comments"]): ?>

            <?php print htmlspecialchars($review["comments"],ENT_QUOTES,$config_charset); ?>

          <?php else: ?>

            <em><?php print translate("This reviewer did not leave any comments."); ?></em>

          <?php endif; ?>

          <br />

          <?php print tapestry_stars($review["rating"],""); ?>

        </blockquote>

      <?php endforeach; ?>

    <?php endif; ?>

    <blockquote>

      <?php if(isset($_GET["pending"])): ?>

        <em><?php print translate("Your review is pending approval. Thank you for your contribution."); ?></em>

      <?php else: ?>

        <form method='post'>

          <div class='row'>

            <div class='small-12 medium-6 columns'>

              <textarea name='comments' placeholder='<?php print translate("Your Comments (optional)"); ?>'></textarea>

            </div>

          </div>

          <div class='row'>

            <div class='small-6 medium-2 columns'>

              <label><?php print translate("Your Rating"); ?></label>

              <select name='rating' id='rating'>

                <option value='5'>*****</option><option value='4'>****</option><option value='3'>***</option><option value='2'>**</option><option value='1'>*</option>

              </select>

            </div>

            <div class='small-6 medium-10 columns'>

              <label>&nbsp;</label>

              <button type='submit' class='button tiny pt_ra_submit' onclick='JavaScript:document.getElementById("confirm").value=document.getElementById("rating").value;'><?php print translate("Submit"); ?></button>

            </div>

          </div>

          <input type='hidden' name='submit' value='1' />

          <input type='hidden' name='confirm' id='confirm' value='' />

        </form>

      <?php endif; ?>

    </blockquote>

  </div>

</div>