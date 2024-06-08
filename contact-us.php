<?php

/*
  Plugin Name: Contact us
  Plugin URI: http://wordpress.org/plugins/contact-us/
  Description: This plugin is used to create contact us form
  Author: Vikash lohiya
  Version: 1.0
  Author URI: vikash.com
 */

class ContactUs {

    public function __construct() {
        add_action('admin_menu', array($this, 'create_contactus_link'));
        add_shortcode('contactus', array($this, 'contactus_form'));
        add_action('wp_enqueue_scripts', array($this, 'contactus_scripts'));
    }

    public function contactus_scripts() {
        // for  css
        wp_register_style('contactus-style', plugins_url('/css/style.css', __FILE__));
        wp_enqueue_style('contactus-style');

        // for js
        wp_register_script('contact-script', plugins_url('/js/customize.js', __FILE__));
        wp_enqueue_script('contact-script');
    }

    public function contactus_form() {
        global $wpdb;
        // Saving data into table
        if (isset($_POST["uname"])) {
            $name = $_POST['uname'];
            $email = $_POST['uemail'];
            $message = $_POST['message'];

            $data = array("name" => $name, "email" => $email, "message" => $message);
            $wpdb->insert($wpdb->base_prefix . "contactus", $data);
            echo "<div class='success_msg'>Your request has been taken. Thanks</div>";
        }
        // Output the HTML form
        $output = '<form  id="my-contact-form" method="post" action="" onsubmit="return validateForm();">
                    <input type="hidden" name="action" value="custom_handle_contact_form">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="uname" ><br>
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="uemail" ><br>
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" ></textarea><br>
                    <button type="submit">Submit</button>
                </form>';

        return $output;
    }

    public function create_contactus_link() {
        add_menu_page('Contact us List', 'Contact us List', 'manage_options', 'contact_us_list', array($this, 'contact_us_list'), 'dashicons-visibility');
    }

    public function contact_us_list() {       
       
        require_once('backend/contactus_list.php');
         $obj = new Contactus_List();
        echo '<div class="wrap col-sm-3"><h2>Contactus List</h2>';
        $obj->prepare_items(); 
        echo '<form method="get" class="contact_us_list">
	<input type="hidden" name="page" value="contact_us_list">';
	if (!$obj->has_items() ) $_REQUEST['s'] = ' ';
	$obj->search_box( 'search', 'search_id' );      
        $obj->display();
         echo '</form>'; 
        echo '</div>';
        ?>
   <?php }

    public static function create_table() {
        global $wpdb;

        // Set the charset and collate
        $charset_collate = $wpdb->get_charset_collate();

        // Define the table name, including the WordPress table prefix
        $table_name = $wpdb->prefix . 'contactus';

        // SQL statement to create the table
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id int NOT NULL AUTO_INCREMENT,
        name tinytext NOT NULL,
        email varchar(50) NOT NULL,
        message  text NOT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
    ) $charset_collate;";

        // Include the file to enable dbDelta function
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Execute the query to create the table
        dbDelta($sql);
    }

}

register_activation_hook(__FILE__, array('ContactUs', 'create_table'));
$obj = new ContactUs();
