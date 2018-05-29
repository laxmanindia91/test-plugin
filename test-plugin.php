<?php
   /*
   Plugin Name: Test Plugin
   Plugin URI: http://my-plugin.com
   description: test cron job a plugin to create awesomeness and spread joy
   Version: 1.2
   Author: Mr. Developer
   Author URI: http://developer.com
   License: GPL2
   */

function plugin_installer()
{
include('installer.php');
}

register_activation_hook( __FILE__, 'plugin_installer' );

function pluginUninstall() {

        global $wpdb;
        $table = $wpdb->prefix."my_tables";
    //Delete any options thats stored also?
    //delete_option('wp_yourplugin_version');

    $wpdb->query("DROP TABLE IF EXISTS $table");
}//end pluginUninstall function

//hook into WordPress when its being deactivated:
register_deactivation_hook( __FILE__, 'pluginUninstall' );
 

update_option( 'testop', 'testop123' );
add_action('admin_menu', 'sample_cron_immediate_execution2');
    function sample_cron_immediate_execution2() {
        add_options_page(
            'Sample Cron Immediate Execution', 
            'Sample Cron Immediate Execution', 
            'manage_options',
            'sample_cron_immediate_execution', 
            'sample_cron_immediate_execution_admin2');
    }
    function sample_cron_immediate_execution_admin2() {
        ?>
        <div class="wrap">
        <?php
            $cron_url = site_url( '?custom_cron=myeventfunc1');         
            wp_remote_post( $cron_url, array( 'timeout' => 0.01, 'blocking' => false, 'sslverify' => apply_filters( 'https_local_ssl_verify', true ) ) );
            $cron_url = site_url( '?custom_cron=myeventfunc2');         
            wp_remote_post( $cron_url, array( 'timeout' => 0.01, 'blocking' => false, 'sslverify' => apply_filters( 'https_local_ssl_verify', true ) ) );
            echo 'cron tasks should be executed by now.';
        ?>
        </div>
        <?php
    }

    add_action('init', 'run_customcron');
    function run_customcron() 
    {
        if (isset($_GET['custom_cron']))
        {
            call_user_func($_GET['custom_cron']);
        }
    }

    function myeventfunc1() {
        sleep(10); // assuming it's a heavy task 
        sample_log(date('l jS \of F Y h:i:s A'), __DIR__ . '/myevent1_log.html');
    }

    function myeventfunc2() {
        sleep(10); // assuming it's a heavy task 
        sample_log(date('l jS \of F Y h:i:s A'), __DIR__ . '/myevent2_log.html');
    }

    function sample_log($time, $file) {
        file_put_contents($file, $time . '<br />', FILE_APPEND);
    }
	
	
	
