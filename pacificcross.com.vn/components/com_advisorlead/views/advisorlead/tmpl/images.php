<?php
/* ------------------------------------------------------------------------
  # images.php - AdvisorLead Component
  # ------------------------------------------------------------------------
  # author    Vu Nguyen
  # copyright Copyright (C) 2015. All Rights Reserved
  # license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  # website   iexodus.com
  ------------------------------------------------------------------------- */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

$iframe_url = JURI::base() . '/index.php?option=com_advisorlead&task=templates.api_call&request=upload-image&type=html';
?>
<div class="row-fluid">
    <div class="span12">
        <h3>My Images</h3>
    </div>
</div>
<div class="row-fluid">
    <div id="paginator" class="span12"></div>
</div>
<div class="row-fluid">
    <div class="span12">
        <hr/>
        <iframe frameborder="0" noresize="noresize" id="mits-image-iframe" src="<?php echo $iframe_url ?>"></iframe>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        $(document).on('click', '.delete_btn', function(e) {
            e.preventDefault();
            if (confirm("Are you sure you want to delete this image ?")) {
                window.location.href = $(this).attr('href');
            }
        });

        $('#paginator').paginate(API_URL + '&request=my-images&is_html=1');

        $(document).on('change', '#fileInput', function() {
            $('#image-upload').submit();
        });

<?php if (!empty($message)) { ?>
            addAlertFromResponse({"msg": "<?php echo $message; ?>", "header": "", "status": "success"});
<?php } ?>
    });
</script>
