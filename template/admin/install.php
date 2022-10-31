<form method="post" enctype="multipart/form-data" name="wfc_frm">

    <?php
        $activeStr = $isReactPressActivated ? 'Installed' : 'Not Installed';
    ?>
    <div class="wfc-panel-div">
        <label>1. ReactPress Status:</label>
        <p class="label" style="width:80%;"><?php echo $activeStr ?></p>
    </div>

    <?php if (!$isReactPressActivated): ?>
    <div class="wfc-panel-div">
        <p class="label">Please Install <a href="/wp-admin/plugin-install.php?s=reactpress&tab=search&type=term">ReactPress Plugin</a></p>
    </div>
    <?php endif ?>

    <?php if ($isReactPressActivated) : ?>
    <div class="wfc-panel-div">
        <label>2. Upload Imma App: </label>
        <?php if ($isImmaUploaded) : ?>
            <p class="label" style="width:25%;">Imma App already Installed</p>
        <?php endif ?>
        <input type='file' name='wfc_imma_upload' style="width:25%;"></input>
        <input type="submit" name="wfc_app_upload" class="button button-primary button-large" value="Upload">	
    </div>
    <?php endif ?>

    <?php if ($isImmaUploaded) : ?>
    <div class="wfc-panel-div">
        <label>3. Generate slug: </label>
        <p class="label" style="width:80%;">Go to <a href="/wp-admin/admin.php?page=reactpress">ReactPress Plugin</a> and add slug like below picture:</p>
    </div>
    <div class="wfc-panel-div">
        <img src="<?php echo plugins_url() . '/'. WFC_PLUGIN_DOMAIN . '/assets/img/reactpress-guide.jpg' ?>" alt="">
    </div>
    <div class="wfc-panel-div">
        <label>4. Installed Done! </label>
        <p class="label">Please confirm Imma app is working. If not working then please contact to developer team!</p>
    </div>
    <?php endif ?>
</form>
