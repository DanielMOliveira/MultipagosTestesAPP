var $lcsq = jQuery;
$lcsq(function($lcsq){
  
  if ($lcsq("#livelychatsupport-chatbox").length) {
    
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

      addMessage: function(content, from_agent, sound, id) {
        var audio = $lcsq("#livelychatsupport-chatbox audio.bell");
        var now = new Date();
        var hours = now.getHours() % 12;
        if (hours == 0) { hours = 12; }
      
        var template = $lcsq("<li />").addClass("message").attr("id", "message_" + id).attr("data-id", id);
        var body = $lcsq("<p />").addClass("body").text($lcsq("<div />").html(content).text());
        var date = $lcsq("<p />").addClass("date").text(hours + ":" + (now.getMinutes() < 10 ? "0" : "") + now.getMinutes());
        var clear = $lcsq("<div />").addClass("clear");
      
        body.appendTo(template);
        date.appendTo(template);
        clear.appendTo(template);
      
        if (from_agent == true) { 
          template.addClass("from_agent");
        
          if (sound == true) {
            $lcsq("#livelychatsupport-chatbox").addClass("open");
            audio[0].play();
          }
        }
      
        template.hide();
        template.appendTo("#livelychatsupport-chatbox .messages");
        if (sound == true) { template.fadeIn(); } else { template.show(); }
        LivelyChatSupport.scrollChatToBottom();
      },
    
      poll: function(on_page_load) {
				if (typeof on_page_load === "undefined") { on_page_load = false; }
        if ($lcsq("#livelychatsupport-chatbox").hasClass("chatting") && $lcsq(".messages").length)
        {
					var latest_id = 0;
          var url = $lcsq(".messages").data("url");
					var last = $lcsq("#livelychatsupport-chatbox .messages .message:last:not(.message_template)")
          if (last.length) { latest_id = last.data("id"); }
          var convo_token = $lcsq("#livelychatsupport-chatbox-token").val();
        
          $lcsq.post(
              url, {
                "action": "poll",
                "latest_id": latest_id,
                "convo_token": convo_token
              }, 
              function(response){
                var data = $lcsq.parseJSON(response);
                $lcsq(".chat").removeClass("chat_loading");

                if (data.messages.length)
                {
                  for(i=0; i<data.messages.length; i++) {
                  	var message = data.messages[i];
										if (!$lcsq("#message_" + message.id).length) {
	                    if (message.from_agent == "1") { var from_agent = true; } else { var from_agent = false; }
	                    LivelyChatSupport.addMessage(message.body, from_agent, !on_page_load, message.id);
										}
                  }
                }
            
                setTimeout(function(){
                  LivelyChatSupport.poll();
                }, 3000);
              }
          );
        }
      },
    
      setCurrentSurvey: function(step) {
        var index = step - 1;
        var current_index = $lcsq("#livelychatsupport-chatbox .survey").data("current_step");
        var steps = parseFloat($lcsq("#livelychatsupport-chatbox .survey").data("steps"));
        if (!current_index) { var toggle_time = 0; } else { var toggle_time = 150; }
      
        $lcsq("#livelychatsupport-chatbox .question").hide(toggle_time);
        $lcsq("#livelychatsupport-chatbox .question:eq(" + index + ")").show(toggle_time);
        $lcsq("#livelychatsupport-chatbox .current_question").text(step + 1);
        $lcsq("#livelychatsupport-chatbox .question_count").text(steps);
        $lcsq("#livelychatsupport-chatbox .survey").data("current_step", step);
      
        if (step == 1) {
          $lcsq("#livelychatsupport-chatbox .survey .back").hide();
        } else {
          $lcsq("#livelychatsupport-chatbox .survey .back").show(); 
        }
      
        if (step == steps) {
          $lcsq("#livelychatsupport-chatbox .survey .next_step").hide();
          $lcsq("#livelychatsupport-chatbox .survey .finish").show();
        }
        else
        {
          $lcsq("#livelychatsupport-chatbox .survey .next_step").show();
          $lcsq("#livelychatsupport-chatbox .survey .finish").hide();        
        }
      },
    
      surveyValidation: function() {
        var survey = $lcsq("#livelychatsupport-chatbox .survey");
        if (survey.find(".question.invalid").length) {
          survey.addClass("invalid");
        } else {
          survey.removeClass("invalid");
        }
      },
    
      popups: function() {
        var site_url = $lcsq("#livelychatsupport-chatbox").data("site_url")
        var popups = LivelyChatSupport_popups.sort(function(a, b) {return parseFloat(a["delay"]) - parseFloat(b["delay"])});
        var popped = false;
      
        for(i = 0; i < popups.length; i++) {
          if (popped == false)
          {
          	var popup = popups[i];
            var current_path = window.location.href.replace(site_url, "");
            var urls = popup.urls.replace("*", "(.*?)").replace(" ", "").split(",");
            var regex = new RegExp(urls.join("|"), "gi");
      
            if (popup.type == "trigger" && !$lcsq("#livelychatsupport-chatbox").hasClass("offline") && $lcsq("#livelychatsupport-chatbox .message").length < 2 && current_path.match(regex))
            {
              popped = true;
          
              setTimeout(function(){
                $lcsq("#livelychatsupport-chatbox").addClass("open chatting");
                LivelyChatSupport.scrollChatToBottom();
              
                $lcsq.post($lcsq("#livelychatsupport-chatbox-new-message").attr("action"), {
                  "action": "create_chatbox_message",
                  "convo_token": $lcsq("#livelychatsupport-chatbox-token").val(),
                  "body": popup.body,
                  "from_agent": 1,
                  "not_initiated": true
                }, function(){
                  LivelyChatSupport.poll();
                });
              
              }, parseFloat(popup.delay) * 1000);
            } else if (popup.type == "survey" && !$lcsq("#livelychatsupport-chatbox").hasClass("open") && !$lcsq("#livelychatsupport-chatbox").hasClass("surveyed")) {
              popped = true;
              var questions = popup.questions;
            
              setTimeout(function(){
                for(q=0; q<questions.length; q++) {
                  var question = questions[q];
                  var template = $lcsq("<div>").addClass("field question invalid");
                  var h3 = $lcsq("<h3>").text(question.prompt);
                  h3.appendTo(template);
                
                  if (question.data_type == "input") {
                   var input = $lcsq("<input>").attr("type", "text").attr("name", "question-" + q);
                   input.appendTo(template);
                  } else if (question.data_type == "textarea") {
                    var textarea = $lcsq("<textarea>").attr("name", "question-" + q);
                    textarea.appendTo(template);
                  } else if (question.data_type == "radio") {
                    var ol = $lcsq("<ol>");
                    var input = $lcsq("<input>").attr("type", "hidden").attr("name", "question-" + q).addClass("hidden");
                  
                    for(a=0; a<question.answers.length; a++) {
                      var answer = question.answers[a];
                      var li = $lcsq("<li>").addClass("answer").text(answer);
                      li.appendTo(ol);
                    }
                  
                    input.appendTo(template);
                    ol.appendTo(template);
                  }
                
                  template.appendTo("#livelychatsupport-chatbox .survey .questions");
                  $lcsq("#livelychatsupport-chatbox .survey .id").val(popup.id);
                }

                $lcsq("#livelychatsupport-chatbox .header span").text(popup.title);
                $lcsq("#livelychatsupport-chatbox .survey-thanks p").text(popup.thanks);
                $lcsq("#livelychatsupport-chatbox").addClass("open surveying");
                $lcsq("#livelychatsupport-chatbox .survey").data("steps", questions.length);
                LivelyChatSupport.setCurrentSurvey(1);
              }, parseFloat(popup.delay) * 1000);
            }
          }
        }
      },
    
      cacheSupport: function() {
        var url = $lcsq("#livelychatsupport-chatbox .messages").data("url");

        $lcsq.post(url, {
          "action": "cache_support",
          "convo_token": $lcsq("#livelychatsupport-chatbox-token").val()
        }, function(data){
          LivelyChatSupport_popups = [];
					var online = false;
          data = $lcsq.parseJSON(data);

					if (data.online == "online") {
						online = true;
					} else if (data.online == "offline") {
						online = false;
					} else if (data.online == "hidden") {
						$lcsq("#livelychatsupport-chatbox").hide();
					} else {
						var today = new Date();
						var days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];

	          for(i=0; i<data.hours.length; i++) {
							var hour = data.hours[i];
							var now_time = parseFloat(today.getHours().toString() + (today.getMinutes() < 10 ? '0' : '').toString() + today.getMinutes());
							var time_difference = data.gmt_offset * 100 - today.getTimezoneOffset() / 60 * -100;
							now_time = now_time + time_difference;

							if (days[today.getDay()] == hour.day && now_time >= hour.open_at && now_time <= hour.close_at) {
								online = true;
							}
	          }
					}
					
					if (data.online != "hidden") {
						if (online == false) {
							$lcsq("#livelychatsupport-chatbox").addClass("offline").show();
						} else {
							$lcsq("#livelychatsupport-chatbox").removeClass("offline").show();
							if (data.messages.length) {
								$lcsq("#livelychatsupport-chatbox").addClass("chatting");
								LivelyChatSupport.poll(true);
							}
						}
					}
        
          for(i=0; i<data.popups.length; i++) {
            var popup = data.popups[i];
            LivelyChatSupport_popups.push(popup);
          }
        
          LivelyChatSupport.popups();
        });
      }
    }

    $lcsq(document).ready(function(){
      LivelyChatSupport.scrollChatToBottom();
      LivelyChatSupport.poll(true);
      LivelyChatSupport.cacheSupport();
    });
  
    $lcsq(document).on("click", "#livelychatsupport-chatbox .delete_history", function(){
      if (confirm("This will delete your conversation. Are you sure you want to continue?")) {
				var url = $lcsq("#livelychatsupport-chatbox .messages").data("url");
				
				$lcsq("#livelychatsupport-chatbox").removeClass("open")
				$lcsq("#livelychatsupport-chatbox-name").val("");
				$lcsq("#livelychatsupport-chatbox-email").val("");
        $lcsq("#livelychatsupport-chatbox .prompter form").show();
				$lcsq("#livelychatsupport-chatbox .message:not(.message_template)").remove();
        $lcsq("#livelychatsupport-chatbox").removeClass("chatting");
				
        $lcsq.post(url, {
          "action": "delete_history"
        }, function(data) {
					data = $lcsq.parseJSON(data);
					$lcsq("#livelychatsupport-chatbox-token").val(data.new_token);
					clearTimeout(LivelyChatSupport.poller);
        });
      }
      return false;
    });
  
    $lcsq(document).on("click", "#livelychatsupport-chatbox .question .answer", function(){
      var question = $lcsq(this).closest(".question");
      question.find(".answer").removeClass("selected");
      $lcsq(this).addClass("selected");
      return false;
    });
  
    $lcsq(document).on("click", "#livelychatsupport-chatbox .survey .next_step", function(){
      if ($lcsq(this).closest(".survey").find(".question:visible").hasClass("invalid")) {
        alert("Please answer the question before continuing.");
      } else {
        var current_step = parseFloat($lcsq("#livelychatsupport-chatbox .survey").data("current_step"));
        LivelyChatSupport.setCurrentSurvey(current_step + 1);
      }
      return false;
    });
  
    $lcsq(document).on("click", "#livelychatsupport-chatbox .survey .back", function(){
      var current_step = parseFloat($lcsq("#livelychatsupport-chatbox .survey").data("current_step"));
      LivelyChatSupport.setCurrentSurvey(current_step - 1);
      return false;
    });
  
    $lcsq(document).on("blur", "#livelychatsupport-chatbox .survey input, #livelychatsupport-chatbox .survey textarea", function(){
      var question = $lcsq(this).closest(".question");
    
      if ($lcsq(this).val() != "") {
        question.removeClass("invalid");
      } else {
        question.addClass("invalid");
      }
    
      LivelyChatSupport.surveyValidation();
      return false;
    });
  
    $lcsq(document).on("click", "#livelychatsupport-chatbox .survey .answer", function(){
      var value = $lcsq(this).text();
      var question = $lcsq(this).closest(".question");
      question.find(".hidden").val(value);
      question.removeClass("invalid");
      LivelyChatSupport.surveyValidation();
      return false;
    });
  
    $lcsq(document).on("click", "#livelychatsupport-chatbox .survey .answer", function(){
    });
  
    $lcsq(document).on("submit", "#livelychatsupport-chatbox .survey", function(e){
      if ($lcsq(this).hasClass("invalid")) {
        alert("Please enter valid information.")
      } else {
        $lcsq.post($lcsq(this).attr("action"), {
          "action": "save_survey",
          "data": $lcsq(this).serializeArray()
        });

        $lcsq(this).addClass("complete");
      }
      return false;
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
        "body": $lcsq("#livelychatsupport-chatbox-body").val()
      });
      return false;
    });
  
    $lcsq(document).on("click", ".livelychatsupport-close, .livelychatsupport-open", function(){
      $lcsq("#livelychatsupport-chatbox").toggleClass("open");
  
      if ($lcsq(this).closest("#livelychatsupport-chatbox").length) {
        var href = $lcsq(this).data("href");
        var open = !(!$lcsq("#livelychatsupport-chatbox.open").length);
        $lcsq(this).attr("href", href + open);
        if (LivelyChatSupport.mobileDevice() && $lcsq("#livelychatsupport-chatbox-new-message:visible").length) { $lcsq("#livelychatsupport-chatbox-new-message").focus(); }
        LivelyChatSupport.scrollChatToBottom();
      }
      else
      {
        return false;
      }
    });

    $lcsq(document).on("submit", "#livelychatsupport-register", function(){
      if ($lcsq("#livelychatsupport-chatbox-name").val() == "" || $lcsq("#livelychatsupport-chatbox-email").val() == "" || $lcsq("#livelychatsupport-chatbox-email").val().indexOf("@") == -1)
      {
        $lcsq("#livelychatsupport-chatbox .prompter .livelychatsupport-error").show(150);
        return false;
      }
      else
      {
        var url = $lcsq(this).attr("action");
        $lcsq("#livelychatsupport-chatbox .prompter .livelychatsupport-error").hide(150);
        $lcsq("#livelychatsupport-chatbox .prompter form").hide(150);
      
        if ($lcsq("#livelychatsupport-chatbox").hasClass("offline"))
        {
          $lcsq.post(url, {
            "action": "subscribe",
            "convo_token": $lcsq("#livelychatsupport-chatbox-token").val(),
            "Name": $lcsq("#livelychatsupport-chatbox-name").val(),
            "Email": $lcsq("#livelychatsupport-chatbox-email").val(),
            "Comment": $lcsq("#livelychatsupport-offline-body").val()
          });
        
          $lcsq("#livelychatsupport-chatbox .prompter .offline-thanks").show(150);
        }
        else
        {
          $lcsq.post(url, {
            "action": "subscribe",
            "convo_token": $lcsq("#livelychatsupport-chatbox-token").val(),
            "Name": $lcsq("#livelychatsupport-chatbox-name").val(),
            "Email": $lcsq("#livelychatsupport-chatbox-email").val()
          });
        
          $lcsq("#livelychatsupport-chatbox").addClass("chatting");
          if (LivelyChatSupport.mobileDevice()) { $lcsq("#livelychatsupport-chatbox-body").focus(); }
          LivelyChatSupport.scrollChatToBottom();
          LivelyChatSupport.poller = setTimeout(LivelyChatSupport.poll, 3000);
        }
      }
    
      return false;
    });
    
  }
  
});