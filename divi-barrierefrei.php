<?php
/*
 * Plugin Name:       Divi Barrierefreiheit
 * Plugin URI:        https://github.com/fabianseelbach/divi-barrierefei
 * Description:       Wordpress Plugin um Divi Barrierefreier zu gestalten
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Fabian Seelbach
 * Author URI:        https://fsdevzone.de/
 * License:           GPL v3
 * License URI:       https://github.com/fabianseelbach/divi-barrierefei/blob/production/LICENSE
 * Text Domain:       divi-barrierefrei
 */

// ALT-Tags aus der Mediathek auslesen //

function get_image_meta( $image, $type = 'alt' ) {

    if ( ! $image ) {

        return “;

    }

    $output = “;

    if ( '/' === $image[0] ) {

        $post_id = attachment_url_to_postid( home_url() . $image );

    } else {

        $post_id = attachment_url_to_postid( $image );

    }

    if ( $post_id && 'title' === $type ) {

        $output = get_post( $post_id )->post_title;

    }

    if ( $post_id && 'alt' === $type ) {

        $output = get_post_meta( $post_id, '_wp_attachment_image_alt', true );

    }

    return $output;

}

// Aktualisiert image alt text in Modulen //

function update_module_alt_text( $attrs, $unprocessed_attrs, $slug ) {

    if ( ( $slug === 'et_pb_image' || $slug === 'et_pb_fullwidth_image' ) && “ === $attrs['alt'] ) {

        $attrs['alt'] = get_image_meta( $attrs['src'] );

        $attrs['title_text'] = get_image_meta( $attrs['src'], 'title' );

    } elseif ( $slug === 'et_pb_blurb' && 'off' === $attrs['use_icon'] && “ === $attrs['alt'] ) {

        $attrs['alt'] = get_image_meta( $attrs['image'] );

    } elseif ( $slug === 'et_pb_slide' && “ !== $attrs['image'] && “ === $attrs['image_alt'] ) {

        $attrs['image_alt'] = get_image_meta( $attrs['image'] );

    } elseif ( $slug === 'et_pb_fullwidth_header' ) {

        if ( “ !== $attrs['logo_image_url'] && “ === $attrs['logo_alt_text'] ) {

            $attrs['logo_alt_text'] = get_image_meta( $attrs['logo_image_url'] );

        }

        if ( “ !== $attrs['header_image_url'] && “ === $attrs['image_alt_text'] ) {

            $attrs['image_alt_text'] = get_image_meta( $attrs['header_image_url'] );

        }

    }

    return $attrs;

}

// PHP Code dass Seiten auf Smartphones zoomfähig sind, //////////////////////////////////////////////////////////////

function wf_remove_et_viewport_meta() {

    remove_action("wp_head", "et_add_viewport_meta");

}

function wf_enable_pinch_zoom() {

    echo('<meta name="viewport" content="width=device-width, user-scalable=yes, initial-scale=1.0, minimum-scale=0.1, maximum-scale=10.0">');

}

// JS File

function divi_barrierefrei_script() {
    wp_register_script('divi-barrierefrei-js', get_template_directory_uri()  
    . '/ivi-barrierefrei.js', array('jquery'), '1.0', true);
    wp_enqueue_script('divi-barrierefrei-js');

    wp_register_style("divi-barrierefrei-css", plugins_url( 'divi-barrierefrei/divi-barrierefrei.css' ));
 }

// Filter injection //

add_filter("et_pb_module_shortcode_attributes", "update_module_alt_text", 20, 3 );
add_action("after_setup_theme", "wf_remove_et_viewport_meta");
add_action("wp_head", "wf_enable_pinch_zoom");
add_action('wp_enqueue_scripts', 'divi_barrierefrei_script');
