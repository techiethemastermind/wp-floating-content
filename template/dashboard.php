<div class="wrap">
    <h1 class="wp-heading-inline">Imma App Management</h1>

    <div class="wfc-tabs">
        <a href="#" class="wfc-tab-link active" tab-target="#install-container">Installation</a>
        <a href="#" class="wfc-tab-link" tab-target="#sticky-container">Sticky Management</a>
    </div>

    <div class="wfc-tabs-container">
        <div id="install-container" class="tab-container">
            <?php
                include_once(WFC_PLUGIN_PATH . '/template/admin/install.php');
            ?>
        </div>

        <div id="sticky-container" class="tab-container d-none">
            <?php
                include_once(WFC_PLUGIN_PATH . '/template/admin/sticky.php');
            ?>
        </div>
    </div>    
</div>
