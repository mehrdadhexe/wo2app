<?php
if (!defined( 'ABSPATH' )) exit;
class woo2app_config_menu
{
    function __construct(){
        $this->config_woomenu();
    }
    function config_woomenu(){
        add_menu_page( __( 'اپلیکیشن نمکدون', 'اپلیکیشن نمکدون' ),
            __( 'اپلیکیشن نمکدون', 'اپلیکیشن نمکدون' ),
            'manage_options',
            'woo2app/woo2app_design/woo2app_show_design.php',
            '',
            plugins_url(plugin_basename(dirname(__FILE__))).'/../files/img/smart-phone.png' );
        add_submenu_page( 'woo2app/woo2app_design/woo2app_show_design.php',
            __( 'تنظیمات پوسته', 'woo2app' ),
            __( 'تنظیمات پوسته', 'woo2app' ),
            'manage_options',
            'woo2app/woo2app_design/woo2app_show_design.php');
        add_submenu_page( 'woo2app/woo2app_design/woo2app_show_design.php',
            __( 'تنظیمات اصلی', 'woo2app' ),
            __( 'تنظیمات اصلی', 'woo2app' ),
            'manage_options',
            'woo2app/woo2app_design/woo2app_primary_setting.php');
        add_submenu_page( 'woo2app/woo2app_design/woo2app_show_design.php',
            __( 'ارسال نوتیفیکیشن', 'woo2app' ),
            __( 'ارسال نوتیفیکیشن', 'woo2app' ),
            'manage_options',
            'woo2app/woo2app_design/woo2app_send_notif.php');
        add_submenu_page( 'woo2app/woo2app_design/woo2app_show_design.php',
            __( 'وبلاگ', 'woo2app' ),
            __( 'وبلاگ', 'woo2app' ),
            'manage_options',
            'woo2app/woo2app_blog/index.php');
        add_submenu_page( 'woo2app/woo2app_design/woo2app_show_design.php',
            __( 'پیشنهاد شگفت انگیز', 'woo2app' ),
            __( 'پیشنهاد شگفت انگیز', 'woo2app' ),
            'manage_options',
            'woo2app/woo2app_design/woo2app_shegeft_angiz.php');

        add_submenu_page( 'woo2app/woo2app_design/woo2app_show_design.php', __( 'تنظیمات حساب کاربری ', 'woo2app' ),__( 'تنظیمات حساب کاربری', 'woo2app' ), 'manage_options', 'woo2app/register/register.php');
        add_submenu_page( 'woo2app/woo2app_design/woo2app_show_design.php', __( ' فیلد ها ثبت سفارش ', 'woo2app' ),__( '  فیلد ها ثبت سفارش', 'woo2app' ), 'manage_options', 'woo2app/order_form/fields.php');
        add_submenu_page( 'woo2app/woo2app_design/woo2app_show_design.php', __( 'چندفروشگاهی', 'woo2app' ), __( 'چندفروشگاهی', 'woo2app' ),'manage_options','woo2app/woo2app_design/shop.php');
        add_submenu_page( 'woo2app/woo2app_design/woo2app_show_design.php', __( 'دسته بندی اپ', 'woo2app' ), __( 'دسته بندی اپ', 'woo2app' ),'manage_options','woo2app/category/category.php');
        add_submenu_page( 'woo2app/woo2app_design/woo2app_show_design.php', __( ' تخفیف کاربر', 'woo2app' ), __( ' تخفیف کاربر', 'woo2app' ),'manage_options','woo2app/discount/index.php');
//        add_submenu_page( 'woo2app/woo2app_design/woo2app_show_design.php', __( 'gps', 'woo2app' ), __( 'gps', 'woo2app' ),'manage_woocommerce','woo2app/gps/index.php');
        add_submenu_page( 'woo2app/woo2app_design/woo2app_show_design.php', __( ' همکاری در فروش', 'woo2app' ), __( ' همکاری در فروش', 'woo2app' ),'manage_options','woo2app/inviter/setting_inviter.php');
    }
}