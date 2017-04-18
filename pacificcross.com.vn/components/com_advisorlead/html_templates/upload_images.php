<link type="text/css" href="<?php echo ASSETS_URL; ?>/css/bootstrap.min.css" rel="stylesheet">
<style>
    body{
        overflow: hidden;
        background: transparent;
    }

    #image-upload{
        margin: 0;
    }

    .alert{
        margin: 0;
        padding: 6px;
        text-align: center;
    }
</style>
<script src="<?php echo ASSETS_URL; ?>/js/jquery.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/bootstrap.min.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/ajax_callback.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/jquery.validate.min.js"></script>
<form class="form-horizontal" enctype="multipart/form-data" action="<?php echo $action_url; ?>" method="POST" id="image-upload" onSubmit="if ($(this).valid()) {
            $('#upload-button').button('loading');
        }" novalidate="novalidate" <?php if ($upload_successful) echo 'style="display:none;"'; ?>>
    <fieldset>
        <label data-loading-text="Uploading..." for="fileInput" onClick="if (!/MSIE\s([\d.]+)/.test(navigator.userAgent)) {
            if ($(this).hasClass('disabled')) {
                return false;
            }
            $('#fileInput').click();
            return false;
        }" class="btn btn-small" id="upload-button"><i class="icon-upload"></i> Upload New Image</label>
        <input type="file" style="position: absolute;left: -9999px;top:-99999px;" name="file" accept="image/*" id="fileInput" class="input-file required">
    </fieldset>
</form>
<?php if ($upload_successful) { ?>
    <div class="alert alert-success" id="alert-msg">Upload Successful</div>
<?php } ?>
<script type="text/javascript">
    var type = '<?php echo $type ?>';
    $().ready(function() {
        $('#fileInput').change(function() {
            $('#image-upload').submit();
        });
        $('#image-upload').validate();
<?php if ($upload_successful) { ?>
            if (type == 'page') {
                window.parent.MITS.loadPictures(<?php echo $image_id ?>, '<?php echo $url; ?>');
            } else if (type == 'cta') {
                window.parent.CCLICKS.loadPictures(<?php echo $image_id ?>, '<?php echo $url; ?>');
            } else {
                window.parent.location.reload();
            }
            setTimeout(function() {
                $('#alert-msg').hide();
                $('#image-upload').fadeIn();
            }, 5000);
<?php } ?>
    });
</script>

