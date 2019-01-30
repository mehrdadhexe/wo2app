<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class woo2app_api_default_blog
{

	function __construct()
	{
		add_action( 'init',array ( $this , 'wp2appir_regular_all' ));
		add_filter( 'query_vars', array ( $this , 'wp2appir_query_vars' ));
		add_action( 'parse_request', array( $this , 'wp2appir_parse_request' ));
	}
	function wp2appir_regular_all()
	{
		add_rewrite_rule( 'my-api.php$', 'index.php?setting', 'top' );
		add_rewrite_rule( 'my-api.php$', 'index.php?cats', 'top' );
	}
	function wp2appir_query_vars($query_vars)
	{
		$query_vars[] = 'setting';
		$query_vars[] = 'cats';
		return $query_vars;
	}
	function wp2appir_parse_request(&$wp)
	{
		if ( array_key_exists( 'setting', $wp->query_vars ) ) {
			$this->setting_webservice();
			exit();
		}
		if ( array_key_exists( 'cats', $wp->query_vars ) ) {
			$this->cats_webservice();
			exit();
		}

		return;
	}
	function setting_webservice()
	{
		ob_start();
		global $wpdb;
		$table_name = $wpdb->prefix . 'hami_set';
		$records = $wpdb->get_results("select * from $table_name");
		foreach ($records as $record) {
			$array[] = array(
				'name' => $record->name,
				'value' => $record->value );
		}
		$main_page = $this->get_mainpage();
		$slider = $this->get_slider();
		//$array['app_set']['wp2app_mainpage'] =  $main_page;
		//$array['app_set']['wp2app_slider'] = $slider;
		ob_clean();
		//var_dump($main_page);
		echo json_encode(array('app_set' => $array , 'wp2app_mainpage' => $main_page , 'wp2app_slider' => $slider));
	}
	public function cats_webservice()
	{
		ob_start();
		$cat_selected = get_option('wp2app_cats');
		$flag_all = 0;
		$cats1 = explode(",",$cat_selected);
		if($cat_selected != ""){
			foreach ($cats1 as $cat) {
				if($cat == 0){
					$flag_all = 1;
				}
			}
		}else{
			$flag_all = 1 ;
		}

		$post_types = get_option("wp2app_post_types");
		$ar = json_decode($post_types,true);
		$array_types = array();
		if ($post_types != "") {
			foreach ($ar as $key) {
				foreach ($key as $k => $v) {
					$array_types[] = $k;
				}
			}
		}else{
			$array_types = array( 'post' );
		}

		if($cat_selected != "" && $cat_selected != null && !empty($cat_selected) && $flag_all == 0)
		{

			$cats1 = explode(",",$cat_selected);

			foreach ($cats1 as $cat) {
				if ($post_types != "") {
					foreach ($ar as $key) {
						foreach ($key as $k => $v) {
							foreach ($v as $x) {
								//if($_post->post_type == $k){
								$record = $this->get_category_hami($cat,OBJECT,'row', $x);
								//}
							}
						}
					}
				}else{
					$record = $this->get_category_hami($cat,OBJECT,'row',"category");
				}

				$array['cats'][] = array(
					'term_id' => $record->term_id,
					'name' => $record->name,
					'description' => $record->description,
					'parent' => $record->parent,
					'count' => $record->count,
				);

			}
		}else{

			if($post_types == ""){
				/*$args = array(
					  'hide_empty' => 0 ,
					  'numberposts' => $num ,
					  'post_type' => $array_types,
					  'tax_query' => array(
							array(
								'taxonomy' => 'category',
								//'field'    => 'term_id',
								//'terms'    => $cats1,
							),
					   ),
				);  */
				$ar2 = "category";
			}else{
				foreach ($ar as $key) {
					foreach ($key as $k => $v) {
						//$array_types[] = $k;
						$tax_query[] = $v;
					}
				}
				$ar1 = array( 'relation' => 'OR' );
				foreach ($tax_query as $key) {
					foreach ($key as $k) {
						$ar1[] = array(
							'taxonomy' => $k,
							//'field'    => 'term_id',
							//'terms'    => $cats1,
						);
						$ar2[] = $k;
					}
				}

				$args = array(
					'hide_empty' => 0 ,
					'numberposts' => $num ,
					'post_type' => $array_types,
					'tax_query' => $ar1
				);

			}

			$records = get_terms( $ar2 );

			/*$args1 =	array('hide_empty' => 0 ,
					'taxonomy' => 'product_cat'
					  );
			$records1 = get_categories($args1);

			$args =	array('hide_empty' => 0 ,
					'taxonomy' => 'category'
					  );
			if (!$records1["errors"]){
				$records = array_merge($records1 , get_categories($args) );
			}else{
				$records = get_categories($args);
			}*/

			foreach ($records as $record) {
				$array['cats'][] = array(
					'term_id' => $record->term_id,
					'name' => $record->name,
					'description' => $record->description,
					'parent' => $record->parent,
					'count' => $record->count,
				);
			}
		}
		ob_clean();
		echo json_encode($array);
	}
	function get_category_hami( $category, $output = OBJECT, $filter = 'raw', $cat_hami )
	{
		$category = get_term( $category, $cat_hami, $output, $filter );
		if ( is_wp_error( $category ) )
			return $category;

		_make_cat_compat( $category );

		return $category;
	}

}