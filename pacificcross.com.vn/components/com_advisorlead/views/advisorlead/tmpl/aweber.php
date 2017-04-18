<?php include_once 'header.php'; ?>
<div class="row">
    <?php include_once('full_menu.tpl.php'); ?>
    <?php if (!$integrations['aweber']['active']) { ?>
        <div class="span12">
            <h3 class="service-title aweber-title">AWeber</h3>
            <hr>
            <a href="<?php echo $base_url; ?>/my-account/services/aweber" class="btn"><i class="icon-magnet"></i> Connect to AWeber</a>
        </div>
    <?php } else { ?>
        <div class="span9">
            <h3 class="service-title aweber-title">AWeber</h3>
            <hr>
            <p><i class="icon-ok"></i> AWeber is connected <a class="btn btn-small fright" href="<?php echo $base_url; ?>/my-account/services/aweber/disconnect"><i class="icon-remove"></i> Disconnect</a></p>

            <hr>
            <div class="form-horizontal">
                <div class="control-group">
                    <label class="control-label">Allow copy/paste</label>
                    <div class="controls">
                        <div data-handler="<?php echo $base_url; ?>/my-account/services/aweber/toggle" id="copy-paste" class="btn-group">
                            <button class="btn on<?php if ($integrations['aweber']['copy_paste']) echo ' active btn-success'; ?>">On</button>
                            <button class="btn off<?php if (!$integrations['aweber']['copy_paste']) echo ' active btn-danger'; ?>">Off</button>
                        </div>
                        <small class="muted hide" id="copy-paste-saved" style="display: none;">Saved</small>
                    </div>
                </div>
            </div>

        </div>
    <?php } ?>
</div>
<?php include_once 'footer.php'; ?>

<script type="text/javascript">

    $(document).ready(function() {


        var disabled = false;
        $("#copy-paste .btn").on("click", function() {
            if (disabled)
                return;
            disabled = true;
            var $this = $(this),
                    active = false;
            $("#copy-paste .btn").removeClass("active btn-success btn-danger");
            if ($this.hasClass("on")) {
                $this.addClass("active btn-success")
                active = true;
            }
            else
                $this.addClass("active btn-danger");
            $.post($("#copy-paste").attr("data-handler"), {status: active}, function() {
                $("#copy-paste-saved").show();
                setTimeout(function() {
                    $("#copy-paste-saved").fadeOut("fast", function() {
                        disabled = false;
                    });
                }, 2000);
            });
        });

<?php if (!empty($message)) { ?>
            addAlertFromResponse({"msg": "<?php echo $message['text']; ?>", "header": "", "status": "<?php echo $message['status']; ?>"});
<?php } ?>
    });
</script>
