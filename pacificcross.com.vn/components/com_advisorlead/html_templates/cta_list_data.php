<?php ?>
<table id="page-table" class="table pages-table table-bordered table-hover">
    <?php
    foreach ($ctas as $cc_data) {
        $publish_url = ADVISORLEAD_URL . "/ctas/$cc_data->id/publish";
        if ($cc_data->status == 0) {
            $published_text = '<i class="fa fa-eye-slash"></i> Unpublished';
            $published_class = 'unpublished';
        } else {
            $published_text = '<i class="fa fa-eye"></i> Published';
            $published_class = '';
        }
        $screenshot = ASSETS_URL . "/inc/cta-templates/$cc_data->template_slug/screenshot.png";
        ?>
        <tr>
            <td>
                <a class="page-name page" data-publish-url="<?php echo $publish_url ?>" href="#" onclick="show_capture_clicks_popup(this);" style="background-image: url('<?php echo $screenshot ?>');"><?php echo $cc_data->name ?></a> 
                <div class="edit-options-analytics">
                    <a class="publish-custom-btn <?php echo $published_class ?>" data-publish-url="<?php echo $publish_url ?>" href="#" onclick="show_capture_clicks_popup(this);">
                        <?php echo $published_text ?>
                    </a>
                    <a class="edit-custom-btn" href="<?php echo ADVISORLEAD_URL . "/ctas/$cc_data->template_id/$cc_data->id/" ?>">
                        <i class="fa fa-edit"></i> Edit Popup
                    </a>
                    <a class="options-btn" href="<?php echo ADVISORLEAD_URL . "/ctas/$cc_data->id/delete/" ?>" onclick="MP.deletePage(this);
                        return false;">
                        <i class="fa fa-trash-o"></i> Delete Popup
                    </a>
                    <div class="page-inline-controls pull-left table-controls table-controls-custom">
                        <div class="black-i" title="Conversion Rate">
                            <a href="#chart=Conversion Rate"><small><?php echo $cc_data->rates ?>%</small></a><div>Conversion Rate</div>
                        </div>
                        <div class="black-i" title="Optins">
                            <a href="#chart=Optins"><small><?php echo $cc_data->optins ?></small></a><div>Opt-ins</div>
                        </div>
                        <div class="black-i" title="Unique Viewers">
                            <a href="#chart=Uniques"><small><?php echo $cc_data->uniques ?></small></a><div>Uniques</div>
                        </div>
                    </div>
                </div>
                <label class="checkbox selected_page_lbl">
                    <input type="checkbox" class="selected_page_chbox" value="<?php echo $cc_data->id ?>">
                </label>

            </td>
        </tr>
<?php } ?>
</table>

<script>

                function show_capture_clicks_popup(elem) {
                    $('#mits-publish-modal iframe').attr('src', $(elem).attr('data-publish-url'));
                    $('#mits-publish-modal').modal('show');
                    return false;
                }

</script>