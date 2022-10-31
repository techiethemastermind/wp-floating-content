<?php

if( !class_exists('WFC_FRONT') ) {

    class WFC_FRONT {

        public function __construct () {

            if (!is_admin()) {
                add_action('wp', array(&$this, 'wfc_front_output'), 10, 3);
            }
        }

        /**
         * Get Data from Database
         */
        private function wfc_data () {

            global $wpdb;
            $table_name = $wpdb->prefix . WFC_TABLE_NAME;
            $data = $wpdb->get_results( "SELECT * FROM $table_name" );
            $return_data = [];
        
            foreach($data as $item) {
                $return_data[$item->name] = $item->value;
            }
            
            return $return_data;
        }

        public function wfc_front_output () {
            global $post;
            $slug = $post->post_name;
    
            $existing_data = self::wfc_data();
    
            if (in_array($slug, json_decode($existing_data['pages']))) {
                $sticky_url = '#';
                if (isset($existing_data['post_id'])) {
                    $sticky_url = wp_get_attachment_url($existing_data['post_id']);
                }
            
                $width = isset($existing_data['width']) ? $existing_data['width'] : 120;
                $height = '100%';
                $position_type = isset($existing_data['position_type']) ? $existing_data['position_type'] : 'fixed';
                $position_x = isset($existing_data['position_x']) ? $existing_data['position_x'] : 'right';
                $position_y = isset($existing_data['position_y']) ? $existing_data['position_y'] : 'bottom';
                $link = isset($existing_data['link']) ? $existing_data['link'] : '#';
                $margin_x = 5;
                $margin_y = 10;
            
                $style_p = 'position: ' . $position_type . ';';
                $style_x = $position_x . ': ' . $margin_x . 'px;';
                $style_y = $position_y . ': ' . $margin_y . 'px;';
            
                if ($position_y == 'center') {
                    $style_y = 'top: calc(50% - '. ($height / 2) .'px);';
                }
                
                $style = $style_p . $style_x . $style_y;
            
                include_once(WFC_PLUGIN_PATH . '/template/front-sticky.php');
            }
        }
    }
}

new WFC_FRONT();

