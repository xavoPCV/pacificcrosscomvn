<?php
/* ------------------------------------------------------------------------
  # default.php - AdvisorLead Component
  # ------------------------------------------------------------------------
  # author    Vu Nguyen
  # copyright Copyright (C) 2015. All Rights Reserved
  # license   GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
  # website   iexodus.com
  ------------------------------------------------------------------------- */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<div class="row-fluid">
    <div id="order-by-block" class="span12">
        <div class="tr-header">
            <div class="pull-left filter">
                <a href="#" id="selection_mode" action="select">Selection Mode</a>
                <div class="btn-group selection_section">
                    <a href="#" data-toggle="dropdown" class="btn dropdown-toggle btn-inverse">
                        <span class="txt">Action</span>
                        <i class="icon-caret-down icon-caret-down-custom"></i>
                    </a>
                    <ul id="filter-selector" class="dropdown-menu">
                        <li><a class="selection_action" action="delete" title="Delete" href="#">Delete</a></li>
                    </ul>
                </div>
            </div>
            <div class="pull-right filter">
                Order:
                <div class="btn-group">
                    <a href="#" data-toggle="dropdown" class="btn dropdown-toggle btn-link" id="orderby">
                        <span class="txt">Updated</span>
                        <span class="caret"></span>
                    </a>
                    <ul id="order-selector" class="dropdown-menu">
                        <li role="presentation"><a data-value="up_asc" title="Ascending" href="#" role="menuitem">↑ Updated</a></li>
                        <li role="presentation"><a data-value="up_desc" title="Descending" href="#" role="menuitem">↓ Updated</a></li>
                        <li role="presentation"><a data-value="cr_asc" title="Ascending" href="#" role="menuitem">↑ Created</a></li>
                        <li role="presentation"><a data-value="cr_desc" title="Descending" href="#" role="menuitem">↓ Created</a></li>
                        <li role="presentation"><a data-value="nm_asc" title="Ascending" href="#" role="menuitem">↑ Name</a></li>
                        <li role="presentation"><a data-value="nm_desc" title="Descending" href="#" role="menuitem">↓ Name</a></li>
                        <li role="presentation"><a data-value="lb_asc" title="Ascending" href="#" role="menuitem">↑ Label</a></li>
                        <li role="presentation"><a data-value="lb_desc" title="Descending" href="#" role="menuitem">↓ Label</a></li>
                        <li role="presentation"><a data-value="crate_asc" title="Ascending" href="#" role="menuitem">↑ Conversion Rate</a></li>
                        <li role="presentation"><a data-value="crate_desc" title="Descending" href="#" role="menuitem">↓ Conversion Rate</a></li>
                        <li role="presentation"><a data-value="uniques_asc" title="Ascending" href="#" role="menuitem">↑ Uniques</a></li>
                        <li role="presentation"><a data-value="uniques_desc" title="Descending" href="#" role="menuitem">↓ Uniques</a></li>
                        <li role="presentation"><a data-value="optins_asc" title="Ascending" href="#" role="menuitem">↑ Optins</a></li>
                        <li role="presentation"><a data-value="optins_desc" title="Descending" href="#" role="menuitem">↓ Optins</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="paginator" class="loaded">
            <p class="loading"></p>
        </div>
    </div>
</div>
<div class="my-pages-alerts" id="alert_placeholder">

</div>

<div id="mits-publish-modal" class="modal hide fade widget-modal p_modal">
    <div class="modal-body" >
        <iframe scolling="no" src="" ></iframe>
    </div>
</div>
<div id="mits-create-test-modal" class="modal hide fade widget-modal" style="margin-top: -245px; width: 705px; height: 490px;">
    <div class="modal-body" style="width: 705px; height: 490px;">
        <iframe scolling="no" src="" style="width: 705px; height: 490px;"></iframe>
    </div>
</div>
<div id="deleteModal" class="modal hide fade">
    <div class="modal-header">
        <a data-dismiss="modal" class="close">×</a>
        <h3>Delete this page</h3>
    </div>
    <div class="modal-body">
        <p>This will PERMANENTLY DELETE your page. There is no recovery!</p>
        <p><span class="label label-important">Warning:</span> Please note that once you delete this page, all instances of this page will likely stop working. This includes pages that you may have published to Facebook, or HTML that you may have uploaded to your own server.</p>
    </div>
    <div class="modal-footer">
        <button data-dismiss="modal" class="btn">Close</button>
        <a class="btn btn-danger" href="javascript:;" id="cont-del-url"><i class="icon-trash icon-white"></i> Delete</a>
    </div>
</div>
<div id="deleteMultiModal" class="modal hide fade">
    <div class="modal-header">
        <a data-dismiss="modal" class="close">×</a>
        <h3>Delete these pages</h3>
    </div>
    <div class="modal-body">
        <p>This will PERMANENTLY DELETE your pages. There is no recovery!</p>
        <p><span class="label label-important">Warning:</span> Please note that once you delete this page, all instances of this page will likely stop working. This includes pages that you may have published to Facebook, or HTML that you may have uploaded to your own server.</p>
    </div>
    <div class="modal-footer">
        <button data-dismiss="modal" class="btn">Close</button>
        <a class="btn btn-danger" href="javascript:;" id="deleteAll"><i class="icon-trash icon-white"></i> Delete</a>
    </div>
</div>
<div id="duplicateModal" class="modal hide fade">
    <div class="modal-header">
        <a data-dismiss="modal" class="close">×</a>
        <h3>Copy page</h3>
    </div>
    <div class="modal-body">
        <form id="duplicate-page-form">
            <input type="hidden" value="n" name="change-name-url" id="change-name-url">
            <input type="hidden" name="page_id" id="duplicate-page-id">
            <div class="control-group">
                <label for="id_duplicate_page_name" class="control-label">Name of copied page</label>
                <div class="controls">
                    <input type="text" value="" class="input-xlarge required" name="id_duplicate_page_name" id="id_duplicate_page_name">
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button data-dismiss="modal" class="btn">Close</button>
        <a onclick="$('#duplicate-page-form').submit();
                return false;" class="btn btn-primary" href="javascript:;" id="page-duplicate-submit">Copy</a>
    </div>
</div>
<div id="renameModal" class="modal hide fade">
    <div class="modal-header">
        <a data-dismiss="modal" class="close">×</a>
        <h3>Rename page</h3>
    </div>
    <div class="modal-body">
        <form id="rename-page-form">
            <input type="hidden" value="n" name="change-name-url" id="change-name-url">
            <div class="control-group">
                <label for="id_page_name" class="control-label">Page name</label>
                <div class="controls">
                    <input type="text" value="" class="input-xlarge" name="id_page_name" id="id_page_name">
                </div>
            </div>
            <div class="control-group">
                <label for="id_page_url" class="control-label">Public URL</label>
                <div class="controls">
                    <div style="display:inline;" class="input-prepend">
                        <span id="account-subdomain-url" class="add-on">/</span>
                        <input type="text" disabled="disabled" value="" name="id_page_url" id="id_page_url" class="input-large">
                    </div>
                    <button style="margin: 3px 0 0 8px;" type="button" class="btn btn-mini" onclick="MP.setPageUrlEditable();
                return false;" id="edit-url-btn"><i class="icon-edit"></i></button>
                </div>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <button data-dismiss="modal" class="btn">Close</button>
        <a onclick="$('#rename-page-form').submit();
                return false;" class="btn btn-primary" href="javascript:;" id="page-rename-submit">Save</a>
    </div>
</div>
<script type="text/javascript">
            var MyPager;
            var custom_label_texts = ['', '', '', '', '', '', '', '', '', ''];
            var ajax_url = API_URL + '&request=get_pages';
            var baseUrl = '<?php echo ADVISORLEAD_URL; ?>';
            $(document).ready(function() {

                $('#selection_mode').on('click', function(event) {
                    event.preventDefault();
                    var $this = $(this);
                    var action = $this.attr('action');
                    if (action == 'select') {
                        $this.attr('action', 'exit_select');
                        $this.text('Exit Selection Mode');
                        $('.edit-options-analytics').fadeOut();
                        $('.selected_page_lbl,.selection_section').fadeIn().css('display', 'inline-block');
                        $('.selected_page_chbox').attr('checked', false);
                    } else {
                        window.exitSelection();
                    }
                });

                window.exitSelection = function()
                {
                    $('#selection_mode').attr('action', 'select');
                    $('#selection_mode').text('Selection Mode');
                    $('.edit-options-analytics').fadeIn();
                    $('.selected_page_lbl,.selection_section').fadeOut();
                }

                $('.selection_action').on('click', function(event) {
                    event.preventDefault();
                    var $this = $(this);
                    var action = $this.attr('action');
                    var ids = [];
                    $('.selected_page_chbox').each(function() {
                        if ($(this).is(":checked"))
                            ids.push($(this).val());
                    });

                    switch (action) {
                        case"delete":
                            $('#deleteMultiModal').modal('show');
                            $('#deleteAll').unbind('click');
                            $('#deleteAll').click(function(event) {
                                event.preventDefault();
                                $('#deleteAll').attr('disabled', 'disabled');
                                var data = {
                                    'action': 'api_call',
                                    'request': 'delete_pages',
                                    'page_ids': ids
                                };
                                MAPI.ajaxcall({
                                    data: data,
                                    type: 'POST',
                                    async: true,
                                    success: function(data) {
                                        if (data.status == '200') {
                                            setTimeout(function() {
                                                $('#deleteMultiModal').modal('hide');
                                                $('#deleteAll').removeAttr('disabled');
                                                window.rePaginate();
                                            }, 1000);
                                        }
                                    }
                                });
                            });
                            break;
                    }
                });

                var last_order = 'up_desc';
                var last_filter = 'all';
                var paginate_url = baseUrl + '/pages/';
                var default_html = $('#paginator').html();
                $('#order-selector a[role="menuitem"]').click(function(event) {
                    $('#orderby span.txt').text($(this).text());
                    $('#paginator').html(default_html);
                    last_order = $(this).attr('data-value');
                    var query = {
                        order: last_order,
                        fltr: last_filter
                    };

                    $('#paginator').paginate(ajax_url, query);
                    event.preventDefault();
                });
                $('#filter-selector a[role="menuitem"]').click(function(event) {
                    var text = $(this).html();
                    if (text.length === 0 || text == '&amp;nbsp;') {
                        text = $(this).attr('data-label-alt');
                    }
                    $('#filterby span.txt').html(text);
                    $('#paginator').html(default_html);
                    last_filter = $(this).attr('data-value');
                    var query = {
                        order: last_order,
                        fltr: last_filter
                    };

                    $('#paginator').paginate(ajax_url, query);
                    event.preventDefault();
                });
                window.rePaginate = function() {
                    var query = {
                        order: last_order,
                        fltr: last_filter
                    };
                    $('#paginator').paginate(ajax_url, query);
                };
                window.rePaginate();

                window.widgetCloseCallback = function() {
                    $(".widget-modal").modal("hide");
                }

                $("#mits-publish-modal").on("hidden", function() {
                    $("li[data-state] button[data-action=edit]").click();
                    $(this).find('iframe').attr('src', '');
                });

<?php if (!empty($_SESSION['page_message'])) { ?>
                    addAlertFromResponse({"msg": "<?php echo $_SESSION['page_message']; ?>", "header": "", "status": "success"});
    <?php
    unset($_SESSION['page_message']);
}
?>
                $('#template_search_btn').click(function() {
                    var text = $('#template_search').val();
                    var url = paginate_url + 'template_search/';
                    var data = {
                        'text': text
                    };
                    window.exitSelection();
                    $('#paginator').paginate(url, data);
                });

            });
</script>