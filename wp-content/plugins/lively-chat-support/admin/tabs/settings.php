<?php

global $wpdb;
$hours_table = $wpdb->prefix . "livelychatsupport_hours";

$convo = (object) array(
  "token" => "notta", 
  "name" => "John Doe", 
  "email" => "johndoe@example.com"
);

?>

<style type="text/css">
  #livelychatsupport-chatbox .cta_online_image { <?php echo $livelychatsupport["position"]; ?>: <?php echo $livelychatsupport["cta_online_image_offset_x"]; ?>px; bottom: <?php echo $livelychatsupport["cta_online_image_offset_y"]; ?>px !important; margin-bottom: 5px; }
  #livelychatsupport-chatbox .cta_offline_image { <?php echo $livelychatsupport["position"]; ?>: <?php echo $livelychatsupport["cta_offline_image_offset_x"]; ?>px; bottom: <?php echo $livelychatsupport["cta_offline_image_offset_y"]; ?>px !important; margin-bottom: 208px; }
  #livelychatsupport-chatbox.offline .cta_offline_image { display: block; }
  #livelychatsupport-chatbox.offline .cta_online_image { display: none !important; }
  #livelychatsupport .livelychatsupport-iframe { display: none; }
</style>

<form action="admin.php?page=livelychatsupport&tab=settings" method="post">
  
  <h2><?php _e( "Lively Chat Theme", "lively-chat-support" ); ?></h2>

  <div id="livelychatsupport-online" class="livelychatsupport-design-form" data-mode="online">
    <div class="field">
      <label for="colour"><?php _e( "Colour", "lively-chat-support" ); ?></label><br>
      <input type="text" name="colour" id="colour" value="<?php echo $livelychatsupport["colour"]; ?>" />
    </div>
  
    <div class="field">
      <label for="cta_text"><?php _e( "Call To Action Text", "lively-chat-support" ); ?></label><br>
      <input type="text" name="cta_online_text" id="cta_online_text" class="cta_text" value="<?php echo $livelychatsupport["cta_online_text"]; ?>" />
    </div>
  
    <div class="field">
      <label for="cta_online_image"><?php _e( "Call To Action Image", "lively-chat-support" ); ?></label><br>
    
      <a href="#!/lively-chat-support" class="livelychatsupport-detail livelychatsupport-show-prebuilt_ctas"><?php _e( "Use pre-built", "lively-chat-support" ); ?></a>,
      <a href="#!/lively-chat-support" class="livelychatsupport-detail choose_cta_online_image"><?php _e( "Choose file", "lively-chat-support" ); ?></a>, <?php _e( "or", "lively-chat-support" ); ?>
      <a href="#!/lively-chat-support" class="livelychatsupport-detail no_cta_online_image"><?php _e( "No image", "lively-chat-support" ); ?></a><br>
      <ul class="livelychatsupport-prebuilt_ctas">
        <?php
          $ctas = opendir(LIVELYCHATSUPPORT_ROOT. "/chatbox/assets/ctas/online");
          
          if ($ctas) {
          while (false !== ($cta = readdir($ctas))) { 
            if ($cta != "." && $cta != "..") { ?>
            
          <li><img src="<?php echo plugins_url( "lively-chat-support/chatbox/assets/ctas/online/$cta" ); ?>"></li>
        
        <?php } } } ?>
      </ul>
      
      <input type="hidden" name="cta_online_image_offset_y" id="cta_online_image_offset_y" value="<?php echo $livelychatsupport["cta_online_image_offset_y"]; ?>" />
      <input type="text" name="cta_online_image" id="cta_online_image" class="cta_image" value="<?php echo $livelychatsupport["cta_online_image"]; ?>" /><br>
      <span class="livelychatsupport-detail"><?php _e( "Hint: You can drag the image vertically.", "lively-chat-support" ); ?></span>
    </div>
  
    <div class="field">
      <label for="position">
        <?php _e( "Position on Screen", "lively-chat-support" ); ?><br>
        <span class="livelychatsupport-detail"><?php _e( "Hint: Also aligns image if present.", "lively-chat-support" ); ?></span>
      </label><br>
      <select id="position" name="position">
        <option value="right" <?php if ($livelychatsupport["position"] == "right") { echo "selected='selected'"; } ?>><?php _e( "Bottom Right", "lively-chat-support" ); ?></option>
        <option value="left" <?php if ($livelychatsupport["position"] == "left") { echo "selected='selected'"; } ?>><?php _e( "Bottom Left", "lively-chat-support" ); ?></option>
      </select>
    </div>
    
    <div class="field">
      <label for="subscriber_email"><?php _e( "Email responses to which address?", "lively-chat-support" ); ?></label><br>
      <input type="text" name="subscriber_email" id="subscriber_email" value="<?php echo $livelychatsupport["subscriber_email"]; ?>" />
    </div>
    
    <div class="field">
      <input type="checkbox" id="show_powered_by"<?php if ($livelychatsupport["show_powered_by"] == "true") { echo " checked=\"checked\""; } ?> />
      <input type="hidden" name="show_powered_by" id="hidden_show_powered_by" value="<?php echo $livelychatsupport["show_powered_by"]; ?>" />
      <label for="show_powered_by"><?php _e( "Show \"Powered By Lively Chat\"", "lively-chat-support" ); ?></label>
    </div>
  
    <div class="field">
      <input type="submit" value="<?php _e( "Save My Settings", "lively-chat-support" ); ?>" class="button-primary" />
    </div>
  </div>
  
  <div class="livelychatsupport-preview" data-mode="online">
    <?php
      
      $livelychatsupport_open = true;
      unset($livelychatsupport_offline); 
      $livelychatsupport_chatting = true;
      
      include(LIVELYCHATSUPPORT_ROOT. "/chatbox/includes/structure.php");
    ?>
  </div>

  <div class="clear"></div>

  <hr />

  <h2><?php _e( "Offline Mode (aka. the perfect lead-collection form)", "lively-chat-support" ); ?></h2>

  <div id="livelychatsupport-offline" class="livelychatsupport-design-form" data-mode="offline">
    <div class="field">
      <label for="cta_text"><?php _e( "Call To Action Text", "lively-chat-support" ); ?></label><br>
      <input type="text" name="cta_offline_text" id="cta_offline_text" class="cta_text" value="<?php echo $livelychatsupport["cta_offline_text"]; ?>" />
    </div>

    <div class="field">
      <label for="cta_offline_image"><?php _e( "Call To Action Image", "lively-chat-support" ); ?></label><br>
  
      <a href="#!/lively-chat-support" class="livelychatsupport-detail livelychatsupport-show-prebuilt_ctas"><?php _e( "Use pre-built", "lively-chat-support" ); ?></a>,
      <a href="#!/lively-chat-support" class="livelychatsupport-detail choose_cta_offline_image"><?php _e( "Choose file", "lively-chat-support" ); ?></a>, <?php _e( "or", "lively-chat-support" ); ?>
      <a href="#!/lively-chat-support" class="livelychatsupport-detail no_cta_offline_image"><?php _e( "No image", "lively-chat-support" ); ?></a><br>
      <ul class="livelychatsupport-prebuilt_ctas">
        <?php
          $ctas = opendir(LIVELYCHATSUPPORT_ROOT. "/chatbox/assets/ctas/offline");
          if ($ctas) {
          while (false !== ($cta = readdir($ctas))) { 
            if ($cta != "." && $cta != "..") { ?>
          
          <li><img src="<?php echo plugins_url( "lively-chat-support/chatbox/assets/ctas/offline/$cta" ); ?>"></li>
      
        <?php } } } ?>
      </ul>
      
      <input type="hidden" name="cta_offline_image_offset_y" id="cta_offline_image_offset_y" value="<?php echo $livelychatsupport["cta_offline_image_offset_y"]; ?>" />
      <input type="text" name="cta_offline_image" id="cta_offline_image" class="cta_image" value="<?php echo $livelychatsupport["cta_offline_image"]; ?>" /><br>
      <span class="livelychatsupport-detail"><?php _e( "Hint: You can drag the image vertically.", "lively-chat-support" ); ?></span>
    </div>
    
    <div class="field">
      <label for="offline_thanks"><?php _e( "Thank you text after submission", "lively-chat-support" ); ?></label><br>
      <textarea id="offline_thanks" name="offline_thanks"><?php echo stripslashes($livelychatsupport["offline_thanks"]); ?></textarea><br>
      <span class="livelychatsupport-detail"><?php _e( "Hint: Basic HTML is acceptable.", "lively-chat-support" ); ?></span><br>
    </div>
    
    <div class="field">
      <input type="submit" value="<?php _e( "Save My Settings", "lively-chat-support" ); ?>" class="button-primary" />
    </div>
  </div>
  
  <div class="livelychatsupport-preview" data-mode="offline">
    <?php
      
      $livelychatsupport_open = true;
      $livelychatsupport_offline = true; 
      unset($livelychatsupport_chatting);
      
      include(LIVELYCHATSUPPORT_ROOT . "/chatbox/includes/structure.php");
    ?>
  </div>

  <div class="clear"></div>

  <hr />
  
  <h2 id="delete_convos_anchor"><?php _e( "Chatbox Visibility", "lively-chat-support" ); ?></h2>
  
  <div class="field">
    <label for="visible_pages"><?php _e( "Which pages should LivelyChat be shown on?", "lively-chat-support" ); ?></label><br>
    <div class="">
      <ul>
        <li>&rarr; &nbsp;<b style="background: #eee; color: #1291C3; padding: 0px 4px; ">*</b> shows the chatbox on every page of your site.</li>
        <li>&rarr; &nbsp;<b style="background: #eee; color: #1291C3 ; padding: 0px 4px; ">/about</b> shows the chatbox on your /about page.</li>
        <li>&rarr; &nbsp;<b style="background: #eee; color: #1291C3 ; padding: 0px 4px; ">*awesome*</b> shows the chatbox on any page that contained the word awesome.</li>
        <li>&rarr; &nbsp;<b style="background: #eee; color: #1291C3 ; padding: 0px 4px; ">/articles/*</b> shows the chatbox on pages that appear in an /articles directory.</li>
        <li>&rarr; &nbsp;<b style="background: #eee; color: #1291C3 ; padding: 0px 4px; ">/articles/*, /about, /contact</b> shows the chatbox on your /about page, anything in the /articles directory, and the /contact page.</li>
        <li>&rarr; &nbsp;<b style="background: #eee; color: #1291C3 ; padding: 0px 4px; ">!/contact</b> use a ! to hide the chat on a specific page (WARNING: only one page allowed)</li>
      </ul>
    </div>
    <input type="text" name="visible_pages" value="<?php echo $livelychatsupport["visible_pages"]; ?>" />
  </div>
  
  <div class="field">
    <input type="submit" value="<?php _e( "Save My Settings", "lively-chat-support" ); ?>" class="button-primary" />
  </div>
  
  <hr />
  
  <h2 id="delete_convos_anchor"><?php _e( "History", "lively-chat-support" ); ?></h2>
  <b><?php _e( "Danger Zone: This action is irreversible.", "lively-chat-support" ); ?></b><br>
  
  <div class="field">
    <a href="#!/lively-chat-support" class="delete_history button-secondary"><?php _e( "Delete All Conversation and Visitor History", "lively-chat-support" ); ?></a>
  </div>

</form>

<form action="admin.php?page=livelychatsupport&tab=settings" method="post" id="delete_history">
  <input type="hidden" name="delete_history" value="1" />
</form>