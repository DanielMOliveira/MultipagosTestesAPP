<h2><?php _e( "Just a quick setup...", "lively-chat-support" ); ?></h2>

<form method="post" action="admin.php?page=livelychatsupport&tab=help">
  <div class="field">
    <label for="subscriber_email"><?php _e( "Which <b>email address</b> should your leads be emailed to?", "lively-chat-support" ); ?></label><br>
    <input type="subscriber_email" id="subscriber_email" name="subscriber_email" value="<?php echo $livelychatsupport["subscriber_email"]; ?>" /><br>
    <span class="livelychatsupport-detail">
      <?php _e( "By setting up LivelyChatSupport, you will receive Lively Chat Support updates.", "lively-chat-support" ); ?><br>
      <?php _e( "You can opt out at any time. We will NOT sell your email address.", "lively-chat-support" ); ?>
    </span><br>
  </div>
  
  <div class="field">
    <label for="subscriber_name"><?php _e( "What's your <b>name</b>?", "lively-chat-support" ); ?></label><br>
    <input type="text" id="subscriber_name" name="subscriber_name" value="<?php echo $livelychatsupport["subscriber_name"]; ?>" /><br>
  </div>
  
  <div class="field">
    <input type="submit" value="<?php _e( "Save My LivelyChatSupport", "lively-chat-support" ); ?>" class="button-primary" />
  </div>
</form>