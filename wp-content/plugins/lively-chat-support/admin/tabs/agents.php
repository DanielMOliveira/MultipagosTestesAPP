<?php

global $wpdb;
$agents_table = $wpdb->prefix . "livelychatsupport_agents";
$agents = LivelyChatSupport_agents(true);

?>

<h2><?php _e( "Customer Support Agents", "lively-chat-support" ); ?></h2>

<div class="updated">
  <?php if (strpos($livelychatsupport["addons"], "sms") !== false) { ?>
    <p><?php _e( "<strong>Phone Number</strong> - should include country code (eg. 19998887777). ", "lively-chat-support" ); ?></p>
  <?php } ?>

  <p><?php echo sprintf( __( "<strong>Email Address</strong> - Can be changed in the <a href=\"%s\">WP Users</a> section.", "lively-chat-support" ), admin_url("users.php") ); ?></p>
  <p><?php _e( "<strong>Display Name</strong> - Shows in the \"You're Chatting With\" section of the chat box.", "lively-chat-support" ); ?></p>
  <p>
    <?php _e( "<strong>Active</strong> - When checked, the user can chat with visitors. At least 1 agent must be active for LivelyChatSupport to work.", "lively-chat-support" ); ?>
    <i><?php if (strpos($livelychatsupport["addons"], "multi") === false) { _e( "To add more agents, purchase the addon below.", "lively-chat-support" ); } ?></i>
  </p>
  <p><?php _e( "<strong>Default</strong> - The default Lively Chat Support agent.", "lively-chat-support" ); ?></p>
</div>

<br>

<form method="post">
  <table id="agents" data-multi="<?php echo (strpos($livelychatsupport["addons"], "multi") !== false) ? "true" : "false" ?>" class="widefat">
    <thead>
      <tr>
        <th style="width: 1%; "><?php _e( "Active", "lively-chat-support" ); ?></th>
        <th style="width: 1%; "><?php _e( "Default", "lively-chat-support" ); ?></th>
        <th style="width: 20%; "><?php _e( "Email Address", "lively-chat-support" ); ?></th>
        <th style="width: 20%; "><?php _e( "Display Name", "lively-chat-support" ); ?></th>
        <?php if (strpos($livelychatsupport["addons"], "sms") !== false) { ?>
          <th style="width: 20%; "><?php _e( "Phone Number", "lively-chat-support" ); ?></th>
        <?php } ?>
        <th style="width: 30%; "><?php _e( "Avatar Url", "lively-chat-support" ); ?></th>
      </tr>
    </thead>
    <tbody>
    
      <?php $n = 0; foreach($agents as $agent) { ?>
        <tr class="agent">
          <td>
            <input type="hidden" name="agents[<?php echo $n; ?>][id]" value="<?php echo $agent->id; ?>" />
            <input type="hidden" name="agents[<?php echo $n; ?>][active]" class="agent_hidden_active" value="<?php if ($agent->active) { echo "true"; } else { echo "false"; } ?>" />
            <input type="checkbox" <?php if ($agent->active) { echo "checked=\"checked\""; } ?> disabled="disabled" class="agent_active_checkbox" />
          </td>
          <td>
            <input type="radio" name="default_responder_id" value="<?php echo $agent->id; ?>" <?php if ($livelychatsupport["default_responder_id"] == $agent->id) { echo "checked=\"checked\""; } ?> class="agent_default_checkbox" />
          </td>
          <td class="email">
            <input type="text" readonly="readonly" value="<?php echo $agent->email; ?>" />
          </td>
          <td class="name">
            <input type="text" name="agents[<?php echo $n; ?>][name]" value="<?php echo $agent->name; ?>" />
          </td>
          <?php if (strpos($livelychatsupport["addons"], "sms") !== false) { ?>
            <td class="phone">
              <input type="text" name="agents[<?php echo $n; ?>][mobile]" value="<?php echo $agent->mobile; ?>" />
            </td>
          <?php } ?>
          <td class="url">
            <input type="text" name="agents[<?php echo $n; ?>][avatar]" class="agent_avatar_url" value="<?php echo $agent->avatar; ?>" />
            <a href="#!/lively-chat-support" class="choose_agent_avatar button-secondary">Choose</a>
          </td>
        </tr>
      <?php $n += 1; } ?>
    
    </tbody>
  </table>

  <?php if (strpos($livelychatsupport["addons"], "multi") !== false) { ?>
    <br>
    <a href="<?php echo admin_url("user-new.php"); ?>" class="new_agent button-secondary">Add Agent</a>
  <?php } ?>

  <div class="field">
    <input type="submit" value="<?php _e("Save Agents", "lively-chat-support"); ?>" class="button-primary" />
  </div>
</form>

<?php if (strpos($livelychatsupport["addons"], "multi") === false) { ?>
  
  <br>

  <hr />

  <br>

  <h2><?php _e( "Need Multiple Agents?", "lively-chat-support" ); ?></h2>
  <p>
    <strong><?php _e( "Multiple Agents chat is a premium feature.", "lively-chat-support" ); ?></strong><br>
    <?php _e( "It is managed through the Wordpress Users pages.", "lively-chat-support" ); ?><br>
    <?php _e( "To purchase the addon, pay here and you'll receive an email with an activation code (which you'll enter in the box below):", "lively-chat-support" ); ?>
  </p>

  <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="M4F2RKSLV9X6Y">
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
  </form>

  <div class="">
    <form action="admin.php?page=livelychatsupport&tab=agents" method="post">
      <div class="field">
        <label>
          <?php _e( "Already have an activation code? Enter it here:", "lively-chat-support" ); ?><br>
          <input type="text" name="activation_code" value="<?php echo $livelychatsupport["activation_code"]; ?>" />
          <input type="submit" value="<?php _e( "Activate", "lively-chat-support" ); ?>" class="button-primary">
        </label>
      </div>
    </form>
  </div>
  
<?php } ?>