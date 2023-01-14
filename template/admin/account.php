<?php 
    $backend_url = isset($data['backend_url']) ? $data['backend_url'] : ""; 
?>
<form method="post" name="wfc_frm">

    <div class="wfc-row">
        <div class="wfc-col wfc-col-left">
            <div class="wfc-panel-div">
                <label>Imma Backend Url:</label>
                <input type="text" name="backend_url" placeholder="Imma Backend Url" 
                    value="<?php echo $backend_url; ?>" style="width: 80%">
            </div>
        </div>
        <div class="wfc-col wfc-col-right">
            <div class="wfc-panel-div">
                <input type="submit" name="wfc_submit" class="button button-primary button-large" value="Save">
            </div>
        </div>
    </div>
</form>