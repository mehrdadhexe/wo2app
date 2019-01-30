<?php
/**
 * Created by PhpStorm.
 * User: Hani
 * Date: 8/21/2018
 * Time: 4:51 PM
 */
class class_gps {
    function __construct() {
        add_action( 'init', array( $this, 'mr2app_gps_api_regular_url' ) );
        add_filter( 'query_vars', array( $this, 'mr2app_gps_api_query_vars' ) );
        add_action( 'parse_request', array( $this, 'mr2app_gps_api_parse_request' ) );
    }
    function mr2app_gps_api_regular_url() {
        add_rewrite_rule( '^mr2app/merketer_gps$', 'index.php?merketer_gps=$matches[1]', 'top' ); //=$matches[1]
        add_rewrite_rule( '^mr2app/export_exel$', 'index.php?export_exel=$matches[1]', 'top' ); //=$matches[1]
        flush_rewrite_rules();
    }
    function mr2app_gps_api_query_vars( $query_vars ) {
        $query_vars[] = 'merketer_gps';
        $query_vars[] = 'export_exel';
        return $query_vars;
    }
    function mr2app_gps_api_parse_request( &$wp ) {
        if ( array_key_exists( 'merketer_gps', $wp->query_vars ) ) {
            $this->marketer_gps();
            exit();
        }
        if ( array_key_exists( 'export_exel', $wp->query_vars ) ) {
            $this->export_exel();
            exit();
        }
        return;
    }

    public function marketer_gps() {
        header('Content-Type: application/json; charset=utf-8');
        $result = array(
            'status' => false,
        );
        if(isset($_POST['in'])) {
            global $wpdb;
            $in = $_POST['in'];
            $slashless = stripcslashes($in);
            $url_json = urldecode($slashless);
            $json = (array) json_decode($url_json);
            //print_r($json['latitude']);return;
            $table_name = $wpdb->prefix . 'marketer_gps';
            $r = $wpdb->insert(
                $table_name,
                array(
                    'mg_uid' => $json['uid'],
                    'mg_time' => time(),
                    'mg_latitude' => $json['latitude'],
                    'mg_longitude' => $json['longitude'],
                )
            );
            if($r) $result['status'] = true;
        }
        echo json_encode($result);
    }

    public function export_exel(){

        if(isset($_GET['uid'])) {
            $id = $_REQUEST["uid"];
            global $wpdb;
            $table_name = $wpdb->prefix . "marketer_gps";
            $r = $wpdb->get_results($wpdb->prepare("select * FROM $table_name WHERE mg_uid = %d" , $id));
            $file_ending = "xls";
            $filename = "report_".$_GET["uid"];         //File Name
            $user = get_user_by('id' , $_GET['uid']);
            $schema_insert = "<table >";
            $schema_insert .= "<tr>";
            $schema_insert .= "<th> report : </th>";
            $schema_insert .= "</tr>";
            $schema_insert .= "<tr>";
            $schema_insert .= "<th> username : </th>";
            $schema_insert .= "<th>".$user->user_login ."</th>";
            $schema_insert .= "</tr>";
            $schema_insert .= "<tr>";
            $schema_insert .= "<th> email : </th>";
            $schema_insert .= "<th>".$user->user_email ."</th>";
            $schema_insert .= "</tr>";
            $schema_insert .= "<tr>";
            $schema_insert .= "<th> ----- </th>";
            $schema_insert .= "<th> ----- </th>";
            $schema_insert .= "<th> ----- </th>";
            $schema_insert .= "<th> ----- </th>";
            $schema_insert .= "</tr>";
            $schema_insert .= "<tr>";
            $schema_insert .= "<th> UID : </th>";
            $schema_insert .= "<th> TIME </th>";
            $schema_insert .= "<th> LATITUDE </th>";
            $schema_insert .= "<th> LONGITUDE </th>";
            $schema_insert .= "</tr>";
            $schema_insert .= "<tr>";
            $schema_insert .= "<th> ----- </th>";
            $schema_insert .= "<th> ----- </th>";
            $schema_insert .= "<th> ----- </th>";
            $schema_insert .= "<th> ----- </th>";
            $schema_insert .= "</tr>";
            foreach ($r as $rr){
                $schema_insert .= "<tr dir='ltr'>";
                $schema_insert.= '<th>'.$rr->mg_uid.'</th>';
                $schema_insert.= '<th>'. $Persian_Number = str_replace(
                            array('۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'),
                            array('0','1','2','3','4','5','6','7','8','9'),
                            jdate('y/m/d',$rr->mg_time)
                        )     .'</th>';
                $schema_insert.= '<th>'.$rr->mg_latitude.'</th>';
                $schema_insert.= '<th>'.$rr->mg_longitude.'</th>';
                $schema_insert .= "</tr>";
            }
            $schema_insert .= "</table>";
            header("Content-type: application/vnd.ms-excel;charset=UTF-8");
            header("Content-Disposition: attachment; filename=$filename.xls");
            echo $schema_insert;
        }
    }

}