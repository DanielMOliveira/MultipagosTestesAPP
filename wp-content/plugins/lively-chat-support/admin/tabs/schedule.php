<?php

  global $wpdb;
  $hours_table = $wpdb->prefix . "livelychatsupport_hours";
  $hours = $wpdb->get_results("SELECT * FROM $hours_table");
  $agents = LivelyChatSupport_agents();
  
?>

<form action="admin.php?page=livelychatsupport&tab=schedule" method="post">
  
  <h2><?php _e( "Schedule & Office Hours", "lively-chat-support" ); ?></h2>
  
  <div class="field">
    <select name="online" id="online">
      <option <?php if ($livelychatsupport["online"] == "hours") { echo "selected='selected'"; } ?>value="hours"><?php _e( "Online According to Office Hours", "lively-chat-support" ); ?></option>
      <option <?php if ($livelychatsupport["online"] == "online") { echo "selected='selected'"; } ?>value="online"><?php _e( "Always Online", "lively-chat-support" ); ?></option>
      <option <?php if ($livelychatsupport["online"] == "offline") { echo "selected='selected'"; } ?>value="offline"><?php _e( "Always Offline", "lively-chat-support" ); ?></option>
      <option <?php if ($livelychatsupport["online"] == "hidden") { echo "selected='selected'"; } ?>value="hidden"><?php _e( "Don't Show LivelyChatSupport At All", "lively-chat-support" ); ?></option>
    </select>
  </div>

  <div class="field hours_field" style="display: none;">
    <p><?php echo sprintf( __( "Is it <b>%s</b>? If not, change your timezone on the <a href=\"%s\">General Settings</a> page.", "lively-chat-support" ), date("g:i A \\o\\n l", current_time("timestamp")), "options-general.php" ); ?>
    <p><?php echo sprintf( __( "Not seeing your agents? Make sure the \"Active\" column is ticked on the <a href=\"%s\">Agents</a> page.", "lively-chat-support" ), admin_url("admin.php?page=livelychatsupport&tab=agents") ); ?>
    
    <table class="widefat">
      <thead>
        <tr>
          <th><?php _e( "Day", "lively-chat-support" ); ?></th>
          <th class="center"><?php _e( "Open At", "lively-chat-support" ); ?> <span class="faint">(eg. 6am, 6 AM, 6:00 AM)</span></th>
          <th class="center"><?php _e( "Close At", "lively-chat-support" ); ?> <span class="faint">(eg. 6am, 6 AM, 6:00 AM)</span></th>
          <th class="center"><?php _e( "Who Should Respond?", "lively-chat-support" ); ?></th>
          <th class="center"><?php _e( "Contact Method", "lively-chat-support" ); ?></th>
          <th></th>
        </tr>
      </thead>
      <tbody id="hours">
      
        <?php $n = 0; foreach($hours as $hour) { ?>
          <tr class="hour">
            <td>
              <input type="hidden" name="hours[<?php echo $n; ?>][id]" class="id" value="<?php echo $hour->id; ?>" />
              <select name="hours[<?php echo $n; ?>][day]">
                <option value="Sunday" <?php if ($hour->day == "Sunday") { echo "selected=\"selected\""; } ?>><?php _e( "Sunday", "lively-chat-support" ); ?></option>
                <option value="Monday" <?php if ($hour->day == "Monday") { echo "selected=\"selected\""; } ?>><?php _e( "Monday", "lively-chat-support" ); ?></option>
                <option value="Tuesday" <?php if ($hour->day == "Tuesday") { echo "selected=\"selected\""; } ?>><?php _e( "Tuesday", "lively-chat-support" ); ?></option>
                <option value="Wednesday" <?php if ($hour->day == "Wednesday") { echo "selected=\"selected\""; } ?>><?php _e( "Wednesday", "lively-chat-support" ); ?></option>
                <option value="Thursday" <?php if ($hour->day == "Thursday") { echo "selected=\"selected\""; } ?>><?php _e( "Thursday", "lively-chat-support" ); ?></option>
                <option value="Friday" <?php if ($hour->day == "Friday") { echo "selected=\"selected\""; } ?>><?php _e( "Friday", "lively-chat-support" ); ?></option>
                <option value="Saturday" <?php if ($hour->day == "Saturday") { echo "selected=\"selected\""; } ?>><?php _e( "Saturday", "lively-chat-support" ); ?></option>
              </select>
            </td>
            <td class="center">
              <input type="text" name="hours[<?php echo $n; ?>][open_at]" value="<?php echo LivelyChatSupport_military_to_pretty((string)$hour->open_at); ?>" class="center" />
            </td>
            <td class="center">
              <input type="text" name="hours[<?php echo $n; ?>][close_at]" value="<?php echo LivelyChatSupport_military_to_pretty((string)$hour->close_at); ?>" class="center" />
            </td>
            <td class="center">
              <select name="hours[<?php echo $n; ?>][responder_id]">
                <option value="0" <?php if ($hour->responder_id == 0) { echo "selected=\"selected\""; } ?>><?php _e( "Anyone", "lively-chat-support" ); ?></option>
                <?php foreach ($agents as $agent) { ?>
                  <option value="<?php echo $agent->id; ?>" <?php if ($hour->responder_id == $agent->id) { echo "selected=\"selected\""; } ?>><?php echo $agent->name; ?></option>
                <?php } ?>
              </select>
            </td>
            <td class="center">
              <select name="hours[<?php echo $n; ?>][via]">
                <?php if (strpos($livelychatsupport["addons"], "sms") !== false) { ?>
                  <option value="sms" <?php if ($hour->via == "sms") { echo "selected=\"selected\""; } ?>><?php _e( "SMS", "lively-chat-support" ); ?></option>
                <?php } ?>
                <option value="web" <?php if ($hour->via == "web") { echo "selected=\"selected\""; } ?>><?php _e( "Wordpress", "lively-chat-support" ); ?></option>
              </select>
            </td>
            <td>
              <input type="hidden" name="hours[<?php echo $n; ?>][delete]" value="0" class="delete" />
              <a href="#!/lively-chat-support" class="delete_row button-secondary" data-row="hour"><?php _e( "Delete", "lively-chat-support" ); ?></a>
            </td>
          </tr>
        <?php $n += 1; } ?>
      
        <tr class="hour hour_template template">
          <td>
            <select name="hours[][day]">
              <option value="Sunday" ><?php _e( "Sunday", "lively-chat-support" ); ?></option>
              <option value="Monday" ><?php _e( "Monday", "lively-chat-support" ); ?></option>
              <option value="Tuesday" ><?php _e( "Tuesday", "lively-chat-support" ); ?></option>
              <option value="Wednesday" ><?php _e( "Wednesday", "lively-chat-support" ); ?></option>
              <option value="Thursday" ><?php _e( "Thursday", "lively-chat-support" ); ?></option>
              <option value="Friday" ><?php _e( "Friday", "lively-chat-support" ); ?></option>
              <option value="Saturday" ><?php _e( "Saturday", "lively-chat-support" ); ?></option>
            </select>
            <input type="hidden" name="hours[][id]" class="id" />
          </td>
          <td class="center">
            <input type="text" name="hours[][open_at]" class="center" />
          </td>
          <td class="center">
            <input type="text" name="hours[][close_at]" class="center" />
          </td>
          <td class="center">
            <select name="hours[][responder_id]">
              <option value="0"><?php _e( "Anyone", "lively-chat-support" ); ?></option>
              <?php foreach ($agents as $agent) { ?>
                <option value="<?php echo $agent->id; ?>"><?php echo $agent->name; ?></option>
              <?php } ?>
            </select>
          </td>
          <td class="center">
            <select name="hours[][via]">
              <?php if (strpos($livelychatsupport["addons"], "sms") !== false) { ?>
                <option value="sms"><?php _e( "SMS", "lively-chat-support" ); ?></option>
              <?php } ?>
              <option value="web"><?php _e( "Wordpress", "lively-chat-support" ); ?></option>
            </select>
          </td>
          <td>
            <input type="hidden" name="hours[][delete]" value="0" class="delete" />
            <a href="#!/lively-chat-support" class="delete_row button-secondary" data-row="hour"><?php _e( "Delete", "lively-chat-support" ); ?></a>
          </td>
        </tr>
      
      </tbody>
    </table>
  
    <br>
    <a href="#!/lively-chat-support" class="add_row button-secondary" data-row="hour"><?php _e( "Add Another Time Slot", "lively-chat-support" ); ?></a>
  </div>

  <div class="field">
    <input type="submit" value="<?php _e( "Save My Settings", "lively-chat-support" ); ?>" class="button-primary" />
  </div>

  <div class="clear"></div>

</form>
