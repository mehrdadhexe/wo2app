<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class woo2app_api_primary_blog
{

	function __construct()
	{
		add_action( 'init',array ( $this , 'wp2appir_regular_all' ));
		add_filter( 'query_vars', array ( $this , 'wp2appir_query_vars' ));
		add_action( 'parse_request', array( $this , 'wp2appir_parse_request' ));
	}
	function wp2appir_regular_all()
	{
		add_rewrite_rule( 'my-api.php$', 'index.php?gpost', 'top' );
		add_rewrite_rule( 'my-api.php$', 'index.php?gpostcat', 'top' );
		add_rewrite_rule( 'my-api.php$', 'index.php?search', 'top' );
		add_rewrite_rule( 'my-api.php$', 'index.php?apost', 'top' );
		add_rewrite_rule( 'my-api.php$', 'index.php?gcomments', 'top' );
		add_rewrite_rule( 'my-api.php$', 'index.php?scomments', 'top' );
	}
	function wp2appir_query_vars($query_vars)
	{
		$query_vars[] = 'gpost';
		$query_vars[] = 'gpostcat';
		$query_vars[] = 'search';
		$query_vars[] = 'apost';
		$query_vars[] = 'scomments';
		$query_vars[] = 'gcomments';
		return $query_vars;
	}
	function wp2appir_parse_request(&$wp)
	{
		if ( array_key_exists( 'gpost', $wp->query_vars ) ) {
			$this->gpost_webservice();
			exit();
		}
		if ( array_key_exists( 'gpostcat', $wp->query_vars ) ) {
			$this->gpostcat_webservice();
			exit();
		}
		if ( array_key_exists( 'search', $wp->query_vars ) ) {
			$this->search_webservice();
			exit();
		}
		if ( array_key_exists( 'apost', $wp->query_vars ) ) {
			$this->apost_webservice();
			exit();
		}
		if ( array_key_exists( 'gcomments', $wp->query_vars ) ) {
			$this->gcomments_webservice();
			exit();
		}
		if ( array_key_exists( 'scomments', $wp->query_vars ) ) {
			$this->scomments_webservice();
			exit();
		}
		return;
	}
	function gpost_webservice()
	{
		ob_start();
		if(!empty($_GET["in"]))
		{
			$in = $_GET['in'];
			$slashless = stripcslashes($in);
			$url_json = urldecode($slashless);
			$json = (array)  json_decode($url_json);
			$count =(int) $json["Count"];
			$last_count =(int) $json["LastCount"];

			$num = $count + $last_count;
			$cat_selected = get_option('wp2app_cats');
			$flag_all = 0;
			if($cat_selected != ""){
				$cats1 = explode(",",$cat_selected);
				foreach ($cats1 as $cat) {
					if($cat == 0){
						$flag_all = 1;
					}
				}
			}else{
				$flag_all = 1;
			}

			$post_types = get_option("wp2app_post_types");
			$ar = json_decode($post_types,true);
			$array_types = array();
			if ($post_types != "") {
				foreach ($ar as $key) {
					foreach ($key as $k => $v) {
						if($k == 'product') continue;
						$array_types[] = $k;
					}
				}
			}else{
				$array_types = array( 'post' );
			}
			if($cat_selected != "" && $cat_selected != null && !empty($cat_selected) && $flag_all == 0){
				if($post_types == ""){
					$args = array(
						'numberposts' => $num ,
						'post_type' => $array_types,
						'tax_query' => array(
							array(
								'taxonomy' => 'category',
								'field'    => 'term_id',
								'terms'    => $cats1,
							),
						),
					);
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
								'field'    => 'term_id',
								'terms'    => $cats1,
							);
						}
					}

					$args = array(
						'numberposts' => $num ,
						'post_type' => $array_types,
						'tax_query' => $ar1
					);

				}

				$posts = get_posts( $args );
			}else{
				$args = array(
					'numberposts' => $num,
					'post_type' => $array_types ,
				);
				$posts = get_posts( $args );
			}
			$i = 1;
			foreach($posts as $_post){
				if($i>$last_count){
					if ( has_post_thumbnail( $_post->ID ) ) {
						$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $_post->ID ), 'large' );
						$pic_post = $large_image_url[0];
					}
					$wp2app_cf = get_option("wp2app_custom_fields");
					if (!is_null($wp2app_cf) || $wp2app_cf != "") {
						$wp2app_cf = $wp2app_cf["wp2pp_cf"];
						foreach ($wp2app_cf as $key) {
							$value = get_post_meta($_post->ID,$key["filed"],true);
							$label = $key["label"];
							if( strlen(trim($value)) > 0 ){
								$array_cf[] = array("value" => $value , "label" => $label);
							}
						}
					}

					if ($post_types != "") {
						foreach ($ar as $key) {
							foreach ($key as $k => $v) {
								foreach ($v as $x) {
									if($_post->post_type == $k){
										$cat_post = $this->wp_get_post_categories_hami($_post->ID , $args , $x);
									}
								}
							}
						}
					}else{
						$cat_post = $this->wp_get_post_categories_hami($_post->ID , $args , "category");
					}
					$cats = "";
					foreach($cat_post as $cat){
						$cats = $cats.$cat . ",";
					}

					$recent_author = get_user_by( 'ID', $_post->post_author );
					$author_display_name = $recent_author->display_name;
					$content = apply_filters("the_content" , $_post->post_content);
					$array['posts'][] = array(
						'id' => $_post->ID,
						'Post_author' => $author_display_name ,
						'Post_date' => $_post->post_date,
						'Post_content' => $content,
						'Post_title' => $_post->post_title,
						'Comment_count' => $_post->comment_count,
						'Comment_status' => $_post->comment_status,
						'Post_pic' => $pic_post,
						'cat' => $cats,
						'Post_link' => get_permalink($_post->ID),
						'Custom_fields' => $array_cf
					);
				}
				$i++;
				$cats="";
				$pic_post ="";
				$arr = "";
				$array_cf = "";
			}
			ob_clean();
			echo json_encode($array);
		}
		else
		{
			echo "not found page";
		}

	}
	function gpostcat_webservice()
	{
		ob_start();
		if(!empty($_GET["in"]))
		{
			$in = $_GET['in'];
			$slashless = stripcslashes($in);
			$url_json = urldecode($slashless);
			$json = (array)  json_decode($url_json);

			$cat = (string) $json["Cat"];
			$count =(int) $json["Count"];
			$last_count =(int) $json["LastCount"];
			$num = $count + $last_count;
			$cats1 = explode(",",$cat);
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

			if($post_types == ""){

				$args = array(
					'numberposts' => $num ,
					'post_type' => $array_types,
					'tax_query' => array(
						array(
							'taxonomy' => 'category',
							'field'    => 'term_id',
							'terms'    => $cats1,
						),
					),
				);
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
							'field'    => 'term_id',
							'terms'    => $cats1,
						);
					}
				}

				$args = array(
					'numberposts' => $num ,
					'post_type' => $array_types,
					'tax_query' => $ar1
				);

			}

			$posts = get_posts( $args );
			$i = 1;
			//-------------------------------------
			foreach($posts as $_post){

				if($i>$last_count){

					if ( has_post_thumbnail( $_post->ID ) ) {
						$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $_post->ID ), 'large' );
						$pic_post = $large_image_url[0];
					}

					if ($post_types != "") {
						foreach ($ar as $key) {
							foreach ($key as $k => $v) {
								foreach ($v as $x) {
									if($_post->post_type == $k){
										$cat_post = $this->wp_get_post_categories_hami($_post->ID , $args , $x);
									}
								}
							}
						}
					}else{
						$cat_post = $this->wp_get_post_categories_hami($_post->ID , $args , "category");
					}
					$cats = "";
					foreach($cat_post as $cat){
						$cats = $cats.$cat . ",";
					}

					$wp2app_cf = get_option("wp2app_custom_fields");
					if (!is_null($wp2app_cf) || $wp2app_cf != "") {
						$wp2app_cf = $wp2app_cf["wp2pp_cf"];
						foreach ($wp2app_cf as $key) {
							$value = get_post_meta($_post->ID,$key["filed"],true);
							$label = $key["label"];
							if( strlen(trim($value)) > 0 ){
								$array_cf[] = array("value" => $value , "label" => $label);
							}
						}
					}
					$recent_author = get_user_by( 'ID',  $_post->post_author );
					$author_display_name = $recent_author->display_name;
					$content = apply_filters("the_content" ,$_post->post_content);
					$array['posts'][] = array(
						'id' => $_post->ID,
						'Post_author' => $author_display_name,
						'Post_date' => $_post->post_date,
						'Post_content' => $content,
						'Post_title' => $_post->post_title,
						'Comment_count' => $_post->comment_count,
						'Comment_status' => $_post->comment_status,
						'Post_pic' => $pic_post,
						'cat' => $cats,
						'Post_link' => get_permalink($_post->ID),
						'Custom_fields' => $array_cf
					);

				}
				$i++;
				$cats="";
				$pic_post="";
				$array_cf = "";
			}

			ob_clean();
			echo json_encode($array);

		}
		else
		{
			echo "not found page";
		}
	}
	function Search_webservice()
	{
		ob_start();
		if(!empty($_GET["in"]))
		{
			$in = $_GET['in'];
			$slashless = stripcslashes($in);
			$url_json = urldecode($slashless);
			$json = (array)  json_decode($url_json);
			$search =  $json["search"];
			global $wpdb;
			$cat_selected = get_option('wp2app_cats');
			$flag_all = 0;
			if($cat_selected != ""){
				$cats1 = explode(",",$cat_selected);
				foreach ($cats1 as $cat) {
					if($cat == 0){
						$flag_all = 1;
					}
				}
			}else{
				$flag_all = 1;
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
			if($cat_selected != "" && $cat_selected != null && !empty($cat_selected) && $flag_all == 0){
				if($post_types == ""){
					$args = array(
						'numberposts' => $num ,
						'post_type' => $array_types,
						"s" => $search ,
						'tax_query' => array(
							array(
								'taxonomy' => 'category',
								'field'    => 'term_id',
								'terms'    => $cats1,
							),
						),
					);
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
								'field'    => 'term_id',
								'terms'    => $cats1,
							);
						}
					}

					$args = array(
						'numberposts' => -1 ,
						'post_type' => $array_types,
						"s" => $search ,
						'tax_query' => $ar1
					);

				}

				$posts = get_posts( $args );
			}else{
				$args = array(
					'numberposts' => -1,
					"s" => $search ,
					'post_type' => $array_types ,
				);
				$posts = get_posts( $args );
			}
			//$args = array( 'numberposts' => -1,"s" => $search , "post_type" => $array_types );
			//$posts = get_posts( $args );
			if(!empty($posts)){
				$i = 1;
				foreach($posts as $_post){
					if ( has_post_thumbnail( $_post->ID ) ) {
						$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $_post->ID ), 'large' );
						$pic_post = $large_image_url[0];
					}
					if ($post_types != "") {
						foreach ($ar as $key) {
							foreach ($key as $k => $v) {
								foreach ($v as $x) {
									if($_post->post_type == $k){
										$cat_post = $this->wp_get_post_categories_hami($_post->ID , $args , $x);
									}
								}
							}
						}
					}else{
						$cat_post = $this->wp_get_post_categories_hami($_post->ID , $args , "category");
					}
					$cats = "";
					foreach($cat_post as $cat){
						$cats = $cats.$cat . ",";
					}

					$wp2app_cf = get_option("wp2app_custom_fields");
					if (!is_null($wp2app_cf) || $wp2app_cf != "") {
						$wp2app_cf = $wp2app_cf["wp2pp_cf"];
						foreach ($wp2app_cf as $key) {
							$value = get_post_meta($_post->ID,$key["filed"],true);
							$label = $key["label"];
							if( strlen(trim($value)) > 0 ){
								$array_cf[] = array("value" => $value , "label" => $label);
							}
						}
					}
					$recent_author = get_user_by( 'ID',  $_post->post_author );
					$author_display_name = $recent_author->display_name;
					$content = apply_filters("the_content" , $_post->post_content);
					$array['posts'][] = array(
						'id' => $_post->ID,
						'Post_author' => $author_display_name,
						'Post_date' => $_post->post_date,
						'Post_content' => $content,
						'Post_title' => $_post->post_title,
						'Comment_count' => $_post->comment_count,
						'Comment_status' => $_post->comment_status,
						'Post_pic' => $pic_post,
						'cat' => $cats,
						'Post_link' => get_permalink($_post->ID),
						'Custom_fields' => $array_cf
					);
					$i++;
					$cats="";
					$pic_post="";
					$array_cf = "";
				}
				ob_clean();
				echo json_encode($array);
			}else{
				echo 0;
			}
		}
		else
		{
			echo "not found page";
		}

	}
	function apost_webservice()
	{
		ob_start();
		if(!empty($_GET["in"]))
		{
			$in = $_GET['in'];
			$slashless = stripcslashes($in);
			$url_json = urldecode($slashless);
			$json = (array)  json_decode($url_json);
			$post_id =  $json["post_id"];

			$post = get_post( $post_id );

			$wp2app_cf = get_option("wp2app_custom_fields");
			if (!is_null($wp2app_cf) || $wp2app_cf != "") {
				$wp2app_cf = $wp2app_cf["wp2pp_cf"];
				foreach ($wp2app_cf as $key) {
					$value = get_post_meta($post_id,$key["filed"],true);
					$label = $key["label"];
					if( strlen(trim($value)) > 0 ){
						$array_cf[] = array("value" => $value , "label" => $label);
					}
				}
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

			if ( has_post_thumbnail( $post_id ) ) {
				$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id ), 'large' );
				$pic_post = $large_image_url[0];
			}

			if ($post_types != "") {
				foreach ($ar as $key) {
					foreach ($key as $k => $v) {
						foreach ($v as $x) {
							if($post->post_type == $k){

								$cat_post = $this->wp_get_post_categories_hami($post_id , $args , $x);

							}
						}
					}
				}
			}else{
				$cat_post = $this->wp_get_post_categories_hami($post_id , $args , "category");
			}
			$cats = "";
			foreach($cat_post as $cat){
				$cats = $cats.$cat . ",";
			}


			$author = $author_display_name;
			$date = $post->post_date;
			$content = apply_filters("the_content" , $post->post_content);
			$title = $post->post_title;
			$comment_count = $post->comment_count;
			$comment_status = $post->comment_status;
			$post_pic = $pic_post;
			$cat = $cats;

			$recent_author = get_user_by( 'ID',  $post->post_author );
			$author_display_name = $recent_author->display_name;
			$array['post'][] = array(
				'id' => $post->ID,
				'Post_author' => $author_display_name,
				'Post_date' => $post->post_date,
				'Post_content' => $content,
				'Post_title' => $title,
				'Comment_count' => $comment_count,
				'Comment_status' => $comment_status,
				'Post_pic' => $post_pic,
				'cat' => $cat,
				'Post_link' => get_permalink($post->ID),
				'Custom_fields' => $array_cf
			);
//ob_clean();
			echo json_encode($array);
		}
		else{
			return 0;
		}

	}
	public function wp_get_post_categories_hami( $post_id = 0, $args = array() ,$my_tax) {
		$post_id = (int) $post_id;

		$defaults = array('fields' => 'ids');
		$args = wp_parse_args( $args, $defaults );

		$cats = wp_get_object_terms($post_id, $my_tax , $args);
		return $cats;
	}

	function gcomments_webservice()
	{
		ob_start();
		if(!empty($_GET["in"]))
		{


			$in = $_GET['in'];
			$slashless = stripcslashes($in);
			$url_json = urldecode($slashless);

			$json = (array)  json_decode($url_json);
			$count =(int) $json["Count"];
			$last_count =(int) $json["LastCount"];
			$post = (int) $json["Post_id"];

			$num=$count+$last_count;
			$args = array(
				'number' =>$num,
				'post_id' => $post
			);
			$records = get_comments($args );

			$i=1;
			foreach($records as $record)
			{
				if ($record->comment_approved == 1) {
					if($i>$last_count){

						$array_p['comments'][] = array(
							'Comment_id'=>$record->comment_ID,
							'Comment_author'=>$record->comment_author,
							'Comment_date'=>$record->comment_date,
							'Comment_content'=>$record->comment_content,
							'Comment_parent'=>$record->comment_parent
						);
					}
					$i++;
				}
			}
			ob_clean();
			echo json_encode($array_p);
		}
		else
		{
			echo "not found page";
		}
	}

	function scomments_webservice()
	{
		ob_start();
		if(!empty($_GET["in"]))
		{
			global $wpdb;
			$table_comments = $wpdb->prefix . 'comments';
			$in = $_GET['in'];
			$slashless = stripcslashes($in);
			$url_json = urldecode($slashless);
			$json = (array)  json_decode($url_json);

			$comment_author       = ! isset( $json['comment_author'] )       ? '' : $json['comment_author'];
			$comment_author_email = ! isset( $json['comment_author_email'] ) ? '' : $json['comment_author_email'];
			$comment_author_url   = ! isset( $json['comment_author_url'] )   ? '' : $json['comment_author_url'];
			$comment_author_IP    = ! isset( $json['comment_author_IP'] )    ? '' : $json['comment_author_IP'];
			$comment_date     = ! isset( $json['comment_date'] )     ? current_time( 'mysql' )            : $json['comment_date'];
			$comment_date_gmt = ! isset( $json['comment_date_gmt'] ) ? get_gmt_from_date( $comment_date ) : $json['comment_date_gmt'];
			$comment_post_id  = ! isset( $json['comment_post_id'] )  ? '' : $json['comment_post_id'];
			$comment_content  = ! isset( $json['comment_content'] )  ? '' : $json['comment_content'];
			$comment_karma    = ! isset( $json['comment_karma'] )    ? 0  : $json['comment_karma'];
			$comment_approved = ! isset( $json['comment_approved'] ) ? 0  : $json['comment_approved'];
			$comment_agent    = ! isset( $json['comment_agent'] )    ? '' : $json['comment_agent'];
			$comment_type     = ! isset( $json['comment_type'] )     ? '' : $json['comment_type'];
			$comment_parent   = ! isset( $json['comment_parent'] )   ? 0  : $json['comment_parent'];

			$user_id  = ! isset( $json['user_id'] ) ? 0 : $json['user_id'];

			$compacted = compact( 'comment_post_id', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_author_IP', 'comment_date', 'comment_date_gmt', 'comment_content', 'comment_karma', 'comment_approved', 'comment_agent', 'comment_type', 'comment_parent', 'user_id' );
			if ( ! $wpdb->insert( $wpdb->comments, $compacted ) ) {
				return false;
			}else{
				ob_clean();
				echo json_encode(array("result"=>1));
			}
			$id = (int) $wpdb->insert_id;
			if ( $comment_approved == 1 ) {
				wp_update_comment_count( $comment_post_id );
			}
			$comment = get_comment( $id );
			// If metadata is provided, store it.
			if ( isset( $commentdata['comment_meta'] ) && is_array( $commentdata['comment_meta'] ) ) {
				foreach ( $commentdata['comment_meta'] as $meta_key => $meta_value ) {
					add_comment_meta( $comment->comment_ID, $meta_key, $meta_value, true );
				}
			}

		}
		else
		{
			echo "not found page";
		}

	}
}