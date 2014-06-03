<?php 
/*
Plugin Name: RT Facebook Like Box
Plugin URI: http://readytheme.net/plugins/rt-facebook-like-box
Description: This plugin for a facebook like box in your website.
Author: Ready Theme
Version: 1.0
Author URI: http://readytheme.net
*/

/* Adding Latest jQuery from Wordpress */
function rt_facebook_like_box_wp_latest_jquery() {
	wp_enqueue_script('jquery');
}
add_action('init', 'rt_facebook_like_box_wp_latest_jquery');


/* Adding Settings API */
require_once dirname( __FILE__ ) . '/class.settings-api.php';


/* WordPress settings API  */
if ( !class_exists('RT_Settings_API_file' ) ):
class RT_Settings_API_file {

    private $settings_api;

    function __construct() {
        $this->settings_api = new RT_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_options_page( 'RT Facebook Like Box', 'RT Facebook Like Box', 'delete_posts', 'settings_api_test', array($this, 'plugin_page') );
    }

    function get_settings_sections() {
        $sections = array(
            array(
                'id' => 'stting_basics',
                'title' => __( 'Settings', 'readytheme' )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(
            'stting_basics' => array(
				
                array(
                    'name' => 'facebook_url',
                    'label' => __( 'Facebook Page Link', 'readytheme' ),
                    'desc' => __( 'Type your Facebook page link ex: http://www.facebook.com/ReadyTheme.net', 'readytheme' ),
					'default' => 'http://www.facebook.com/ReadyTheme.net',
                    'type' => 'text'
					
                )
            ),
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;

$settings = new RT_Settings_API_file();

/* Get the value of a settings field */

$option=facebook_url;
$section=stting_basics;

function my_get_option( $option, $section, $default = '' ) {
 
    $options = get_option( $section );
 
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
 
    return $default;
}



function rt_facebook_like_box_main() {
?>
<div style="position: fixed; right: 0; margin-bottom: 0; border: 1px solid #eee; background:#fff; z-index: 999; bottom: 0px; display: block; text-align:left" id="facebook_likebox">

<div style="position: absolute; left: -25px; margin: -15px 0 0 10px; z-index: 1000;">
<span id="closefacebook_likebox" style="cursor:pointer"><?php
echo '<img height="20" src="' . plugins_url( 'images/close.png' , __FILE__ ) . '" > ';
?></span>
</div>
<div id="fb-root"></div><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like-box href="<?php echo my_get_option( 'facebook_url', 'stting_basics' ); ?>" width="180" height="202" show_faces="true" border_color="#fff" stream="false" header="false"></fb:like-box>
</div>
<?php

}
add_action ('wp_enqueue_scripts', 'rt_facebook_like_box_main');



function rt_facebook_like_box_script() {
?>
<script type="text/javascript">
jQuery(document).ready(function () {
jQuery('#facebook_likebox').slideDown(5000);
jQuery('#closefacebook_likebox').click(function(){
jQuery(this).fadeOut();
jQuery('#facebook_Likebox').css('display','none');
jQuery('#facebook_likebox').slideUp(1500);
});
});
</script>

<?php
}
add_action ('wp_footer', 'rt_facebook_like_box_script')


?>