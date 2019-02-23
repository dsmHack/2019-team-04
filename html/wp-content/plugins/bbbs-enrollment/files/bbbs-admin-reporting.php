<?php
function bbbs_volunteer_menu() {
    add_menu_page("BBBS Volunteer", "BBBS Volunteer", "manage_options", "bbbs-volunteer", "volunteer_dashboard_page" ,null, 15);
    bbbs_add_volunteer_submenu();
}

function bbbs_add_volunteer_submenu() {
    add_submenu_page("bbbs-volunteer", "BBBS Dashboard", "BBBS Dashboard", "manage_options", "bbbs-volunteer", "volunteer_dashboard_page");
    add_submenu_page("bbbs-volunteer", "BBBS Reports", "BBBS Reports", "manage_options", "bbbs-reports", "volunteer_reports_page");
}

function volunteer_dashboard_page() {
?>
<h2>BBBS Volunteer Dashboard</h2>
<?php
}

function volunteer_reports_page() {
?>
<h2>BBBS Volunteer Reports</h2>
<?php

class Form_List_Table extends WP_List_Table {

    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct() {
        parent::__construct( array(
       'singular'=> 'wp_list_text_link', //Singular label
       'plural' => 'wp_list_test_links', //plural label, also this well be one of the table css class
       'ajax'   => false //We won't support Ajax for this table
       ) );
    }

    function extra_tablenav( $which ) {
        if ( $which == "top" ){
        //The code that goes before the table is here
        echo"Hello, I'm before the table";
        }
        if ( $which == "bottom" ){
        //The code that goes after the table is there
        echo"Hi, I'm after the table";
        }
    }

    function get_columns() {
        return $columns= array(
        'col_form_id'=>__('ID'),
        'col_form_name'=>__('Name')
        );
    }
 
    public function get_sortable_columns() {
        return $sortable = array(
           'col_form_id'=>'id',
           'col_form_name'=>'title'
        );
     }

     /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb, $_wp_column_headers;
        $screen = get_current_screen();
    
        /* -- Preparing your query -- */
            //$query = "SELECT * FROM {$wpdb->prefix}gf_form";

            $data = array(
                array("id"=>1,"title"=>"My Title 1"),
                array("id"=>2,"title"=>"My Title 2"),
                array("id"=>3,"title"=>"My Title 3"),
                array("id"=>4,"title"=>"My Title 4"),
                array("id"=>5,"title"=>"My Title 5"),
                array("id"=>6,"title"=>"My Title 6"),
                array("id"=>7,"title"=>"My Title 7"),
            );
    
        /* -- Ordering parameters -- */
            //Parameters that are going to be used to order the result
            /*
            $orderby = !empty($_GET["orderby"]) ? mysql_real_escape_string($_GET["orderby"]) : '';
            $order = !empty($_GET["order"]) ? mysql_real_escape_string($_GET["order"]) : 'ASC';
            if(!empty($orderby) & !empty($order)){ $query.=' ORDER BY '.$orderby.' '.$order; }
            */
    
        /* -- Pagination parameters -- */
            //Number of elements in your table?
            //$totalitems = $wpdb->query($query); //return the total number of affected rows
            $totalitems = count($data);
            //How many to display per page?
            $perpage = 5;
            //Which page is this?
            $paged = !empty($_GET["paged"]) ? $_GET["paged"] : 1;
            //Page Number
            
            if(empty($paged) || !is_numeric($paged) || $paged<=0 ){ $paged=1; } //How many pages do we have in total?  //adjust the query to take pagination into account if(!empty($paged) && !empty($perpage)){ $offset=($paged-1)*$perpage; $query.=' LIMIT '.(int)$offset.','.(int)$perpage; } 

            $totalpages = ceil($totalitems/$perpage);

            /* -- Register the pagination -- */ 
            $this->set_pagination_args( array(
                "total_items" => $totalitems,
                "total_pages" => $totalpages,
                "per_page" => $perpage,
            ) );
        //The pagination links are automatically built according to those parameters
    
        /* -- Register the Columns -- */
        $columns = $this->get_columns();
        $_wp_column_headers[$screen->id]=$columns;

        $this->_column_headers = array($columns);
    
        /* -- Fetch the items -- */
        //$this->items = $wpdb->get_results($query);
        $this->items = $data;
    }

    /**
     * Display the rows of records in the table
     * @return string, echo the markup of the rows
     */
    //function display_rows() {

        //Get the records registered in the prepare_items method
        /*
        $records = $this->items;

    
        //Get the columns registered in the get_columns and get_sortable_columns methods
        list( $columns, $hidden ) = $this->get_column_info();
    
        //Loop for each record
        if(!empty($records)) {
            foreach($records as $rec){

                var_dump($rec);
    
            //Open the line
            echo '<tr id="record_'.$rec->id.'">';
            foreach ( $columns as $column_name => $column_display_name ) {

    
                //Style attributes for each col
                $class = "class='$column_name column-$column_name'";
                $style = "";
                if ( in_array( $column_name, $hidden ) ) $style = ' style="display:none;"';
                $attributes = $class . $style;
        
                //edit link
                $editlink  = '/wp-admin/link.php?action=edit&link_id='.(int)$rec->form_id;
        
                //Display the cell
                switch ( $column_name ) {
                    case "col_form_id":  echo '< td '.$attributes.'>'.stripslashes($rec->form_id).'< /td>';   break;
                    case "col_form_title": echo '< td '.$attributes.'>'.stripslashes($rec->link_title).'< /td>'; break;
                }
            }
    
            //Close the line
            echo'< /tr>';
        }}
        */
    //}
 
 }

 
 //Prepare Table of elements
$wp_list_table = new Form_List_Table();
$wp_list_table->prepare_items();
 //Table of elements
$wp_list_table->display(); 



}
?>