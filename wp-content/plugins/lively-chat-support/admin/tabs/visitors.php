<?php

global $wpdb;
$convos_table = $wpdb->prefix . "livelychatsupport_convos";
if (isset($_GET["convo_token"])) {
  $convo = $wpdb->get_row("SELECT * FROM $convos_table WHERE token = '$_GET[convo_token]'");
} else {
  $convo = null;
}

if ($convo != null) {
  if (strtotime($livelychatsupport["start"]) >= strtotime($convo->updated_at)) {
    $livelychatsupport["start"] = date("F j, Y", current_time("timestamp") - 86400);
    LivelyChatSupport_settings(array("start" => $livelychatsupport["start"]));
  }

  if (strtotime($livelychatsupport["finish"]) <= strtotime($convo->updated_at)) {
    $livelychatsupport["finish"] = date("F j, Y", current_time("timestamp") + 86400);
    LivelyChatSupport_settings(array("finish" => $livelychatsupport["finish"]));
  }
}

$convos = LivelyChatSupport_find_visitors();

?>

<ul id="online_convos" class="livelychatsupport-sidebar" data-add_convo="<?php echo admin_url('admin-ajax.php'); ?>" data-convos="<?php echo htmlspecialchars(json_encode(array("convos" => $convos))); ?>">
  <li>
    <label>
      <?php _e( "Start", "lively-chat-support" ); ?><br>
      <input type="text" id="start" value="<?php echo $livelychatsupport["start"] ?>" />
    </label>
    <a href="#!/lively-chat-support" class="set_as_today" data-value="<?php echo date("F j, Y", current_time("timestamp")); ?>"><?php _e( "Today", "lively-chat-support" ); ?></a>
    <a href="#!/lively-chat-support" class="set_as_today" data-value="<?php echo date("F j, Y", current_time("timestamp") - 86400); ?>"><?php _e( "Yesterday", "lively-chat-support" ); ?></a>
    <a href="#!/lively-chat-support" class="set_as_today" data-value="<?php echo date("F j, Y", current_time("timestamp") - 604800); ?>"><?php _e( "1 Week Ago", "lively-chat-support" ); ?></a>
    <div class="clear"></div>
  </li>
  <li>
    <label>
      <?php _e( "Finish", "lively-chat-support" ); ?><br>
      <input type="text" id="finish" value="<?php echo $livelychatsupport["finish"] ?>" />
    </label>
    <a href="#!/lively-chat-support" class="set_as_today" data-value="<?php echo date("F j, Y", current_time("timestamp")); ?>"><?php _e( "Today", "lively-chat-support" ); ?></a>
    <a href="#!/lively-chat-support" class="set_as_today" data-value="<?php echo date("F j, Y", current_time("timestamp") - 86400); ?>"><?php _e( "Yesterday", "lively-chat-support" ); ?></a>
    <a href="#!/lively-chat-support" class="set_as_today" data-value="<?php echo date("F j, Y", current_time("timestamp") - 604800); ?>"><?php _e( "1 Week Ago", "lively-chat-support" ); ?></a>
    <div class="clear"></div>
  </li>
  <li class="divider"></li>
</ul>

<div class="livelychatsupport-yield-with-sidebar">
  
<?php if ($convo == null) { ?>
  
  <p>
    <?php _e( "Choose a visitor on the left.", "lively-chat-support" ); ?>
  </p>
  
<?php
  
  } else {
    
    $wpdb->update( 
      $convos_table, 
      array("pending" => false),
      array("token" => $convo->token)
    );
  
  ?>
  
  <a href="admin.php?page=livelychatsupport&tab=visitors&convo_token=<?php echo $convo->token; ?>&delete_convo=true" class="delete_convo button-secondary"><?php _e( "Delete This Conversation", "lively-chat-support" ); ?></a>
  <p class="right_help"><?php _e( "Delete all convos on the <a href=\"admin.php?page=livelychatsupport&tab=settings#delete_convos_anchor\">Settings</a> page.", "lively-chat-support" ); ?></p>
  
  <?php if ($convo->name == "") { ?>
    <h1><?php echo $convo->city; ?>, <?php echo $convo->province; ?></h1>
    <div class="stats">
      <p>
        <?php _e( "Agent:", "lively-chat-support" ); ?> <strong><?php echo LivelyChatSupport_agent($convo->agent_id)->name; ?></strong><br>
        <?php _e( "IP:", "lively-chat-support" ); ?> <strong><?php echo $convo->ip; ?></strong><br>
        <?php _e( "Last seen:", "lively-chat-support" ); ?> <strong><?php echo date("D, M j, Y \a\\t g:ia", strtotime($convo->last_seen) + get_option("gmt_offset") * 60 * 60); ?></strong>
      </p>
  <?php } else { ?>
    <h1><?php echo $convo->name; ?></h1>
    <div class="stats">
      <p>
        <?php _e( "Agent:", "lively-chat-support" ); ?> <strong><?php echo LivelyChatSupport_agent($convo->agent_id)->name; ?></strong><br>
        <?php _e( "Location:", "lively-chat-support" ); ?> <strong><?php echo $convo->city; ?>, <?php echo $convo->province; ?></strong><br>
        <?php _e( "Email:", "lively-chat-support" ); ?> <strong><?php echo $convo->email; ?></strong><br>
        <?php _e( "IP:", "lively-chat-support" ); ?> <strong><?php echo $convo->ip; ?></strong><br>
        <?php _e( "Last seen:", "lively-chat-support" ); ?> <strong><?php echo date("D, M j, Y \a\\t g:ia", strtotime($convo->last_seen) + get_option("gmt_offset") * 60 * 60); ?></strong>
      </p>
  <?php }?>
  
      <ul id="all_pages">
        <?php $urls = json_decode($convo->referrers); ?>
        <?php if (is_array($urls)) { ?>
          <?php foreach (array_reverse($urls) as $url) { ?>
            <li class="<?php if (!isset($first_page)) { echo "first_page"; } ?>">
              <span class="on_first"><?php _e( "Now visiting:", "lively-chat-support" ); ?> </span>
              <a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a>
              <span class="on_first">(<a href="#!/lively-chat-support" class="show_all_pages"><?php _e( "see all", "lively-chat-support" ); ?></a>)</span>
            </li>
            <?php if (!isset($first_page)) { $first_page = true; } ?>
          <?php } ?>
        <?php } ?>
      </ul>
  
    </div>
    
  <div id="admin_chat" class="chat">
    
    <?php $messages = LivelyChatSupport_messages($convo->token, "desc") ?>
    
    <?php if ($wpdb->num_rows > 0) { ?>
      <form id="livelychatsupport-chatbox-new-message" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post">
        <input type="hidden" id="livelychatsupport-chatbox-from_agent" name="from_agent" value="true">
        <input type="hidden" id="livelychatsupport-chatbox-token" name="livelychatsupport-chatbox-token" value="<?php echo $convo->token; ?>" />
        <textarea id="livelychatsupport-chatbox-body" name="livelychatsupport-chatbox-body" placeholder="<?php _e( "Type here and press <Enter>", "lively-chat-support" ); ?>" rows="1"></textarea>
        <img src="<?php echo plugins_url("lively-chat-support/chatbox/assets/loading.gif"); ?>" class="loading">
      </form>
    <?php } else { ?>
      <p>
        <?php _e( "You can't talk to this person because they haven't registered yet.", "lively-chat-support" ); ?>
      </p>
      <p>
        <?php _e( "We'll be unlocking this feature in a future version.", "lively-chat-support" ); ?>
      </p>
    <?php } ?>
  
    <ul id="messages" class="convo_<?php echo $convo->token; ?>_messages messages">
      <li class="message message_template">
        <p class="body"></p>
        <p class="date"></p>
        <div class="clear"></div>
      </li>
    
      <?php foreach ($messages as $message) { ?>
        <li id="message_<?php echo $message->id; ?>" data-id="<?php echo $message->id; ?>" class="message <?php if ($message->from_agent == 1) { echo "from_agent"; } ?>">
          <p class="body"><?php echo stripslashes($message->body); ?></p>
          <p class="date"><?php echo strftime("%l:%M", strtotime($message->created_at) + get_option("gmt_offset") * 60 * 60); ?></p>
          <div class="clear"></div>
        </li>
      <?php } ?>
    </ul>
    
  </div>
    
<?php } ?>

</div>

<style type="text/css" media="print">
  /* PRINT STYLES */
  .icon32, #all_pages, #wpfooter, #adminmenuback, #adminmenuwrap, .button-secondary, .button-primary, #wpadminbar, .nav-tab-wrapper, #online_convos, .right_help, button, input, textarea, .upsell { display: none !important; }
  #admin_chat { display: block !important; }
  #wpcontent, #wpbody, .wrap, .livelychatsupport-yield-with-sidebar { margin: 0 !important; width: 100% !important; float: none !important; }
  * { font-size: 13px; }
</style>