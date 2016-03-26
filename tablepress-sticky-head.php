<?php
/**
 * WordPress plugin "TablePress Sticky Head" main file, responsible for initiating the plugin
 *
 * @package TablePress Plugins
 * @author Alexander Heimbuch
 * @version 0.1
 */

/*
Plugin Name: TablePress Extension: Sticky Head
Plugin URI: http://aktivstoff.de/
Description: Extend TablePress tables with the ability to make the head sticky in the viewport
Version: 0.1
Author: Alexander Heimbuch
Author URI: http://aktivstoff.de
Author email: kontakt@aktivstoff.de
Text Domain: tablepress
Domain Path: /i18n
License: GPL 2
*/

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

add_action( 'tablepress_run', array( 'TablePress_Sticky_Head', 'init' ) );

class TablePress_Sticky_Head {

    protected static $slug = 'tablepress-sticky-head';
    protected static $version = '0.1';

    public static function init() {
        add_filter( 'tablepress_shortcode_table_default_shortcode_atts', array( __CLASS__, 'shortcode_table_default_shortcode_atts' ) );
        add_filter( 'tablepress_table_render_options', array( __CLASS__, 'table_render_options' ), 10, 2 );
        add_filter( 'tablepress_table_js_options', array( __CLASS__, 'table_js_options' ), 10, 3 );
        add_filter( 'tablepress_table_output', array( __CLASS__, 'table_output' ), 10, 3 );
    }

    public static function shortcode_table_default_shortcode_atts( $default_atts ) {
        $default_atts['sticky-head'] = '';

        return $default_atts;
    }

    public static function table_render_options( $render_options, $table ) {
        if ( strlen( $render_options['sticky-head'] ) == 0 ) {
            $render_options['sticky-head'] = false;
        } else {
            $render_options['use_datatables'] = true;
            $render_options['table_head'] = true;
            $render_options['sticky-head'] = true;
        }

        return $render_options;
    }

    public static function table_js_options( $js_options, $table_id, $render_options ) {
        if( $render_options['sticky-head'] !== false) {
            $js_options['sticky-head'] = true;
            wp_enqueue_script( self::$slug, plugins_url( 'tablepress-sticky-head.js', __FILE__ ), array( 'tablepress-datatables' ), self::$version, true );
            wp_enqueue_style( self::$slug, plugins_url( 'tablepress-sticky-head.css', __FILE__ ));
        }

        return $js_options;
    }


    public static function table_output( $output, $table, $render_options ) {
        if( !$render_options['sticky-head'] ) {
            return $output;
        }

        return $output . '<script>
            if (window.STICKY_HEAD === undefined) {
                window.STICKY_HEAD = {};
            }

            window.STICKY_HEAD["' . $render_options['html_id'] . '"] = true;
        </script>';
    }
}
?>
