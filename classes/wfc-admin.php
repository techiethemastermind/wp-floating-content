<?php

if( !class_exists('WFC_ADMIN') ) {

    class WFC_ADMIN {

        private $_inputs = [
            'title', 'position_type', 'position_x', 'position_y', 'link', 'pages', 'width', 'repeat_type', 'backend_url', 'exclude_from'
        ];

        public function __construct () {

            include_once(WFC_PLUGIN_PATH . '/classes/zip-uploader.php');
            add_action('wfc_admin_output', array(&$this, 'output'), 10, 2);
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

        /**
         * Get All pages
         */
        private function pages () {

            $post_pages = get_pages();
            $pages = [];

            foreach($post_pages as $page) {
                array_push($pages, [
                    'id'    => $page->ID,
                    'title' => $page->post_title,
                    'slug'  => $page->post_name
                ]);
            }

            return $pages;
        }

        /**
         * Store WFC data
         * @return void
         */
        private function handlPost () {

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
              
                if (isset($_POST['wfc_submit'])) {

                    if(isset($_FILES['wfc_file_upload'])) {
                        $img = $_FILES['wfc_file_upload'];
         
                        $uploaded = media_handle_upload('wfc_file_upload', 0);
                
                        // Error checking using WP functions
                        if (!is_wp_error($uploaded)) {
        
                            self::update('post_id', $uploaded);
                        }
                    }

                    foreach($this->_inputs as $input) {

                        if (isset($_POST[$input])) {
                            self::update($input, $_POST[$input]);
                        }
                    }
                }

                if (isset($_POST['wfc_app_upload'])) {

                    if(isset($_FILES['wfc_imma_upload'])) {
                        $fileData = $_FILES['wfc_imma_upload'];

                        if ($fileData['size'] > 0) {
                            $uploader = new ZIP_Uploader( 'imma' );
                            $result   = $uploader->upload($_FILES['wfc_imma_upload']);
                        }
                    }
                }
            }
        }

        /**
         * Update Database Table
         */
        private function update ($name, $value) {

            global $wpdb;
            $table_name = $wpdb->prefix . WFC_TABLE_NAME;
            $data = self::wfc_data();

            if ($name == 'pages') {
                $value = json_encode($value);
            }

            if (isset($data[$name])) {
                $wpdb->update( 
                    $table_name, 
                    array(
                        'name' => $name, 
                        'value' => $value, 
                    ),
                    array('name' => $name)
                );
            } else {
                $wpdb->insert( 
                    $table_name, 
                    array(
                        'name' => $name, 
                        'value' => $value, 
                    )
                );
            }
        }

        /**
         * Check Reactpress plugin is activated or not
         */
        private function isReactPressActivated () {
            $activePlugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

            foreach ( $activePlugins as $plugin ) {

                if ( str_contains( $plugin, 'reactpress' ) ) {
                    return true;
                }
            }

            return false;
        }

        /**
         * Check directorys in Reactpress
         */
        private function isImmaUploaded () {
            $reactpressApps = array_filter(glob( ABSPATH . 'wp-content/reactpress/apps/*'), 'is_dir');

            foreach ( $reactpressApps as $app ) {

                if ( str_contains( $app, 'imma' ) ) {
                    return true;
                }
            }

            return false;
        }

        /**
         * Check slug is generated or not
         */
        private function isSlugGenerated () {
            global $wpdb;
            if ($wpdb->get_row("SELECT post_name FROM wp_posts WHERE post_name = 'imma'", 'ARRAY_A')) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Output Admin UI
         * @return html
         */
        public function output () {

            self::handlPost();
            $data  = self::wfc_data();
            $pages = self::pages();
            $isReactPressActivated = self::isReactPressActivated();
            $isImmaUploaded = self::isImmaUploaded();
            $isSlugGenerated = self::isSlugGenerated();
            
            include_once(WFC_PLUGIN_PATH . '/template/dashboard.php');
        }
    }
}

new WFC_ADMIN();