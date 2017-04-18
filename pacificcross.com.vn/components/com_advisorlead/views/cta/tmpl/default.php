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
            <p class="loading">
        </div>
    </div>
</div>
<div id="mits-publish-modal" class="modal hide fade widget-modal cc_modal">
    <div class="modal-body" >
        <iframe src=""></iframe>
    </div>
</div>
<div id="deleteModal" class="modal hide fade">
    <div class="modal-header">
        <a data-dismiss="modal" class="close">×</a>
        <h3>Delete this CTA</h3>
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
        <h3>Delete these CTAs</h3>
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
        <h3>Copy CTA</h3>
    </div>
    <div class="modal-body">
        <form id="duplicate-page-form">
            <input type="hidden" value="n" name="change-name-url" id="change-name-url">
            <input type="hidden" name="page_id" id="duplicate-page-id">
            <div class="control-group">
                <label for="id_duplicate_page_name" class="control-label">Name of copied CTA</label>
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

<script type="text/javascript">
            var MyPager;
            var custom_label_texts = ['', '', '', '', '', '', '', '', '', ''];
            var ajax_url = API_URL + '&request=get_ctas';
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
//                                MAPI.ajaxcall({
//                                    data: data,
//                                    type: 'POST',
//                                    async: true,
//                                    success: function(data) {
//                                        if (data.status == '200') {
//                                            setTimeout(function() {
//                                                $('#deleteMultiModal').modal('hide');
//                                                $('#deleteAll').removeAttr('disabled');
//                                                window.rePaginate();
//                                            }, 1000);
//                                        }
//                                    }
//                                });
                            });
                            break;
                    }
                });

                var last_order = 'up_desc';
                var last_filter = 'all';
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

                window.rePaginate = function() {

                    var query = {
                        order: last_order,
                        fltr: last_filter
                    };
                    $('#paginator').paginate(ajax_url, query);
                };
                window.rePaginate();

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
                $('#box_search_btn').click(function() {
                    var text = $(this).prev('input').val();
                    var url = ajax_url + '&search=1';
                    var data = {
                        'text': text
                    };
                    window.exitSelection();
                    $('#paginator').paginate(url, data);
                });

            });


            function load_create_capture_clicks_popup(e, elem) {
                var $self = $(elem);

                e.preventDefault();
                e.stopPropagation();

                window.industryWidgetCallback = function(industry) {
                    var location = $self.attr("href");

                    window.location.href = location;
                };

                $("#industry-widget").modal("show");
            }
</script>

