<div id="settings-panel">
    <div class="section-company">
        <div class="left-side">
            <ul>
                <li><a class="change-table active" data-table="general-settings-table"><i class="fas fa-tools"></i> General Setting</a></li>
                <li><a class="change-table" data-table="system-info-table"><i class="fas fa-shield-alt"></i> System Info</a></li>
                <li><a class="support-item" href="https://wp-masters.com" target="_blank"><i class="fas fa-life-ring"></i> Plugin Support</a></li>
            </ul>
        </div>
        <div class="right-side">
            <a href="https://wp-masters.com" target="_blank"><img src="<?php echo esc_attr(PLUGIN_ONLY_ONE_BUY_PATH.'/templates/assets/img/logo.png') ?>" alt=""></a>
        </div>
    </div>
    <div class="select-table" id="general-settings-table">
        <form action="" method="post">
            <div class="section_data">
                <div class="title">Main Settings</div>
                <div class="head_items">
                    <div class="item-table">Disable Multiple Purchases: <a href="#" data-tooltip="When block order product again" class="help-icon clicktips"><i class="fas fa-question-circle"></i></a></div>
                </div>
                <div class="items-list">
                    <div class="item-content">
                        <div class="item-table">
                            <div class="radio-group">
                                <input type="radio" name="only_one_buy[work_mode]" id="work_mode_1" value="0" <?php if(isset($settings['work_mode']) && $settings['work_mode'] == '0') { echo esc_html('checked'); } elseif(!isset($settings['work_mode'])) { echo esc_html('checked'); } ?>>
                                <label for="work_mode_1">Set by Products</label>

                                <input type="radio" name="only_one_buy[work_mode]" id="work_mode_2" value="1" <?php if(isset($settings['work_mode']) && $settings['work_mode'] == '1') { echo esc_html('checked'); } ?>>
                                <label for="work_mode_2">Set for All</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="button button-primary button-large" id="save-settings" type="submit">Save settings</button>
        </form>
    </div>
    <div class="select-table" id="system-info-table" style="display: none">
        <div class="section_data">
            <div class="alert-help">
                <i class="fas fa-question-circle"></i> The following is a system report containing useful technical information for troubleshooting issues. If you need further help after viewing the report, do the screenshots of this page and send it to our Support.
            </div>
            <table class="status-table" cellpadding="0" cellspacing="0">
                <thead>
                <tr>
                    <th colspan="2">WordPress</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Home URL</td>
                    <td><?php echo esc_html(get_home_url()) ?></td>
                </tr>
                <tr>
                    <td>Site URL</td>
                    <td><?php echo esc_html(get_site_url()) ?></td>
                </tr>
                <tr>
                    <td>REST API Base URL</td>
                    <td><?php echo esc_html(rest_url()) ?></td>
                </tr>
                <tr>
                    <td>WordPress Version</td>
                    <td><?php echo esc_html($wp_version) ?></td>
                </tr>
                <tr>
                    <td>WordPress Memory Limit</td>
                    <td><?php echo esc_html(WP_MEMORY_LIMIT) ?></td>
                </tr>
                <tr>
                    <td>WordPress Debug Mode</td>
                    <td><?php echo esc_html(WP_DEBUG ? 'Yes' : 'No') ?></td>
                </tr>
                <tr>
                    <td>WordPress Debug Log</td>
                    <td><?php echo esc_html(WP_DEBUG_LOG ? 'Yes' : 'No'); ?></td>
                </tr>
                <tr>
                    <td>WordPress Script Debug Mode</td>
                    <td><?php echo esc_html(SCRIPT_DEBUG ? 'Yes' : 'No'); ?></td>
                </tr>
                <tr>
                    <td>WordPress Cron</td>
                    <td><?php echo esc_html(defined('DISABLE_WP_CRON') && DISABLE_WP_CRON ? 'Yes' : 'No'); ?></td>
                </tr>
                <tr>
                    <td>WordPress Alternate Cron</td>
                    <td><?php echo esc_html(defined('ALTERNATE_WP_CRON') && ALTERNATE_WP_CRON ? 'Yes' : 'No'); ?></td>
                </tr>
                </tbody>
            </table>
            <table class="status-table" cellpadding="0" cellspacing="0">
                <thead>
                <tr>
                    <th colspan="2">Web Server</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Software</td>
                    <td><?php echo esc_html($_SERVER['SERVER_SOFTWARE']) ?></td>
                </tr>
                <tr>
                    <td>Port</td>
                    <td><?php echo esc_html($_SERVER['SERVER_PORT']) ?></td>
                </tr>
                <tr>
                    <td>Document Root</td>
                    <td><?php echo esc_html($_SERVER['DOCUMENT_ROOT']) ?></td>
                </tr>
                </tbody>
            </table>
            <table class="status-table" cellpadding="0" cellspacing="0">
                <thead>
                <tr>
                    <th colspan="2">PHP</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Version</td>
                    <td><?php echo esc_html(phpversion()) ?></td>
                </tr>
                <tr>
                    <td>Memory Limit (memory_limit)</td>
                    <td><?php echo esc_html(ini_get('memory_limit')) ?></td>
                </tr>
                <tr>
                    <td>Maximum Execution Time (max_execution_time)</td>
                    <td><?php echo esc_html(ini_get('max_execution_time')) ?></td>
                </tr>
                <tr>
                    <td>Maximum File Upload Size (upload_max_filesize)</td>
                    <td><?php echo esc_html(ini_get('upload_max_filesize')) ?></td>
                </tr>
                <tr>
                    <td>Maximum File Uploads (max_file_uploads)</td>
                    <td><?php echo esc_html(ini_get('max_file_uploads')) ?></td>
                </tr>
                <tr>
                    <td>Maximum Post Size (post_max_size)</td>
                    <td><?php echo esc_html(ini_get('post_max_size')) ?></td>
                </tr>
                <tr>
                    <td>Maximum Input Variables (max_input_vars)</td>
                    <td><?php echo esc_html(ini_get('max_input_vars')) ?></td>
                </tr>
                <tr>
                    <td>cURL Enabled</td>
                    <td><?php $curl = curl_version();
                        if(isset($curl['version'])) {
                            echo esc_html("Yes (version $curl[version])");
                        } else {
                            echo esc_html("No");
                        } ?></td>
                </tr>
                <tr>
                    <td>Mcrypt Enabled</td>
                    <td><?php echo esc_html(function_exists('mcrypt_encrypt') ? 'Yes' : 'No') ?></td>
                </tr>
                <tr>
                    <td>Mbstring Enabled</td>
                    <td><?php echo esc_html(function_exists('mb_strlen') ? 'Yes' : 'No') ?></td>
                </tr>
                <tr>
                    <td>Loaded Extensions</td>
                    <td><?php echo esc_html(implode(', ', get_loaded_extensions())) ?></td>
                </tr>
                </tbody>
            </table>
            <table class="status-table" cellpadding="0" cellspacing="0">
                <thead>
                <tr>
                    <th colspan="2">Database Server</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>Database Server</td>
                    <td><?php echo esc_html($wpdb->get_var('SELECT @@character_set_database')) ?></td>
                </tr>
                <tr>
                    <td>Database Collation</td>
                    <td><?php echo esc_html($wpdb->get_var('SELECT @@collation_database')) ?></td>
                </tr>
                </tbody>
            </table>
            <table class="status-table" cellpadding="0" cellspacing="0">
                <thead>
                <tr>
                    <th colspan="2">Date and Time</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>WordPress (Local) Timezone</td>
                    <td><?php echo esc_html(get_option('timezone_string')) ?></td>
                </tr>
                <tr>
                    <td>MySQL (UTC)</td>
                    <td><?php echo esc_html($wpdb->get_var('SELECT utc_timestamp()')) ?></td>
                </tr>
                <tr>
                    <td>MySQL (Local)</td>
                    <td><?php echo esc_html(date("F j, Y, g:i a", strtotime($wpdb->get_var('SELECT utc_timestamp()')))) ?></td>
                </tr>
                <tr>
                    <td>PHP (UTC)</td>
                    <td><?php echo esc_html(date('Y-m-d H:i:s')) ?></td>
                </tr>
                <tr>
                    <td>PHP (Local)</td>
                    <td><?php echo esc_html(date("F j, Y, g:i a")) ?></td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
