<?php
/*
Plugin Name: Humanitix Event Connector
Description: Connect Humanitix events to WordPress posts on the fly.
Version: 1.1
Author: Mikael Grön <skaramicke@gmail.com>
*/

// add settings page for setting the Humanitix API key
function humanitix_event_connector_settings_init() {
    add_settings_section(
        'humanitix_event_connector_section',
        'Humanitix Event Connector',
        'humanitix_event_connector_section_html',
        'general'
    );

    add_settings_field(
        'humanitix_event_connector_api_key',
        'Humanitix API Key',
        'humanitix_event_connector_api_key_html',
        'general',
        'humanitix_event_connector_section'
    );

    register_setting( 'general', 'humanitix_event_connector_api_key' );
}
add_action( 'admin_init', 'humanitix_event_connector_settings_init' );

// display the settings section HTML
function humanitix_event_connector_section_html() {
    echo '<p>Enter your Humanitix API key below.</p>';
}

// display the API key field HTML
function humanitix_event_connector_api_key_html() {
    $value = get_option( 'humanitix_event_connector_api_key', '' );
    echo '<input type="text" id="humanitix_event_connector_api_key" name="humanitix_event_connector_api_key" value="' . esc_attr( $value ) . '" size="25" />';
}

// add a meta box for the humanitix event ID
function humanitix_event_connector_meta_box() {
    add_meta_box( 'humanitix_event_connector', 'Humanitix Event', 'humanitix_event_connector_meta_box_html', 'post', 'side', 'default' );
}
add_action( 'add_meta_boxes', 'humanitix_event_connector_meta_box' );

// display the meta box HTML
function humanitix_event_connector_meta_box_html( $post ) {
    $humanitix_event_id = get_post_meta( $post->ID, 'humanitix_event_id', true );
    echo '<label for="humanitix_event_connector_field">Humanitix Event ID:</label>';
    echo '<input type="text" id="humanitix_event_connector_field" name="humanitix_event_connector_field" value="' . esc_attr( $humanitix_event_id ) . '" size="25" />';
}

// save the meta box data
function humanitix_event_connector_save_meta_box( $post_id ) {
    if ( array_key_exists( 'humanitix_event_connector_field', $_POST ) ) {
        update_post_meta(
            $post_id,
            'humanitix_event_id',
            $_POST['humanitix_event_connector_field']
        );
    }
}
add_action( 'save_post', 'humanitix_event_connector_save_meta_box' );

// Function to get the event data from the Humanitix API
function get_humanitix_event_data() {
    $api_key = get_option( 'humanitix_event_connector_api_key', '' );
    if ( empty( $api_key ) ) {
        throw new Exception( 'No Humanitix API key specified.' );
    }

    $humanitix_event_id = get_post_meta( get_the_ID(), 'humanitix_event_id', true );
    if ( empty( $humanitix_event_id ) ) {
        throw new Exception( 'No Humanitix event ID specified.' );
    }

    // get event data from the API
    $event_url = 'https://api.humanitix.com/v1/events/' . $humanitix_event_id;
    $event_data = get_transient( $event_url );
    if ( false === $event_data ) {
        // $response = wp_remote_get( 'https://api.humanitix.com/api/events/' . $humanitix_event_id );
        // Get the event data from the Humanitix API using the API key as x-api-key header
        $response = wp_remote_get( $event_url, array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'x-api-key' => $api_key,
            ),
        ) );
        if ( is_wp_error( $response ) ) {
            return 'Error: ' . $response->get_error_message();
        }
        $event_data = json_decode( $response['body'] );
        set_transient( $event_url, $event_data, HOUR_IN_SECONDS );
    }

    return $event_data;
}

// register the shortcode
function humanitix_event_connector_shortcode( $atts ) {

    try {
        $event_data = get_humanitix_event_data();
    } catch ( Exception $e ) {
        return $e->getMessage();
    }

    // "2018-01-12T12:00:00.000Z"
    $start_time = $event_data->startDate;

    // Convert to timestamp
    $start_time = strtotime( $start_time );

    return wp_date( 'l M j, g:i A T', $start_time );
}
add_shortcode( 'humanitix_date', 'humanitix_event_connector_shortcode' );
