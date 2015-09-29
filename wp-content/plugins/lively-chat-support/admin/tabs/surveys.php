<?php if (strpos($livelychatsupport["addons"], "surveys") !== false) { ?>
  
  <?php

  global $wpdb;
  $surveys_table = $wpdb->prefix . "livelychatsupport_surveys";
  $surveys = $wpdb->get_results("SELECT * FROM $surveys_table");

  ?>

  <h2>Surveys</h2>

  <p>
    Surveys can give you the insights you need into your customers attitudes and behaviours.<br>
    All surveys will be emailed to <strong><?php echo $livelychatsupport["subscriber_email"] ?></strong>.<br>
    <a href="#!/lively-chat-support" class="show_example" data-example="surveys">See Examples (you won't leave this page)</a>.
  </p>

  <div class="surveys_example example updated">
  
    <p>
      The url field matches your visitor's path (the url WITHOUT your domain). Separate multiple urls with a comma. An asterisk <b>(*)</b> is a wildcard placeholder. For example:<br>
    </p>
  
    <ul>
      <li><b>/contact</b> will launch the survey on <b>http://mywebsite.com/contact</b>, but will not launch the survey on <b>http://mywebsite.com/about</b></li>
      <li><b>*</b> will launch the survey on your entire website
      <li><b>/articles/*</b> will launch the survey on any pages in the <b>/articles</b> directory
    </ul>
  
  </div>

  <form action="admin.php?page=livelychatsupport&tab=surveys" method="post">
    <ul id="surveys" data-surveys="<?php echo htmlspecialchars(json_encode($surveys)); ?>">
      <li class="survey survey_template template">
        <a href="#!/lively-chat-support" class="delete_row" data-row="survey">Delete This Survey</a>
        <input type="hidden" class="id" name="surveys[][id]" />
        <input type="hidden" class="delete" name="surveys[][delete]" value="0" />
      
        <label>Survey Title</label>
        <input type="text" class="title" name="surveys[][title]" />
      
        <div class="field">
          When someone visits:<br>
          <input type="text" name="surveys[][urls]" class="urls" value="" /><br>
          wait <input type="text" name="surveys[][delay]" class="delay" value="" /> seconds and ask:<br>
        </div>
      
        <div class="questions">
          <div class="question template question_template">
            <div class="field">
              <a href="#!/lively-chat-support" class="delete_row" data-row="question">Delete This Question</a>
              <a href="#!/lively-chat-support" class="handle">Drag to Reorder</a>
              <label>Question:</label><br>
              <input type="text" class="prompt" />
            </div>
            <div class="field">
              <label>Answer:</label><br>
              <select class="data_type">
                <option value="input">Text</option>
                <option value="radio">Multiple Choice</option>
                <option value="textarea">Paragraph Text</option>
              </select>
          
              <div class="multiple_choice">
                <ol class="answers">
                  <li class="template answer_template answer">
                    <input type="text" class="body" />
                    <a href="#!/lively-chat-support" class="handle">Drag to Reorder</a>
                    <a href="#!/lively-chat-support" class="delete_row" data-row="answer">Delete</a>
                  </li>
                </ol>
                <a href="#!/lively-chat-support" class="button-secondary add_row" data-row="answer">Add Answer</a>
              </div>
            </div>
          </div>
        </div>
      
        <a href="#!/lively-chat-support" class="button-secondary add_row" data-row="question">Add Question</a><br><br>
        <input type="hidden" name="surveys[][questions]" class="survey_questions" />
      
        <div class="field">
          <label>Thank you text</label>
          <textarea class="thanks" name="surveys[][thanks]"></textarea>
        </div>
      </li>
    </ul>
  
    <a href="#!/lively-chat-support" class="button-secondary add_row" data-row="survey">Add Survey</a><br><br>
  
    <input type="submit" value="Save Surveys" class="button-primary">
  </form>
  
<?php } else { ?>
  
  <h2><?php _e( "Get information quickly and unintrusively from your visitors.", "lively-chat-support" ); ?></h2>

  <p>
    <?php _e( "Surveys are quick, fun ways to question your visitors.", "lively-chat-support" ); ?>
  </p>
  <p>
    <?php _e( "Visitors love to complete quick surveys, if they know it will benefit them in the future.", "lively-chat-support" ); ?>
  </p>
  <p>
    <?php _e( "They are also a great lead in to a conversation.", "lively-chat-support" ); ?>
  </p>

  <img src="<?php echo plugins_url("lively-chat-support/assets/surveys.png"); ?>" />

  <p>
    <strong><?php _e( "Surveys are a premium feature.", "lively-chat-support" ); ?></strong>
  </p>
  <p>
    <?php _e( "To purchase the addon, pay here and you'll receive an email with an activation code (which you'll enter in the box below):", "lively-chat-support" ); ?>
  </p>

  <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="T4Y8KB4RJLURW">
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
  </form>

  <p>
    <?php _e( "Thanks for your support!", "lively-chat-support" ); ?>
  </p>

  <div class="updated">
    <form action="admin.php?page=livelychatsupport&tab=surveys" method="post">
      <div class="field">
        <label>
          <?php _e( "Already have an activation code for Surveys? Enter it here:", "lively-chat-support" ); ?><br>
          <input type="text" name="activation_code" value="<?php echo $livelychatsupport["activation_code"]; ?>" />
          <input type="submit" value="<?php _e( "Activate Surveys", "lively-chat-support" ); ?>" class="button-primary">
        </label>
      </div>
    </form>
  </div>
  
<?php } ?>