<div class="wrap">
    <div class="icon32"><img src="<?php echo FUE_TEMPLATES_URL; ?>/images/send_mail.png" /></div>
    <h2>
        <?php _e('Follow-Up Emails &raquo; Settings', 'follow_up_emails'); ?>
    </h2>

    <?php if (isset($_GET['settings_updated'])): ?>
    <div id="message" class="updated"><p><?php _e('Settings updated', 'follow_up_emails'); ?></p></div>
    <?php endif; ?>

    <?php if (isset($_GET['imported'])): ?>
    <div id="message" class="updated"><p><?php _e('Data imported successfully', 'follow_up_emails'); ?></p></div>
    <?php endif; ?>

    <form action="admin-post.php" method="post" enctype="multipart/form-data">
        <h3><?php _e('Unsubscribe Page', 'follow_up_emails'); ?></h3>

        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th><label for="unsubscribe_page"><?php _e('Select Unsubscribe Page', 'follow_up_emails'); ?></label></th>
                    <td>
                        <select name="unsubscribe_page" id="unsubscribe_page">
                            <?php
                            foreach ($pages as $p):
                                $sel = ($p->ID == $page) ? 'selected' : '';
                            ?>
                            <option value="<?php echo esc_attr($p->ID); ?>" <?php echo $sel; ?>><?php echo esc_html($p->post_title); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <a href="post.php?post=<?php echo $page; ?>&action=edit"><?php _e('Edit Unsubscribe Page', 'follow_up_emails'); ?></a>
                    </td>
                </tr>
            </tbody>
        </table>

        <h3><?php _e('BCC', 'follow_up_emails'); ?></h3>

        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th><label for="bcc"><?php _e('Email Address', 'follow_up_emails'); ?></label></th>
                    <td>
                        <input type="text" name="bcc" id="bcc" value="<?php echo esc_attr( $bcc ); ?>" />
                    </td>
                </tr>
            </tbody>
        </table>

        <h3><?php _e('Daily Emails Summary', 'follow_up_emails'); ?></h3>

        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th><label for="daily_emails"><?php _e('Email Address(es)', 'follow_up_emails'); ?></label></th>
                    <td>
                        <input type="text" name="daily_emails" id="daily_emails" value="<?php echo esc_attr( get_option('fue_daily_emails', '') ); ?>" />
                        <span class="description"><?php _e('comma separated', 'follow_up_emails'); ?></span>
                    </td>
                </tr>
                <tr valign="top">
                    <th><label for="daily_emails_time_hour"><?php _e('Preferred Time', 'follow_up_emails'); ?></label></th>
                    <td>
                        <?php
                        $time   = get_option('fue_daily_emails_time', '12:00 AM');
                        $parts  = explode(':', $time);
                        $parts2 = explode(' ', $parts[1]);
                        $hour   = $parts[0];
                        $minute = $parts2[0];
                        $ampm   = $parts2[1];
                        ?>
                        <select name="daily_emails_time_hour" id="daily_emails_time_hour">
                            <?php for ($x = 1; $x <= 12; $x++): ?>
                            <option value="<?php echo $x; ?>" <?php selected($hour, $x); ?>><?php echo ($x >= 10) ? $x : '0'. $x; ?></option>
                            <?php endfor; ?>
                        </select>

                        <select name="daily_emails_time_minute" id="daily_emails_time_minute">
                            <?php for ($x = 0; $x <= 55; $x+=15): ?>
                            <option value="<?php echo $x; ?>" <?php selected($minute, $x); ?>><?php echo ($x >= 10) ? $x : '0'. $x; ?></option>
                            <?php endfor; ?>
                        </select>

                        <select name="daily_emails_time_ampm" id="daily_emails_time_ampm">
                            <option value="AM" <?php selected($ampm, 'AM'); ?>>AM</option>
                            <option value="PM" <?php selected($ampm, 'PM'); ?>>PM</option>
                        </select>
                    </td>
                </tr>
            </tbody>
        </table>

        <h3><?php _e('Permissions', 'follow_up_emails'); ?></h3>

        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th><label for="roles"><?php _e('Roles', 'follow_up_emails'); ?></label></th>
                    <td>
                        <select name="roles[]" id="roles" multiple style="width: 400px;">
                        <?php
                        $roles = get_editable_roles();
                        foreach ( $roles as $key => $role ) {
                            $selected = false;
                            $readonly = '';
                            if (array_key_exists('manage_follow_up_emails', $role['capabilities'])) {
                                $selected = true;

                                if ( $key == 'administrator' ) {
                                    $readonly = 'readonly';
                                }
                            }
                            echo '<option value="'. $key .'" '. selected($selected, true, false) .'>'. $role['name'] .'</option>';

                        }
                    ?>
                        </select>
                        <script>jQuery("#roles").chosen();</script>
                    </td>
                </tr>
            </tbody>
        </table>

        <h3><?php _e('Backup &amp; Restore', 'follow_up_emails'); ?></h3>

        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <td colspan="2">
                        <strong><?php _e('Download Backups', 'follow_up_emails'); ?></strong>
                        <br/>
                        <a class="button" href="<?php echo wp_nonce_url('admin-post.php?action=fue_backup_emails', 'fue_backup'); ?>"><?php _e('Follow-Up Emails', 'follow_up_emails'); ?></a>
                        <a class="button" href="<?php echo wp_nonce_url('admin-post.php?action=fue_backup_settings', 'fue_backup'); ?>"><?php _e('Settings', 'follow_up_emails'); ?></a>
                    </td>
                </tr>
                <tr valign="top">
                    <td colspan="2">
                        <strong>Restore</strong>
                        <table class="form-table">
                            <tbody>
                                <tr valign="top">
                                    <th><label for="restore_emails"><?php _e('Emails', 'follow_up_emails'); ?></label></th>
                                    <td><input type="file" name="emails_file" /></td>
                                </tr>
                                <tr valign="top">
                                    <th><label for="restore_settings"><?php _e('Settings', 'follow_up_emails'); ?></label></th>
                                    <td><input type="file" name="settings_file" /></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php do_action('fue_settings_form'); ?>

        <p class="submit">
            <input type="hidden" name="action" value="sfn_followup_save_settings" />
            <input type="submit" name="save" value="<?php _e('Save Settings', 'follow_up_emails'); ?>" class="button-primary" />
        </p>
    </form>
</div>
