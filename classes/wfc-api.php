<?php

if( !class_exists('WFC_API') ) {

    class WFC_API {

        public function __construct () {

            add_action('rest_api_init', array(&$this, 'wfc_register_rest_route'), 10, 5);
        }

        public function wfc_register_rest_route() {
            register_rest_route( 'imma/v1', '/settings', array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array(&$this, 'handle_api'),
            ) );
        }

        /**
         * Handle API request
         */
        public function handle_api (WP_REST_Request $request) {

            global $wpdb;
            $table_name = $wpdb->prefix . WFC_TABLE_NAME;
            $data = $wpdb->get_results( "SELECT * FROM $table_name" );
            $return_data = '';

            foreach ($data as $item) {
                if($item->name == 'backend_url') {
                    $return_data = $item->value;
                }
            }
            
            return $return_data;
        }
    }
}

new WFC_API();