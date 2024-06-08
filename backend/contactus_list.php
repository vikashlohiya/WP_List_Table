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
     public function get_sortable_columns() {
        $sortable_columns = [
            'email'  => [ 'email',false ], // false means not sorted by default.
          
        ];
        return $sortable_columns;
    }
     
    
      public function get_columns() {
        $columns = array(
            //Column Name     //Display Name 
            'cb'=>'<input type="checkbox"  />',
            'name' => __('Customer Name','plugingettext'),
            'email' => __('Email','plugingettext'),
            'message' => __('Message','plugingettext'),          
            'action' => __('Action','plugingettext'),          
            
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
     public function column_cb( $item ) {
        ?>
		<input type="checkbox" name="contactus[]" id="cb-select-<?php echo $item['id']; ?>" value="<?php echo esc_attr( $item['id'] ); ?>" />
		
		<?php
    }
    // column_{column name}
      public function column_action( $item ) {
        ?>
		<a href="">Edit</a>
		
		<?php
    }
    public function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
            // Add more actions as needed
        );
        return $actions;
    }
    
    public function process_bulk_action() {
     
    if ( 'delete' === $this->current_action() ) {
        // Handle bulk delete action
        $ids = isset( $_REQUEST['contactus'] ) ? $_REQUEST['contactus'] : array();

        if ( is_array( $ids ) && ! empty( $ids ) ) {
            global $wpdb;

            $table_name = $wpdb->prefix . 'contactus'; // Change this to your actual table name.

            foreach ( $ids as $id ) {
                // Delete item with $id from the database
                $wpdb->delete( $table_name, array( 'id' => $id ) );
            }
        }
    }
}
     public function prepare_items() {
        global $wpdb;      
        $this->_column_headers = array($this->get_columns(), array(), $this->get_sortable_columns());
        $where="";  
        $this->process_bulk_action();
        // Handle sorting
        $orderby = isset( $_REQUEST['orderby']  ) ? $_REQUEST['orderby'] : '';
        $order = isset( $_REQUEST['order']  ) ? $_REQUEST['order'] : '';
        
        // Search
        if(isset($_REQUEST['s'])){
                $where=" and email like '%{$_REQUEST['s']}%'";
            }
        $query = "select * from ".$wpdb->base_prefix."contactus where 1=1 ".$where;	
        if($orderby!=''){
           $query=$query." order by " .$orderby ." ".$order;
        }
        // pagination
        $total_items = $wpdb->query($query);
        $per_page = 10;
        $current_page = $this->get_pagenum();
        $totalpages = ceil($total_items / $per_page);
        $offset = ($current_page - 1) * $per_page;
        $query.=' LIMIT ' . (int) $offset . ',' . (int) $per_page;

        $this->set_pagination_args(array(
            "total_items" => $total_items,
            "total_pages" => $totalpages,
            "per_page" => $per_page,
        ));
        // end pagination
        $this->items = $wpdb->get_results($query, ARRAY_A);
        
    }
    
}

