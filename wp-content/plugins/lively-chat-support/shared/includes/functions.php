<?php

  function LivelyChatSupport_settings($update = false) {
    $defaults = array(
      "addon_version" => 0,
      "db_version" => 0,
      "activation_code" => "",
      "addons" => "",
      "visible_pages" => "*",
      "subscriber_name" => "",
      "subscriber_email" => "",
      "default_responder_id" => "",
      "online" => "offline",
      "colour" => "#570060",
      "position" => "right",
      "offline_thanks" => __("Thanks for contacting us. We'll get back to you as soon as we can.", "lively-chat-support"),
      "cta_online_text" => __("Chat With Us Now!", "lively-chat-support"),
      "cta_offline_text" => __("Need help? Email Us Here!", "lively-chat-support"),
      "cta_online_image" => "",
      "cta_online_image_offset_y" => 200,
      "cta_online_image_offset_x" => 0,
      "cta_offline_image" => "",
      "cta_offline_image_offset_y" => 200,
      "cta_offline_image_offset_x" => 0,
      "sms_responder_id" => "",
      "twilio_sid" => "",
      "twilio_auth" => "",
      "twilio_phone" => "",
      "start" => date("F j, Y", current_time("timestamp")),
      "finish" => date("F j, Y", current_time("timestamp")),
      "show_powered_by" => "true"
    );
    
    $settings_json = json_decode( get_option("livelychatsupport_settings"), true );
    if (empty($settings_json)) { $settings_json = array(); }
    $merged_with_defaults = array_merge($defaults, $settings_json);
    
    if ($update) {
      $merged_with_defaults = array_merge($merged_with_defaults, $update);
      $settings_json = update_option("livelychatsupport_settings", json_encode($merged_with_defaults));
      $_SESSION["livelychatsupport_options"] = $merged_with_defaults;
      return $merged_with_defaults;
    } else {
      return $merged_with_defaults;
    }
  }
  
  function LivelyChatSupport_details() {
    global $wpdb;
    
    if (empty($_SESSION["livelychatsupport_options"])) {
      $_SESSION["livelychatsupport_options"] = LivelyChatSupport_settings();
    }
    
    return $_SESSION["livelychatsupport_options"];
  }
  
  function LivelyChatSupport_poll(){
    global $wpdb;
    $messages_table = $wpdb->prefix . "livelychatsupport_messages";
    $convos_table = $wpdb->prefix . "livelychatsupport_convos";
    
    $id = $_POST["latest_id"];
    
    if (isset($_POST["convo_token"])) {
      $convo_token = $_POST["convo_token"];
      $messages = $wpdb->get_results("SELECT * FROM $messages_table WHERE convo_token = '$convo_token' AND $messages_table.id > '$id'");
    } else if (LIVELYCHATSUPPORT_ADMIN == true) {
      if (current_user_can("manage_options")) {
        $messages = $wpdb->get_results("SELECT * FROM $messages_table INNER JOIN $convos_table ON $convos_table.token = $messages_table.convo_token WHERE $messages_table.id > '$id'");
      } else {
        $agent_id = get_current_user_id();
        $messages = $wpdb->get_results("SELECT * FROM $messages_table INNER JOIN $convos_table ON $convos_table.token = $messages_table.convo_token WHERE $convos_table.agent_id = '$agent_id' AND $messages_table.id > '$id'");
      }
    }
    
    if (!empty($messages)) {
      $count = count($messages) - 1;
      $latest = $messages[$count];
      $latest_id = $latest->id;
    } else {
      $latest_id = $id;
    }
    
    die(json_encode(array(
      "messages" => stripslashes_deep($messages),
      "agent_id" => $agent_id,
      "latest_id" => $latest_id
    )));
  }
  
  function LivelyChatSupport_update_db_check() {
    global $livelychatsupport_db_version;
    global $livelychatsupport_addon_version;
    
    $livelychatsupport = LivelyChatSupport_details();
    load_plugin_textdomain( "lively-chat-support", false, "lively-chat-support/i18n/mo/" );
    
    if ($livelychatsupport["db_version"] != $livelychatsupport_db_version) {
      $user = new WP_User( get_current_user_id() );
      $user->add_cap( 'can_livelychatsupport' );
      LivelyChatSupport_installation();
      
      if ((float) $livelychatsupport["addon_version"] < 1.5) {
        LivelyChatSupport_settings_hash_updater();
      }
    }
  }
  
  function LivelyChatSupport_installation(){
    global $wpdb;
    global $livelychatsupport_db_version;
    
    if ( ! empty( $wpdb->charset ) ) {
      $charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
    }
			
		if ( ! empty( $wpdb->collate ) ) {
      $charset_collate .= " COLLATE $wpdb->collate";
		}
    
    $convos_table = $wpdb->prefix . "livelychatsupport_convos";
    $messages_table = $wpdb->prefix . "livelychatsupport_messages";
    $agents_table = $wpdb->prefix . "livelychatsupport_agents";
    $triggers_table = $wpdb->prefix . "livelychatsupport_triggers";
    $surveys_table = $wpdb->prefix . "livelychatsupport_surveys";
    $hours_table = $wpdb->prefix . "livelychatsupport_hours";

    $convos_sql = "CREATE TABLE $convos_table (
      agent_id mediumint(9),
      token VARCHAR(32) NOT NULL,
      mini_token VARCHAR(3) NOT NULL,
      name VARCHAR(50),
      email VARCHAR(100),
      pending BOOLEAN DEFAULT 1 NOT NULL,
      messages_count MEDIUMINT(9) DEFAULT 0 NOT NULL,
      initiated BOOLEAN DEFAULT 0,
      ip VARCHAR(15),
      user_agent TEXT,
      city VARCHAR(15),
      province VARCHAR(15),
      country VARCHAR(15),
      country_code VARCHAR(15),
      province_code VARCHAR(15),
      latitude VARCHAR(15),
      longitude VARCHAR(15),
      referrers TEXT NOT NULL,
      last_seen DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
      created_at DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
      updated_at DATETIME DEFAULT '0000-00-00 00:00:00' NOT NULL,
      PRIMARY KEY  (token)
    ) $charset_collate;
    ";
    
    $messages_sql = "CREATE TABLE $messages_table (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      convo_token VARCHAR(32) NOT NULL,
      body text NOT NULL,
      from_agent boolean NOT NULL,
      created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;
    ";
    
    $triggers_sql = "CREATE TABLE $triggers_table (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      urls text NOT NULL,
      delay VARCHAR(5) NOT NULL,
      body text NOT NULL,
      created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;
    ";
    
    $surveys_sql = "CREATE TABLE $surveys_table (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      title VARCHAR(155),
      urls text NOT NULL,
      delay VARCHAR(5) NOT NULL,
      questions text NOT NULL,
      thanks text,
      created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;
    ";
    
    $hours_sql = "CREATE TABLE $hours_table (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      day VARCHAR(20),
      open_at MEDIUMINT(9),
      close_at MEDIUMINT(9),
      responder_id MEDIUMINT(9) NOT NULL,
      via VARCHAR(20),
      created_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      updated_at datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;
    ";
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $convos_sql );
    dbDelta( $messages_sql );
    dbDelta( $triggers_sql );
    dbDelta( $surveys_sql );
    dbDelta( $hours_sql );
    
    if (!get_option("livelychatsupport_db_collation")) {
      $charset_collate = str_replace("DEFAULT ", "", $charset_collate);
      $collate_sql = "ALTER TABLE $convos_table CONVERT TO $charset_collate;";
      $wpdb->query($collate_sql);
      $collate_sql = "ALTER TABLE $messages_table CONVERT TO $charset_collate;";
      $wpdb->query($collate_sql);
      $collate_sql = "ALTER TABLE $triggers_table CONVERT TO $charset_collate;";
      $wpdb->query($collate_sql);
      $collate_sql = "ALTER TABLE $surveys_table CONVERT TO $charset_collate;";
      $wpdb->query($collate_sql);
      
      update_option("livelychatsupport_db_collation", true);
    }
    
    LivelyChatSupport_settings(array( "db_version" => $livelychatsupport_db_version ));
  }
  
  function LivelyChatSupport_uninstallation() {
    global $wpdb;
    
    $convos_table = $wpdb->prefix . "livelychatsupport_convos";
    $messages_table = $wpdb->prefix . "livelychatsupport_messages";
    $agents_table = $wpdb->prefix . "livelychatsupport_agents";
    $triggers_table = $wpdb->prefix . "livelychatsupport_triggers";
    $surveys_table = $wpdb->prefix . "livelychatsupport_surveys";
    $hours_table = $wpdb->prefix . "livelychatsupport_hours";
    
  	$wpdb->query("DROP TABLE IF EXISTS $convos_table");
  	$wpdb->query("DROP TABLE IF EXISTS $messages_table");
  	$wpdb->query("DROP TABLE IF EXISTS $agents_table");
    $wpdb->query("DROP TABLE IF EXISTS $triggers_table");
    $wpdb->query("DROP TABLE IF EXISTS $surveys_table");
    $wpdb->query("DROP TABLE IF EXISTS $hours_table");
    
    $wpdb->delete($wpdb->usermeta, array("meta_key" => "livelychatsupport-mobile"));
    $wpdb->delete($wpdb->usermeta, array("meta_key" => "livelychatsupport-avatar"));
    $wpdb->delete($wpdb->usermeta, array("meta_key" => "livelychatsupport-name"));
    $wpdb->delete($wpdb->usermeta, array("meta_key" => "livelychatsupport-active"));
    
    delete_option("livelychatsupport_db_version");
    delete_option("livelychatsupport_db_collation");
    delete_option("livelychatsupport_settings");
  }
  
  function LivelyChatSupport_send_sms($mini_token, $body, $agent) {
    global $wpdb;
    
    $livelychatsupport = LivelyChatSupport_details();
    
    if ($livelychatsupport["twilio_auth"] != "") {
      $url = "https://api.twilio.com/2010-04-01/Accounts/" . $livelychatsupport["twilio_sid"] . "/SMS/Messages";

      foreach(str_split($body, 155) as $part)
      {
        $request = new WP_Http;
        $args = array(
          "method" => "POST",
          "sslverify" => false,
          "body" => array(
            "From" => $livelychatsupport["twilio_phone"],
            "To" => $agent->mobile,
            "Body" => $mini_token . ": " . $part
          ),
          "headers" => array(
            "Authorization" => "Basic " . base64_encode( $livelychatsupport["twilio_sid"] . ":" . $livelychatsupport["twilio_auth"]  )
          )
        );
        $result = $request->request( $url, $args );
      }
    }
  }
  
  function LivelyChatSupport_receive_sms() {
    global $wpdb;
    $livelychatsupport = LivelyChatSupport_details();
    
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		$headers .= "From: Dallas <dallas@excitecreative.ca>" . "\r\n";
    wp_mail($to, $subject, $msg, $headers);
    
    if ($livelychatsupport["twilio_auth"] != "") {
      if (isset($_GET["from_twilio"])) {
        if ($_POST["AccountSid"] == $livelychatsupport["twilio_sid"]) {
          $convos_table = $wpdb->prefix . "livelychatsupport_convos";
          $body_split = explode(":", $_POST["Body"]);
      
          if (count($body_split) != 1) {
            $mini_token = trim(strtolower($body_split[0]));
            $body = trim($body_split[1]);
            $convo = $wpdb->get_row("SELECT * FROM $convos_table WHERE mini_token = '$mini_token' ORDER BY updated_at DESC LIMIT 1");
          }
      
          if (!$convo) {
            $convo = $wpdb->get_row("SELECT * FROM $convos_table ORDER BY updated_at DESC LIMIT 1");
          }
    
          if ($convo) {
            if (!isset($body)) { $body = trim($_POST["Body"]); }
            $return = LivelyChatSupport_create_message($convo->token, $body, 1);
          }
        }
        
        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<Response></Response>";
      }
    }
  }
  
  function LivelyChatSupport_settings_hash_updater() {
    global $livelychatsupport_addon_version;
    
    $old_settings = array(
      "activation_code" => get_option("livelychatsupport_activation_code"),
      "addons" => get_option("livelychatsupport_addons"),
      "visible_pages" => stripslashes(get_option("livelychatsupport_visible_pages", "*")),
      "subscriber_name" => get_option("livelychatsupport_name"),
      "subscriber_email" => get_option("livelychatsupport_email"),
      "default_responder_id" => get_option("livelychatsupport_default_responder_id"),
      "online" => get_option("livelychatsupport_online", "hours"),
      "colour" => get_option("livelychatsupport_colour", "#570060"),
      "position" => get_option("livelychatsupport_position", "right"),
      "offline_thanks" => nl2br(stripslashes(get_option("livelychatsupport_offline_thanks", __("Thanks for contacting us. We'll get back to you as soon as we can.", "lively-chat-support")))),
      "cta_online_text" => stripslashes(get_option("livelychatsupport_cta_online_text", __("Chat With Us Now!", "lively-chat-support"))),
      "cta_offline_text" => stripslashes(get_option("livelychatsupport_cta_offline_text", __("Need help? Email Us Here!", "lively-chat-support"))),
      "cta_online_image" => get_option("livelychatsupport_cta_online_image", ""),
      "cta_online_image_offset_y" => get_option("livelychatsupport_cta_online_image_offset_y", 200),
      "cta_online_image_offset_x" => get_option("livelychatsupport_cta_online_image_offset_x", 0),
      "cta_offline_image" => get_option("livelychatsupport_cta_offline_image", ""),
      "cta_offline_image_offset_y" => get_option("livelychatsupport_cta_offline_image_offset_y", 200),
      "cta_offline_image_offset_x" => get_option("livelychatsupport_cta_offline_image_offset_x", 0),
      "sms_responder_id" => get_option("livelychatsupport_sms_responder_id"),
      "twilio_sid" => get_option("livelychatsupport_twilio_sid"),
      "twilio_auth" => get_option("livelychatsupport_twilio_auth"),
      "twilio_phone" => get_option("livelychatsupport_twilio_phone"),
      "start" => get_option("livelychatsupport_filter_start", date("F j, Y", current_time("timestamp"))),
      "finish" => get_option("livelychatsupport_filter_finish", date("F j, Y", current_time("timestamp"))),
      "show_powered_by" => get_option("livelychatsupport_show_powered_by", "true")
    );
    
    LivelyChatSupport_settings($old_settings);
    LivelyChatSupport_settings(array( "addon_version" => $livelychatsupport_addon_version ));
  }

?>