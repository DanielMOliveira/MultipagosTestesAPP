<?php if (strpos($livelychatsupport["addons"], "triggers") !== false) { ?>
  
  <?php

  global $wpdb;
  $triggers_table = $wpdb->prefix . "livelychatsupport_triggers";
  $triggers = $wpdb->get_results("SELECT * FROM $triggers_table");

  ?>

  <h2>Triggers</h2>

  <p>
    Triggers can automatically start conversations for you based on certain criteria.
    <a href="#!/lively-chat-support" class="show_example" data-example="triggers">See Examples (you won't leave this page)</a>.
  </p>

  <div class="triggers_example example updated">
  
    <p>
      The url field matches your visitor's path (the url WITHOUT your domain). Separate multiple urls with a comma. An asterisk <b>(*)</b> is a wildcard placeholder. For example:<br>
    </p>
  
    <ul>
      <li><b>/contact</b> will trigger on <b>http://mywebsite.com/contact</b>, but will not trigger on <b>http://mywebsite.com/about</b></li>
      <li><b>*</b> will trigger on your entire website
      <li><b>/articles/*</b> will trigger on any pages in the <b>/articles</b> directory
    </ul>

    <p>Here's a few full examples:</p>
  
    <ul>
      <li>
        When someone visits: <b>/contact, /about-us</b><br>
        wait <b>5</b> seconds and say:<br>
        <b>We're online right now! What would you like to know?</b>
      </li>
      <li>
        When someone visits: <b>/articles/*</b><br>
        wait <b>2.4</b> seconds and say:<br>
        <b>Do you have any questions about this article?</b>
      </li>
      <li>
        When someone visits: <b>*</b><br>
        wait <b>0</b> seconds and say:<br>
        <b>Welcome to our website! Are you looking for anything in particular?</b>
      </li>
    </ul>
  
  </div>

  <form action="admin.php?page=livelychatsupport&tab=triggers" method="post">
    <ul id="triggers">
      <?php $n = 0; foreach($triggers as $trigger) { ?>
        <li class="trigger">
          <a href="#!/lively-chat-support" class="delete_row" data-row="trigger">Delete This Trigger</a>
          <input type="hidden" class="id" name="triggers[<?php echo $n; ?>][id]" value="<?php echo $trigger->id; ?>" />
          <input type="hidden" class="delete" name="triggers[<?php echo $n; ?>][delete]" value="0" />
          When someone visits:<br>
          <input type="text" name="triggers[<?php echo $n; ?>][urls]" value="<?php echo $trigger->urls; ?>" /><br>
          wait <input type="text" name="triggers[<?php echo $n; ?>][delay]" class="delay" value="<?php echo $trigger->delay; ?>" /> seconds and say:<br>
          <textarea name="triggers[<?php echo $n; ?>][body]"><?php echo $trigger->body; ?></textarea>
        </li>
      <?php $n += 1; } ?>
      <li class="trigger trigger_template template" data-row="trigger">
        <a href="#!/lively-chat-support" class="delete_row" data-row="trigger">Delete This Trigger</a>
        <input type="hidden" class="id" name="triggers[][id]" value="template" />
        <input type="hidden" class="delete" name="triggers[][delete]" value="0" />
        When someone visits:<br>
        <input type="text" name="triggers[][urls]" value="*" /><br>
        wait <input type="text" name="triggers[][delay]" class="delay" value="3" /> seconds and say:<br>
        <textarea name="triggers[][body]">Welcome to our site. Do you have any questions?</textarea>
      </li>
    </ul>
  
    <a href="#!/lively-chat-support" class="button-secondary add_row" data-row="trigger">Add Trigger</a><br><br>
  
    <input type="submit" value="Save Triggers" class="button-primary">
  </form>

<?php } else { ?>
  
  <h2><?php _e( "Automatically start conversations with your visitors.", "lively-chat-support" ); ?></h2>

  <p>
    <?php _e( "Triggers automatically start a conversation with your visitors based on the rules you provide.", "lively-chat-support" ); ?>
  </p>
  <p>
    <?php _e( "Once the user responds, you take over the conversation (these aren't automated robots).", "lively-chat-support" ); ?>
  </p>
  <p>
    <?php _e( "They are a great way to start a conversation, much like you would if you walked into a physical store.", "lively-chat-support" ); ?>
  </p>

  <img src="<?php echo plugins_url("lively-chat-support/assets/triggers.png"); ?>" />

  <p>
    <strong><?php _e( "Triggers are a premium feature.", "lively-chat-support" ); ?></strong>
  </p>
  <p>
    <?php _e( "To purchase the addon, pay here and you'll receive an email with an activation code (which you'll enter in the box below):", "lively-chat-support" ); ?>
  </p>

  <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="2BKE7U7LNT46U">
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
  </form>

  <p>
    <?php _e( "Thanks for your support!", "lively-chat-support" ); ?>
  </p>

  <div class="updated">
    <form action="admin.php?page=livelychatsupport&tab=triggers" method="post">
      <div class="field">
        <label>
          <?php _e( "Already have an activation code for Triggers? Enter it here:", "lively-chat-support" ); ?><br>
          <input type="text" name="activation_code" value="<?php echo $livelychatsupport["activation_code"]; ?>" />
          <input type="submit" value="<?php _e( "Activate Triggers", "lively-chat-support" ); ?>" class="button-primary">
        </label>
      </div>
    </form>
  </div>
  
<?php } ?>