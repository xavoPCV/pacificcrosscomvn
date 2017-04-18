<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Advisor Leads&trade;</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/template.css" rel="stylesheet">
        <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/popup_function.css" rel="stylesheet">
        <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/jquery.miniColors.css" rel="stylesheet">
        <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/bootstrap-slider.min.css" rel="stylesheet">

        <!--[if lt IE 9]>
            <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <style>
            #capture_clicks_open{
                transition: 0.5s all;
                display: inline-block;
                cursor: pointer;
                text-decoration: none;
            }
        </style>
        <script>
            var API_URL = '<?php echo JURI::base() . "/index.php?option=com_advisorlead&task=cta.api_call" ?>';
            var analytics = false;
        </script>
    </head>
    <body>
        <div id="publish-widget" class="widget">
            <i class="icon-remove-circle icon-3" id="close-button2"></i>
            <div id="main-content">
                <div id="popup_subheader">
                    How would you like to publish your CTA?
                </div>
                <div class="row-fluid">
                    <div class="span3">
                        <ul id="cc_tabs" class="box_tab vertical_tab">
                            <li class="active">
                                <a class="box_tab_btn" href="javascript:;" data-tab="link-box-tab" box_type="link"><i class="icon-publish-link-box"></i>Text Link</a>
                            </li>
                            <li>
                                <a class="box_tab_btn" href="javascript:;" data-tab="button-box-tab" box_type="button"><i class="icon-publish-button-box"></i>Button</a>
                            </li>
                            <li>
                                <a class="box_tab_btn" href="javascript:;" data-tab="popup-box-tab" box_type="popup"><i class="icon-publish-popup-box"></i>Popup</a>
                            </li>
                        </ul>
                    </div>
                    <div class="span9" id="publish_editable_wrap">
                        <div class="tab box_tab active" id="link-box-tab">
                            <div id="show_for_link_button">
                                <div class="row-fluid">
                                    <div class="link_panel">
                                        <h4>What Do You Want Your Text Link To Say?</h4>
                                        <input type="text" class="link_text" value="<?php echo $this->link_text ?>"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab box_tab" id="button-box-tab">
                            <div class="row-fluid">
                                <div class="span3">
                                    <strong>Text to display</strong>
                                </div>
                                <div class="span9">
                                    <input type="text" class="link_text" value="<?php echo $this->link_text ?>"/>
                                </div>
                            </div>
                            <div class="row-fluid">
                                <div class="span3">
                                    <div class="row-fluid">
                                        <strong>Background color</strong>
                                        <input type="minicolors" changeable_type="background-color" data-lb-base-color="#d91c00" value="<?php echo!empty($this->button_style_arr['background-color']) ? $this->button_style_arr['background-color'] : '#d91c00' ?>" class="color-picker input-mini" />
                                    </div>
                                    <div class="row-fluid">
                                        <strong>Button Text color</strong>
                                        <input type="minicolors" changeable_type="color" data-lb-base-color="#ffffff" value="<?php echo!empty($this->button_style_arr['color']) ? $this->button_style_arr['color'] : '#ffffff' ?>" class="color-picker input-mini" />
                                    </div>
                                    <div class="row-fluid">
                                        <strong>Border Style</strong>
                                        <select class="style_select" name="border-style" changeable_type="border-style">
                                            <option <?php echo $this->button_style_arr['border-style'] == 'solid' ? 'selected' : '' ?> value="solid">Solid</option>
                                            <option <?php echo $this->button_style_arr['border-style'] == 'double' ? 'selected' : '' ?> value="double">Double</option>
                                            <option <?php echo $this->button_style_arr['border-style'] == 'dotted' ? 'selected' : '' ?> value="dotted">Dotted</option>
                                            <option <?php echo $this->button_style_arr['border-style'] == 'dashed' ? 'selected' : '' ?> value="dashed">Dashed</option>
                                        </select>
                                    </div>
                                    <div class="row-fluid">
                                        <strong>Shadow</strong>
                                        <input type="text" class="style_slider" base_value="<?php echo!empty($this->button_box_shadow[1]) ? $this->button_box_shadow[1] : '1' ?>" min="0" max="5" changeable_type="box-shadow"/>
                                    </div>
                                </div>
                                <div class="span3">
                                    <div class="row-fluid">
                                        <strong>Top Border Color</strong>
                                        <input type="minicolors" changeable_type="border-top-color" data-lb-base-color="#ffffff" value="<?php echo!empty($this->button_style_arr['border-top-color']) ? $this->button_style_arr['border-top-color'] : '#ffffff' ?>" class="color-picker input-mini" />
                                    </div>
                                    <div class="row-fluid">
                                        <strong>Bottom Border Color</strong>
                                        <input type="minicolors" changeable_type="border-bottom-color" data-lb-base-color="#ffffff" value="<?php echo!empty($this->button_style_arr['border-bottom-color']) ? $this->button_style_arr['border-bottom-color'] : '#ffffff' ?>" class="color-picker input-mini" />
                                    </div>
                                    <div class="row-fluid">
                                        <strong>Left Border Color</strong>
                                        <input type="minicolors" changeable_type="border-left-color" data-lb-base-color="#ffffff" value="<?php echo!empty($this->button_style_arr['border-left-color']) ? $this->button_style_arr['border-left-color'] : '#ffffff' ?>" class="color-picker input-mini" />
                                    </div>
                                    <div class="row-fluid">
                                        <strong>Right Border Color</strong>
                                        <input type="minicolors" changeable_type="border-right-color" data-lb-base-color="#ffffff" value="<?php echo!empty($this->button_style_arr['border-right-color']) ? $this->button_style_arr['border-right-color'] : '#ffffff' ?>" class="color-picker input-mini" />
                                    </div>
                                </div>
                                <div class="span3">
                                    <div class="row-fluid">
                                        <strong>Top Border Width</strong>
                                        <input type="text" class="style_slider" base_value="<?php echo!empty($this->button_style_arr['border-top-width']) ? $this->button_style_arr['border-top-width'] : '0' ?>" min="0" max="20" changeable_type="border-top-width"/>
                                    </div>
                                    <div class="row-fluid">
                                        <strong>Bottom Border Width</strong>
                                        <input type="text" class="style_slider" base_value="<?php echo!empty($this->button_style_arr['border-bottom-width']) ? $this->button_style_arr['border-bottom-width'] : '0' ?>" min="0" max="20" changeable_type="border-bottom-width"/>
                                    </div>
                                    <div class="row-fluid">
                                        <strong>Left Border Width</strong>
                                        <input type="text" class="style_slider" base_value="<?php echo!empty($this->button_style_arr['border-left-width']) ? $this->button_style_arr['border-left-width'] : '0' ?>" min="0" max="20" changeable_type="border-left-width"/>
                                    </div>
                                    <div class="row-fluid">
                                        <strong>Right Border Width</strong>
                                        <input type="text" class="style_slider" base_value="<?php echo!empty($this->button_style_arr['border-right-width']) ? $this->button_style_arr['border-right-width'] : '0' ?>" min="0" max="20" changeable_type="border-right-width"/>
                                    </div>
                                </div>
                                <div class="span3">
                                    <div class="row-fluid">
                                        <strong>Border Radius</strong>
                                        <input type="text" class="style_slider" base_value="<?php echo!empty($this->button_style_arr['border-radius']) ? $this->button_style_arr['border-radius'] : '5' ?>" min="0" max="20" changeable_type="border-radius"/>
                                    </div>
                                    <div class="row-fluid">
                                        <strong>Letter Spacing</strong>
                                        <input type="text" class="style_slider" base_value="<?php echo!empty($this->button_style_arr['letter-spacing']) ? $this->button_style_arr['letter-spacing'] : '0' ?>" min="0" max="10" changeable_type="letter-spacing"/>
                                    </div>
                                    <div class="row-fluid">
                                        <strong>Button Width</strong>
                                        <input type="text" class="style_slider" base_value="<?php echo!empty($this->button_style_arr['width']) ? $this->button_style_arr['width'] : '200' ?>" min="100" max="500" changeable_type="width"/>
                                    </div>
                                    <div class="row-fluid">
                                        <strong>Button Height</strong>
                                        <input type="text" class="style_slider" base_value="<?php echo!empty($this->button_style_arr['height']) ? $this->button_style_arr['height'] : '20' ?>" min="10" max="40" changeable_type="height"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab box_tab" id="popup-box-tab">
                            <div class="row-fluid margin_bottom">
                                <div class="span6">
                                    Time before CTA appears
                                </div>
                                <div class="span6">
                                    <input type="text" style="width: 20px; text-align: center;" id="popup_appear_time" value="<?php echo $this->appear_time ?>" /> &nbsp; seconds
                                </div>
                            </div>
                            <!--                            <div class="row-fluid margin_bottom">
                                                            <div class="span6">
                                                                Don't show Capture Clicks again for
                                                            </div>
                                                            <div class="span6">
                                                                <input type="text" style="width: 20px; text-align: center;" id="popup_show_again" value="<?php // echo $appear_again       ?>" /> &nbsp; days (0 = show on every visit)
                                                            </div>
                                                        </div>-->
                        </div>
                        <div class="publish_code">
                            <div id="preview_wrap" class="row-fluid">
                                <div class="span12">
                                    <strong>Preview of Element Appearance</strong>
                                    <div id="preview_placeholder">
                                        <?php echo $this->embed_link ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row-fluid ">
                                <div class="span12">
                                    <a class="btn btn-success" id="save_display_data">Publish & Save</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="hidden" id="hidden_embed_script" value="<?php echo $embed_script ?>"/>
                <div class="clearboth"></div>
            </div>
        </div>
        <script src="<?php echo ASSETS_URL; ?>/js/json.js"></script>
        <script src="<?php echo ASSETS_URL; ?>/js/jquery.min.js"></script>
        <script src="<?php echo ASSETS_URL; ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo ASSETS_URL; ?>/js/ajax_callback.js"></script>
        <script src="<?php echo ASSETS_URL; ?>/js/jquery.validate.min.js"></script>
        <script src="<?php echo ASSETS_URL; ?>/js/users_objects.js"></script>
        <script src="<?php echo ASSETS_URL; ?>/js/jquery.minicolors.js"></script>
        <script src="<?php echo ASSETS_URL; ?>/js/bootstrap-slider.min.js"></script>

        <script>
            var box_type = '<?php echo $this->active_tab ?>';
            var capture_clicks_id = '<?php echo $this->cta_id ?>';
            var old_text = '<?php echo $this->link_text ?>';

            var button_style = {
                'background-color': '#d91c00',
                'color': '#fff',
                'outline': 'none',
                'border-style': ' solid',
                'border-top-width': '0',
                'border-bottom-width': '0',
                'border-left-width': '0',
                'border-right-width': '0',
                'border-top-color': '0',
                'border-bottom-color': '0',
                'border-left-color': '0',
                'border-right-color': '0',
                'border-radius': '5px',
                'padding': '10px 15px',
                'box-shadow': '0 1px 3px rgba(0,0,0,0.9)',
                'font-weight': 'bold',
                'width': 'auto',
                'height': 'auto'
            };

<?php if (!empty($this->button_style_arr)) { ?>
                button_style = <?php echo json_encode($this->button_style_arr) ?>;
<?php } ?>
            $(document).ready(function() {

                $("input.color-picker").minicolors({
                    change: function() {
                        var type = $(this).attr('changeable_type');
                        $('#capture_clicks_open').css(type, $(this).val());
                        button_style[type] = $(this).val();
                        update_embed_code();
                    },
                    changeDelay: 100
                });

                $('.link_text').keyup(function() {
                    var value = $(this).val();
                    $('.link_text').not(this).val(value);
                    $('#preview_placeholder #capture_clicks_open').text(value);
                    update_embed_code();
                });

                $('.publish_code textarea').focus(function() {
                    var $this = $(this);
                    $this.select();

                    $this.mouseup(function() {
                        $this.unbind("mouseup");
                        return false;
                    });
                });

                $('.style_select').change(function() {
                    var value = $(this).val();
                    var type = $(this).attr('changeable_type');
                    $('#capture_clicks_open').css(type, value);
                    button_style[type] = value;
                    update_embed_code();
                });

                $('.style_slider').each(function() {
                    var value = '';
                    var slider = $(this).slider({
                        min: parseFloat($(this).attr('min')),
                        max: parseFloat($(this).attr('max')),
                        value: parseFloat($(this).attr('base_value')),
                        formatter: function(value) {
                            return value + 'px';
                        }
                    });

                    slider.on('slide slideStop', function(slide) {
                        var type = $(this).attr('changeable_type');
                        if (type == 'box-shadow') {
                            value = '0 ' + slide.value + 'px 2px rgba(0,0,0,0.5)';
                        } else {
                            value = slide.value + 'px';
                        }
                        $('#capture_clicks_open').css(type, value);
                        button_style[type] = value;
                        update_embed_code();
                    });
                });

                $('#save_display_data').click(function() {

                    var data = {};
                    var values = {
                        'action': 'api_call',
                        'request': 'save-cta-display',
                        'cta_id': capture_clicks_id,
                        'data': data
                    };

                    data.popup_type = box_type;

                    switch (box_type) {
                        case "link":
                            data.link_text = $('.link_text').val();
                            break;
                        case "button":
                            data.link_text = $('.link_text').val();
                            data.style = button_style;
                            break;

                        case "popup":
                            data.appear_time = $('#popup_appear_time').val();
                            data.appear_view = $('#popup_appear_view').val();
                            data.appear_again = $('#popup_show_again').val();
                            break;
                    }

                    values.data = JSON.stringify(data);

                    MAPI.ajaxcall({
                        data: values,
                        type: 'POST',
                        success: function(data) {
                            parent.window.$("#mits-publish-modal").modal("hide");
                            var paginate_url = '<?php echo JURI::base() ?>/ctas/';
                            parent.window.rePaginate();
                        }
                    });
                });

                $(".box_tab_btn").click(function() {
                    var tab = $(this).attr('data-tab');
                    box_type = $(this).attr('box_type');
                    $("#cc_tabs li").removeClass('active');
                    $(this).parent('li').addClass('active');
                    $('.tab').hide();
                    $('.tab[id="' + tab + '"]').fadeIn();
                    switch (box_type) {
                        case "link":
                            $('#preview_placeholder').show();
                            $('#box_button_to_display').hide();
                            $('#preview_wrap').fadeIn();
                            $('#preview_wrap').css('margin-bottom', '80px');
                            $('#capture_clicks_open').removeAttr('style');
                            break;

                        case "button":
                            $('#preview_placeholder').show();
                            $('#preview_wrap').fadeIn();
                            $('#preview_wrap').css('margin-bottom', '20px');
                            $('#box_button_to_display').fadeIn();
                            $('#capture_clicks_open').css(button_style);
                            break;

                        case "popup":
                            $('#preview_placeholder').hide();
                            $('#preview_wrap').hide();
                            break;
                    }
                    update_embed_code();


                });

                $('a.box_tab_btn[box_type="' + box_type + '"]').click();

                $("#close-button2").click(function() {
                    parent.window.$("#mits-publish-modal").modal("hide");
                });
            });

            function update_embed_code() {
                var embed_link = '';
                if ($('#preview_placeholder').is(":visible")) {
                    embed_link = $('#preview_placeholder').html().trim();
                }
                var embed_script = $('#hidden_embed_script').val();
//                $('.publish_code textarea').val(embed_link + embed_script);

            }
        </script>
    </body>
</html>