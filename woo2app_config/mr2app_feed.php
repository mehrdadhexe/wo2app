<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class mr2app_feed {
	function __construct() {
		add_action('wp_dashboard_setup', array($this , 'mr2app_feed_news'));
		add_filter( 'wp_feed_cache_transient_lifetime', array($this , 'mr2app_feed_filter'));
	}

	function mr2app_feed_news() {
		wp_add_dashboard_widget( 'lawyerist_dashboard_widget',
			'خبرهای جدید مستر2 اپ',
			array($this , 'mr2app_feed_news_design') );
	}

	function mr2app_feed_news_design() {
		$rss = fetch_feed( "http://mr2app.com/blog/feed/?count=5" );
		if ( is_wp_error($rss) ) {
			if ( is_admin() || current_user_can('manage_options') ) {
				echo '<p>';
				printf(__('<strong>خطای RSS</strong>: %s'), $rss->get_error_message());
				echo '</p>';
			}
			return;
		}
		if ( !$rss->get_item_quantity() ) {
			echo '<p>ظاهرا، هیچ خبری در مستر 2 اپ وجود ندارد!</p></br>';
			echo "اپلیکیشن ساز سایت های وردپرس برای اولین با در ایران <a href='http://www.mr2app.com' target='_blank'>مستر 2 اپ</a>";
			$rss->__destruct();
			unset($rss);
			return;
		}

		echo "<ul>\n";
		if ( !isset($items) )
			$items = 15;
		foreach ( $rss->get_items(0, $items) as $item ) {

			$publisher = '';

			$site_link = '';

			$link = '';

			$content = '';

			$date = '';

			$link = esc_url( strip_tags( $item->get_link() ) );

			$title = esc_html( $item->get_title() );

			$content = $item->get_content();

			$content = wp_html_excerpt($content, 250) . ' ...';

			echo "<li><a target='_blank' class='rsswidget' href='$link'>$title</a>\n<div class='rssSummary'>$content</div>\n";

			echo "اپلیکیشن ساز سایت های وردپرس برای اولین با در ایران <a href='http://www.mr2app.com' target='_blank'>مستر 2 اپ</a>";

		}

		echo "</ul>\n";

		$rss->__destruct();
		unset($rss);
	}
	function mr2app_feed_filter()
	{
		return 5;
	}

}