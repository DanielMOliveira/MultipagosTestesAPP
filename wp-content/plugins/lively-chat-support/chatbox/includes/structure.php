<?php

if ($livelychatsupport["online"] == "offline") { $livelychatsupport_offline = true; }
if (($convo->name != "" && $convo->email != "") || $convo->messages_count > 0 || LIVELYCHATSUPPORT_ADMIN == true) { $livelychatsupport_chatting = true; }
$livelychatsupport_states = LivelyChatSupport_state(isset($livelychatsupport_open), isset($livelychatsupport_offline), isset($livelychatsupport_chatting));

if (property_exists($convo, "agent_id")) { 
  $agent = LivelyChatSupport_agent($convo->agent_id);
} else {
  $agent = LivelyChatSupport_agent();
}

?>

<style type="text/css">
  #livelychatsupport-chatbox .livelychatsupport-chatbox-border-colour { border-color: <?php echo $livelychatsupport["colour"]; ?>; }
  #livelychatsupport-chatbox .livelychatsupport-chatbox-background-colour { background-color: <?php echo $livelychatsupport["colour"]; ?>; }
  #livelychatsupport-chatbox .powered_by, #livelychatsupport-chatbox .user_colour { color: <?php echo $livelychatsupport["colour"]; ?>; }
  #livelychatsupport-chatbox .cta_online_image { <?php echo $livelychatsupport["position"]; ?>: <?php echo $livelychatsupport["cta_online_image_offset_x"]; ?>px; bottom: <?php echo $livelychatsupport["cta_online_image_offset_y"] - 161; ?>px; }
  #livelychatsupport-chatbox .cta_offline_image { <?php echo $livelychatsupport["position"]; ?>: <?php echo $livelychatsupport["cta_offline_image_offset_x"]; ?>px; bottom: <?php echo $livelychatsupport["cta_offline_image_offset_y"] - 155; ?>px; }
  #livelychatsupport-chatbox { <?php if (LIVELYCHATSUPPORT_ADMIN != true) { ?>display: none; <?php } ?><?php echo $livelychatsupport["position"]; ?>: 15px; }
  <?php if ($livelychatsupport["show_powered_by"] != "true") { echo "#livelychatsupport-chatbox .powered_by { display: none; }"; } ?>
</style>

<div id="livelychatsupport-chatbox" data-site_url="<?php echo site_url(); ?>" class="<?php echo implode($livelychatsupport_states, " "); ?>" data-ip_url="<?php echo plugins_url("lively-chat-support/chatbox/find_ip.php"); ?>" data-version="<?php global $livelychatsupport_version; echo $livelychatsupport_version; ?>">
  <audio class="bell">
    <source src="<?php echo plugins_url("lively-chat-support/chatbox/assets/audio/bell.mp3"); ?>" type="audio/mpeg">
    <source src="<?php echo plugins_url("lively-chat-support/chatbox/assets/audio/bell.ogg"); ?>" type="audio/ogg">
  </audio>
  
  <?php if ($livelychatsupport["cta_online_image"] != "" || LIVELYCHATSUPPORT_ADMIN == true) { ?>
    <a href="#!/lively-chat-support" target="livelychatsupport-iframe" data-href="<?php echo plugins_url("lively-chat-support/chatbox/convos.php?open="); ?>" class="livelychatsupport-open">
      <img src="<?php echo $livelychatsupport["cta_online_image"] ?>" class="cta_online_image" />
    </a>
  <?php } ?>
  <?php if ($livelychatsupport["cta_offline_image"] != "" || LIVELYCHATSUPPORT_ADMIN == true) { ?>
    <a href="#!/lively-chat-support" target="livelychatsupport-iframe" data-href="<?php echo plugins_url("lively-chat-support/chatbox/convos.php?open="); ?>" class="livelychatsupport-open">
      <img src="<?php echo $livelychatsupport["cta_offline_image"] ?>" class="cta_offline_image livelychatsupport-open" />
    </a>
  <?php } ?>
  
  <div class="header livelychatsupport-chatbox-background-colour">
    <h3>
      <a href="#!/lively-chat-support" target="livelychatsupport-iframe" data-href="<?php echo plugins_url("lively-chat-support/chatbox/convos.php?open="); ?>" class="livelychatsupport-close">&mdash;</a>
      <a href="#!/lively-chat-support" target="livelychatsupport-iframe" data-href="<?php echo plugins_url("lively-chat-support/chatbox/convos.php?open="); ?>" class="livelychatsupport-open">
        <span class="cta_offline_text"><?php echo stripslashes($livelychatsupport["cta_offline_text"]); ?></span>
        <span class="cta_online_text"><?php echo stripslashes($livelychatsupport["cta_online_text"]); ?></span>
      </a>
    </h3>
  </div>
  
  <?php if (LIVELYCHATSUPPORT_ADMIN != true) { ?>
    <form class="survey invalid" method="post" action="<?php echo admin_url('admin-ajax.php'); ?>">
      <input type="hidden" name="id" class="id" />
      <div class="questions"></div>
    
      <div class="actions field">
        <button class="next_step livelychatsupport-chatbox-border-colour livelychatsupport-chatbox-background-colour"><?php _e( "Next", "lively-chat-support" ); ?> (<span class="current_question">0</span> of <span class="question_count">0</span>) &rarr;</button>
        <button class="finish livelychatsupport-chatbox-border-colour livelychatsupport-chatbox-background-colour"><?php _e( "Submit", "lively-chat-support" ); ?></button>
        <a href="#!/lively-chat-support" class="back user_colour">&larr; <?php _e( "Prev Step", "lively-chat-support" ); ?></a>
      </div>
    
      <div class="survey-thanks">
        <div class="field">
          <p></p>
        </div>
      </div>
    </form>
  <?php } ?>
  
  <div class="prompter">
    <div class="offline-thanks">
      <div class="field">
        <p>
          <?php echo nl2br(stripslashes($livelychatsupport["offline_thanks"])); ?>
        </p>
      </div>
    </div>
    
    <<?php echo LIVELYCHATSUPPORT_ADMIN == true ? "div" : "form" ?> id="livelychatsupport-register" method="post" target="livelychatsupport-iframe" action="<?php echo admin_url('admin-ajax.php'); ?>">
      <div class="field">
        <label for="livelychatsupport-chatbox-name"><?php _e( "What is your name?", "lively-chat-support" ); ?></label>
        <input id="livelychatsupport-chatbox-name" type="text" name="Name" value="<?php echo $convo->name; ?>">
      </div>
    
      <div class="field">
        <label for="livelychatsupport-chatbox-email"><?php _e( "What is your email?", "lively-chat-support" ); ?></label>
        <input id="livelychatsupport-chatbox-email" type="email" name="Email" value="<?php echo $convo->email; ?>">
      </div>
      
      <div class="field livelychatsupport-offline-message">
        <label for="livelychatsupport-offline-body"><?php _e( "What is your question?", "lively-chat-support" ); ?></label>
        <textarea id="livelychatsupport-offline-body" name="Comment"></textarea>
      </div>
      
      <div class="field livelychatsupport-error">
        <p><?php _e( "Please provide a valid name, email, and question.", "lively-chat-support" ); ?></p>
      </div>
    
      <div class="field">
        <button type="submit" class="livelychatsupport-chatbox-border-colour livelychatsupport-chatbox-background-colour">
          <span class="loading"><?php _e( "Loading...", "lively-chat-support" ); ?></span>
          <span class="nonloading">
            <span class="offline_button"><?php _e("Send Email!", "lively-chat-support"); ?></span>
            <span class="online_button"><?php _e("Start Chatting Now!", "lively-chat-support"); ?></span>
          </span>
        </button>
      </div>
      
      <a href="https://github.com/dallas22ca/lively-chat-support" class="powered_by"><?php _e( "Powered by LivelyChat", "lively-chat-support" ); ?></a>
      <div class="clear"></div>
    </<?php echo LIVELYCHATSUPPORT_ADMIN == true ? "div" : "form" ?>>
  </div>
  
  <div class="chat">

    <ul class="messages" data-latest_id="0" data-url="<?php echo admin_url('admin-ajax.php'); ?>">
      <li class="chatting_with">
        <?php if ($agent->avatar != "") { ?>
          <img src="<?php echo $agent->avatar; ?>" />
        <?php } ?>
        <?php if ($agent->name != "") { ?>
          <p>
            <?php _e( "You're chatting with", "lively-chat-support" ); ?><br>
            <strong><?php echo $agent->name; ?></strong>
          </p>
        <?php } ?>
        <div class="clear"></div>
      </li>
      
      <li class="message message_template">
        <p class="body"></p>
        <p class="date"></p>
        <div class="clear"></div>
      </li>
    </ul>
    
    <img src="<?php echo plugins_url("lively-chat-support/chatbox/assets/loading.gif"); ?>" class="loading">
    
    <a href="http://wordpress.org/plugins/lively-chat-support" class="powered_by"><?php _e( "Powered by LivelyChat", "lively-chat-support" ); ?></a>
    <a href="#!/lively-chat-support" class="delete_history"><?php _e( "Delete History", "lively-chat-support" ); ?></a>
    
    <<?php echo LIVELYCHATSUPPORT_ADMIN == true ? "div" : "form" ?> id="livelychatsupport-chatbox-new-message" action="<?php echo admin_url('admin-ajax.php'); ?>" method="post">
      <input type="hidden" id="livelychatsupport-chatbox-token" name="livelychatsupport-chatbox-token" value="<?php echo $convo->token; ?>" />
      <textarea id="livelychatsupport-chatbox-body" name="livelychatsupport-chatbox-body" placeholder="<?php _e( "Type here and press <Enter>", "lively-chat-support" ); ?>" rows="1"></textarea>
    </<?php echo LIVELYCHATSUPPORT_ADMIN == true ? "div" : "form" ?>>
  </div>
</div>

<iframe class="livelychatsupport-iframe" name="livelychatsupport-iframe"></iframe>