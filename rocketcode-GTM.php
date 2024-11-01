<?php
/*
Plugin Name: Tag Manager by RocketCode
Plugin URI: https://wordpress.org/plugins/tag-manager-by-rocketcode
Description: Add Google Tag Manager scripts to your WordPress site
Version: 1.2
Author: RocketCode
Author URI: https://rocketcode.com.br
*/


function rocketcode_gtm_add_google_tag_manager_head() {
    $rocketcode_gtm_id = get_option('rocketcode_gtm_id');
    if (!empty($rocketcode_gtm_id)) {
        echo '
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({"gtm.start":
        new Date().getTime(),event:"gtm.js"});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!="dataLayer"?"&l="+l:"";j.async=true;j.src=
        "https://www.googletagmanager.com/gtm.js?id="+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,"script","dataLayer","'. esc_attr($rocketcode_gtm_id) .'");</script>
        <!-- End Google Tag Manager -->
        ';
    }
}

add_action('wp_head', 'rocketcode_gtm_add_google_tag_manager_head');


function rocketcode_gtm_add_google_tag_manager_body() {
    $rocketcode_gtm_id = get_option('rocketcode_gtm_id');
    if (!empty($rocketcode_gtm_id)) {
        echo '
        <!-- Google Tag Manager (noscript) -->
        <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=' . esc_attr($rocketcode_gtm_id) . '"
        height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>
        <!-- End Google Tag Manager (noscript) -->';
    }
}

add_action('wp_body_open', 'rocketcode_gtm_add_google_tag_manager_body');


function rocketcode_gtm_add_settings_link($links)
{
    $settings_link = '<a href="' . admin_url('options-general.php?page=rocketcode_gtm') . '">' . __('Settings', 'rocketcode_gtm') . '</a>';
    array_push($links, $settings_link);
    return $links;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'rocketcode_gtm_add_settings_link');


function rocketcode_gtm_settings_page()
{
    add_options_page('Tag Manager by RocketCode', 'Tag Manager - RocketCode', 'manage_options', 'rocketcode_gtm', 'rocketcode_gtm_settings_page_html');
}

add_action('admin_menu', 'rocketcode_gtm_settings_page');


function rocketcode_gtm_settings_page_html()
{
    if (!current_user_can('manage_options')) { return; }

    if (isset($_GET['settings-updated'])) {
        add_settings_error('rocketcode_gtm_messages', 'rocketcode_gtm_message', __('Settings Saved', 'rocketcode_gtm'), 'updated');
    }

    settings_errors('rocketcode_gtm_messages');

    $rocketcode_gtm_id = get_option('rocketcode_gtm_id');
?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('rocketcode_gtm');
            do_settings_sections('rocketcode_gtm');
            submit_button('Save Settings');
            ?>
        </form>
    </div>
<?php
}


function rocketcode_gtm_settings_init()
{
    register_setting('rocketcode_gtm', 'rocketcode_gtm_id');

    add_settings_section(
        'rocketcode_gtm_section',
        __('Settings', 'rocketcode_gtm'),
        'rocketcode_gtm_section_cb',
        'rocketcode_gtm'
    );

    add_settings_field(
        'rocketcode_gtm_id',
        __('Google Tag Manager ID', 'rocketcode_gtm'),
        'rocketcode_gtm_id_cb',
        'rocketcode_gtm',
        'rocketcode_gtm_section',
        [
            'label_for' => 'rocketcode_gtm_id',
            'class' => 'rocketcode_gtm_row',
            'rocketcode_gtm_custom_data' => 'custom',
        ]
    );

    add_settings_section(
        'rocketcode_gtm_footer',
        __('', 'rocketcode_gtm'),
        'rocketcode_gtm_footer',
        'rocketcode_gtm'
    );
}

add_action('admin_init', 'rocketcode_gtm_settings_init');


function rocketcode_gtm_section_cb($args)
{
?>
    <p style="text-align: left;">This plugin just to add Google Tag Manager scripts to your website. It includes a function to add the Google Tag Manager script in the head tag and another to add the script to the beginning of the body tag.<br>
        <span id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Enter your Google Tag Manager ID in the following format GTM-XXXXXXX', 'rocketcode_gtm'); ?></span>
    </p>
<?php
}


function rocketcode_gtm_id_cb($args) {
    $rocketcode_gtm_id = get_option('rocketcode_gtm_id'); 
?>
    <input placeholder="GTM-XXXXXXX" type="text" id="<?php echo esc_attr($args['label_for']); ?>" data-custom="<?php echo esc_attr($args['rocketcode_gtm_custom_data']); ?>" name="rocketcode_gtm_id" value="<?php echo $rocketcode_gtm_id; ?>" />
<?php
}


function rocketcode_gtm_footer() {
?>
    <p style="text-align: left;">Thank you for use Tag Manager by <a href="https://rocketcode.com.br" target="_blank">RocketCode.</a></p>
<?php 
}
?>