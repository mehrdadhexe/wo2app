<?phpif (!defined( 'ABSPATH' )) exit;class config_table_woo2app{    public function __construct() {        //-----------------begin create table hami appost---------------------------        $this->create_tbl_woo2app_slider();        //-----------------end create table hami appost-----------------------------        //-----------------begin create table hami appstatic------------------------        $this->create_tbl_woo2app_setting();        //-----------------end create table hami appstatic--------------------------        //-----------------begin create table hami slider------------------------        $this->create_tbl_woo2app_mainpage();        //-----------------end create table hami slider--------------------------        $this->create_tbl_woo2app_menu();        $this->create_tbl_woo2app_nazar();    }    //--------------begin function for table slider----------------------------------    function create_tbl_woo2app_nazar(){        global $wpdb;        $charset_collate = $wpdb->get_charset_collate();        $table_name = $wpdb->prefix . 'woo2app_nazar';        $sql = "CREATE TABLE $table_name (		id bigint(20) NOT NULL AUTO_INCREMENT,		PRIMARY KEY (id),		title TEXT NOT NULL ,		type tinyint(4) NOT NULL,		value TEXT NOT NULL,		disable tinyint(4) NOT NULL	) $charset_collate;";        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );        dbDelta( $sql );        add_option( 'woo2app_version', '1.3' );    }    function create_tbl_woo2app_slider(){        global $wpdb;        $charset_collate = $wpdb->get_charset_collate();        $table_name = $wpdb->prefix . 'woo2app_slider';        $sql = "CREATE TABLE $table_name (		sl_id bigint(20) NOT NULL AUTO_INCREMENT,		PRIMARY KEY (sl_id),		sl_title varchar(100) NOT NULL ,		sl_type tinyint(4) NOT NULL,		sl_value varchar(150) NOT NULL,		sl_pic TEXT NOT NULL	) $charset_collate;";        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );        dbDelta( $sql );        add_option( 'woo2app_version', '1.3' );    }    //--------------end function for table slider----------------------------------    //--------------begin function for table setting----------------------------------    function create_tbl_woo2app_setting(){        global $wpdb;        $charset_collate = $wpdb->get_charset_collate();        $table_name = $wpdb->prefix . 'woo2app_setting';        $sql = "CREATE TABLE $table_name (		st_id bigint(20) NOT NULL AUTO_INCREMENT,		PRIMARY KEY (st_id),		st_name varchar(100) NOT NULL,		st_value varchar(200) NOT NULL,		st_desc	varchar(200) NOT NULL	) $charset_collate;";        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );        dbDelta( $sql );        add_option( 'woo2app_version', '1.3' );    }    //--------------end function for table setting----------------------------------    //--------------begin function for table hami slider------------------------------    function create_tbl_woo2app_mainpage(){        global $wpdb;        $charset_collate = $wpdb->get_charset_collate();        $table_name = $wpdb->prefix . 'woo2app_mainpage';        $sql = "CREATE TABLE $table_name (			mp_id bigint(20) NOT NULL AUTO_INCREMENT,			PRIMARY key(mp_id),			mp_title varchar(100) NOT NULL,			mp_type tinyint(4) NOT NULL ,			mp_value text NOT NULL ,			mp_showtype tinyint(4) NOT NULL,			mp_pic text NOT NULL,			mp_order bigint(20),			mp_sort text NULL 		) $charset_collate;";        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );        dbDelta( $sql );        add_option( 'woo2app_version', '1.3' );    }    //--------------end function for table hami slider------------------------------    function create_tbl_woo2app_menu(){        global $wpdb;        $charset_collate = $wpdb->get_charset_collate();        $table_name = $wpdb->prefix . 'woo2app_menu';        $sql = "CREATE TABLE $table_name (			menu_id bigint(20) NOT NULL AUTO_INCREMENT,			PRIMARY key(menu_id),			menu_title varchar(100) NOT NULL,			menu_action tinyint(4) NOT NULL ,			menu_value text NOT NULL ,			menu_pic text NOT NULL,			menu_menu tinyint(4) NOT NULL ,			menu_order bigint(20)		) $charset_collate;";        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );        dbDelta( $sql );        add_option( 'woo2app_version', '1.3' );    }}