<?php
/**
 * Created by PhpStorm.
 * User: Hani
 * Date: 8/29/2018
 * Time: 4:20 PM
 */


if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class mr2app_user_list_report_gps extends WP_List_Table {

    public function __construct() {
        parent::__construct(array(
            'singular' => 'transaction',
            'plural' => 'transactions',
            'ajax' => false,
            'screen' => 'woo-wallet',
        ));
    }

    public function get_columns() {
        return array(
            'id' => __('ID', 'woo-wallet'),
            'username' => __('Username', 'woo-wallet'),
            'name' => __('Name', 'woo-wallet'),
            'email' => __('Email', 'woo-wallet'),
            'actions' => __('Actions', 'woo-wallet'),
        );
    }

    function process_bulk_action() {
        $current_url = admin_url().'admin.php?page=woo2app/gps/index.php&tab=report';
        if( 'delete' == $this->current_action() ) {
            $id = $_REQUEST["uid"];
            global $wpdb;
            $table_name = $wpdb->prefix . "marketer_gps";
            $r = $wpdb->query($wpdb->prepare("DELETE FROM $table_name WHERE mg_uid = %d" , $id));
            if($r){
                ?>
                <p style="color: green;">
                    تعداد
                    <?= $r ?>
                    رکورد ، حذف شد.
                </p>
                <?php
            }else{
                ?>
                <p style="color: red;">
                    هیچ رکوردی پیدا نشد.
                </p>
                <?php
            }
            ?>
            <a href="<?= $current_url ?>" class="button button-primary" > بازگشت</a>
            <?php
            exit();
        }

    }
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items() {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $data = $this->table_data();
        $this->process_bulk_action();
        usort($data, array(&$this, 'sort_data'));
        $perPage = $this->get_items_per_page('users_per_page', 15);
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);
        $this->set_pagination_args(array(
            'total_items' => $totalItems,
            'per_page' => $perPage
        ));
        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns() {
        return array('id');
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns() {
        $sortable_columns = array(
            'username' => array('username', false),
        );
        return $sortable_columns;
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data() {
        $data = array();
        $args = array(
            'blog_id' => $GLOBALS['blog_id'],
            'role' => '',
            'role__in' => array(),
            'role__not_in' => array(),
            'meta_key' => '',
            'meta_value' => '',
            'meta_compare' => '',
            'meta_query' => array(),
            'date_query' => array(),
            'include' => array(),
            'exclude' => array(),
            'orderby' => 'login',
            'order' => 'ASC',
            'offset' => '',
            'search' => isset($_POST['s']) ? '*' . $_POST['s'] . '*' : '',
            'number' => '',
            'count_total' => false,
            'fields' => 'all',
            'who' => '',
        );
        $c_user = wp_get_current_user();
        if($c_user->roles[0] != 'administrator'){
            $args['meta_key'] = '_woo2app_super_viser';
            $args['meta_value'] = $c_user->ID;
        }
        $users = get_users($args);

        foreach ($users as $key => $user) {
            $data[] = array(
                'id' => $user->ID,
                'username' => $user->data->user_login,
                'name' => $user->data->display_name,
                'email' => $user->data->user_email,
                'actions' => ''
            );
        }
        return $data;
    }



    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default($item, $column_name) {
        $current_url = admin_url().'admin.php?page=woo2app/gps/index.php&tab=report';
        switch ($column_name) {
            case 'id':
            case 'username':
            case 'name':
            case 'email':
                return $item[$column_name];
            case 'actions':
                return '<p><a target="_blank" href="'. get_home_url().'/mr2app/export_exel/?uid='. $item['id'].'" class="button btn_report"> گزارش گیری</a> <a class="button btn_delete" href="'.$current_url.'&action=delete&uid='.$item['id'].'"> پاک کردن موقعیت ها</a></p>';
            default:
                return print_r($item, true);
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data($a, $b) {
        // Set defaults
        $orderby = 'username';
        $order = 'asc';
        // If orderby is set, use this as the sort column
        if (!empty($_GET['orderby'])) {
            $orderby = $_GET['orderby'];
        }
        // If order is set use this as the order
        if (!empty($_GET['order'])) {
            $order = $_GET['order'];
        }
        $result = strcmp($a[$orderby], $b[$orderby]);
        if ($order === 'asc') {
            return $result;
        }
        return -$result;
    }

}
$ListTable = new mr2app_user_list_report_gps();
$ListTable->prepare_items();
?>

<h1> گزارش گیری </h1>
<form id="movies-filter" method="post">

    <?php $ListTable->search_box('search', 'search_id');?>
    <?php $ListTable->display() ?>
</form>
<script>
    jQuery(".btn_delete").click(function () {
        if(!confirm(" آیا میخواهید موقعیت های ثبت شده این کاربر را پاک کنید ؟")){
            return false;
        }

    })
</script>