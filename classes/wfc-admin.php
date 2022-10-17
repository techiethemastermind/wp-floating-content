<?php

if( !class_exists('WFC_ADMIN') ) {

    class WFC_ADMIN {

        private $_inputs = [
            'title', 'position_type', 'position_x', 'position_y', 'link', 'pages', 'width'
        ];

        public function __construct () {

            add_action('wfc_admin_output', array(&$this, 'output'), 10, 1);
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
                        self::update($input, $_POST[$input]);
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
         * Output Admin UI
         * @return html
         */
        public function output () {

            self::handlPost();
            $data  = self::wfc_data();
            $pages = self::pages();
            
            ?>

            <div class="wrap">
                <h1 class="wp-heading-inline">Floating Content Management</h1>

                <!-- Form to handle the upload - The enctype value here is very important -->
                <form method="post" enctype="multipart/form-data" name="wfc_frm">
                    <div id="poststuff">
                        <div id="post-body" class="metabox-holder columns-2">
                            <div id="post-body-content">
                                <div id="titlediv">
                                    <div id="titlewrap">
                                        <?php
                                            $title = isset($data['title']) ? $data['title'] : "";
                                        ?>
                                        <input type="text" name="title" size="30" 
                                        value="<?php echo $title ?>" id="title" 
                                        spellcheck="true" autocomplete="off" placeholder="Add Title">
                                    </div>
                                </div>
                            </div>
                            <div id="postbox-container-1" class="postbox-container">
                                <div id="side-sortables" class="meta-box-sortables ui-sortable">
                                    <div id="submitdiv" class="postbox">
                                        <div class="postbox-header"><h2 class="hndle ui-sortable-handle">Publish</h2>
                                            <div class="handle-actions hide-if-no-js">
                                                <button type="button" class="handle-order-higher" aria-disabled="true" aria-describedby="submitdiv-handle-order-higher-description">
                                                    <span class="screen-reader-text">Move up</span><span class="order-higher-indicator" aria-hidden="true"></span>
                                                </button>
                                                <span class="hidden" id="submitdiv-handle-order-higher-description">Move Publish box up</span>
                                                <button type="button" class="handle-order-lower" aria-disabled="false" aria-describedby="submitdiv-handle-order-lower-description">
                                                    <span class="screen-reader-text">Move down</span><span class="order-lower-indicator" aria-hidden="true"></span>
                                                </button>
                                                <span class="hidden" id="submitdiv-handle-order-lower-description">Move Publish box down</span>
                                                <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Publish</span>
                                                    <span class="toggle-indicator" aria-hidden="true"></span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="inside">
                                            <div id="submitpost">
                                                <div id="minor-publishing" style="padding:15px">
                                                    <?php
                                                        if (isset($data['post_id'])) {
                                                            $url = wp_get_attachment_url($data['post_id']);
                                                    ?>
                                                        <img id="blah" src="<?php echo $url ?>" alt="your image" style="width: 100%; height: auto;" />
                                                    <?php
                                                        } else {
                                                    ?>
                                                        <img id="blah" src="#" alt="your image" style="width: 100%; height: auto; display:none;" />
                                                    <?php
                                                        }
                                                    ?>
                                                    
                                                    <input type='file' name='wfc_file_upload'></input>
                                                </div>
                                                
                                                <div id="major-publishing-actions">
                                                    <div id="publishing-action">
                                                        <span class="spinner"></span>
                                                        <input name="original_publish" type="hidden" id="original_publish" value="Publish">
                                                        <input type="submit" name="wfc_submit" id="publish" class="button button-primary button-large" value="Publish">						
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="postbox-container-2" class="postbox-container">
                                <div id="advanced-sortables" class="meta-box-sortables ui-sortable">
                                    <div id="wp_floating_content_meta_box" class="postbox">
                                        <div class="postbox-header">
                                            <h2 class="hndle ui-sortable-handle">Floating Content Details</h2>
                                            <div class="handle-actions hide-if-no-js">
                                                <button type="button" class="handle-order-higher" aria-disabled="false" aria-describedby="wp_floating_content_meta_box-handle-order-higher-description">
                                                    <span class="screen-reader-text">Move up</span>
                                                    <span class="order-higher-indicator" aria-hidden="true"></span>
                                                </button>
                                                <span class="hidden" id="wp_floating_content_meta_box-handle-order-higher-description">Move Floating Content Details box up</span>
                                                <button type="button" class="handle-order-lower" aria-disabled="false" aria-describedby="wp_floating_content_meta_box-handle-order-lower-description">
                                                    <span class="screen-reader-text">Move down</span><span class="order-lower-indicator" aria-hidden="true"></span>
                                                </button><span class="hidden" id="wp_floating_content_meta_box-handle-order-lower-description">Move Floating Content Details box down</span>
                                                <button type="button" class="handlediv" aria-expanded="true">
                                                    <span class="screen-reader-text">Toggle panel: Floating Content Details</span>
                                                    <span class="toggle-indicator" aria-hidden="true"></span>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="inside">
                                            <div class="afc-panel">
                                                <div class="afc-panel-div">
                                                    <label for="bafckground_color">Position</label>
                                                    <?php
                                                        $position_type = isset($data['position_type']) ? $data['position_type'] : "";
                                                    ?>
                                                    <select style="width:22%;" name="position_type" id="wfc_position_place">
                                                        <option value="fixed" <?php if($position_type == 'fixed') echo 'selected="selected"' ?>>Fixed</option>
                                                        <option value="absolute" <?php if($position_type == 'absolute') echo 'selected="selected"' ?>>Absolute</option>
                                                    </select>
                                                    <?php
                                                        $position_y = isset($data['position_y']) ? $data['position_y'] : "";
                                                    ?>
                                                    <select style="width:22%;" name="position_y" id="wfc_position_y">
                                                        <option value="top"  <?php if($position_y == 'top') echo 'selected="selected"' ?>>Top</option>
                                                        <option value="center" <?php if($position_y == 'center') echo 'selected="selected"' ?>>Center</option>
                                                        <option value="bottom" <?php if($position_y == 'bottom') echo 'selected="selected"' ?>>Bottom</option>
                                                    </select>
                                                    <?php
                                                        $position_x = isset($data['position_x']) ? $data['position_x'] : "";
                                                    ?>
                                                    <select style="width:22%;" name="position_x" id="wfc_position_x">
                                                        <option value="left" <?php if($position_x == 'left') echo 'selected="selected"' ?>>Left</option>
                                                        <option value="right" <?php if($position_x == 'right') echo 'selected="selected"' ?>>Right</option>
                                                    </select>     
                                                    
                                                </div>
                                                <div class="afc-panel-div">
                                                    <?php $width = isset($data['width']) ? $data['width'] : 120;?>
                                                    <label>Icon Width (px):</label>
                                                    <input type="text" name="width" placeholder="Icon Width" value="<?php echo $width; ?>" style="width: 40%">
                                                </div>
                                                <div class="afc-panel-div">
                                                    <?php $link = isset($data['link']) ? $data['link'] : "#";?>
                                                    <label>Link</label>
                                                    <input type="text" name="link" placeholder="Link" value="<?php echo $link; ?>" style="width: 40%">
                                                </div>
                                                <div class="afc-panel-div">
                                                    <label>Pages to display:</label>
                                                    <select name="pages[]" id="wfc_pages" multiple="multiple" style="width:80%">
                                                        <?php foreach($pages as $page) : ?>
                                                        <option value="<?php echo $page['slug'] ?>" 
                                                            <?php if(isset($data['pages']) && in_array($page['slug'], json_decode($data['pages']))) echo 'selected="selected"' ?>>
                                                            <?php echo $page['title'] ?></option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <?php
        }
    }
}

new WFC_ADMIN();