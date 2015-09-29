<?php 

if (strpos($livelychatsupport["addons"], "sms") !== false) { ?>
  
  <form action="admin.php?page=livelychatsupport&tab=sms" method="post">
    <div class="updated">
      <p>
        <?php _e( "For the SMS to work, you'll need to purchase a phone number from which your website can send you messages.", "lively-chat-support" ); ?><br>
      </p>
      <ol>
        <li><?php echo sprintf( __( "Visit the <a href=\"%s\">Agents</a> page to add your personal mobile phone number.", "lively-chat-support" ), "admin.php?page=livelychatsupport&tab=agents"); ?></li>
        <li><?php echo sprintf( __( "Sign up at <a href=\"%s\" target=\"_blank\">Twilio.com</a>.", "lively-chat-support" ), "https://www.twilio.com/try-twilio?from_livelychatsupport_wp_plugin=true" ); ?></li>
        <li>
          <a href="https://www.twilio.com/user/account/phone-numbers/available/local?from_livelychatsupport_wp_plugin=true" target="_blank"><?php _e( "Buy a number", "lively-chat-support" ); ?></a>. <?php _e( "You'll probably want a local number to avoid any long distance/international SMS charges.", "lively-chat-support" ); ?><br>
          <b><?php _e( "DON'T GET A TOLL FREE (1-800/1-888/1-877) NUMBER - IT WILL NOT WORK.", "lively-chat-support" ); ?></b> <?php _e( "Place the phone number in the appropriate box below INCUDING THE COUNTRY CODE (eg. 1 for North Amercia, 44 for UK).", "lively-chat-support" ); ?>
        </li>
        <li><?php echo sprintf( __( "When prompted by Twilio, fill in the box for <b>Messaging</b> with \"%s\".", "lively-chat-support" ), site_url() . "/?from_twilio=true"); ?></li>
        <li><?php _e( "From the Twilio dashboard, copy your <b>ACCOUNT SID</b> and <b>AUTH TOKEN</b> into the boxes below.", "lively-chat-support" ); ?></li>
        <li><?php _e( "Click <b>Save My Credentials</b> on this page. You should receive an SMS confirming that everything's ready to go.", "lively-chat-support" ); ?></li>
        <li><?php echo sprintf( __( "Now, you must go to the <a href=\"%s\">Schedule</a> page and choose <b>Online</b> or <b>According to Office Hours</b> from the drop down list. Finally, add the hours you'd like to be available as an SMS customer support agent.", "lively-chat-support" ), "admin.php?page=livelychatsupport&tab=schedule" ); ?></li>
        <li><?php _e( "Thanks for supporting Lively Chat Support! Email dallas@excitecreative.ca if you need any help!", "lively-chat-support" ); ?></li>
      </ol>
    </div>
  
    <div class="field">
      <label for=""><?php _e( "What is your Twilio SID?", "lively-chat-support" ); ?><br></label><br>
      <input type="text" name="twilio_sid" value="<?php echo $livelychatsupport["twilio_sid"]; ?>" autocomplete="off" />
    </div>
  
    <div class="field">
      <label for=""><?php _e( "What is your Twilio Auth Token?", "lively-chat-support" ); ?></label><br>
      <input type="password" name="twilio_auth" value="<?php echo $livelychatsupport["twilio_auth"]; ?>" autocomplete="off" />
    </div>
  
    <div class="field">
      <label for=""><?php _e( "What is your Twilio Phone Number (including country code)?", "lively-chat-support" ); ?></label><br>
      <input type="text" name="twilio_phone" value="<?php echo $livelychatsupport["twilio_phone"]; ?>" />
    </div>
  
    <!--
    <div class="field">
          <label for="sms_responder_id">Who should the messages be sent to? (Go to your Users > Your Profile page to update)</label><br>
          <select name="sms_responder_id">
            <php
            
              global $wpdb;
              $agents_table = $wpdb->prefix . "livelychatsupport_agents";
              $agents = $wpdb->get_results("SELECT * FROM $agents_table", OBJECT_K);
              foreach($agents as $agent) {
            ?>
              <option value="<php echo $agent->id; ?>" <php if ($livelychatsupport["sms_responder_id"] == $agent->id) { echo "selected=\"selected\""; } ?>><php echo $agent->name; ?> (<php echo $agent->phone; ?>)</option>
            <php } ?>
          </select>
        </div>-->
    
  
    <div class="field">
      <input type="submit" value="Save My Credentials" class="button-primary" />
    </div>
  </form>

  <p>
    <b><?php _e("How do I respond to SMS messages?", "lively-chat-support"); ?></b><br>
    <?php _e("Each message you receive begins with a small token and a colon (eg. 4k2:).", "lively-chat-support"); ?><br>
    <?php _e("To respond to a specific conversation, type \"4k2: YOUR MESSAGE HERE\".", "lively-chat-support"); ?><br>
    <?php _e("If you don't include this token in your response, we'll just send it back to the customer that last sent you a message.", "lively-chat-support"); ?>
  </p>
  
<?php } else { ?>
  
  <h2><?php _e( "Chat with your visitors from your phone.", "lively-chat-support" ); ?></h2>
  
  <p>
    <?php _e( "Are you always on the go - not in front of your computer?", "lively-chat-support" ); ?>
  </p>
  <p>
    <?php _e( "With SMS, your visitor's messages are sent to your phone as text messages - simply reply as you would to any sms.", "lively-chat-support" ); ?>
  </p>
  <p>
    <strong><?php _e( "SMS chat is a premium feature and REQUIRES YOUR OWN <a href=\"https://www.twilio.com\">TWILIO</a> ACCOUNT (usually costs $.01/message).", "lively-chat-support" ); ?></strong>
  </p>
  <p>
    <?php _e( "To purchase the addon, pay here and you'll receive an email with an activation code (which you'll enter in the box below):", "lively-chat-support" ); ?>
  </p>

  <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="R6WMR5FYAA2DU">
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
  </form>

  <p>
    <?php _e( "Thanks for your support!", "lively-chat-support" ); ?>
  </p>

  <div class="updated">
    <form action="admin.php?page=livelychatsupport&tab=sms" method="post">
      <div class="field">
        <label>
          <?php _e( "Already have an activation code for SMS? Enter it here:", "lively-chat-support" ); ?><br>
          <input type="text" name="activation_code" value="<?php echo $livelychatsupport["activation_code"]; ?>" />
          <input type="submit" value="<?php _e( "Activate SMS", "lively-chat-support" ); ?>" class="button-primary">
        </label>
      </div>
    </form>
  </div>
  
<?php } ?>