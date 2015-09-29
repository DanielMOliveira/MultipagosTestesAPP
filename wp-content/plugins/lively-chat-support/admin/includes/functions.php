<?php

  function register_LivelyChatSupport_admin_menu() {
    add_menu_page( "Lively Chat", "Lively Chat", "read", "livelychatsupport", "LivelyChatSupport_admin", plugins_url( "lively-chat-support/assets/icon32.png" ) );
  }
  
  function LivelyChatSupport_admin() {
    global $wpdb;
    
    wp_register_style( "LivelyChatSupport-admin-css", plugins_url( "lively-chat-support/admin/css/style.css" ) );
    wp_register_style( "LivelyChatSupport-chatbox-reset", plugins_url( "lively-chat-support/chatbox/css/reset.css" ) );
    wp_register_style( "LivelyChatSupport-chatbox-style", plugins_url( "lively-chat-support/chatbox/css/style.css" ) );
    wp_register_style( "LivelyChatSupport-jQuery-UI", plugins_url( "lively-chat-support/admin/css/livelychatsupport.jquery-ui.min.css" ) );
    wp_enqueue_style( array("LivelyChatSupport-chatbox-reset", "LivelyChatSupport-chatbox-style", "LivelyChatSupport-chatbox-colours", "LivelyChatSupport-admin-css", "wp-color-picker", "LivelyChatSupport-jQuery-UI") );
  
    wp_register_script( "LivelyChatSupport-admin-js", plugins_url( "lively-chat-support/admin/js/admin.js" ) );
    wp_enqueue_script( array("jquery", "jquery-ui", "jquery-ui-datepicker", "wp-color-picker", "LivelyChatSupport-admin-js") );
  	wp_enqueue_media();
    
    if (isset($_POST)) {
      if (function_exists("flush_pgcache")) { flush_pgcache(); }
      if (function_exists("reset_oc_version")) { reset_oc_version(); }
      
      $posted_data = array();
      $post_fields = array(
        "subscriber_email", 
        "subscriber_name", 
        "default_responder_id", 
        "visible_pages", 
        "online", 
        "offline_thanks", 
        "colour", 
        "position", 
        "cta_online_text", 
        "cta_offline_text", 
        "cta_online_image_offset_y", 
        "cta_online_image_offset_x", 
        "cta_offline_image_offset_y", 
        "cta_offline_image_offset_x", 
        "cta_online_image", 
        "cta_offline_image", 
        "start", 
        "finish", 
        "twilio_sid", 
        "twilio_auth", 
        "sms_responder_id", 
        "show_powered_by"
      );
      $allow_blanks = array();
      
      foreach ($post_fields as $field) {
        if (isset($_POST[$field])) {
          $posted_data[$field] = trim(stripslashes( $_POST[$field] ));
        }
      }
      
      LivelyChatSupport_settings($posted_data);
    }

    if (isset($_POST["activation_code"])) { LivelyChatSupport_activate(); }
    if (isset($_GET["delete_convo"])) { LivelyChatSupport_delete_convo($_GET["convo_token"]); }
    if (isset($_POST["twilio_phone"])) { $agent = LivelyChatSupport_agent(get_current_user_id()); LivelyChatSupport_settings( array("twilio_phone" => "+" . preg_replace("/[^0-9]/", "", trim($_POST["twilio_phone"]))) ); LivelyChatSupport_send_sms("Site", "Your Lively Chat Support is installed!", $agent); }
    
    if (isset($_POST["agents"])) {
      foreach($_POST["agents"] as $agent) {
        $active = ($agent["active"] == "true" ? true : false);
      	if (isset($agent["mobile"]) && $agent["mobile"] != "") { update_user_meta( $agent["id"], "livelychatsupport-mobile",  "+" . preg_replace("/[^0-9]/", "", trim($agent["mobile"])) ); }
        if (isset($agent["avatar"])) { update_user_meta( $agent["id"], "livelychatsupport-avatar", $agent["avatar"] ); }
        if (isset($agent["name"])) { update_user_meta( $agent["id"], "livelychatsupport-name", $agent["name"] ); }
        if (isset($agent["active"])) { update_user_meta( $agent["id"], "livelychatsupport-active", $active); }
  
      	if ($active) {
          $user = new WP_User( $agent["id"] );
          $user->add_cap( "can_livelychatsupport" );
        } else {
          $user = new WP_User( $agent["id"] );
          $user->remove_cap( "can_livelychatsupport" );
        } 
      }
    }
    
    if (isset($_POST["triggers"]))
    {
      foreach ($_POST["triggers"] as $trigger)
      {
        $now = date("Y-m-d H:i:s", current_time("timestamp"));
        
        if ($trigger["id"] != "template")
        {
          if ($trigger["delete"] == "1") {
            $wpdb->delete(
              $wpdb->prefix . "livelychatsupport_triggers",
              array(
                "id" => $trigger["id"]
              )
            );
          } else {
            if ($trigger["body"] != "") {
              if ($trigger["id"] == "new") {
                $wpdb->insert( 
                	$wpdb->prefix . "livelychatsupport_triggers", 
                  array(
                	  "urls" => $trigger["urls"],
                    "delay" => $trigger["delay"],
                    "body" => filter_var($trigger["body"], FILTER_SANITIZE_STRING),
                    "created_at" => $now,
                    "updated_at" => $now
                	)
                );
              } else {
                $wpdb->update( 
                	$wpdb->prefix . "livelychatsupport_triggers", 
                  array(
                	  "urls" => $trigger["urls"],
                    "delay" => $trigger["delay"],
                    "body" => filter_var($trigger["body"], FILTER_SANITIZE_STRING),
                    "updated_at" => $now
                	),
                  array(
                    "id" => $trigger["id"]
                  )
                );
              }
            }
          }
        }
      }
    }
    
    if (isset($_POST["surveys"]))
    {
      foreach ($_POST["surveys"] as $survey)
      {
        $now = date("Y-m-d H:i:s", current_time("timestamp"));
        $questions = stripslashes($survey["questions"]);
        $title = stripslashes($survey["title"]);
        $urls = stripslashes($survey["urls"]);
        $delay = stripslashes($survey["delay"]);
        $thanks = stripslashes($survey["thanks"]);
        
        if ($survey["id"] != "template")
        {
          if ($survey["delete"] == "1") {
            $wpdb->delete(
              $wpdb->prefix . "livelychatsupport_surveys",
              array(
                "id" => $survey["id"]
              )
            );
          } else {
            if ($title != "") {
              if ($survey["id"] == "new") {
                $wpdb->insert( 
                	$wpdb->prefix . "livelychatsupport_surveys", 
                  array(
                    "title" => $title,
                	  "urls" => $urls,
                    "delay" => $delay,
                    "questions" => $questions,
                    "thanks" => $thanks,
                    "created_at" => $now,
                    "updated_at" => $now
                	)
                );
              } else {
                $wpdb->update(
                	$wpdb->prefix . "livelychatsupport_surveys", 
                  array(
                    "title" => $title,
                	  "urls" => $urls,
                    "delay" => $delay,
                    "questions" => $questions,
                    "thanks" => $thanks,
                    "updated_at" => $now
                	),
                  array(
                    "id" => $survey["id"]
                  )
                );
              }
            }
          }
        }
      }
    }
    
    if (isset($_POST["hours"]))
    {
      foreach ($_POST["hours"] as $hour)
      {
        $now = date("Y-m-d H:i:s", current_time("timestamp"));
        
        if (isset($hour["id"])) {
          if ($hour["id"] != "template")
          {
            if ($hour["delete"] == "1") {
              $wpdb->delete(
                $wpdb->prefix . "livelychatsupport_hours",
                array(
                  "id" => $hour["id"]
                )
              );
            } else if ($hour["id"] == "new") {
              $wpdb->insert( 
              	$wpdb->prefix . "livelychatsupport_hours", 
                array(
              	  "day" => $hour["day"],
                  "open_at" => date("Hi", strtotime($hour["open_at"])),
                  "close_at" => date("Hi", strtotime($hour["close_at"])),
                  "responder_id" => $hour["responder_id"],
                  "via" => $hour["via"],
                  "created_at" => $now,
                  "updated_at" => $now
              	)
              );
            } else {
              $wpdb->update( 
              	$wpdb->prefix . "livelychatsupport_hours", 
                array(
              	  "day" => $hour["day"],
                  "open_at" => date("Hi", strtotime($hour["open_at"])),
                  "close_at" => date("Hi", strtotime($hour["close_at"])),
                  "responder_id" => $hour["responder_id"],
                  "via" => $hour["via"],
                  "updated_at" => $now
              	),
                array(
                  "id" => $hour["id"]
                )
              );
            }
          }
        }
      }
    }
    
    $livelychatsupport = LivelyChatSupport_details();

    echo '<div id="livelychatsupport" class="wrap">';
  
    include_once(LIVELYCHATSUPPORT_ROOT . "/admin/includes/header.php");
  
    if ($livelychatsupport["subscriber_email"] == "" || $livelychatsupport["subscriber_name"] == "") {
      include_once(LIVELYCHATSUPPORT_ROOT . "/admin/tabs/email_required.php");
    } else {
      $_GET["tab"] = isset($_GET["tab"]) ? $_GET["tab"] : "visitors";
      include_once(LIVELYCHATSUPPORT_ROOT . "/admin/tabs/" . $_GET["tab"] . ".php");
    }
  
    echo '</div>';
  }
  
  function LivelyChatSupport_delete_all_convos() {
    global $wpdb;
    $convos_table = $wpdb->prefix . "livelychatsupport_convos";
    $messages_table = $wpdb->prefix . "livelychatsupport_messages";
    $wpdb->delete($convos_table, array( "pending" => 1 ));
    $wpdb->delete($convos_table, array( "pending" => 0 ));
    $wpdb->delete($messages_table, array( "from_agent" => 1 ));
    $wpdb->delete($messages_table, array( "from_agent" => 0 ));
    return "All convos have been deleted.";
  }
  
  function LivelyChatSupport_latest_message_id() {
    global $wpdb;
    $latest_id = 0;
    $messages_table = $wpdb->prefix . "livelychatsupport_messages";
    $convos_table = $wpdb->prefix . "livelychatsupport_convos";
    $message = $wpdb->get_row("SELECT * FROM $messages_table INNER JOIN $convos_table ON $convos_table.token = $messages_table.convo_token ORDER BY $messages_table.created_at DESC LIMIT 1");
    if ($message) { $latest_id = $message->id; }
    return $latest_id;
  }

  function LivelyChatSupport_subscribe_to_touchbase($url, $subscriber){
    global $wpdb;
    
    if ($subscriber["email"] != "" && $subscriber["name"] != "") {
      $wpdb->insert( 
      	$wpdb->prefix . "livelychatsupport_agents", 
        array(
      	  "name" => $subscriber["name"],
          "avatar_url" => plugins_url("lively-chat-support/chatbox/assets/ctas/online/chat_bubbles.png")
      	)
      );
  
      LivelyChatSupport_settings( array(
        "subscriber_email" => $subscriber["email"],
        "subscriber_name" => $subscriber["name"]
      ) );
      
      $user = new WP_User( get_current_user_id() );
      $user->add_cap( 'can_livelychatsupport' );
      update_user_meta( get_current_user_id(), "livelychatsupport-active", true);
    }
  }
  
  function LivelyChatSupport_add_convo() {
    global $wpdb;
    $convo = LivelyChatSupport_convo($_POST["convo_token"]);
    die(json_encode($convo));
  }
  
  function LivelyChatSupport_find_visitors() {
    global $wpdb;
    
    if (isset($_POST["start"])) { LivelyChatSupport_settings(array("start" => $_POST["start"])); }
    if (isset($_POST["finish"])) { LivelyChatSupport_settings(array("finish" => $_POST["finish"])); }
    $messages_table = $wpdb->prefix . "livelychatsupport_messages";
    $convos_table = $wpdb->prefix . "livelychatsupport_convos";
    $livelychatsupport = LivelyChatSupport_details();
    
    $agent_id = get_current_user_id();
    $start = date("Y-m-d H:i:s", strtotime($livelychatsupport["start"]));
    $finish = date("Y-m-d H:i:s", strtotime($livelychatsupport["finish"]) + 86400 + 86399);
    
    if (current_user_can("manage_options")) {
      $convos = $wpdb->get_results("SELECT DISTINCT $convos_table.* FROM $convos_table INNER JOIN $messages_table ON $convos_table.token = $messages_table.convo_token WHERE $messages_table.created_at >= '$start' AND $messages_table.created_at <= '$finish'");
    } else {
      $convos = $wpdb->get_results("SELECT DISTINCT $convos_table.* FROM $convos_table INNER JOIN $messages_table ON $convos_table.token = $messages_table.convo_token WHERE $convos_table.agent_id = '$agent_id' AND $messages_table.created_at >= '$start' AND $messages_table.created_at <= '$finish'");
    }
    
    if (isset($_POST["start"])) {
      die(json_encode(array("convos" => $convos)));
    } else {
      return json_encode(array("convos" => $convos));
    }
  }
  
  function LivelyChatSupport_read_convo() {
    global $wpdb;
    $convo = LivelyChatSupport_convo($_POST["convo_token"]);
    $wpdb->update( 
    	$wpdb->prefix . "livelychatsupport_convos", 
      array(
    	  "pending" => false
    	),
      array(
        "token" => $convo->token
      )
    );
    
    die(json_encode(array("success" => true)));
  }
  
  function LivelyChatSupport_activate() {
    global $livelychatsupport_addon_version;
    
    if (isset($_POST["activation_code"])) {
      $request = new WP_Http;
      $result = $request->request( 'http://guitarvid.com/activation/lively-chat-support/activate.php?code=' . trim($_POST["activation_code"]) );
      $response = json_decode($result["body"]);
    
      if ($response->success == 1)
      {
        $addons = array();
        foreach($response->files as $file => $data)
        {
          array_push($addons, $file);
        }
        
        LivelyChatSupport_settings(array(
          "addons" => implode("|", $addons),
          "activation_code" => trim($_POST["activation_code"]),
          "addon_version" => $livelychatsupport_addon_version
        ));
      }
    }
  }
  
  function LivelyChatSupport_delete_convo($convo_token) {
    global $wpdb;
    $convos_table = $wpdb->prefix . "livelychatsupport_convos";
    $wpdb->delete($convos_table, array(
      "token" => $convo_token
    ));
  }
  
  function LivelyChatSupport_military_to_pretty($time) {
    if (strlen($time) == 3) { $time = "0$time"; }
    $hour = (integer) substr($time, 0, 2);
    if ($hour > 12) { $hour = $hour - 12; $pm = true; }
    $minute = substr($time, 2, 2);
    $output = "$hour:$minute ";
    if (isset($pm)) { $output .= "PM"; } else { $output .= "AM"; }
    return $output;
  }
  
?>