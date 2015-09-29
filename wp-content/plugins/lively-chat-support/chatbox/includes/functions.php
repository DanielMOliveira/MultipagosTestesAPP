<?php

  function LivelyChatSupport_convo($token) {
    global $wpdb;
    $convos_table = $wpdb->prefix . "livelychatsupport_convos";
    return $wpdb->get_row("SELECT * FROM $convos_table WHERE token='$token'");
  }
  
  function LivelyChatSupport_agents($all_users = false) {
    global $wpdb;
    $agents = array();
    
    if ($all_users) {
      $users = $wpdb->get_results("SELECT * FROM $wpdb->users");
    } else {
      $users = (object) array();
      $user_ids = array();
      $metas = $wpdb->get_results("SELECT user_id FROM $wpdb->usermeta WHERE meta_key = 'livelychatsupport-active' AND meta_value = '1'");
      foreach ($metas as $meta) { array_push($user_ids, $meta->user_id); }
      $user_ids = join(",", $user_ids);

      if (!empty($user_ids)) {
        $users = $wpdb->get_results("SELECT * FROM $wpdb->users WHERE id IN ($user_ids)");
      }
    }
    
    foreach ($users as $user) {
      $agent = (object) array(
        "id" => $user->ID,
        "email" => $user->user_email,
        "name" => get_user_meta($user->ID, "livelychatsupport-name", true),
        "mobile" => get_user_meta($user->ID, "livelychatsupport-mobile", true),
        "avatar" => get_user_meta($user->ID, "livelychatsupport-avatar", true),
        "active" => get_user_meta($user->ID, "livelychatsupport-active", true)
      );
      array_push($agents, $agent);
    }

    return $agents;
  }
  
  function LivelyChatSupport_default_agent() {
    global $wpdb;
    $livelychatsupport = LivelyChatSupport_details();
    $agent = LivelyChatSupport_agent($livelychatsupport["default_responder_id"]);
    return $agent;
  }
  
  function LivelyChatSupport_agent($id = false) {
    global $wpdb;
    $agent = false;
    $livelychatsupport = LivelyChatSupport_details();
  
    if ($id) {
      $agent = (object) array(
        "id" => $id,
        "name" => get_user_meta($id, "livelychatsupport-name", true),
        "mobile" => get_user_meta($id, "livelychatsupport-mobile", true),
        "avatar" => get_user_meta($id, "livelychatsupport-avatar", true),
        "active" => get_user_meta($id, "livelychatsupport-active", true)
      );
    } else {
      $agents = LivelyChatSupport_agents();
      
      if ($livelychatsupport["online"] == "hours") {
        $hour = LivelyChatSupport_hour();

        if ($hour != null) {
          if ($hour->responder_id == 0) {
            $agent = $agents[rand(0, sizeof($agents) - 1)];
          } else {
            $agent = LivelyChatSupport_agent($hour->responder_id);
          }
        }
      }
    
      if (!$agent) {
        if (!empty($agents)) {
          $agent = $agents[rand(0, sizeof($agents) - 1)];
        }
      }
    }

    return $agent;
  }
  
  function LivelyChatSupport_hour() {
    global $wpdb;
    $hours_table = $wpdb->prefix . "livelychatsupport_hours";
    
    $day = date("l", current_time("timestamp"));
    $military = (integer) date("Hi", current_time("timestamp"));
    $hour = $wpdb->get_row("SELECT * FROM $hours_table WHERE day = '$day' AND open_at <= '$military' AND close_at > '$military' LIMIT 1");
    
    return $hour;
  }
  
  function LivelyChatSupport_messages($convo_token, $sort) {
    global $wpdb;
    $messages_table = $wpdb->prefix . "livelychatsupport_messages";
    $messages = $wpdb->get_results("SELECT * FROM $messages_table WHERE convo_token = '$convo_token' ORDER BY created_at $sort");
    return $messages;
  }
  
  function LivelyChatSupport_cache_support() {
    global $wpdb;
    $convo_token = $_POST["convo_token"];
    $livelychatsupport = LivelyChatSupport_details();
    
    $messages_table = $wpdb->prefix . "livelychatsupport_messages";
    $triggers_table = $wpdb->prefix . "livelychatsupport_triggers";
    $surveys_table = $wpdb->prefix . "livelychatsupport_surveys";
    $hours_table = $wpdb->prefix . "livelychatsupport_hours";
    
    $convo = LivelyChatSupport_convo($convo_token);
    $messages = $wpdb->get_results("SELECT * FROM $messages_table WHERE convo_token = '$convo_token' ORDER BY created_at ASC");
    $triggers = $wpdb->get_results("SELECT * FROM $triggers_table ORDER BY delay ASC");
    $surveys = $wpdb->get_results("SELECT * FROM $surveys_table ORDER BY delay ASC");
    $hours = $wpdb->get_results("SELECT * FROM $hours_table");
    $popups = array();
    $ms = array();
    $hrs = array();
    
    foreach($hours as $hour) {
      $h = array(
        "day" => $hour->day,
        "open_at" => $hour->open_at,
        "close_at" => $hour->close_at
      );
      array_push($hrs, $h);
    }
    
    foreach($triggers as $trigger) {
      $t = array(
        "type" => "trigger",
        "urls" => $trigger->urls,
        "delay" => $trigger->delay,
        "body" => nl2br(stripslashes($trigger->body))
      );
      array_push($popups, $t);
    }
    
    foreach($surveys as $survey) {
      $s = array(
        "type" => "survey",
        "title" => $survey->title,
        "id" => $survey->id,
        "urls" => $survey->urls,
        "delay" => $survey->delay,
        "questions" => json_decode($survey->questions),
        "thanks" => nl2br(stripslashes($survey->thanks))
      );
      array_push($popups, $s);
    }
    
    foreach ($messages as $message) {
      $m = array(
        "id" => $message->id,
        "convo_token" => $message->convo_token,
        "body" => nl2br(stripslashes($message->body)),
        "from_agent" => $message->from_agent,
        "created_at" => $message->created_at
      );
      array_push($ms, $m);
    }
    
    die(json_encode(array(
      "messages" => $ms, 
      "popups" => $popups, 
      "hours" => $hrs, 
      "online" => $livelychatsupport["online"],
      "gmt_offset" => get_option("gmt_offset")
    )));
  }
  
  function LivelyChatSupport_triggers() {
    global $wpdb;
    $triggers_table = $wpdb->prefix . "livelychatsupport_triggers";
    $triggers = $wpdb->get_results("SELECT * FROM $triggers_table ORDER BY CAST(delay AS DECIMAL(5,2)) ASC");
    return $triggers;
  }
  
  function LivelyChatSupport_surveys() {
    global $wpdb;
    $surveys_table = $wpdb->prefix . "livelychatsupport_surveys";
    $surveys = $wpdb->get_results("SELECT * FROM $surveys_table ORDER BY CAST(delay AS DECIMAL(5,2)) ASC");
    return $surveys;
  }
  
  function LivelyChatSupport_set_cookies() {
    if (!isset($_COOKIE["livelychatsupport_convo_open"])) {
      $_COOKIE["livelychatsupport_convo_open"] = "false";
      setcookie("livelychatsupport_convo_open", $_COOKIE["livelychatsupport_convo_open"], mktime(0, 0, 0, 12, 31, date("Y") + 2), "/");
    }

    if (!isset($_COOKIE["livelychatsupport_convo_token"]) || $_COOKIE["livelychatsupport_convo_token"] == "") {
      $_COOKIE["livelychatsupport_convo_token"] = md5(uniqid(rand(), true));
      setcookie("livelychatsupport_convo_token", $_COOKIE["livelychatsupport_convo_token"], mktime(0, 0, 0, 12, 31, date("Y") + 2), "/");
    }
  }
  
  function LivelyChatSupport_state($open, $offline, $chatting){
    $classes = array();
  
    if ($offline == true) {
      array_push($classes, "offline");
    }
    else
    {
      if ($chatting == true) { array_push($classes, "chatting"); }
    }
  
    if ($open == true) { array_push($classes, "open"); }
    if (isset($_COOKIE["livelychatsupport_surveyed"])) { array_push($classes, "surveyed"); }
    
    return $classes;
  }

  function LivelyChatSupport_frontend_footer()
  {
    global $wpdb;
    $convos_table = $wpdb->prefix . "livelychatsupport_convos";
    
    LivelyChatSupport_set_cookies();
    
    if (isset($_GET["from_twilio"])) { LivelyChatSupport_receive_sms(); }
    
    wp_register_style( "LivelyChatSupport-chatbox-reset", plugins_url( "lively-chat-support/chatbox/css/reset.css" ) );
    wp_register_style( "LivelyChatSupport-chatbox-style", plugins_url( "lively-chat-support/chatbox/css/style.css" ) );
    wp_register_script( "LivelyChatSupport-chatbox-script", plugins_url( "lively-chat-support/chatbox/js/chatbox.js" ) );
    
    wp_enqueue_style( array("LivelyChatSupport-chatbox-reset", "LivelyChatSupport-chatbox-style", "LivelyChatSupport-chatbox-colours") );
    wp_enqueue_script( array("jquery", "LivelyChatSupport-chatbox-script") );
  
    $livelychatsupport = LivelyChatSupport_details();
    $convo = LivelyChatSupport_convo($_COOKIE["livelychatsupport_convo_token"]);
    $agent = LivelyChatSupport_agent();
    
    if (!$agent) {
      $livelychatsupport_offline = true;
    } else {
      
    }
    if ((isset($_COOKIE["livelychatsupport_convo_open"]) && $_COOKIE["livelychatsupport_convo_open"] == "true") || LIVELYCHATSUPPORT_ADMIN == true) { $livelychatsupport_open = true; }
    
    if ($livelychatsupport["online"] == "offline") {
      $livelychatsupport_offline = true;
    } else if ($livelychatsupport["online"] == "hours") {
      $hour = LivelyChatSupport_hour();
      
      if (!$hour) {
        $livelychatsupport_offline = true;
      }
    }
  
    if (LIVELYCHATSUPPORT_ADMIN == false && isset($_COOKIE["livelychatsupport_convo_token"]))
    {
      if ($convo) { $referrers = json_decode($convo->referrers); }
      if (!is_array($referrers) || !isset($referrers)) { $referrers = array(); }
      $referrers = array_push($referrers, "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
      
      if ($convo) {
        $wpdb->update( 
        	$convos_table, 
        	array(
            "referrers" => json_encode($referrers),
            "updated_at" => date("Y-m-d H:i:s", current_time("timestamp")),
            "last_seen" => date("Y-m-d H:i:s", current_time("timestamp"))
        	),
          array(
            "token" => $_COOKIE["livelychatsupport_convo_token"]
          )
        );
      } else if (!is_admin()) {
        $convo = LivelyChatSupport_create_visitor($referrers);
      }

      if (LivelyChatSupport_is_visible_for_current_page()) {
        include_once(LIVELYCHATSUPPORT_ROOT. "/chatbox/includes/structure.php");
      }
    }
  }
  
  function LivelyChatSupport_create_visitor($referrers = "") {
    global $wpdb;
    $convos_table = $wpdb->prefix . "livelychatsupport_convos";
    
    $request = new WP_Http;
    $result = $request->request( "http://freegeoip.net/json/" . $_SERVER["REMOTE_ADDR"] );
    
    if (!is_wp_error($result)) {
      $geo = json_decode($result["body"]);
    	$args = array( 
        "agent_id" => LivelyChatSupport_agent()->id,
        "token" => $_COOKIE["livelychatsupport_convo_token"],
        "mini_token" => strtolower(substr($_COOKIE["livelychatsupport_convo_token"], 0, 3)),
        "referrers" => json_encode($referrers),
        "ip" => $_SERVER["REMOTE_ADDR"],
        "user_agent" => $_SERVER['HTTP_USER_AGENT'],
        "city" => $geo->city,
        "province" => $geo->region_name,
        "country" => $geo->country_name,
        "country_code" => $geo->country_code,
        "province_code" => $geo->region_code,
        "latitude" => $geo->latitude,
        "longitude" => $geo->longitude,
        "created_at" => date("Y-m-d H:i:s", current_time("timestamp")),
        "updated_at" => date("Y-m-d H:i:s", current_time("timestamp")),
        "last_seen" => date("Y-m-d H:i:s", current_time("timestamp"))
    	);
    }
    else
    {
    	$args = array(
        "agent_id" => LivelyChatSupport_agent()->id,
        "token" => $_COOKIE["livelychatsupport_convo_token"],
        "mini_token" => strtolower(substr($_COOKIE["livelychatsupport_convo_token"], 0, 3)),
        "referrers" => json_encode($referrers),
        "ip" => $_SERVER["REMOTE_ADDR"],
        "user_agent" => $_SERVER['HTTP_USER_AGENT'],
        "created_at" => date("Y-m-d H:i:s", current_time("timestamp")),
        "updated_at" => date("Y-m-d H:i:s", current_time("timestamp")),
        "last_seen" => date("Y-m-d H:i:s", current_time("timestamp"))
    	);
    }

    $wpdb->insert( 
    	$convos_table, 
    	$args
    );
    
    return (object) $args;
  }
  
  function LivelyChatSupport_is_visible_for_current_page() {
    $livelychatsupport = LivelyChatSupport_details();
    $reg = $livelychatsupport["visible_pages"];
    $reg_exploded = explode(",", $reg);
    $new_reg = array();
    
    foreach ($reg_exploded as &$value) {
      $value = preg_quote($value, "/");
      $value = trim($value);
      $value = str_replace("\*", "(.*?)", $value);
      $value = str_replace("\!", "!", $value);
      if ($value[0] == "!") { 
        $value = str_replace("!", "", $value);
        $value = "((?!^$value$).)*";
      }
      array_push($new_reg, $value);
    }
    
    $reg_imploded = implode("|", $new_reg);
    $reg = "/^($reg_imploded)$/i";
    
    return preg_match($reg, $_SERVER["REQUEST_URI"]) == 1;
  }
  
  function LivelyChatSupport_create_chatbox_message() {
    $from_agent = isset($_POST["from_agent"]) ? 1 : 0;
    $not_initiated = isset($_POST["not_initiated"]) ? 1 : 0;
    LivelyChatSupport_create_message($_POST["convo_token"], filter_var($_POST["body"], FILTER_SANITIZE_STRING), $from_agent, $not_initiated);
  }
  
  function LivelyChatSupport_create_message($convo_token, $body, $from_agent, $not_initiated = false) {
    if ($body != "") {
      global $wpdb;
      $livelychatsupport = LivelyChatSupport_details();
      $convo = LivelyChatSupport_convo($convo_token);
      $agent = LivelyChatSupport_agent($convo->agent_id);
      $now = date("Y-m-d H:i:s.u");
    
      if ($convo)
      {
        $message = $wpdb->insert( 
          $wpdb->prefix . "livelychatsupport_messages", 
          array(
            "convo_token" => $convo->token,
            "body" => $body,
            "from_agent" => $from_agent,
            "created_at" => $now
          )
        );
      
        if ($from_agent == 1) {
          if ($not_initiated == true) {
            $details = array(
              "messages_count" => $convo->messages_count + 1,
              "updated_at" => $now,
              "agent_id" => get_current_user_id()
            );
          } else {
            $details = array(
              "messages_count" => $convo->messages_count + 1,
              "updated_at" => $now,
              "initiated" => 1,
              "agent_id" => get_current_user_id()
            );
          }
        } else {
          $hour = LivelyChatSupport_hour();
          $sms_activated = strpos($livelychatsupport["addons"], "sms") !== false;
          
          if (($hour && $hour->via == "sms") || ($sms_activated && get_user_meta($agent->id, "livelychatsupport-mobile") != ""))
          {
            LivelyChatSupport_send_sms($convo->mini_token, $body, $agent);
            $details = array(
              "messages_count" => $convo->messages_count + 1,
              "updated_at" => $now,
              "last_seen" => $now,
              "initiated" => 1
            );
          }
          else
          {
            $details = array(
              "pending" => true,
              "messages_count" => $convo->messages_count + 1,
              "updated_at" => $now,
              "last_seen" => $now,
              "initiated" => 1
            );
          }
        }
    
        $wpdb->update( 
          $wpdb->prefix . "livelychatsupport_convos", 
          $details,
          array(
            "token" => $convo->token
          )
        );
      }
    }
    die(json_encode(array("success" => true)));
  }
  
  function LivelyChatSupport_subscribe() {
    if (isset($_POST["convo_token"]) && isset($_POST["Email"]) && isset($_POST["Name"])) {
      global $wpdb;
      $livelychatsupport = LivelyChatSupport_details();
      $convo = LivelyChatSupport_convo($_POST["convo_token"]);
      $convos_table = $wpdb->prefix . "livelychatsupport_convos";

      if (filter_var($_POST["Email"], FILTER_VALIDATE_EMAIL) && isset($_POST["Name"]))
      {
        $name = filter_var($_POST["Name"], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST["Email"], FILTER_SANITIZE_EMAIL);
    
        $wpdb->update( 
        	$convos_table, 
        	array(
        	  "name" => $name,
            "email" => $email
        	),
          array(
            "token" => $_POST["convo_token"]
          )
        );
    
        if (isset($_POST["Comment"]))
        {
          $_COOKIE["livelychatsupport_convo_open"] = "false";
          setcookie("livelychatsupport_convo_open", "false", mktime(0, 0, 0, 12, 31, date("Y") + 2), "/");
        }
        
        $msg = "<table>";
    		foreach ($_POST as $field => $value)
    		{
          if ($field != "action" && $field != "convo_token")
          {
            $value = filter_var($value, FILTER_SANITIZE_STRING);
      			$msg .= "
              <tr>
                <td style=\"text-align:right; \">
                  <strong>$field:</strong>
                </td>
                <td>$value</td>
              </tr>";
          }
    		}
    		$msg .= "</table>";

    		$to = "$livelychatsupport[subscriber_name] <$livelychatsupport[subscriber_email]>";

    		$subject = "Website Form - $name";
    		$headers = "MIME-Version: 1.0" . "\r\n";
    		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
    		$headers .= "From: $name <$email>" . "\r\n";

    		wp_mail($to, $subject, $msg, $headers);
      }

    }
    
    die(json_encode(array("success" => true)));
  }
  
  function LivelyChatSupport_save_survey() {
    global $wpdb;
    $id = $_POST["data"][0]["value"];
    $surveys_table = $wpdb->prefix . "livelychatsupport_surveys";
    $survey = $wpdb->get_row("SELECT * FROM $surveys_table WHERE id='$id'");
    $questions = json_decode($survey->questions); 
    $livelychatsupport = LivelyChatSupport_details();
    
    $_COOKIE["livelychatsupport_convo_open"] = "false";
    setcookie("livelychatsupport_convo_open", "false", mktime(0, 0, 0, 12, 31, date("Y") + 2), "/");
    $_COOKIE["livelychatsupport_surveyed"] = "true";
    setcookie("livelychatsupport_surveyed", "true", mktime(0, 0, 0, 12, 31, date("Y") + 2), "/");
    
    $msg = "<table>";
		foreach ($_POST["data"] as $field)
		{
      if ($field["name"] != "id") {
        $index = explode("-", $field["name"]);
        $value = filter_var($field["value"], FILTER_SANITIZE_STRING);
        $question = $questions[$index[1]]->prompt;
  			$msg .= "
          <tr>
            <td>$question</td>
          </tr>
          <tr>
            <td><strong>$value</strong></td>
          </tr>
          <tr>
            <td></td>
          </tr>";
      }
		}
		$msg .= "</table>";

		$to = "$livelychatsupport[subscriber_name] <$livelychatsupport[subscriber_email]>";

		$subject = __("Website Form", "lively-chat-support") . " - $name";
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
		$headers .= "From: $name <$email>" . "\r\n";

		wp_mail($to, $subject, $msg, $headers);
    
    die();
  }
  
  function LivelyChatSupport_delete_history() {
    $_COOKIE["livelychatsupport_convo_token"] = md5(uniqid(rand(), true));
    setcookie("livelychatsupport_convo_token", $_COOKIE["livelychatsupport_convo_token"], mktime(0, 0, 0, 12, 31, date("Y") + 2), "/");
    LivelyChatSupport_create_visitor();
    die(json_encode(array(
      "new_token" => $_COOKIE["livelychatsupport_convo_token"]
    )));
  }
  
?>