<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}
// Child class
class Contactus_List extends WP_List_Table{
    
    public function __construct() {
        parent::__construct(array(
            'singular' => __('Contact us','plugingettext'), //singular name of the listed records
            'plural' => __('Contact us','plugingettext'), //plural name of the listed records
            'ajax'=>false
        ));
     }
     
     
    
      public function get_columns() {
        $columns = array(
            //Column Name     //Display Name 
            'name' => __('Customer Name','plugingettext'),
            'email' => __('Email','plugingettext'),
            'message' => __('Message','plugingettext'),          
            
        );
        return $columns;
    }
    
    public function column_default($item, $column_name) {
         switch ($column_name) {
            case 'name':
            return $item[$column_name];
            case 'email':
            return $item[$column_name];
            case 'message':
            return $item[$column_name];
           
        }
    }
    
     public function prepare_items() {
        global $wpdb;      
        $this->_column_headers = array($this->get_columns(), array(), array());
        $where="";           
        $query = "select * from ".$wpdb->base_prefix."contactus where 1=1 ".$where;	
        $this->items = $wpdb->get_results($query, ARRAY_A);
        
    }
    
}

