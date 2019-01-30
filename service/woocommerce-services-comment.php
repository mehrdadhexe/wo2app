<?php
if (!defined( 'ABSPATH' )) exit;
class woocommerce_services_comment
{
	
	function __construct()
	{
		  add_action( 'init', array( $this , 'webservice_all' ));
		  add_filter( 'query_vars', array( $this , 'wp_all_query_vars' ));
		  add_action( 'parse_request', array( $this , 'wp_all_parse_request' ));
	}
	function webservice_all()
	{
	    add_rewrite_rule( 'my-api.php$', 'index.php?scomments_woo', 'top' );
	    add_rewrite_rule( 'my-api.php$', 'index.php?gcomments_woo', 'top' );
	}
	function wp_all_query_vars( $query_vars )
	{
	    $query_vars[] = 'scomments_woo';
	    $query_vars[] = 'gcomments_woo';
	    return $query_vars;
	}
	function wp_all_parse_request( &$wp )
	{
	    if ( array_key_exists( 'scomments_woo', $wp->query_vars ) ) {
	        $this->scomments_webservice();
	        exit();
	    }
	    if ( array_key_exists( 'gcomments_woo', $wp->query_vars ) ) {
	        $this->gcomments_webservice();
	        exit();
	    }
	    return;
	}
	function scomments_webservice()
	{				
		if(!empty($_GET["in"]))
		{
			ob_start();
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
	function gcomments_webservice()
	{
			if(!empty($_GET["in"]))
			{
				ob_start();
				
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
				$array_p = array();
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
	
}