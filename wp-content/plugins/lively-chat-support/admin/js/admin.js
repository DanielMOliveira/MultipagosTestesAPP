var $lcsq = jQuery.noConflict();
$lcsq(function($lcsq){
  
  LivelyChatSupport = {
    scrollChatToBottom: function() {
      if ($lcsq("#livelychatsupport-chatbox").length)
      {
        $lcsq("#livelychatsupport-chatbox .messages").animate({
          scrollTop: $lcsq("#livelychatsupport-chatbox .messages")[0].scrollHeight
        }, 0);
      }
    },

    mobileDevice: function() {
      return $lcsq(window).width() > 720;
    },
    
    addConvo: function(convo) {
      if (convo.initiated != 0 && !$lcsq("[data-convo_token='" + convo.token + "']").length) {
        var window_path = window.location.pathname + window.location.search;
        var li = $lcsq("<li>").attr("id", "convo_" + convo.token).addClass("convo").data("convo_token", convo.token);
        var a = $lcsq("<a>").attr("href", "admin.php?page=livelychatsupport&tab=visitors&convo_token=" + convo.token);
        
        if (window_path.indexOf(a.attr("href")) != -1) {
          a.addClass("selected");
        }
        
        if (convo.name == null) {
          convo.name = convo.city + ", " + convo.province;
        }
        
        a.appendTo(li);
        
        if (convo.pending == 0) {
          a.html(convo.name + "<p class=\"message_waiting\"></p>")
        } else {
          a.html(convo.name + "<p class=\"message_waiting\"></p>");
          a.find(".message_waiting").show();
        }
      
        li.insertAfter("#online_convos .divider");
      }
    },

    addMessage: function(content, from_agent, convo_token, id) {
      var audio = $lcsq("#bell");
      var messages = $lcsq(".convo_" + convo_token + "_messages");
			content = $lcsq("<div />").html(content).text();
      if (from_agent != true) { audio[0].play(); }
      
      if (messages.length)
      {
        var template = $lcsq(".message_template").clone();
        var now = new Date();
        var hours = now.getHours() % 12;
        if (hours == 0) { hours = 12; }
        if (from_agent == true) { template.addClass("from_agent"); }
        
        template.removeClass("message_template")
				template.attr("id", "message_" + id).data("id", id);
        template.find(".body").text(content);
        template.find(".date").text(hours + ":" + (now.getMinutes() < 10 ? "0" : "") + now.getMinutes());
        template.hide();
        template.prependTo(messages);
        template.fadeIn();
        
        LivelyChatSupport.readConvo(convo_token);
      }
      else
      {
        $lcsq(".nav-tab-wrapper .message_waiting").css("display", "inline-block");
        $lcsq("#convo_" + convo_token + " .message_waiting").show();
        
        if ($lcsq("#online_convos").length && !$lcsq("#convo_" + convo_token).length)
        {
          var url = $lcsq("#online_convos").data("add_convo");
          $lcsq.post(url, {
            action: "add_convo",
            convo_token: convo_token
          }, function(data){
            var convo = $lcsq.parseJSON(data);
            LivelyChatSupport.addConvo(convo);
          });
        }
        else
        {
          if (!$lcsq("#convo_" + convo_token + " .message_waiting").length) {
						var message_waiting = $lcsq("<p class=\"message_waiting\"></p>");
						$lcsq("#convo_" + convo_token).append(message_waiting);
					}
        }
      }
    },

    poll: function() {
			var latest_id = 0;
      var url = $lcsq("#bell").data("url");
			
			if (typeof $lcsq("#bell").data("latest_id") != "undefined") { 
				latest_id = $lcsq("#bell").data("latest_id");
			}

      $lcsq.post(
          ajaxurl, {
            "action": "poll",
            "latest_id": latest_id
          }, 
          function(response){
            var data = $lcsq.parseJSON(response);
            $lcsq(".chat").removeClass("chat_loading");
						$lcsq("#bell").data("latest_id", data.latest_id);
            
            for(i=0; i<data.messages.length; i++) {
            	var message = data.messages[i];
							if (!$lcsq("#message_" + message.id).length) {
	              if (message.from_agent == "1") { var from_agent = true; } else { var from_agent = false; }
	              LivelyChatSupport.addMessage(message.body, from_agent, message.convo_token, message.id);
							}
            }
            
            setTimeout(function(){
              LivelyChatSupport.poll();
            }, 3000);
          }
      );
    },
    
    readConvo: function(convo_token) {
      var url = $lcsq("#bell").data("read_url");
      $lcsq.post(url, {
        "action": "read_convo",
        "convo_token": convo_token
      });
    },
    
    allowMultiAgent: function() {
      if ($lcsq("[data-multi]").length) {
        var multi = $lcsq("[data-multi]").attr("data-multi");
        if (multi == "true") {
          $lcsq(".agent input").removeAttr("disabled");
        } else {
          if ($lcsq(".agent_active_checkbox:checked").length) {
            $lcsq(".agent").each(function(){
              if ($lcsq(this).find(".agent_active_checkbox").is(":checked")) {
                $lcsq(this).find("input:visible").removeAttr("disabled");
                $lcsq(this).find(".agent_default_checkbox").click();
              } else {
                $lcsq(this).find("input:visible").attr("disabled", true);
              }
            });
          } else {
            $lcsq(".agent input:visible").removeAttr("disabled");
          }
        }
      }
    },
    
    initSurveys: function() {
      var surveys = $lcsq("#surveys").data("surveys");
      
      for(i=0; i<surveys.length; i++) {
        var survey = surveys[i];
        var questions = $lcsq.parseJSON(survey.questions);
        var template = $lcsq(".survey_template").clone();

        if (questions != null) {
          for(q=0; q<questions.length; q++) {
            var question = questions[q];
            var answers = question.answers;
            var qtemplate = $lcsq(".question_template:first").clone();
          
            for(a=0; a<answers.length; a++) {
              var answer = answers[a];
              var atemplate = $lcsq(".answer_template:first").clone();
            
              atemplate.removeClass("answer_template");
              atemplate.removeClass("template");
              atemplate.find(".body").val(answer);
              atemplate.appendTo(qtemplate.find(".answers"))
            }
            
            qtemplate.removeClass("question_template");
            qtemplate.removeClass("template");
            qtemplate.find(".data_type").val(question.data_type);
            qtemplate.find(".prompt").val(question.prompt);
            qtemplate.appendTo(template.find(".questions"));
          }
        }
        
        template.removeClass("survey_template");
        template.removeClass("template");
        template.find(".urls").val(survey.urls);
        template.find(".delay").val(survey.delay);
        template.find(".id").val(survey.id);
        template.find(".title").val(survey.title);
        template.find(".thanks").val(survey.thanks);
        template.find("input, textarea").each(function(){
          if ($lcsq(this).attr("name")) {
            $lcsq(this).attr("name", $lcsq(this).attr("name").replace("[]", "[" + survey.id + "]"));
          }
        });
        template.appendTo("#surveys");
        
        $lcsq(".data_type").change();
      }
    },
    
    saveSurveys: function() {
      $lcsq(".survey:not(.template)").each(function(){
        var questions = [];
        
        $lcsq(this).find(".question:not(.template)").each(function(){
          var answers = [];
          
          if ($lcsq(this).find(".data_type").val() == "radio") {
            $lcsq(this).find(".answer:not(.template)").each(function(){
              answers.push($lcsq(this).find(".body").val());
            });
          }
          
          var question = {
            prompt: $lcsq(this).find(".prompt").val(),
            answers: answers,
            data_type: $lcsq(this).closest(".question").find(".data_type").val()
          };
          
          questions.push(question);
        });
        
        $lcsq(this).find(".survey_questions").val(JSON.stringify(questions));
      });
      
      $lcsq(".answers").sortable({
        handle: ".handle",
        update: function() {
          LivelyChatSupport.saveSurveys();
        }
      });
      
      $lcsq(".questions").sortable({
        handle: ".handle",
        update: function() {
          LivelyChatSupport.saveSurveys();
        }
      });
    }
  }

  $lcsq(document).on("blur", "#livelychatsupport .question .prompt, #livelychatsupport .question .answer .body", function(){
    LivelyChatSupport.saveSurveys();
  });
  
  $lcsq(document).on("click", ".agent_active_checkbox", function(){
    if ($lcsq(this).is(":checked")) {
      $lcsq(this).closest("tr").find(".agent_hidden_active").val("true");
    } else {
      $lcsq(this).closest("tr").find(".agent_hidden_active").val("false");
    }
    
    LivelyChatSupport.allowMultiAgent();
  });
  
  $lcsq(document).on("click", "#livelychatsupport .set_as_today", function() {
    var li = $lcsq(this).closest("li");
    var value = $lcsq(this).data("value");
    li.find("input").val(value).change();
    return false;
  });
  
  $lcsq(document).on("change", "#livelychatsupport .question .data_type", function(){
    var type = $lcsq(this).val();
    if (type == "radio") {
      $lcsq(this).closest(".question").find(".multiple_choice").show();
    } else {
      $lcsq(this).closest(".question").find(".multiple_choice").hide();
    }
    LivelyChatSupport.saveSurveys();
  });

  $lcsq(document).on("keypress", "#livelychatsupport-chatbox-body", function(e){
    var code = (e.keyCode ? e.keyCode : e.which);
    if (code == 13)
    {
      $lcsq(this).closest("form").submit();
      $lcsq("#livelychatsupport-chatbox-body").val("");
      return false;
    }
  });

  $lcsq(document).on("submit", "#livelychatsupport-chatbox-new-message", function(e){
    $lcsq(".chat").addClass("chat_loading");
    
    $lcsq.post($lcsq(this).attr("action"), {
      "action": "create_chatbox_message",
      "convo_token": $lcsq("#livelychatsupport-chatbox-token").val(),
      "from_agent": $lcsq("#livelychatsupport-chatbox-from_agent").val(),
      "body": $lcsq("#livelychatsupport-chatbox-body").val()
    });
    return false;
  });
  
  var file_frame;
  $lcsq(document).on("click", ".choose_agent_avatar", function( event ){
    var tr = $lcsq(this).closest("tr");
    
    if ( file_frame ) {
      file_frame.open();
      return;
    }

    file_frame = wp.media.frames.file_frame = wp.media({
      title: "Choose An Agent Avatar",
      button: {
        text: "Use This As An Avatar",
      },
      multiple: false
    });

    file_frame.on( "select", function() {
      attachment = file_frame.state().get('selection').first().toJSON();
      tr.find(".agent_avatar_url").val(attachment.url);
      file_frame = null;
      return false;
    });

    file_frame.open();

    return false;
  });
  
  
  $lcsq(document).on("click", ".choose_cta_online_image, .choose_cta_offline_image", function( event ){
    var form = $lcsq(this).closest(".livelychatsupport-design-form");
    form.find(".livelychatsupport-prebuilt_ctas").hide(150);
    
    if ( file_frame ) {
      file_frame.open();
      return;
    }

    file_frame = wp.media.frames.file_frame = wp.media({
      title: "Choose A Call To Action Image",
      button: {
        text: "Use This Call To Action Image",
      },
      multiple: false
    });
    
    file_frame.on( "select", function() {
      attachment = file_frame.state().get('selection').first().toJSON();
      form.find(".cta_image").val(attachment.url).keyup();
      return false;
    });

    file_frame.open();
    
    return false;
  });
  
  $lcsq(document).on("click", ".no_cta_online_image, .no_cta_offline_image", function(){
    var form = $lcsq(this).closest(".livelychatsupport-design-form");
    form.find(".cta_image").val("").keyup();
    return false;
  });
  
  $lcsq(document).on("keyup", ".cta_text", function(){
    var mode = $lcsq(this).closest(".livelychatsupport-design-form").data("mode");
    var cta_text = $lcsq(this).val();
    $lcsq(".cta_" + mode + "_text").text(cta_text);
  });
  
  $lcsq(document).on("keyup", ".cta_image", function(){
    var form = $lcsq(this).closest(".livelychatsupport-design-form");
    var mode = form.data("mode");
    var cta_image = $lcsq(this).val();
    $lcsq(".cta_" + mode + "_image").attr("src", cta_image);
    if (cta_image == "") { $lcsq(".cta_" + mode + "_image").hide(); } else { $lcsq(".cta_" + mode + "_image").show(); }
  });
  
  $lcsq(document).on("change", "#position", function(){
    var position = $lcsq(this).val();
    var opposite = "left";
    if (position == "left") { opposite = "right"; }
    $lcsq("#livelychatsupport-chatbox .cta_online_image").css(position, 0);
    $lcsq("#livelychatsupport-chatbox .cta_online_image").css(opposite, "auto");
    $lcsq("#cta_online_image_offset_x").val(0);
  });
  
  $lcsq(document).on("keyup change", "#colour", function(){
    var colour = $lcsq(this).val();
    $lcsq(".livelychatsupport-chatbox-border-colour").css("border-color", colour);
    $lcsq(".livelychatsupport-chatbox-background-colour").css("background-color", colour);
    $lcsq(".powered_by").css("color", colour);
  });
  
  $lcsq(document).on("click", ".livelychatsupport-show-prebuilt_ctas", function(){
    var form = $lcsq(this).closest(".livelychatsupport-design-form");
    form.find(".livelychatsupport-prebuilt_ctas").toggle(150)
    return false;
  });
  
  $lcsq(document).on("click", ".livelychatsupport-prebuilt_ctas img", function(){
    var mode = $lcsq(this).closest(".livelychatsupport-design-form").data("mode");
    $lcsq("#cta_" + mode + "_image").val( $lcsq(this).attr("src") );
    $lcsq(".cta_image").keyup();
  });
  
  $lcsq(document).on("click", ".delete_history", function(){
    $lcsq("#delete_history").submit();
    return false;
  });
  
  $lcsq(document).on("submit", "#delete_history", function(){
    if (confirm("This action is irreversible. Are you sure you want to delete all of your conversation history?")) {
      $lcsq.post($lcsq("#livelychatsupport-chatbox-new-message").attr("action"), {
        "action": "delete_all_convos"
      }, function() {
        alert("Your history has been deleted!");
      });
    }
    return false;
  });
  
  $lcsq(document).on("click", ".show_all_pages", function(){
    $lcsq("#all_pages li:not(.first_page)").toggle(150);
    return false;
  });
  
  $lcsq(document).on("change", "#online", function(){
    if ($lcsq(this).val() == "hours") {
      $lcsq(".hours_field").fadeIn(150);
    } else {
      $lcsq(".hours_field").fadeOut(150);
    }
  });
  
  $lcsq(document).on("click", ".add_row", function(){
    var row = $lcsq(this).data("row");
    var template = $lcsq("." + row + "_template:first").clone();
    var rand = Math.floor(( Math.random() * 100000000 ) + 1);
    
    template.removeClass(row + "_template");
    template.removeClass("template");
    template.find(".id").val("new");

    if (row == "answer") {
      var answers = $lcsq(this).closest(".multiple_choice").find(".answers");
      template.appendTo(answers);
      LivelyChatSupport.saveSurveys();
    } else if (row == "question") {
      var questions = $lcsq(this).closest(".survey").find(".questions:first");
      template.appendTo(questions);
      LivelyChatSupport.saveSurveys();
    } else {
      template.find("input, textarea, select").each(function(){
        if ($lcsq(this).attr("name")) {
          $lcsq(this).attr("name", $lcsq(this).attr("name").replace("[]", "[" + rand + "]"));
        }
      });
      
      template.appendTo("#" + row + "s");
    }

    return false;
  });
  
  $lcsq(document).on("click", ".delete_row", function(){
    var row = $lcsq(this).data("row");
    
    if (confirm("Are you sure you want to delete this " + row + "?"))
    {
      var trigger = $lcsq(this).closest("." + row);
      
      if (row == "question" || row == "answer") {
        trigger.remove()
        LivelyChatSupport.saveSurveys();
      } else {
        trigger.hide();
        trigger.find(".delete").val("1");
      }
    }
    
    return false;
  });
  
  $lcsq(document).on("click", ".show_example", function(){
    var example = $lcsq(this).data("example");
    $lcsq("." + example + "_example").toggle(150);
    return false;
  });
  
  $lcsq(document).on("change", "#start, #finish", function() {
    var url = $lcsq("#online_convos").data("add_convo");
    $lcsq("<li class='loading'><a>Loading...</a></li>").insertBefore(".convo:first");
    $lcsq(".convo").remove();

    $lcsq.post(url, {
      action: "find_visitors",
      start: $lcsq("#start").val(),
      finish: $lcsq("#finish").val()
    }, function(data){
      data = $lcsq.parseJSON(data);
      $lcsq("#online_convos .convo").remove()
      $lcsq("#online_convos .loading").remove();
      for(i=0; i<data.convos.length; i++) {
        convo = data.convos[i];
        LivelyChatSupport.addConvo(convo);
      }
    });
  });
  
  $lcsq(document).on("click", "#show_powered_by", function(){
    var value = "false";
    $lcsq(".powered_by").hide();
    
    if ($lcsq(this).is(":checked")) {
      value = "true";
      $lcsq(".powered_by").show();
    }
    
    $lcsq("#hidden_show_powered_by").val(value);
  });
  
  $lcsq(document).on("click", ".delete_convo", function(){
    if (!confirm("This action is irreversible. Are you sure you want to delete this convo?")) {
      return false;
    }
  });
  
  $lcsq(document).on("click", ".livelychatsupport-agent-check", function(){
    if ($lcsq(this).is(":checked")) {
      $lcsq(".livelychatsupport-agent").val("1");
    } else {
      $lcsq(".livelychatsupport-agent").val("0");      
    }
  });
  
  $lcsq(document).ready(function(){
    LivelyChatSupport.allowMultiAgent();
    LivelyChatSupport.scrollChatToBottom();
    LivelyChatSupport.poll();
    if ($lcsq("#surveys").length) { LivelyChatSupport.initSurveys(); }

    $lcsq("#colour").wpColorPicker({
      change: function(event, ui) {
        $lcsq("#colour").keyup();
      },
      clear: function() {
        $lcsq("#colour").val("#00687c");
        $lcsq("#colour").change();
      }
    });
    
    $lcsq("#online").change(); 
    $lcsq(".cta_image").keyup();
    
    if ($lcsq(".agent_default_checkbox").length) {
      if (!$lcsq(".agent_default_checkbox:checked").length) {
        $lcsq(".agent_default_checkbox:first").attr("checked", true);
      }
    }
    
    $lcsq(".cta_online_image, .cta_offline_image").draggable({
      axis: "y",
      stop: function(e, ui) {
        var mode = $lcsq(this).closest(".livelychatsupport-preview").data("mode");
        if (mode == "online")
        {
          var y_diff = - parseInt( ui.position.top + 7 + $lcsq(".cta_online_image").outerHeight() - $lcsq("#livelychatsupport-chatbox").outerHeight() );
        }
        else
        {
          var y_diff = - parseInt( ui.position.top + 13 + $lcsq(".cta_offline_image").outerHeight() - $lcsq("#livelychatsupport-chatbox").outerHeight() );
        }
        $lcsq("#cta_" + mode + "_image_offset_y").val(y_diff);
      }
    });
    
    $lcsq("#finish").datepicker({
      dateFormat: "MM d, yy"
    });
    
    $lcsq("#start").datepicker({
      dateFormat: "MM d, yy",
      onClose: function(selectedDate) {
        $lcsq("#finish").datepicker("option", "minDate", selectedDate);
      }
    });
    
    if ($lcsq("#online_convos").length) {
      var convos = $lcsq.parseJSON($lcsq("#online_convos").data("convos").convos).convos;
      for(i=0; i<convos.length; i++) {
        LivelyChatSupport.addConvo(convos[i]);
      }
    }
  });
  
});