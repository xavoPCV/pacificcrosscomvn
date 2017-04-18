$().ready(function() {

    $(document).on('click', '.mits-editable-fields', function() {

        var field_name = $(this).attr('field_name');
        var header = $(this).text();
        CCLICKS.openFieldEditor(header, field_name);
    });

    $(document).on('click', '.mits-show-fields', function(e) {
        e.stopPropagation();

        var field_name = $(this).attr('name');
        if ($(this).is(":checked")) {
            CCLICKS.changeable_fields['fields'][field_name]['is_show'] = 1;
            CCLICKS.iframe.contents().find('#form_fields li[field_name="' + field_name + '"]').fadeIn();
        } else {
            CCLICKS.changeable_fields['fields'][field_name]['is_show'] = 0;
            CCLICKS.iframe.contents().find('#form_fields li[field_name="' + field_name + '"]').hide();
        }
    });

    $(document).on('click', '#mits-form-fields .is_field_required', function() {
        if (CCLICKS.form_status == 'required') {
            var field_name = $(this).closest('#mits-form-fields').attr('name');
            if ($(this).is(":checked")) {
                CCLICKS.changeable_fields['fields'][field_name]['is_required'] = 1;
                CCLICKS.iframe.contents().find('#form_fields li[field_name="' + field_name + '"] input').addClass('error');
                CCLICKS.iframe.contents().find('#form_fields li[field_name="' + field_name + '"] input').attr('is_required', 'true');
                CCLICKS.iframe.contents().find('#form_fields li[field_name="' + field_name + '"] .error-message[type="required"]').fadeIn();
            } else {
                CCLICKS.changeable_fields['fields'][field_name]['is_required'] = 0;
                CCLICKS.iframe.contents().find('#form_fields li[field_name="' + field_name + '"] input').removeClass('error');
                CCLICKS.iframe.contents().find('#form_fields li[field_name="' + field_name + '"] input').attr('is_required', 'false');
                CCLICKS.iframe.contents().find('#form_fields li[field_name="' + field_name + '"] .error-message[type="required"]').hide();
            }
        }
    });


    $(document).on('keyup', '.mits-form-field-details', function() {
        var value = $(this).val();
        var name = $(this).closest('#mits-form-fields').attr('name');
        var field_type = $(this).attr('field_type');
        switch (field_type) {
            case "placeholder_text":
                CCLICKS.iframe.contents().find('#form_fields input[name="' + name + '"]').attr('placeholder', value);
                break;
            case "invalid_message":
                CCLICKS.iframe.contents().find('#form_fields li[field_name="' + name + '"] .error-message[type="invalid"]').text(value);
                break;
            case "required_message":
                CCLICKS.iframe.contents().find('#form_fields li[field_name="' + name + '"] .error-message[type="required"]').text(value);
                break;
        }
    });

    $('#mits-reload-forms').click(function() {
        CCLICKS.formTypeChangeAjax('', '');
    });

    window.widgetResizeCallback = function(w, h) {
        var els = $(".widget-modal, .widget-modal .modal-body, .widget-modal iframe");
        w = w || 705;
        $(".widget-modal").css({
            "margin-top": -(h / 2),
            "margin-left": -(w / 2)
        });
        els.width(w);
        els.height(h);
    };
    var $optionsMenu = $(".nav.options");
    var $navWrappers = $("#mits-top-navigation.editor-navigation.navbar .navbar-inner");
    var $window = $(window);
    var $topNavi = $("#mits-top-navigation");
    var $settingsMenu = $("#editable-elements");
    $(window).on("resize", function() {
        var width = $(this).width(),
                height = $(this).height();
        var d = 42;
        if ($optionsMenu.position().top == 0) {
            $navWrappers.css("height", "42px");
            $optionsMenu.css("border-left", "1px solid #ccc");
        } else {
            $navWrappers.css("height", "84px");
            $optionsMenu.css("border-left", "none");
            d = 0;
        }
        $settingsMenu.height($window.height() - $topNavi.outerHeight(true) - 15);
        CCLICKS.positionLeftPanel();
        $("#mits-form-editor").width(width).height($(window).height()).css("top", d + "px");
        $("#mits-form-editor .form-editor").css("height", "42px");
        ;
        $("iframe").each(function() {
            try {
                if (this.contentWindow.mainWindowResizeCallback)
                    this.contentWindow.mainWindowResizeCallback();
            } catch (e) {
            }
        });
    }).trigger("resize");
    window.widgetCloseCallback = function(is_editor) {
        if (is_editor)
            $("#mits-form-editor").fadeOut("fast");
        else
            $(".widget-modal").modal("hide");
    };
    window.markChangedCallback = CCLICKS.markChange;
    window.creatingVariation = false;
    window.newVariationCallback = function(type, id) {
        if (!window.creatingVariation) {
            window.creatingVariation = true;
            window.onbeforeunload = null;
            window.location.href = "/new-variation/" + CCLICKS.capture_clicks_id + "/?type=" + type + "&id=" + id;
        }
    };
    $(".widget-modal").on("hidden", function() {
        var publishButton = $("li[data-action] button[data-action=publish]");
        publishButton.removeClass(publishButton.attr("data-active-class"));
        publishButton.removeClass("active");
    });
    CCLICKS.return_false = function() {
        return false;
    };
    var simpleNameChanged = function(e) {
        var name = $('#id_capture_clicks_name').val();
        $('#mits-page-menu-title').text(name);
        CCLICKS.markChange();
        CCLICKS.name_changed = true;
        if (e.type === "keyup" && e.keyCode === 13) {
            $(this).blur();
        }
    };
    $('#id_capture_clicks_name').change(simpleNameChanged);
    $('#id_capture_clicks_name').keydown(simpleNameChanged);
    $('#id_capture_clicks_name').keyup(simpleNameChanged);
    $(window).resize(CCLICKS.windowResize);
    $(window).load(function() {
   
        CCLICKS.loadPictures(null, null);
        $(window).on('beforeunload', function() {
            if (CCLICKS.change_made) {
                return "Are you sure you want to leave without saving?";
            }
        });
    });
  
    var navHeight = $("#mits-top-navigation").height();
    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    $("li[data-action] > button").each(function() {
        var button = $(this);
        var confirmationText = "You must save the CTA before you can publish it. Would you like to do this now?";
        var confirmationText2 = "Would you like to save your CTA before you publish it?";
        var activate = function() {
            if (button.attr("data-active-class")) {
                button.addClass(button.attr("data-active-class"));
                button.addClass("active");
            }
        };
        var deactivate = function() {
            button.removeClass(button.attr("data-active-class"));
            button.removeClass("active");
        };
        button.click(function() {
            $("li[data-action] > button").removeAttr("class").addClass("btn");
            $("button#save-button").addClass("btn-primary");
            activate();
            switch (button.attr("data-action")) {
                case "save":
                    CCLICKS.uiSave();
                    break;
                case "analytics":
                    window.location.href = button.attr("data-href");
                    break;
                case "publish":
                    if (!CCLICKS.edit) {
                        if (!confirm(confirmationText)) {
                            deactivate();
                            return;
                        }
                        CCLICKS.uiSave(function(success) {
                            if (success) {
                                var publishUrl = baseUrl + "/ctas/" + CCLICKS.capture_clicks_id + "/publish";
                                $("#mits-publish-modal iframe").attr("src", publishUrl);
                                $("#mits-publish-modal").css("border", "none");
                                var frame = $("#mits-publish-modal").modal("show").find("iframe")[0];
                                if (frame.contentWindow.callOnResizeCb)
                                    frame.contentWindow.callOnResizeCb();
                            } else {
                                deactivate();
                            }
                        });
                    } else {
                        if (CCLICKS.change_made && confirm(confirmationText2)) {
                            CCLICKS.uiSave(function(success) {
                                if (success) {
                                    var publishUrl = baseUrl + "/ctas/" + CCLICKS.capture_clicks_id + "/publish";
                                    $("#mits-publish-modal iframe").attr("src", publishUrl);
                                    var frame = $("#mits-publish-modal").modal("show").find("iframe")[0];
                                    if (frame.contentWindow.callOnResizeCb)
                                        frame.contentWindow.callOnResizeCb();
                                } else {
                                    deactivate();
                                }
                            });
                        } else {
                            var publishUrl = baseUrl + "/ctas/" + CCLICKS.capture_clicks_id + "/publish";
                            $("#mits-publish-modal iframe").attr("src", publishUrl);
                            var frame = $("#mits-publish-modal").modal("show").find("iframe")[0];
                            if (frame.contentWindow.callOnResizeCb)
                                frame.contentWindow.callOnResizeCb();
                        }
                    }
                    break;
            }
        });
    });
    $(".quit-button").click(function(e) {
        e.preventDefault();
        var anchor = $(this);
        if (CCLICKS.change_made && confirm("Save changes?")) {
            CCLICKS.saveIt();
        }
        setTimeout(function() {
            window.onbeforeunload = null;
            window.location = anchor.attr("href");
        }, 500);
    });
    CCLICKS.windowResize();

    var sizelist = [];
    for (var i = 8; i <= 48; i++) {
        sizelist.push(i);
    }

    var custom_fontsize = {};
    $.each(sizelist, function(index, value) {
        custom_fontsize[index] = {
            title: value + "px",
            callback: function(obj, e, sFont) {
                var redactor_class = obj.$content[0].className.split(' ');
                var target = '.' + redactor_class[0] + ' p';

                var selected_text = obj.getSelection().toString();
                if (selected_text != '') {
                    var new_text = '<span style="font-size: ' + value + 'px">' + selected_text + '</span>';
                    obj.insertHtml(new_text);
                } else {
                    if ($(target).find('span').length > 0)
                    {
                        $(target).find('span').css('font-size', value + 'px');
                    } else {
                        $(target).wrapInner('<span style="font-size: ' + value + 'px"></span>');
                    }
                }
            }
        }
    });

    $('#mits-rich-text-basic').redactor({
        source: false,
        allowedTags: ["a", "b", "i", "p", "br", "strike", "span", 'u', 'ul', 'li', 'ol'],
        buttons: ['bold', 'italic', 'underline', 'deleted', '|', 'link', '|', 'fontcolor'],
        buttonsAdd: ["fontsize"],
        buttonsCustom: {
            fontsize: {
                title: "Font Size",
                dropdown: custom_fontsize
            }
        },
        execCommandCallback: function(obj) {
            CCLICKS.basicRichTextChanged();
        },
        keyupCallback: function(obj) {
            CCLICKS.basicRichTextChanged();
        }
    });
    var toggleToggleRunningButton = function() {
        var button = $("button[data-test-action=toggle-running]");
        button.show();
        button.children().remove();
        if (CCLICKS.is_running) {
            button.append($("<i/>").addClass("icon-stop"));
        } else {
            button.append($("<i/>").addClass("icon-play"));
        }
    };
});

var CCLICKS = {
    parent_img: '',
    loadedAWeberForms: false,
    loadedMailChimpForms: false,
    loadedIContactForms: false,
    loadedInfusionsoftForms: false,
    loadedGetresponseForms: false,
    loadedGoToWebinarWebinars: false,
    loadedConstantContactForms: false,
    integrationLoaded: false,
    different_simple_name: false,
    saving: false,
    change_made: false,
    edit: false,
    iframe: null,
    form_status: 'normal',
    openingEditWindow: false,
    nowEditingElement: false,
    aweber_copy_paste: false,
    getresponse_copy_paste: false,
    edit_data: {},
    style_data: {},
    field_data: {},
    color_data: {},
    font_options_str: '',
    page_keywords: '',
    page_description: '',
    user_end_code: '',
    user_head_code: '',
    iframe_top_position: 0,
    js_variables: {},
    template_js_variables: {},
    changeble_elements: {},
    changable_colors: {},
    changeable_attribute: {},
    changeable_fields: {},
    element_attribute: {},
    richtext_timeout: null,
    changable_fonts: {},
    submitFormListener: function() {
    },
    closeEditorListener: function() {
    },
    selectImage: function() {
    },
    cleanAndSetBasicRichText: function() {
    },
    cleanAndSetRichTextArea: function() {
    },
    template_id: null,
    player_logo_url: {
        'empty': '',
        'mits': ''
    },
    selected_element_id: null,
    selected_form_id: null,
    selected_form_optin: null,
    selected_form_optin_type: null,
    private_template: false,
    public_page_url: false,
    template_name: false,
    name_changed: false,
    skip_initial: false,
    is_initial: false,
    is_test: false,
    is_control: false,
    is_running: false,
    is_variation: false,
    has_custom_mail: false,
    industry: null,
    editor_blocks_by_type: {
        'opt-in-form': '',
        'image': 'mits-change-img',
        'link': 'mits-change-link',
        'submit': 'mits-edit-btn-text',
        'fadin-box': 'mits-edit-optin-box',
        'embed-area': 'mits-edit-area',
        'text_input': 'mits-edit-placeholder',
        'text': '',
        'richtext-area': 'mits-edit-richtext-area'
    },
    first_name_available: ['aweber', 'mailchimp', 'icontact', 'getresponse', 'infusionsoft', 'constantcontact', 'gotowebinar', 'other'],
    phone_available: ['aweber', 'mailchimp', 'icontact', 'getresponse', 'infusionsoft', 'constantcontact', 'other'],
    loadData: function(url, player_logo_urls, template_id, capture_clicks_id, edit, capture_clicks_slug, capture_clicks_name) {
        $('#editable-elements #editable_placeholder').html('<img alt="loading" src="https://googledrive.com/host/0B_Y1OBeOK_x4UGtBTjdqNjNPcWs/ajax-loader-small.gif"/> Loading form elements');
        CCLICKS.capture_clicks_slug = capture_clicks_slug;
        CCLICKS.capture_clicks_name = capture_clicks_name;
        CCLICKS.user_end_code = $('#id-mits-analytics').val();
        CCLICKS.user_head_code = $('#id-mits-head-code').val();
        CCLICKS.capture_clicks_id = capture_clicks_id;
        CCLICKS.player_logo_url = player_logo_urls;
        CCLICKS.template_id = template_id;
        if (edit) {
            CCLICKS.edit = true;
            MAPI.ajaxcall({
                data: {
                    "action": "api_call",
                    'request': 'get-cta-data',
                    'cta_id': capture_clicks_id
                },
                type: 'POST',
                async: true,
                success: function(data) {
                    var page_data = data.body;
                    CCLICKS.edit_data = page_data['data'];
                    if (page_data['color_data']) {
                        CCLICKS.color_data = page_data['color_data'];
                    }
                    if (page_data['style_data']) {
                        CCLICKS.style_data = page_data['style_data'];
                    }
                    if (page_data['field_data']) {
                        CCLICKS.field_data = page_data['field_data'];
                        if (CCLICKS.field_data['selected_integration'] && CCLICKS.field_data['selected_form'])
                            CCLICKS.formTypeChangeAjax(CCLICKS.field_data['selected_integration'], CCLICKS.field_data['selected_form']);
                    }
                    CCLICKS.afterLoadData(url);
                }
            });
            var queryString = document.location.href.match(/(.*)#!(.*)/);
            var queryParameters = {};
            if (queryString !== null) {
                var pairs = queryString[2].split("&");
                for (var i = 0; i < pairs.length; i++) {
                    var pair = pairs[i].split("=");
                    queryParameters[pair[0]] = pair[1];
                }
            }
            if ("add-variation" in queryParameters) {
                $("#mits-add-variation-modal").modal("show");
            }
        } else {
            CCLICKS.afterLoadData(url);
        }
    },
    openPopupSettings: function(selector)
    {
        var width = $('#template-settings').width();
        var height = $('#white-box-left').height();
        selector.fancybox({
            helpers: {
                overlay: {
                    css: {
                        width: width,
                        height: height
                    }
                }
            }
        });
    },
    afterLoadData: function(url) {

        CCLICKS.iframe = $('<iframe>', {
            'src': url,
            'id': 'mits-template-iframe',
            'noresize': 'noresize',
            'frameborder': '0',
            'height': ($(window).outerHeight() - $('#mits-top-navigation').outerHeight())
        }).load(function() {
            CCLICKS.afterTemplateLoaded();
            CCLICKS.iframe_top_position = $('#mits-template-iframe').offset().top;
        }).appendTo('#mits-iframe-wrapper');
        CCLICKS.iframe.hide();


        $('.accordion_btn').click(function(event) {
            event.preventDefault();
            var action = $(this).attr('action');
            switch (action) {
                case "integration":
                    CCLICKS.openOptinSettings();
                    break;
                case "form_style":
                    CCLICKS.openStyleSettings();
                    break;
                case "tracking_code":
                    CCLICKS.openTrackingCodeSettings();
                    break;
            }
        });
        $('#font-settings').click(function(event) {
            event.preventDefault();
            CCLICKS.openFontSettings();
        });

        CCLICKS.setListeners();
        CCLICKS.setJsVariables();
    },
    basicRichTextChangedResize: function() {
        if ($('#mits-edit-rich-text .redactor_editor').get(0)) {
            var height = 0;
            $('#mits-edit-rich-text .redactor_editor > *').each(function() {
                height += $(this).outerHeight(true);
            });
            height += ($('#mits-edit-rich-text .redactor_toolbar').outerHeight(true) + $('#mits-modal-header').outerHeight(true) + $('#lb-submit-btns hr').outerHeight(true) + $('#lb-submit-btns').outerHeight(true) + 120);
            if ($('#mits-removable-elem').is(':visible')) {
                height += $('#mits-removable-elem').outerHeight(true);
            }
            if ($('#mits-editor').height() <= (height)) {
                var editor_space_height = $('#mits-editor').height();
                editor_space_height -= ($('#mits-edit-rich-text .redactor_toolbar').outerHeight(true) + $('#mits-modal-header').outerHeight(true) + $('#lb-submit-btns hr').outerHeight(true) - $('#lb-submit-btns').outerHeight(true) + 120);
                if ($('#mits-removable-elem').is(':visible')) {
                    editor_space_height -= $('#mits-removable-elem').outerHeight(true);
                }
                $('#mits-edit-rich-text .redactor_editor').height(editor_space_height);
            } else {
                $('#mits-edit-rich-text .redactor_editor').css('height', 'auto');
            }
        }
    },
    richTextChangedResize: function() {
        if ($('#mits-edit-richtext-area .redactor_editor').get(0)) {
            var height = 0;
            $('#mits-edit-richtext-area .redactor_editor > *').each(function() {
                height += $(this).outerHeight(true);
            });
            height += ($('#mits-edit-richtext-area .redactor_toolbar').outerHeight(true) + $('#mits-modal-header').outerHeight(true) + $('#lb-submit-btns hr').outerHeight(true) + $('#lb-submit-btns').outerHeight(true) + 120);
            if ($('#mits-removable-elem').is(':visible')) {
                height += $('#mits-removable-elem').outerHeight(true);
            }
            if ($('#mits-editor').height() <= (height)) {
                var editor_space_height = $('#mits-editor').height();
                editor_space_height -= ($('#mits-edit-richtext-area .redactor_toolbar').outerHeight(true) + $('#mits-modal-header').outerHeight(true) + $('#lb-submit-btns hr').outerHeight(true) - $('#lb-submit-btns').outerHeight(true) + 120);
                if ($('#mits-removable-elem').is(':visible')) {
                    editor_space_height -= $('#mits-removable-elem').outerHeight(true);
                }
                $('#mits-edit-richtext-area .redactor_editor').height(editor_space_height);
            } else {
                $('#mits-edit-richtext-area .redactor_editor').css('height', 'auto');
            }
        }
    },
    basicRichTextChanged: function() {
        CCLICKS.cleanAndSetBasicRichText();
        CCLICKS.basicRichTextChangedResize();
    },
    richTextAreaChanged: function() {
        CCLICKS.cleanAndSetRichTextArea();
        CCLICKS.richTextChangedResize();
        clearTimeout(CCLICKS.richtext_timeout);
        CCLICKS.richtext_timeout = setTimeout(function() {
            CCLICKS.cleanAndSetRichTextArea();
            CCLICKS.richTextChangedResize();
        }, 200);
    },
    windowResize: function() {
        if (CCLICKS.iframe) {
            if ($("#mits-top-navigation").is(":visible")) {
                CCLICKS.iframe.height($(window).height() - $('#mits-top-navigation').height());
            } else {
                CCLICKS.iframe.height($(window).height());
            }
        }
        var templete_settings_width = (($('#template-settings').attr('data-hidden') === 'true') ? 0 : $('#template-settings').width());
        $('#mits-iframe-wrapper').height('100%');
        $('#mits-iframe-wrapper #mits-template-iframe').height('100%');
        $('#mits-iframe-wrapper').width($(window).width() - templete_settings_width);
        $('#mits-iframe-wrapper').css('padding-left', templete_settings_width + 'px');
        $('#mits-iframe-wrapper').css('padding-top', $('#powered_block').height() + 'px');
        $('#template-settings').height($(window).height() - $('#mits-top-navigation').outerHeight(true) - 5);
        $('#powered_block').width($(window).width() - $('#mits-top-navigation').width());
        $('#white-box-left').height($(window).height() - $('#mits-top-navigation').outerHeight(true) - 20);
        $('#mits-editor').css('max-height', ($('#white-box-left').height() + $('#mits-top-navigation').height() - 30) + 'px');
        $('#mits-hide-editor-button').height($('#mits-iframe-wrapper').height());
        CCLICKS.basicRichTextChangedResize();
        CCLICKS.richTextChangedResize();
    },
    saveThisTemplate: function() {
        if (CCLICKS.edit) {
            CCLICKS.saveIt();
        } else {
            $('#mits-save-modal').modal('show');
        }
    },
    loadPictures: function(id, url) {

        if (id && url) {

            CCLICKS.selectImage(null, url, id);
        }
        $('#images-loader').show();
        CCLICKS.loadPicturesHandler(true, null);
    },
    loadPicturesHandler: function(first_time, cstr) {
        MAPI.ajaxcall({
            data: {
                "action": "api_call",
                'request': 'my-images',
                'cstr': cstr
            },
            type: 'GET',
            async: true,
            success: function(data) {
                if (first_time) {
                    $('#lb-my-images .my-images').remove();
                }
                for (var i in data.body.images) {
                    $('#lb-my-images').append($('<li class="my-images">').html('<a href="#" class="image-choose-btn" onclick="CCLICKS.selectImage(this, \'' + data.body.images[i]['url'] + '\',' + data.body.images[i]['id'] + ');return false;"><img src="' + data.body.images[i]['url'] + '" alt="Image"></a>'));
                }
                if (data.body.has_more) {
                    CCLICKS.loadPicturesHandler(false, data.body.cstr);
                } else {
                    $('#images-loader').hide();
                }
            },
            error: function() {
                $('#images-loader').hide();
            }
        });
    },
    afterTemplateLoaded: function() {
        $('#editable-elements #editable_placeholder').html('');
        CCLICKS.setFonts();
        CCLICKS.setEditableText();
        CCLICKS.setChangebleStyle();
        CCLICKS.iframe.contents().scroll(function() {
            CCLICKS.moveElementBorder();
        });
        CCLICKS.windowResize();
        $('#mits-template-iframe').contents().find('#capture_clicks_close').click(function() {
            window.location.href = baseUrl + '/ctas';
        });

        if (CCLICKS.field_data['selected_integration'] && CCLICKS.field_data['selected_form'] && CCLICKS.field_data['fields']) {
            CCLICKS.changeable_fields = CCLICKS.field_data;
            var fields = CCLICKS.field_data['fields'];
            var text_append = '';
            var form_append = '';
            for (var field_name in fields) {
                var required = '';
                var required_field = 'is_required="false"';

                if (fields[field_name].is_required == 1) {
                    required = 'disabled';
                    required_field = 'is_required="true"';
                }
                var visible = '';
                var is_show = 'checked';
                if (fields[field_name].is_show == 0) {
                    visible = 'style="display: none"';
                    is_show = '';
                }
                var placeholder = fields[field_name].placeholder;
                var invalid_text = fields[field_name].invalid_text;
                var required_text = fields[field_name].required_text;

                text_append += '<li><a field_name="' + field_name + '" class="btn-block content_accordion_btn mits-editable-fields"><input type="checkbox" name="' + field_name + '" ' + required + ' ' + is_show + ' class="mits-show-fields" />' + placeholder + '</a></li>';

                form_append += '<li field_name="' + field_name + '" ' + visible + '>';
                form_append += '    <input type="text" placeholder="' + placeholder + '" name="' + field_name + '" ' + required_field + '>';
                form_append += '    <span type="invalid" class="error-message">' + invalid_text + '</span>';
                form_append += '    <span type="required" class="error-message">' + required_text + '</span>';
                form_append += '    <i class="icon_drag"></i>';
                form_append += '</li>';
            }

            $('#mits-template-iframe').contents().find('#form_fields').html(form_append);
            $('#editable_fields_placeholder').html(text_append);

        } else {
            $('#editable_fields_placeholder').html('Please select the integration');
        }

        CCLICKS.iframe.contents().find('.capture_clicks_button').click(function() {
            var hidden_class = $(this).attr("hidden_class");
            var status = $(this).attr("status");
            CCLICKS.form_status = status;
            CCLICKS.iframe.contents().find('.capture_clicks_button').removeClass('error success');
            $(this).addClass(hidden_class);
            var invalid_message = CCLICKS.iframe.contents().find('.error-message[type="invalid"]');
            var required_message = CCLICKS.iframe.contents().find('.error-message[type="required"]');
            var input = CCLICKS.iframe.contents().find('#form_fields input');

            switch (status) {
                case "normal":
                    invalid_message.hide();
                    required_message.hide();
                    input.removeClass('error');
                    break;
                case "invalid":
                    invalid_message.fadeIn();
                    required_message.hide();
                    input.addClass('error');
                    break;
                case "required":
                    invalid_message.hide();
                    input.each(function() {
                        var is_required = $(this).attr('is_required');
                        var required_message = $(this).parent().find('.error-message[type="required"]');
                        if (is_required == 'true') {
                            required_message.fadeIn();
                            $(this).addClass('error');
                        } else {
                            $(this).removeClass('error');
                            required_message.hide();
                        }
                    });
                    break;
            }
        });

        CCLICKS.iframeFieldClickable();
    },
    moveElementBorder: function() {
        $('.lb-border-element').each(function() {
            var $this = $(this);
            $this.css('top', (parseFloat($this.attr('data-lb-top')) + CCLICKS.iframe_top_position - CCLICKS.iframe.contents().scrollTop()) + 'px');
        });
    },
    loadIContactForms: function(reset) {
        var data = {
            "action": "api_call",
            'request': 'icontact-forms'
        };
        if (reset) {
            $('#lb-reload-btn-icontact').hide();
            $('#lb-icontact-forms-select').hide();
            $('#lb-icontact-loading').show();
            data['reset'] = 'y';
        }
        MAPI.ajaxcall({
            data: data,
            type: 'GET',
            async: true,
            success: function(data) {
                if (data.status == '200') {
                    $('#lb-icontact-forms-select').empty();
                    $('#lb-icontact-forms-select').append($('<option class="muted" value="">----</option>'));
                    for (var i in data.body) {
                        var form = data.body[i];
                        $('#lb-icontact-forms-select').append($('<option value="' + form['id'] + '">' + form['name'] + '</option>'));
                    }
                    if (CCLICKS.edit_data['opt-in']) {
                        $('#lb-icontact-forms-select').val(CCLICKS.edit_data['opt-in']['value']);
                    }
                    $('#lb-reload-btn-icontact').show();
                    $('#lb-icontact-forms-select').show();
                    $('#lb-icontact-loading').hide();
                    AVAILABLE_SERVICES['icontact'] = true;
                } else if (data.status == '201') {
                    $('#lb-icontact-forms').html('<div class="alert alert-info">First you need to <b><a href="' + data.body + '">setup your iContact</a></b> account.</div>');
                } else if (data.status == '202') {
                    if (!$('#lb-icontact-loading').is('visible')) {
                        $('#lb-reload-btn-icontact').hide();
                        $('#lb-icontact-forms-select').hide();
                        $('#lb-icontact-loading').show();
                    }
                    setTimeout(function() {
                        CCLICKS.loadIContactForms(false);
                    }, 3000);
                }
                CCLICKS.loadedIContactForms = true;
                CCLICKS.checkIntegrationLoadedStatus();
            }
        });
    },
    loadAWeberForms: function(reset) {
        var data = {
            "action": "api_call",
            'request': 'aweber-forms'
        };
        if (reset) {
            $('#lb-reload-btn-aweber').hide();
            $('#lb-aweber-forms-select').hide();
            $('#lb-aweber-loading').show();
            data['reset'] = 'y';
        }
        MAPI.ajaxcall({
            data: data,
            type: 'GET',
            async: true,
            success: function(data) {
                if (data.status == '200') {
                    $('#lb-aweber-forms-select').empty();
                    $('#lb-aweber-forms-select').append($('<option class="muted" value="">----</option>'));
                    for (var i in data.body) {
                        var list = data.body[i];
                        var group = $('<optgroup label="' + list['name'] + '">');
                        for (var k in list['web_forms']) {
                            var form = list['web_forms'][k];
                            group.append($('<option value="' + list['id'] + '">' + form['name'] + '</option>'));
                        }
                        $('#lb-aweber-forms-select').append(group);
                    }
                    if (CCLICKS.edit_data['opt-in']) {
                        $('#lb-aweber-forms-select').val(CCLICKS.edit_data['opt-in']['value']);
                    }
                    $('#lb-reload-btn-aweber').show();
                    $('#lb-aweber-forms-select').show();
                    $('#lb-aweber-loading').hide();
                    AVAILABLE_SERVICES['aweber'] = true;
                } else if (data.status == '201') {
                    $('#lb-aweber-forms').html('<div class="alert alert-info">First you need to <b><a href="' + data.body + '">connect your AWeber</a></b> account.</div>');
                } else if (data.status == '202') {
                    if (!$('#lb-aweber-loading').is('visible')) {
                        $('#lb-reload-btn-aweber').hide();
                        $('#lb-aweber-forms-select').hide();
                        $('#lb-aweber-loading').show();
                    }
                    setTimeout(function() {
                        CCLICKS.loadAWeberForms(false);
                    }, 3000);
                } else if (data.status == '203') {
                    CCLICKS.aweber_copy_paste = true;
                    $('#lb-aweber-copy-paste').show();
                    $('#aweber-choice .form-help-text').hide();
                    $('#lb-aweber-forms').empty();
                    $('#lb-aweber-forms').hide();
                }
                CCLICKS.loadedAWeberForms = true;
                CCLICKS.checkIntegrationLoadedStatus();
            }
        });
    },
    loadConstantContactForms: function(reset) {
        var data = {
            "action": "api_call",
            'request': 'constantcontact-forms'
        };
        if (reset) {
            $('#lb-reload-btn-constantcontact').hide();
            $('#lb-constantcontact-forms-select').hide();
            $('#lb-constantcontact-loading').show();
            data['reset'] = 'y';
        }
        MAPI.ajaxcall({
            data: data,
            type: 'GET',
            async: true,
            success: function(data) {
                if (data.status == '200') {
                    $('#lb-constantcontact-forms-select').empty();
                    $('#lb-constantcontact-forms-select').append($('<option class="muted" value="">----</option>'));
                    for (var i in data.body) {
                        var form = data.body[i];
                        $('#lb-constantcontact-forms-select').append($('<option value="' + form['id'] + '">' + form['name'] + '</option>'));
                    }
                    if (CCLICKS.edit_data['opt-in']) {
                        $('#lb-constantcontact-forms-select').val(CCLICKS.edit_data['opt-in']['value']);
                    }
                    $('#lb-reload-btn-constantcontact').show();
                    $('#lb-constantcontact-forms-select').show();
                    $('#lb-constantcontact-loading').hide();
                    AVAILABLE_SERVICES['constantcontact'] = true;
                } else if (data.status == '201') {
                    $('#lb-constantcontact-forms').html('<div class="alert alert-info">First you need to <b><a href="' + data.body + '">connect your ConstantContact</a></b> account.</div>');
                } else if (data.status == '202') {
                    if (!$('#lb-constantcontact-loading').is('visible')) {
                        $('#lb-reload-btn-constantcontact').hide();
                        $('#lb-constantcontact-loading').show();
                        $('#lb-constantcontact-forms-select').hide();
                    }
                    setTimeout(function() {
                        CCLICKS.loadConstantContactForms(false);
                    }, 3000);
                }
                CCLICKS.loadedConstantContactForms = true;
                CCLICKS.checkIntegrationLoadedStatus();
            }
        });
    },
    loadGetresponseForms: function(reset) {
        var data = {
            "action": "api_call",
            'request': 'getresponse-forms'
        };
        if (reset) {
            $('#lb-reload-btn-getresponse').hide();
            $('#lb-getresponse-forms-select').hide();
            $('#lb-getresponse-loading').show();
            data['reset'] = 'y';
        }
        MAPI.ajaxcall({
            data: data,
            type: 'GET',
            async: true,
            success: function(data) {
                if (data.status == '200') {
                    $('#lb-getresponse-forms-select').empty();
                    $('#lb-getresponse-forms-select').append($('<option class="muted" value="">----</option>'));
                    for (var i in data.body) {
                        var form = data.body[i];
                        $('#lb-getresponse-forms-select').append($('<option value="' + form['id'] + '">' + form['name'] + '</option>'));
                    }
                    if (CCLICKS.edit_data['opt-in']) {
                        $('#lb-getresponse-forms-select').val(CCLICKS.edit_data['opt-in']['value']);
                    }
                    $('#lb-reload-btn-getresponse').show();
                    $('#lb-getresponse-forms-select').show();
                    $('#lb-getresponse-loading').hide();
                    AVAILABLE_SERVICES['getresponse'] = true;
                } else if (data.status == '201') {
                    $('#lb-getresponse-forms').html('<div class="alert alert-info">First you need to <b><a href="' + data.body + '">connect your GetResponse</a></b> account.</div>');
                } else if (data.status == '202') {
                    if (!$('#lb-getresponse-loading').is('visible')) {
                        $('#lb-reload-btn-getresponse').hide();
                        $('#lb-getresponse-loading').show();
                        $('#lb-getresponse-forms-select').hide();
                    }
                    setTimeout(function() {
                        CCLICKS.loadGetresponseForms(false);
                    }, 3000);
                } else if (data.status == '203') {
                    CCLICKS.getresponse_copy_paste = true;
                    $('#lb-getresponse-copy-paste').show();
                    $('#getresponse-choice .form-help-text').hide();
                    $('#lb-getresponse-forms').empty();
                    $('#lb-getresponse-forms').hide();
                }
                CCLICKS.loadedGetresponseForms = true;
                CCLICKS.checkIntegrationLoadedStatus();
            }
        });
    },
    loadInfusionsoftForms: function(reset) {
        var data = {
            "action": "api_call",
            'request': 'infusionsoft-forms'
        };
        if (reset) {
            $('#lb-reload-btn-infusionsoft').hide();
            $('#lb-infusionsoft-forms-select').hide();
            $('#lb-infusionsoft-loading').show();
            data['reset'] = 'y';
        }
        MAPI.ajaxcall({
            data: data,
            type: 'GET',
            async: true,
            success: function(data) {
                if (data.status == '200') {
                    $('#lb-infusionsoft-forms-select').empty();
                    $('#lb-infusionsoft-forms-select').append($('<option class="muted" value="">----</option>'));
                    for (var i in data.body) {
                        var form = data.body[i];
                        $('#lb-infusionsoft-forms-select').append($('<option value="' + form['id'] + '">' + form['name'] + '</option>'));
                    }
                    if (CCLICKS.edit_data['opt-in']) {
                        $('#lb-infusionsoft-forms-select').val(CCLICKS.edit_data['opt-in']['value']);
                    }
                    $('#lb-reload-btn-infusionsoft').show();
                    $('#lb-infusionsoft-forms-select').show();
                    $('#lb-infusionsoft-loading').hide();
                    AVAILABLE_SERVICES['infusionsoft'] = true;
                } else if (data.status == '201') {
                    $('#lb-infusionsoft-forms').html('<div class="alert alert-info">First you need to <b><a href="' + data.body + '">connect your Infusionsoft</a></b> account.</div>');
                } else if (data.status == '202') {
                    if (!$('#lb-infusionsoft-loading').is('visible')) {
                        $('#lb-reload-btn-infusionsoft').hide();
                        $('#lb-infusionsoft-loading').show();
                        $('#lb-infusionsoft-forms-select').hide();
                    }
                    setTimeout(function() {
                        CCLICKS.loadInfusionsoftForms(false);
                    }, 3000);
                }
                CCLICKS.loadedInfusionsoftForms = true;
                CCLICKS.checkIntegrationLoadedStatus();
            }
        });
    },
    loadMailChimpForms: function(reset) {
        var data = {
            "action": "api_call",
            'request': 'mailchimp-forms'
        };
        if (reset) {
            $('#lb-reload-btn-mailchimp').hide();
            $('#lb-mailchimp-forms-select').hide();
            $('#lb-mailchimp-loading').show();
            data['reset'] = 'y';
        }
        MAPI.ajaxcall({
            data: data,
            type: 'GET',
            async: true,
            success: function(data) {
                if (data.status == '200') {
                    $('#lb-mailchimp-forms-select').empty();
                    $('#lb-mailchimp-forms-select').append($('<option class="muted" value="">----</option>'));
                    for (var i in data.body) {
                        var form = data.body[i];
                        $('#lb-mailchimp-forms-select').append($('<option value="' + form['id'] + '">' + form['name'] + '</option>'));
                    }
                    if (CCLICKS.edit_data['opt-in']) {
                        $('#lb-mailchimp-forms-select').val(CCLICKS.edit_data['opt-in']['value']);
                    }
                    $('#lb-reload-btn-mailchimp').show();
                    $('#lb-mailchimp-forms-select').show();
                    $('#lb-mailchimp-loading').hide();
                    AVAILABLE_SERVICES['mailchimp'] = true;
                } else if (data.status == '201') {
                    $('#lb-mailchimp-forms').html('<div class="alert alert-info">First you need to <b><a href="' + data.body + '">setup your MailChimp</a></b> account.</div>');
                } else if (data.status == '202') {
                    if (!$('#lb-mailchimp-loading').is('visible')) {
                        $('#lb-reload-btn-mailchimp').hide();
                        $('#lb-mailchimp-loading').show();
                        $('#lb-mailchimp-forms-select').hide();
                    }
                    setTimeout(function() {
                        CCLICKS.loadMailChimpForms(false);
                    }, 3000);
                }
                CCLICKS.loadedMailChimpForms = true;
                CCLICKS.checkIntegrationLoadedStatus();
            }
        });
    },
    loadGoToWebinarWebinars: function(reset) {
        if (reset) {
            $('#lb-reload-btn-gotowebinar').hide();
            $('#lb-gotowebinar-forms-select').hide();
            $('#lb-gotowebinar-loading').show();
        }
        CCLICKS.loadingGoToWebinarWebinars = true;
        MAPI.ajaxcall({
            data: {
                "action": "api_call",
                'request': 'gotowebinar-webinars'
            },
            type: 'GET',
            async: true,
            success: function(data) {
                if (data.status == '200') {
                    $('#lb-gotowebinar-forms-select').empty();
                    $('#lb-gotowebinar-forms-select').append($('<option class="muted" value="">----</option>'));
                    for (var i in data.body) {
                        var form = data.body[i];
                        $('#lb-gotowebinar-forms-select').append($('<option value="' + form['webinarKey'] + '">' + form['subject'] + '</option>'));
                    }
                    if (CCLICKS.edit_data['opt-in']) {
                        $('#lb-gotowebinar-forms-select').val(CCLICKS.edit_data['opt-in']['value']);
                    }
                    $('#lb-reload-btn-gotowebinar').show();
                    $('#lb-gotowebinar-forms-select').show();
                    $('#lb-gotowebinar-loading').hide();
                    AVAILABLE_SERVICES['gotowebinar'] = true;
                    CCLICKS.loadingGoToWebinarWebinars = false;
                } else if (data.status == '201') {
                    if (data.error)
                    {
                        $('#lb-gotowebinar-forms').html('<div class="alert alert-error">' + data.error + '</div>');
                        $('#lb-reload-btn-gotowebinar').show();
                    }
                    else
                        $('#lb-gotowebinar-forms').html('<div class="alert alert-info">First you need to <b><a href="' + data.body + '">setup your GoToWebinar</a></b> account.</div>');
                } else if (data.status == '202') {
                    if (!$('#lb-gotowebinar-loading').is('visible')) {
                        $('#lb-reload-btn-gotowebinar').hide();
                        $('#lb-gotowebinar-loading').show();
                        $('#lb-gotowebinar-forms-select').hide();
                    }
                    setTimeout(function() {
                        CCLICKS.loadGoToWebinarWebinars(false);
                    }, 3000);
                }
                CCLICKS.loadedGoToWebinarWebinars = true;
                CCLICKS.checkIntegrationLoadedStatus();
            }
        });
    },
    formTypeChangeAjax: function(integration_type, form) {
        CCLICKS.integrationLoaded = false;

        var val = $('#id-mits-integration-select').val();

        if (integration_type != '') {
            $('#id-mits-integration-select').val(integration_type);
            val = integration_type;
        }

        if (val != 'other' && val != '') {
            $('#mits-loading-forms').show();
            $('#mits-form-select').empty();
            $('#integration-form').hide();

            var data = {
                action: "api_call",
                integration_type: val,
                request: 'cta-integrations'
            };

            MAPI.ajaxcall({
                data: data,
                type: 'GET',
                async: true,
                success: function(data) {
                    var status = (data.status).toString();
                    switch (status) {
                        case "200":
                            for (var key in data.lists) {
                                var list = data.lists[key];
                                var group_name = '';
                                var list_append = '';
                                var form_name = '';
                                var selected = '';
                                if (form != '' && key == form) {
                                    selected = 'selected';
                                }

                                if (list.indexOf('*@*') !== -1) {
                                    var list_name = list.split('*@*');
                                    form_name = list_name[1];
                                    group_name = list_name[0];
                                    list_append = $('<optgroup label="' + group_name + '">');
                                    list_append.append($('<option value="' + key + '" ' + selected + '>' + form_name + '</option>'));
                                } else {
                                    form_name = list;
                                    list_append = $('<option value="' + key + '" ' + selected + '>' + form_name + '</option>');
                                }

                                $('#mits-form-select').append(list_append);
                            }

                            $('#integration-form').fadeIn();
                            $('#mits-loading-forms').hide();
                            CCLICKS.integrationLoaded = true;
                            break;
                        case "201":
                            alert(data.message);
                            $('#mits-loading-forms').hide();
                            break;
                    }
                }
            });


            if ((val == 'aweber' && !CCLICKS.aweber_copy_paste) || val == 'mailchimp' || val == 'icontact' || (val == 'getresponse' && !CCLICKS.getresponse_copy_paste) || val == 'constantcontact' || val == 'gotowebinar') {
                var service_name = 'service';
                if (val == 'aweber') {
                    service_name = 'Aweber';
                } else if (val == 'mailchimp') {
                    service_name = 'MailChimp';
                } else if (val == 'icontact') {
                    service_name = 'iContact';
                } else if (val == 'getresponse') {
                    service_name = 'GetResponse';
                } else if (val == 'constantcontact') {
                    service_name = 'ConstantContact';
                } else if (val == 'gotowebinar') {
                    service_name = 'GoToWebinar';
                }
                $('.mits-service-name-for-t-y-p').text(service_name);
            }


            if (val != CCLICKS.selected_form_optin_type) {
                $('#' + CCLICKS.selected_form_optin_type + '-choice .btn-success.active').removeClass('btn-success active');
                CCLICKS.selected_form_optin_type = val;
                CCLICKS.selected_form_optin = null;
            }
        }
    },
    openOptinSettings: function() {
        $('#mits-modal-header').html('Integration Settings');

        if ($('#id-mits-integration-select option.integration').length <= 0) {
            $('#mits-optin-forms').hide();
            $('#mits-optin-no-forms').show();
        } else {
            $('#mits-optin-forms').show();
            $('#mits-optin-no-forms').hide();
        }

        CCLICKS.selected_form_optin = null;
        CCLICKS.selected_form_optin_type = null;
        CCLICKS.selected_webinar_key = null;
        $('#id-mits-aweber-copy-paste').val('');
        $('#id-mits-getresponse-copy-paste').val('');
        $('#id-mits-other-copy-paste').val('');
        var integration_type = CCLICKS.optin_type;
        if (integration_type && CCLICKS.optin_value) {
            CCLICKS.selected_form_optin = CCLICKS.optin_value;
            CCLICKS.selected_form_optin_type = integration_type;
        }
        CCLICKS.beforeOpenEditor(null);
        $('#mits-edit-optin-form').show();
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            CCLICKS.selectOptInFormSubmit();
        });
        CCLICKS.closeEditorListener = function() {
            CCLICKS.selected_form_id = null;
            CCLICKS.selected_form_optin = null;
            CCLICKS.selected_form_optin_type = null;
        };
        switch (integration_type) {
            //GOTOWEBINAR
            case "gotowebinar":
                if (CCLICKS.webinar) {
                    $('#gtw-on').addClass('btn-success active');
                    $('#gtw-off').removeClass('btn-danger active');
                    $('#mits-gotowebinar-check').prop('checked', true);
                    $('#gotowebinar-choice').show();
                    CCLICKS.selected_webinar_key = CCLICKS.webinar_key;
                    $('#lb-gotowebinar-forms-select').val(CCLICKS.selected_webinar_key);
                    $('#gotowebinar-choice').show();
                    $('#selected-webinar-dont-exist').hide();
                    if ($('#lb-gotowebinar-forms-select').val() != CCLICKS.selected_webinar_key) {
                        if (CCLICKS.loadedGoToWebinarWebinars) {
                            $('#selected-webinar-dont-exist').show();
                            CCLICKS.webinar_key = null;
                            CCLICKS.selected_webinar_key = null;
                        } else {
                            $('#lb-reload-btn-gotowebinar').hide();
                            $('#lb-gotowebinar-forms-select').hide();
                            $('#lb-gotowebinar-loading').show();
                            var intId = setInterval(function() {
                                if (CCLICKS.loadedGoToWebinarWebinars) {
                                    $('#lb-gotowebinar-forms-select').val(CCLICKS.selected_webinar_key);
                                    clearInterval(intId);
                                }
                                else {
                                    if (!CCLICKS.loadingGoToWebinarWebinars)
                                        CCLICKS.loadGoToWebinarWebinars(false);
                                }
                            }, 1000);
                        }
                    }
                } else {
                    $('#gtw-on').removeClass('btn-success active');
                    $('#gtw-off').addClass('btn-danger active');
                    $('#mits-gotowebinar-check').prop('checked', false);
                    $('#gotowebinar-choice').hide();
                }
                break;
                //AWEBER
            case "aweber":
                $('#id-mits-integration-select').val(integration_type);
                if (CCLICKS.aweber_copy_paste) {
                    CCLICKS.optin_type = 'aweber';
                    if (CCLICKS.optin_value) {
                        $('#id-mits-aweber-copy-paste').val(unescape(CCLICKS.optin_value));
                    }
                } else {
                    $('#lb-' + integration_type + '-forms-select').val(CCLICKS.optin_value);
                }
                break;
                //GETRESPONSE
            case "getresponse":
                $('#id-mits-integration-select').val(integration_type);
                if (CCLICKS.getresponse_copy_paste) {
                    CCLICKS.optin_type = 'getresponse';
                    if (CCLICKS.optin_value) {
                        $('#id-mits-getresponse-copy-paste').val(unescape(CCLICKS.optin_value));
                    }
                } else {
                    $('#lb-' + integration_type + '-forms-select').val(CCLICKS.optin_value);
                }
                break;
                //MAILCHIMP,INFUSIONSOFT,ICONTACT,CONSTANTCONTACT
            case "mailchimp":
            case "infusionsoft":
            case "icontact":
            case "constantcontact":
                $('#id-mits-integration-select').val(integration_type);
                $('#lb-' + integration_type + '-forms-select').val(CCLICKS.optin_value);
                break;
                //OTHER
            case "other":
                $('#id-mits-integration-select').val(integration_type);
                CCLICKS.optin_type = 'other';
                if (CCLICKS.optin_value) {
                    $('#id-mits-other-copy-paste').val(unescape(CCLICKS.optin_value));
                }
                break
        }

        if ($('#id-mits-integration-select option.integration').length == 1) {
            var one_form = $('#id-mits-integration-select option.integration').attr('value');
            $('#id-mits-integration-select').val(one_form);
            CCLICKS.selected_form_optin_type = one_form;
        }
        if (integration_type) {
            CCLICKS.selected_form_optin_type = integration_type;
        }
        if (CCLICKS.optin_value) {
            CCLICKS.selected_form_optin = CCLICKS.optin_value;
        }
        CCLICKS.openEditor(null);
    },
    openTrackingCodeSettings: function() {
        CCLICKS.beforeOpenEditor(null);
        $('#mits-modal-header').html('Tracking Code');
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#tracking-code-form').submit();
        });
        $('#mits-tracking-code').show();
        CCLICKS.openEditor(null);
        $('#id-mits-head-code').val(CCLICKS.user_head_code);
        $('#id-mits-analytics').val(CCLICKS.user_end_code);
        CCLICKS.submitFormListener = function() {
            CCLICKS.saveTrackingCodeSettings();
        };
        CCLICKS.closeEditorListener = function(cancel_button) {
            if (cancel_button !== true) {
                CCLICKS.submitFormListener();
                return;
            }
            $('#id-mits-head-code').val(CCLICKS.user_head_code);
            $('#id-mits-analytics').val(CCLICKS.user_end_code);
        };
    },
    saveTrackingCodeSettings: function() {
        CCLICKS.user_end_code = $('#id-mits-analytics').val();
        CCLICKS.user_head_code = $('#id-mits-head-code').val();
        $('#tracking-code-settings').addClass('success');
    },
    iframeFieldClickable: function() {
        CCLICKS.iframe.contents().find('#form_fields input').each(function() {
            $(this).click(function() {
                var header = $(this).attr('placeholder');
                var field_name = $(this).attr('name');
                CCLICKS.openFieldEditor(header, field_name);
            });
        });
    },
    selectOptInFormSubmit: function() {

        var selected_integration = $('#id-mits-integration-select').val();
        var selected_form = $('#mits-form-select').val();


        if (!CCLICKS.integrationLoaded || selected_integration == '') {
            return false;
        }

        $('#editable_fields_placeholder').html('<img alt="loading" src="https://googledrive.com/host/0B_Y1OBeOK_x4UGtBTjdqNjNPcWs/ajax-loader-small.gif"/> Loading fields');

        var data = {
            action: "api_call",
            integration_type: selected_integration,
            form_id: selected_form,
            request: 'cta-form-fields'
        };

        MAPI.ajaxcall({
            data: data,
            type: 'GET',
            async: true,
            success: function(data) {
                var status = (data.status).toString();
                switch (status) {
                    case "200":
                        var text_input = data.content.text_input;
//                        var hidden_input = data.content.hidden_input;
                        var text_append = '';
                        var form_append = '';
//                        var hidden_append = '';
                        var fields = {};
                        for (var i in text_input) {
                            var display_name = text_input[i].display_name;
                            var field_name = text_input[i].field_name;
                            var required = '';
                            var required_field = 'is_required="false"';

                            if (text_input[i].required == 1) {
                                required = 'disabled';
                                required_field = 'is_required="true"';
                            }
                            var visible = '';
                            var is_show = 'checked';
                            if (text_input[i].is_show == 0) {
                                visible = 'style="display: none"';
                                is_show = '';
                            }
                            var placeholder = display_name.charAt(0).toUpperCase() + display_name.substring(1);

                            var invalid_text = 'Please enter a correct value for this field';
                            var required_text = 'You must fill out this field in order to proceed';

                            fields[field_name] = {
                                is_show: text_input[i].is_show,
                                is_required: text_input[i].required,
                                placeholder: placeholder,
                                invalid_text: invalid_text,
                                required_text: required_text
                            };

                            text_append += '<li><a field_name="' + field_name + '" class="btn-block content_accordion_btn mits-editable-fields"><input type="checkbox" name="' + field_name + '" ' + required + ' ' + is_show + ' class="mits-show-fields" />' + placeholder + '</a></li>';

                            form_append += '<li field_name="' + field_name + '" ' + visible + '>';
                            form_append += '    <input type="text" placeholder="' + placeholder + '" name="' + field_name + '" ' + required_field + '>';
                            form_append += '    <span type="invalid" class="error-message">' + invalid_text + '</span>';
                            form_append += '    <span type="required" class="error-message">' + required_text + '</span>';
                            form_append += '    <i class="icon_drag"></i>';
                            form_append += '</li>';
                        }

                        $('#mits-template-iframe').contents().find('#form_fields').html(form_append);
                        $('#editable_fields_placeholder').html(text_append);
                        CCLICKS.iframeFieldClickable();
                        CCLICKS.iframe.contents().find('.capture_clicks_button[status="normal"]').click();

                        CCLICKS.changeable_fields = {
                            selected_integration: selected_integration,
                            selected_form: selected_form,
                            fields: fields
                        };

                        break;
                    case "201":
                        alert(data.message);
                        break;
                }
            }
        });

        $('#lb-button-' + CCLICKS.selected_form_id).addClass('success');
        CCLICKS.closeEditor();
        CCLICKS.markChange();
    },
    openFieldEditor: function(header, field_name) {
        $('#mits-modal-header').html(header);

        CCLICKS.beforeOpenEditor(field_name);
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            CCLICKS.selectFieldSubmit(field_name);
        });
        $('#mits-form-fields').show();
        $('#mits-form-fields').attr('name', field_name);

        $('.mits-form-field-details[field_type="placeholder_text"]').val(CCLICKS.changeable_fields['fields'][field_name]['placeholder']);
        $('.mits-form-field-details[field_type="invalid_message"]').val(CCLICKS.changeable_fields['fields'][field_name]['invalid_text']);
        $('.mits-form-field-details[field_type="required_message"]').val(CCLICKS.changeable_fields['fields'][field_name]['required_text']);

        if (CCLICKS.changeable_fields['fields'][field_name]['is_required'] == '1') {
            $('#mits-form-fields .is_field_required').prop('disabled', true);
            $('#mits-form-fields .is_field_required').prop('checked', true);
        } else {
            $('#mits-form-fields .is_field_required').prop('disabled', false);
            $('#mits-form-fields .is_field_required').prop('checked', false);
        }

        $('.mits-form-field-details[field_type="placeholder_text"]').val(header);

        CCLICKS.openEditor(null);
    },
    selectFieldSubmit: function(element) {

        var place_holder = $('.mits-form-field-details[field_type="placeholder_text"]').val();
        var invalid_message = $('.mits-form-field-details[field_type="invalid_message"]').val();
        var required_message = $('.mits-form-field-details[field_type="required_message"]').val();
        var is_required = $('#mits-form-fields[name="' + element + '"]').find('.is_field_required').prop('checked');

        CCLICKS.changeable_fields['fields'][element]['is_required'] = is_required ? 1 : 0;
        CCLICKS.changeable_fields['fields'][element]['placeholder'] = place_holder;
        CCLICKS.changeable_fields['fields'][element]['invalid_text'] = invalid_message;
        CCLICKS.changeable_fields['fields'][element]['required_text'] = required_message;
        CCLICKS.closeEditor();
        CCLICKS.markChange();
    },
    saveIt: function(callback, beforeAPICb) {

        if (CCLICKS.saving) {
            return;
        }
        var request = 'save-cta';

        CCLICKS.iframe.contents().find('.mits-text-editable').attr('contentEditable', null);
        var element_data = $.extend(true, {}, CCLICKS.changeble_elements);
        for (var k in element_data) {
            delete element_data[k]['name-elements'];
            delete element_data[k]['phone-elements'];
            delete element_data[k]['elements'];
            delete element_data[k]['name'];
            delete element_data[k]['function_to_call'];
            delete element_data[k]['icon'];
            delete element_data[k]['default_text'];
            delete element_data[k]['hidden'];
            delete element_data[k]['connected-image'];
            delete element_data[k]['connected-link'];
            delete element_data[k]['comment'];
            delete element_data[k]['video_parent'];
        }
        CCLICKS.saving = true;
        if (CCLICKS.hasValidationError)
            CCLICKS.hideValidationError();
        if (beforeAPICb)
            beforeAPICb();
        MAPI.ajaxcall({
            data: {
                "action": "api_call",
                'request': request,
                'capture_clicks_id': CCLICKS.capture_clicks_id,
                'template_id': CCLICKS.template_id,
                'capture_clicks_name': $('#id_capture_clicks_name').val(),
                'data': JSON.stringify(element_data),
                'color_data': JSON.stringify(CCLICKS.changable_colors),
                'style_data': JSON.stringify(CCLICKS.changeable_attribute),
                'field_data': JSON.stringify(CCLICKS.changeable_fields),
                'user_end_code': CCLICKS.user_end_code,
                'user_head_code': CCLICKS.user_head_code
            },
            type: 'POST',
            async: true,
            success: function(data) {
                var id = data.body * 1;
                var onSuccess = function() {
                    CCLICKS.change_made = false;
                    $("#save-button").removeClass("disabled");
                    $("#save-button").removeClass("has-changes");
                    CCLICKS.saving = false;
                    CCLICKS.capture_clicks_id = id;
                    CCLICKS.edit = true;
                    setTimeout(function() {
                        $("#mits-publish-modal iframe")[0].contentDocument.location.reload();
                    }, 500);
                    if (callback)
                        callback(true);
                };
                onSuccess();
            },
            error: function() {
                CCLICKS.saving = false;
                if (callback)
                    callback(false);
            }
        });
    },
    setListeners: function() {
        var pre_submit = function() {
            if (!$(this).valid()) {
                return false;
            }
            CCLICKS.submitFormListener();
            CCLICKS.closeEditorListener = function() {
            };
            CCLICKS.closeEditor();
            CCLICKS.markChange();
            return false;
        };
        $('#mits-edit-text form').submit(pre_submit);
        $('#mits-edit-rich-text form').submit(pre_submit);
        $('#mits-edit-richtext-area form').submit(pre_submit);
        $('#mits-change-link form').submit(pre_submit);
        $('#mits-edit-btn-text form').submit(pre_submit);
        $('#mits-edit-placeholder form').submit(pre_submit);
        $('#mits-edit-video form').submit(pre_submit);
        $('#mits-edit-optin-box form').submit(pre_submit);
        $('#mits-change-style form').submit(pre_submit);
        $('#mits-change-font form').submit(pre_submit);
        $('#mits-edit-area form').submit(pre_submit);
        $('#tracking-code-form').submit(pre_submit);
        $('#basic-settings-form').submit(pre_submit);
        $('#exit-popup-settings-form').submit(pre_submit);
        $('#mits-modal form').validate();
        $('#mits-modal-forms-form').validate();
        $('#mits-modal-forms-form').submit(function() {
            CCLICKS.selectOptInFormSubmit();
            return false;
        });
        $('#mits-change-js-variables form').submit(pre_submit);
        $('#mits-edit-analytics form').submit(function() {
            if (!$(this).valid()) {
                return false;
            }
            CCLICKS.submitFormListener();
            CCLICKS.markChange();
            return false;
        });
        $('#id-mits-integration-select').change(function() {
            CCLICKS.formTypeChangeAjax('', '');
        });
        $('#lb-gotowebinar-forms-select').change(function() {
            $('#selected-webinar-dont-exist').fadeOut();
        });
    },
    setJsVariables: function() {
        var variables = false;
        for (var k in CCLICKS.template_js_variables) {
            variables = true;
            var variable = CCLICKS.template_js_variables[k];
            var html_form = '';
            var value = variable['dafault'];
            var name = variable['name'];
            var validate = variable['validate'];
            var desc = '';
            if (variable['desc'])
            {
                desc = variable['desc'];
            }

            if (CCLICKS.js_variables[k]) {
                value = CCLICKS.js_variables[k]['value'];
            }
            html_form = ' <div class="control-group">';
            html_form += '  <label class="control-label" for="id-lpjs-' + k + '">' + name + '</label>' + desc;
            html_form += '  <div class="input-space controls">';
            html_form += '      <input id="id-lpjs-' + k + '" data-id="' + k + '" class="input-w90 js-variable-input ' + validate + '" type="text" value="' + value + '" name="lpjs-' + k + '">';
            html_form += '  </div>';
            html_form += '</div>';
            $('#js-var-space').append(html_form);
        }
        if (variables) {
            $('#js-settings').click(function() {
                CCLICKS.editJavasCriptVariables();
                return false;
            });
            $('#js-settings').show();
        }
    },
    setFonts: function() {
        CCLICKS.font_options_str = '<option value="Arial">Default font</option>';
        for (var k in GOOGLE_FONTS) {
            $('head').append($('<link href="' + GOOGLE_FONTS[k]['url'] + '" rel="stylesheet" type="text/css" />'));
            CCLICKS.iframe.contents().find('head').append($('<link href="' + GOOGLE_FONTS[k]['url'] + '" rel="stylesheet" type="text/css" />'));
            CCLICKS.font_options_str = CCLICKS.font_options_str + '<option value="' + GOOGLE_FONTS[k]['name'] + '">' + GOOGLE_FONTS[k]['name'] + '</option>';
        }
    },
    setChangebleStyle: function() {


        /* Colors */
        var colors = false;
        $('#mits-template-iframe').contents().find('#color_changeable color').each(function() {
            colors = true;
            var $this = $(this);
            var color = $.trim($this.attr('value'));
            var name = $.trim($this.attr('name'));
            var element = $.trim($this.attr('element'));
            var type = $.trim($this.attr('type'));
            var more = $this.attr('more') ? $.trim($this.attr('more')) : '';
            var parent = $this.attr('parent') ? $.trim($this.attr('parent')) : '';
            var value = color;
            if (CCLICKS.color_data[color] && CCLICKS.color_data[color]['value']) {
                value = CCLICKS.color_data[color]['value'];
            }
            CCLICKS.changable_colors[color] = {
                'type': type,
                'name': name,
                'element': element,
                'value': value,
                'more': more,
                'parent': parent
            };
        });
        if (colors) {
            for (var c in CCLICKS.changable_colors) {
                var element_id = CCLICKS.changable_colors[c]['element'].replace('#', '');
                var color_form_html = '';
                color_form_html = color_form_html + '<div form_element="' + element_id + '" parent_element="' + CCLICKS.changable_colors[c]['parent'] + '" class="control-group">';
                color_form_html = color_form_html + ' <label class="control-label">' + CCLICKS.changable_colors[c]['name'] + '</label>';
                color_form_html = color_form_html + ' <div class="input-space controls">';
                color_form_html = color_form_html + '     <input type="minicolors" data-lb-base-color="' + c + '" value="' + CCLICKS.changable_colors[c]['value'] + '" class="color-picker input-mini" />';
                color_form_html = color_form_html + ' </div>';
                color_form_html = color_form_html + '</div>';
                $('#colors-inp-space').append($(color_form_html));
            }
            $("input.color-picker").minicolors({
                change: function() {
                    CCLICKS.doStyleQuickChange();
                },
                changeDelay: 0
            });
        } else {
            $('.mits-colors-space').hide();
        }
        $('#style-settings').show();
        $('#mits-template-iframe').contents().find('head style[type="text/css"]').each(function() {
            $this = $(this);
            $this.attr('data-lb-original', $this.text());
            $this.attr('data-lb-changing', '');
        });
        CCLICKS.doStyleChange();

        /* Other styles */
        var styles = false;
        $('#mits-template-iframe').contents().find('#attribute_changeable attribute').each(function() {
            styles = true;
            var $this = $(this);
            var attribute_id = $this.attr('att_id');
            var attribute_type = $this.attr('att_type');
            var default_value = $this.attr('def_value');
            var value = $this.attr('att_value') ? $this.attr('att_value') : default_value;
            var element = $this.attr('element');
            var edit_type = $this.attr('edit_type');
            var display_name = $this.attr('display_name');
            var min = $this.attr('min') ? $this.attr('min') : '';
            var max = $this.attr('max') ? $this.attr('max') : '';

            if (CCLICKS.style_data[attribute_id] && CCLICKS.style_data[attribute_id]['value']) {
                value = CCLICKS.style_data[attribute_id]['value'];
            }

            CCLICKS.changeable_attribute[attribute_id] = {
                'type': attribute_type,
                'element': element,
                'value': value,
                'display_name': display_name,
                'edit_type': edit_type,
                'min': min,
                'max': max
            };
        });

        if (styles) {
            for (var att_id in  CCLICKS.changeable_attribute) {
                var element = CCLICKS.changeable_attribute[att_id]['element'];
                var element_id = element.replace('#', '');
                var attribute_type = CCLICKS.changeable_attribute[att_id]['type'];
                var edit_type = CCLICKS.changeable_attribute[att_id]['edit_type'];
                var value = CCLICKS.changeable_attribute[att_id]['value'];
                CCLICKS.element_attribute[element] = element_id;

                switch (edit_type) {
                    case "slider":
                        var min = parseFloat(CCLICKS.changeable_attribute[att_id]['min']);
                        var max = parseFloat(CCLICKS.changeable_attribute[att_id]['max']);

                        var style_html = '';
                        style_html += '<div form_element="' + element_id + '" class="control-group mits-form-attribute">';
                        style_html += '  <label>' + CCLICKS.changeable_attribute[att_id]['display_name'] + '</label>';
                        style_html += '  <div class="input-space controls">';
                        style_html += '      <input type="text" class="element_style_attribute" id="' + att_id + '" name="' + att_id + '"  attribute="' + attribute_type + '"/>';
                        style_html += '  </div>';
                        style_html += '</div>';
                        $('#mits-edit-styles').append(style_html);
                        
                        var slider = $('#' + att_id).slider({
                            min: min,
                            max: max,
                            value: parseFloat(value),
                            formatter: function(value) {
                                return value + 'px';
                            }
                        });
                        //Init value
                        $('#mits-template-iframe').contents().find(element).css(attribute_type, value + 'px');

                        slider.on('slide slideStop', function(slide) {
                            var att_id = $(this).attr('id');
                            var element = '#' + $(this).closest('.mits-form-attribute').attr('form_element');
                            var attribute = $(this).attr('attribute');
                            $('#mits-template-iframe').contents().find(element).css(attribute, slide.value + 'px');
                            CCLICKS.changeable_attribute[att_id]['value'] = slide.value;
                        });
                        break;
                        
                    case "select-font":
                        var font_form_html = '';
                        font_form_html += '<div form_element="' + element_id + '" class="control-group mits-form-attribute">';
                        font_form_html += '   <label>Font</label>';
                        font_form_html += '   <div class="input-space controls">';
                        font_form_html += '       <select id="' + att_id + '" attribute="' + attribute_type + '" class="element_style_attribute input-large">' + CCLICKS.font_options_str + '</select>';
                        font_form_html += '   </div>';
                        font_form_html += '</div>';
                        $('#mits-edit-styles').append(font_form_html);

                        //Init value
                        $('#' + att_id).val(value);
                        $('#mits-template-iframe').contents().find(element).css(attribute_type, value);

                        $('#' + att_id).change(function() {
                            var att_id = $(this).attr('id');
                            var value = $(this).val();
                            var element = '#' + $(this).closest('.mits-form-attribute').attr('form_element');
                            var attribute = $(this).attr('attribute');
                            $('#mits-template-iframe').contents().find(element).css(attribute, value);
                            CCLICKS.changeable_attribute[att_id]['value'] = value;
                        });
                        break;

                    case "select-border-style":
                        var font_form_html = '';
                        font_form_html += '<div form_element="' + element_id + '" class="control-group mits-form-attribute">';
                        font_form_html += '   <label>Border Style</label>';
                        font_form_html += '   <div class="input-space controls">';
                        font_form_html += '       <select id="' + att_id + '" attribute="' + attribute_type + '" class="element_style_attribute input-large">';
                        font_form_html += '           <option value="solid">Solid</option>';
                        font_form_html += '           <option value="dashed">Dashed</option>';
                        font_form_html += '           <option value="dotted">Dotted</option>';
                        font_form_html += '           <option value="double">Double</option>';
                        font_form_html += '       </select>';
                        font_form_html += '   </div>';
                        font_form_html += '</div>';
                        $('#mits-edit-styles').append(font_form_html);

                        //Init value

                        $('#' + att_id).val(value);
                        $('#mits-template-iframe').contents().find(element).css(attribute_type, value);

                        $('#' + att_id).change(function() {
                            var att_id = $(this).attr('id');
                            var value = $(this).val();
                            var element = '#' + $(this).closest('.mits-form-attribute').attr('form_element');
                            var attribute = $(this).attr('attribute');
                            $('#mits-template-iframe').contents().find(element).css(attribute, value);
                            CCLICKS.changeable_attribute[att_id]['value'] = value;
                        });
                        break;
                }
            }
        }
    },
    showElementAttribute: function(element_id) {
        if (CCLICKS.element_attribute['#' + element_id]) {
            $('[form_element]').hide();
            $('#mits-edit-styles').show();
            $('[form_element="' + element_id + '"]').show();
        }
    },
    saveElementAttribute: function(element_id) {
        if (CCLICKS.element_attribute['#' + element_id]) {
            var form_element = $('[form_element="' + element_id + '"]');
            form_element.find('.element_style_attribute').each(function() {
                var att_id = $(this).attr('id');
                var attribute = $(this).attr('attribute');
                var new_value = CCLICKS.changeable_attribute[att_id]['value'];
                var element = '#' + element_id;

                $('#' + att_id).val(new_value);
                $('#mits-template-iframe').contents().find(element).css(attribute, new_value);

                if ($('#' + att_id).prev('div').hasClass('slider')) {
                    $('#' + att_id).slider('setValue', parseFloat(new_value));
                }
            });
        }
    },
    resetElementAttribute: function(element_id) {
        if (CCLICKS.element_attribute['#' + element_id]) {
            var form_element = $('[form_element="' + element_id + '"]');
            form_element.find('.element_style_attribute').each(function() {
                var att_id = $(this).attr('id');
                var old_value = CCLICKS.changeable_attribute[att_id]['value'];
                var attribute = $(this).attr('attribute');
                var element = '#' + element_id;

                $('#' + att_id).val(old_value);
                $('#mits-template-iframe').contents().find(element).css(attribute, old_value);

                if ($('#' + att_id).prev('div').hasClass('slider')) {
                    $('#' + att_id).slider('setValue', parseFloat(old_value));
                }
            });
        }
    },
    openStyleSettings: function() {
        CCLICKS.beforeOpenEditor(null);
        $('#mits-modal-header').html('Style Settings');
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#mits-change-style form').submit();
        });
        CCLICKS.openEditor();

        $('#mits-edit-styles').show();
        $('#mits-editor-tools-space').show();
        $('[form_element]').hide();
        $('[form_element="form_style"],[parent_element="#form_style"]').show();
        $('#mits-change-style').show();
        CCLICKS.submitFormListener = function() {
            $("input.color-picker").each(function() {
                var $this = $(this);
                var value = $this.val();
                var key = $this.attr('data-lb-base-color');
                if (CCLICKS.changable_colors[key]['value'] != value) {
                    if (value === '') {
                        value = key;
                    }
                    CCLICKS.changable_colors[key]['value'] = value;
                }
            });
            $('#style-settings').addClass('success');
            CCLICKS.doStyleChange();
            CCLICKS.saveElementAttribute('form_style');
        };
        CCLICKS.closeEditorListener = function() {
            CCLICKS.resetElementAttribute('form_style');
            $("input.color-picker").each(function() {
                var $this = $(this);
                var key = $this.attr('data-lb-base-color');
                $this.val(CCLICKS.changable_colors[key]['value'].toLowerCase());
                $this.minicolors('value', CCLICKS.changable_colors[key]['value'].toLowerCase());
            });
            CCLICKS.doStyleChange();
        };
    },
    openFontSettings: function() {
        CCLICKS.beforeOpenEditor(null);
        $('#mits-modal-header').html('Style Settings');
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#mits-change-font form').submit();
        });
        CCLICKS.openEditor();
        $('#mits-change-font').show();
        CCLICKS.submitFormListener = function() {
            $("select.font-picker").each(function() {
                var $this = $(this);
                var value = $this.val();
                var key = $this.attr('data-lb-base-font');
                if (CCLICKS.changable_fonts[key]['value'] != value) {
                    CCLICKS.changable_fonts[key]['value'] = value;
                }
            });
            $('#style-settings').addClass('success');
            CCLICKS.doStyleChange();
        };
        CCLICKS.closeEditorListener = function() {
            $("select.font-picker").each(function() {
                var $this = $(this);
                var key = $this.attr('data-lb-base-font');
                $this.val(CCLICKS.changable_fonts[key]['value']);
            });
            CCLICKS.doStyleChange();
        };
    },
    resetColors: function() {
        $("input.color-picker").each(function() {
            var $this = $(this);
            var key = $this.attr('data-lb-base-color');
            $this.val(CCLICKS.changable_colors[key]['value'].toLowerCase());
            $this.minicolors('value', CCLICKS.changable_colors[key]['value'].toLowerCase());
        });
        CCLICKS.doStyleQuickChange();
    },
    doStyleChange: function() {
        CCLICKS.doColorChange();
    },
    doStyleQuickChange: function() {
        CCLICKS.doColorQuickChange();
    },
    doColorQuickChange: function() {
        $("input.color-picker").each(function() {
            var $this = $(this);
            var key = $this.attr('data-lb-base-color');
            var new_color = $this.val();
            var element = CCLICKS.changable_colors[key]['element'];
            var type = CCLICKS.changable_colors[key]['type'];
            var more = CCLICKS.changable_colors[key]['more'];
            CCLICKS.executeColorChangeInCss(element, type, new_color, more);
        });
    },
    doColorChange: function() {
        for (var k in CCLICKS.changable_colors) {
            if (CCLICKS.changable_colors[k]['value']) {
                var new_color = CCLICKS.changable_colors[k]['value'];
                var element = CCLICKS.changable_colors[k]['element'];
                var type = CCLICKS.changable_colors[k]['type'];
                var more = CCLICKS.changable_colors[k]['more'];
                CCLICKS.executeColorChangeInCss(element, type, new_color, more);
            }
        }
    },
    executeColorChangeInCss: function(element, type, new_color, more) {
        if (more != '') {
            new_color = more + ' ' + new_color;
        }
        $('#mits-template-iframe').contents().find(element).css(type, new_color);
    },
    setEditableText: function() {
        $('#mits-template-iframe').contents().find('form').submit(CCLICKS.return_false);
        $('#mits-template-iframe').contents().find('a').click(function(e) {
            e.preventDefault();
        });
        $('#mits-template-iframe').contents().find('label').click(CCLICKS.return_false);

        $('#mits-template-iframe').contents().find('.mits_form_element').each(function() {
            var elem = this;
            var $this = $(this);
            var element_id = null;
            var no_clicking = false;
            var exist = false;
            var element_data = {};
            var function_to_call = function() {
            };
            if ($this.attr('element_id')) {
                element_id = $this.attr('element_id');
            }
            if (!CCLICKS.changeble_elements[element_id]) {
                element_data = {
                    'element_id': element_id,
                    'removable': false,
                    'removed': false,
                    'hidden': false,
                    'comment': ($this.attr('data-lb-comment')) ? $this.attr('data-lb-comment') : null,
                    'elements': [elem],
                    'type': $this.attr('element_type'),
                    'name': $this.attr('element_name'),
                    'icon': ''
                };
            } else {
                exist = true;
                element_data = CCLICKS.changeble_elements[element_id];
                element_data['elements'].push(elem);
            }

            switch (element_data['type']) {
                //IMAGE
                case "image":
                    var img_id = '';
                    if ((CCLICKS.edit || exist) && CCLICKS.edit_data[element_id] && CCLICKS.edit_data[element_id]['type'] == 'image' && CCLICKS.edit_data[element_id]['url']) {
                        $this.attr('src', CCLICKS.edit_data[element_id]['url']);
                        img_id = CCLICKS.edit_data[element_id]['id'];
                    }
                    if (!exist) {
                        function_to_call = function() {
                            CCLICKS.editImage(element_id);
                        };
                        element_data['type'] = 'image';
                        element_data['default_url'] = $this.attr('src');
                    }

                    element_data['url'] = $this.attr('src');
                    element_data['id'] = img_id;
                    var $parent = $this.parent();
                    if ($parent.is('[element_type~="link"]') && $parent.is('[element_type~="link-only"]')) {
                        element_data['hidden'] = true;
                        no_clicking = true;
                    }
                    var video_parent = $this.closest('div,section');
                    if (video_parent.is('[element_type~=video]')) {
                        element_data['video_parent'] = video_parent;
                    } else {
                        element_data['video_parent'] = false;
                    }
                    break;
                    //TEXT
                case "text":
                    if ((CCLICKS.edit || exist) && CCLICKS.edit_data[element_id] && CCLICKS.edit_data[element_id]['text']) {
                        $this.html(CCLICKS.edit_data[element_id]['text']);
                        element_data['default_text'] = null;
                    }
                    if (!exist) {
                        function_to_call = function() {
                            CCLICKS.editText(elem, element_id);
                        };
                        element_data['type'] = 'text';
                        element_data['default_text'] = $this.html();
                        element_data['text'] = $this.html();
                    }

                    break;
                    //SUBMIT
                case "submit":
                    if ((CCLICKS.edit || exist) && CCLICKS.edit_data[element_id] && CCLICKS.edit_data[element_id]['value']) {
                        $this.val(CCLICKS.edit_data[element_id]['value']);
                        element_data['default_value'] = null;
                    }
                    if (!exist) {
                        function_to_call = function() {
                            CCLICKS.editSubmitButton(element_id);
                        };
                        element_data['type'] = 'submit';
                        element_data['value'] = $this.val();
                        element_data['default_value'] = $this.val();
                    }
                    break;
            }

//            if ($this.is('form[data-lb~="editable-opt-in-form"]') && (!exist || element_data['type'] == 'opt-in-form')) {
//                function_to_call = function() {
//                    CCLICKS.editOptinForm(element_id);
//                };
//                no_clicking = true;
//                if (!exist) {
//                    element_data['type'] = 'opt-in-form';
//                    element_data['redirect_url'] = '';
//                    element_data['webinar'] = false;
//                    element_data['use-name'] = false;
//                    element_data['use-phone'] = false;
//                    element_data['name-opt-in'] = false;
//                    element_data['phone-opt-in'] = false;
//                    element_data['name-elements'] = [];
//                    element_data['phone-elements'] = [];
//                    element_data['webinar_key'] = null;
//                    if ($this.find('input[data-lb~="opt-in-name"]').get(0)) {
//                        element_data['name-opt-in'] = true;
//                    }
//                    if ($this.find('input[data-lb~="opt-in-phone"]').get(0)) {
//                        element_data['phone-opt-in'] = true;
//                    }
//
//                    if (CCLICKS.edit && CCLICKS.edit_data[element_id] && CCLICKS.edit_data[element_id]['type'] == 'opt-in-form') {
//                        if (CCLICKS.edit_data[element_id]['optin_type'] && CCLICKS.edit_data[element_id]['value']) {
//                            element_data['optin_type'] = CCLICKS.edit_data[element_id]['optin_type'];
//                            element_data['value'] = CCLICKS.edit_data[element_id]['value'];
//                            if (element_data['optin_type'] == 'aweber' || element_data['optin_type'] == 'mailchimp' || element_data['optin_type'] == 'icontact' || element_data['optin_type'] == 'getresponse' || element_data['optin_type'] == 'constantcontact' || element_data['optin_type'] == 'gotowebinar') {
//                                element_data['redirect_url'] = CCLICKS.edit_data[element_id]['redirect_url'];
//                            }
//                            if (element_data['name-opt-in'] && CCLICKS.edit_data[element_id]['use-name']) {
//                                element_data['use-name'] = true;
//                            }
//                            if (element_data['phone-opt-in'] && CCLICKS.edit_data[element_id]['use-phone']) {
//                                element_data['use-phone'] = true;
//                            }
//                            if (!AVAILABLE_SERVICES[element_data['optin_type']]) {
//                                element_data['optin_type'] = '';
//                                element_data['value'] = '';
//                            }
//                            if (CCLICKS.edit_data[element_id]['webinar'] && CCLICKS.edit_data[element_id]['webinar_key'] && AVAILABLE_SERVICES['gotowebinar']) {
//                                element_data['webinar'] = CCLICKS.edit_data[element_id]['webinar'];
//                                element_data['webinar_key'] = CCLICKS.edit_data[element_id]['webinar_key'];
//                            }
//                        } else {
//                            element_data['optin_type'] = '';
//                            element_data['value'] = '';
//                        }
//                    } else {
//                        element_data['optin_type'] = '';
//                        element_data['value'] = '';
//                    }
//                }
//                element_data['icon'] = 'optinform';
//            }
            if ($this.is('[removable]') || element_data['removable']) {
                element_data['removable'] = true;
                if (!exist && !element_data['type']) {
                    function_to_call = function() {
                        CCLICKS.editRemovable(elem, element_id);
                    };
                }
                if (!element_data['type']) {
                    element_data['type'] = 'removable';
                }
                if (CCLICKS.edit && CCLICKS.edit_data[element_id] && CCLICKS.edit_data[element_id]['removed'] === true) {
                    element_data['removed'] = true;
                }
                if (element_data['removed'] === true) {
                    $this.hide();
                }

            }

            if (!exist) {
                element_data['function_to_call'] = function() {
                    if (CCLICKS.selected_element_id && (CCLICKS.selected_element_id != element_id || (CCLICKS.nowEditingElement === false || CCLICKS.nowEditingElement != CCLICKS.selected_element_id))) {
                        CCLICKS.closeEditorListener();
                    }
                    CCLICKS.selected_element_id = element_id;
                    function_to_call();
                };
            }
            if (element_id) {
                CCLICKS.changeble_elements[element_id] = element_data;
            }
            if (element_data['type'] != 'opt-in-form' && element_data['type'] != 'fadin-box') {
                $this.css('cursor', 'pointer');
            }
            if (!no_clicking) {
                $this.click(function(event) {
                    if (CCLICKS.openingEditWindow) {
                        return;
                    }
                    CCLICKS.openingEditWindow = true;
                    element_data['function_to_call']();
                    setTimeout(function() {
                        CCLICKS.openingEditWindow = false;
                    }, 100);
                });
            }
        });

        for (var k in CCLICKS.changeble_elements) {
            var addclass = 'content_accordion_btn';
            if (CCLICKS.changeble_elements[k]['element_id'] === 'opt-in')
                addclass = 'accordion_btn';
            var button_elem = $('<a>', {
                'id': 'lb-button-' + CCLICKS.changeble_elements[k]['element_id'],
                'class': 'btn-block ' + addclass,
                'href': '#popup_settings'
            }).html(CCLICKS.changeble_elements[k]['name'] + '<span></span>');
            button_elem.click(CCLICKS.changeble_elements[k]['function_to_call']);
            var mouseOver = function(key) {
                return function() {
                    var this_elems = CCLICKS.changeble_elements[key]['elements'];
                    if (CCLICKS.changeble_elements[key]['removable'] === true && CCLICKS.changeble_elements[key]['removed'] === true) {
                        return;
                    }
                    var addBorder = function(elem) {
                        elem = $(elem);
                        var offset = elem.offset();
                        var width = elem.outerWidth();
                        var height = elem.outerHeight();
                        var border_top = $('<div></div>', {
                            'class': 'lb-border-element',
                            'data-lb-top': offset.top
                        });
                        var border_bottom = $('<div></div>', {
                            'class': 'lb-border-element',
                            'data-lb-top': offset.top + height - 1
                        });
                        var border_left = $('<div></div>', {
                            'class': 'lb-border-element',
                            'data-lb-top': offset.top
                        });
                        var border_right = $('<div></div>', {
                            'class': 'lb-border-element',
                            'data-lb-top': offset.top
                        });
                        $('body').append(border_top, border_bottom, border_left, border_right);
                        border_top.width(width);
                        border_bottom.width(width);
                        border_left.height(height);
                        border_right.height(height);
                        var elem_top = offset.top + CCLICKS.iframe_top_position - CCLICKS.iframe.contents().scrollTop();
                        var elem_left = offset.left + (($('#template-settings').attr('data-hidden') === 'true') ? 0 : $('#template-settings').width());
                        border_top.css('top', elem_top + 'px');
                        border_top.css('left', elem_left + 'px');
                        border_left.css('top', elem_top + 'px');
                        border_left.css('left', elem_left + 'px');
                        border_bottom.css('top', (elem_top + height - 1) + 'px');
                        border_bottom.css('left', elem_left + 'px');
                        border_right.css('top', elem_top + 'px');
                        border_right.css('left', (elem_left + width) + 'px');
                        if ((CCLICKS.changeble_elements[key]['type'] != 'image' && CCLICKS.changeble_elements[key]['element_id'] != 'background') && CCLICKS.changeble_elements[key]['type'] != 'opt-in-form') {
                            var zindex = '100';
                            if (elem.parent().hasClass('fancybox-pop')) {
                                zindex = '-1';
                            }
                            var background_clickable = $('<div></div>', {
                                'class': 'lb-border-element',
                                'data-lb-top': offset.top,
                                'style': 'background: rgba(255,255,225,0.4);box-shadow: none;cursor:pointer;z-index:' + zindex
                            });
                            background_clickable.css('top', elem_top + 'px');
                            background_clickable.css('left', elem_left + 'px');
                            background_clickable.width(width);
                            background_clickable.height(height);
                            background_clickable.click(CCLICKS.changeble_elements[key]['function_to_call']);
                            $('body').append(background_clickable);
                        }
                    };
                    $('.lb-border-element').remove();
                    $('#editable-elements a.hover').removeClass('hover');
                    $('#lb-button-' + key).addClass('hover');
                    for (var iii in this_elems) {
                        addBorder(this_elems[iii]);
                    }
                };
            };
            if (CCLICKS.changeble_elements[k])
                var mouseOut = function(key) {
                    return function() {
                        $('.lb-border-element').remove();
                        $('#editable-elements a.hover').removeClass('hover');
                    };
                };
            if (!CCLICKS.changeble_elements[k]['hidden']) {
                button_elem.mouseover(mouseOver(k));
                if (CCLICKS.changeble_elements[k]['type'] != 'opt-in-form' && CCLICKS.changeble_elements[k]['type'] != 'fadin-box') {
                    for (var i in CCLICKS.changeble_elements[k]['elements']) {
                        $(CCLICKS.changeble_elements[k]['elements'][i]).mouseover(mouseOver(k));
                    }
                }

                $('#editable-elements #editable_placeholder').append($('<li>').append(button_elem));

            }
        }
        CCLICKS.positionLeftPanel();
        $('#template-settings').fadeIn();
        CCLICKS.iframe.fadeIn();
        $('#mits-iframe-wrapper').removeClass('iframe-loading');
    },
    positionLeftPanel: function() {
        $('#template-settings').css('top', $('#mits-top-navigation').outerHeight() + 'px');
    },
    checkIntegrationLoadedStatus: function() {
        if (CCLICKS.allIntegrationsLoaded()) {
            $('#lb-button-opt-in').css({
                'color': ''
            });
        }
    },
    allIntegrationsLoaded: function() {
        return (CCLICKS.loadedAWeberForms === true && CCLICKS.loadedMailChimpForms === true && CCLICKS.loadedIContactForms === true && CCLICKS.loadedInfusionsoftForms === true && CCLICKS.loadedGetresponseForms === true && CCLICKS.loadedGoToWebinarWebinars === true && CCLICKS.loadedConstantContactForms === true);
    },
//    allIntegrationsLoaded: function() {
//        return (CCLICKS.loadedBribeMailFiles === true && CCLICKS.loadedAWeberForms === true && CCLICKS.loadedMailChimpForms === true && CCLICKS.loadedIContactForms === true && CCLICKS.loadedInfusionsoftForms === true && CCLICKS.loadedGetresponseForms === true && CCLICKS.loadedGoToWebinarWebinars === true && CCLICKS.loadedConstantContactForms === true);
//    },
    editOptinForm: function(element_id) {
        if ($('#mits-template-iframe').contents().find('.hidden_popup').length > 0) {
            $('#mits-template-iframe')[0].contentWindow.fancybox(false, true);
        }

        if ($('#id-mits-integration-select option.integration').length <= 0) {
            $('#mits-optin-forms').hide();
            $('#mits-optin-no-forms').show();
        } else {
            $('#mits-optin-forms').show();
            $('#mits-optin-no-forms').hide();
        }
        $('#officeautopilot-error-msg').removeClass('error-msg');
        $('#shoppingcart-error-msg').removeClass('error-msg');
        $('#sendreach-error-msg').removeClass('error-msg');
        CCLICKS.selected_form_id = null;
        CCLICKS.selected_form_optin = null;
        CCLICKS.selected_form_optin_type = null;
        CCLICKS.selected_webinar_key = null;
        $('#id-mits-aweber-copy-paste').val('');
        $('#id-mits-getresponse-copy-paste').val('');
        $('#id-mits-other-copy-paste').val('');
        $('#id-mits-officeautopilot-copy-paste').val('');
        $('#id-mits-sendreach-copy-paste').val('');
        $('#id-mits-shoppingcart-copy-paste').val('');
        var integration_type = CCLICKS.changeble_elements[element_id]['optin_type'];
        CCLICKS.selected_form_id = element_id;
        if (integration_type && CCLICKS.changeble_elements[element_id]['value']) {
            CCLICKS.selected_form_optin = CCLICKS.changeble_elements[element_id]['value'];
            CCLICKS.selected_form_optin_type = integration_type;
        }
//        $('#mits-modal-header').html(CCLICKS.changeble_elements[element_id]['name'] + ' <i class="editico ic-' + CCLICKS.changeble_elements[element_id]['icon'] + ' pull-right"></i>');
        CCLICKS.beforeOpenEditor(element_id);
        $('#mits-edit-optin-form').show();
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            CCLICKS.selectOptInFormSubmit();
        });
        CCLICKS.closeEditorListener = function() {
            CCLICKS.showHideFirstName(CCLICKS.changeble_elements[CCLICKS.selected_form_id]['use-name']);
            CCLICKS.showHidePhone(CCLICKS.changeble_elements[CCLICKS.selected_form_id]['use-phone']);
            CCLICKS.selected_form_id = null;
            CCLICKS.selected_form_optin = null;
            CCLICKS.selected_form_optin_type = null;
        };
        /* GoToWebinar */
        if (CCLICKS.changeble_elements[element_id]['webinar'] && integration_type != 'gotowebinar') {
            $('#gtw-on').addClass('btn-success active');
            $('#gtw-off').removeClass('btn-danger active');
            $('#mits-gotowebinar-check').prop('checked', true);
            $('#gotowebinar-choice').show();
            CCLICKS.selected_webinar_key = CCLICKS.changeble_elements[element_id]['webinar_key'];
            $('#lb-gotowebinar-forms-select').val(CCLICKS.selected_webinar_key);
            $('#gotowebinar-choice').show();
            $('#selected-webinar-dont-exist').hide();
            if ($('#lb-gotowebinar-forms-select').val() != CCLICKS.selected_webinar_key) {
                if (CCLICKS.loadedGoToWebinarWebinars) {
                    $('#selected-webinar-dont-exist').show();
                    CCLICKS.changeble_elements[element_id]['webinar_key'] = null;
                    CCLICKS.selected_webinar_key = null;
                } else {
                    $('#lb-reload-btn-gotowebinar').hide();
                    $('#lb-gotowebinar-forms-select').hide();
                    $('#lb-gotowebinar-loading').show();
                    var intId = setInterval(function() {
                        if (CCLICKS.loadedGoToWebinarWebinars) {
                            $('#lb-gotowebinar-forms-select').val(CCLICKS.selected_webinar_key);
                            clearInterval(intId);
                        }
                        else {
                            if (!CCLICKS.loadingGoToWebinarWebinars)
                                CCLICKS.loadGoToWebinarWebinars(false);
                        }
                    }, 1000);
                }
            }
        } else {
            $('#gtw-on').removeClass('btn-success active');
            $('#gtw-off').addClass('btn-danger active');
            $('#mits-gotowebinar-check').prop('checked', false);
            $('#gotowebinar-choice').hide();
        }
        if (integration_type == 'aweber') {
            $('#id-mits-integration-select').val(integration_type);
            if (CCLICKS.aweber_copy_paste) {
                CCLICKS.changeble_elements[CCLICKS.selected_form_id]['optin_type'] = 'aweber';
                if (CCLICKS.changeble_elements[CCLICKS.selected_form_id]['value']) {
                    $('#id-mits-aweber-copy-paste').val(unescape(CCLICKS.changeble_elements[CCLICKS.selected_form_id]['value']));
                }
            } else {
                $('#lb-' + integration_type + '-forms-select').val(CCLICKS.changeble_elements[element_id]['value']);
            }
        } else if (integration_type == 'getresponse') {
            $('#id-mits-integration-select').val(integration_type);
            if (CCLICKS.getresponse_copy_paste) {
                CCLICKS.changeble_elements[CCLICKS.selected_form_id]['optin_type'] = 'getresponse';
                if (CCLICKS.changeble_elements[CCLICKS.selected_form_id]['value']) {
                    $('#id-mits-getresponse-copy-paste').val(unescape(CCLICKS.changeble_elements[CCLICKS.selected_form_id]['value']));
                }
            } else {
                $('#lb-' + integration_type + '-forms-select').val(CCLICKS.changeble_elements[element_id]['value']);
            }
        } else if (integration_type == 'mailchimp' || integration_type == 'infusionsoft' || integration_type == 'icontact' || integration_type == 'constantcontact' || integration_type == 'gotowebinar') {
            $('#id-mits-integration-select').val(integration_type);
            $('#lb-' + integration_type + '-forms-select').val(CCLICKS.changeble_elements[element_id]['value']);
        } else if (integration_type == 'officeautopilot') {
            $('#id-mits-integration-select').val(integration_type);
            CCLICKS.changeble_elements[CCLICKS.selected_form_id]['optin_type'] = 'officeautopilot';
            if (CCLICKS.changeble_elements[CCLICKS.selected_form_id]['value']) {
                $('#id-mits-officeautopilot-copy-paste').val(unescape(CCLICKS.changeble_elements[CCLICKS.selected_form_id]['value']));
            }
        } else if (integration_type == 'sendreach') {
            $('#id-mits-integration-select').val(integration_type);
            CCLICKS.changeble_elements[CCLICKS.selected_form_id]['optin_type'] = 'sendreach';
            if (CCLICKS.changeble_elements[CCLICKS.selected_form_id]['value']) {
                $('#id-mits-sendreach-copy-paste').val(unescape(CCLICKS.changeble_elements[CCLICKS.selected_form_id]['value']));
            }
        } else if (integration_type == 'shoppingcart') {
            $('#id-mits-integration-select').val(integration_type);
            CCLICKS.changeble_elements[CCLICKS.selected_form_id]['optin_type'] = 'shoppingcart';
            if (CCLICKS.changeble_elements[CCLICKS.selected_form_id]['value']) {
                $('#id-mits-shoppingcart-copy-paste').val(unescape(CCLICKS.changeble_elements[CCLICKS.selected_form_id]['value']));
            }
        } else if (integration_type == 'other') {
            $('#id-mits-integration-select').val(integration_type);
            CCLICKS.changeble_elements[CCLICKS.selected_form_id]['optin_type'] = 'other';
            if (CCLICKS.changeble_elements[CCLICKS.selected_form_id]['value']) {
                $('#id-mits-other-copy-paste').val(unescape(CCLICKS.changeble_elements[CCLICKS.selected_form_id]['value']));
            }
        }
        if ($('#id-mits-integration-select option.integration').length == 1) {
            var one_form = $('#id-mits-integration-select option.integration').attr('value');
            $('#id-mits-integration-select').val(one_form);
            CCLICKS.selected_form_optin_type = one_form;
        }
        CCLICKS.selected_form_id = element_id;
        if (integration_type) {
            CCLICKS.selected_form_optin_type = integration_type;
        }
        if (CCLICKS.changeble_elements[element_id]['value']) {
            CCLICKS.selected_form_optin = CCLICKS.changeble_elements[element_id]['value'];
        }
        CCLICKS.openEditor(null, element_id);
        if (CCLICKS.changeble_elements[CCLICKS.selected_form_id]['use-name']) {
            $('#mits-use-first-name-checkbox').prop('checked', true);
            $('#first-name-on').addClass('btn-success active');
            $('#first-name-off').removeClass('btn-danger active');
        } else {
            $('#mits-use-first-name-checkbox').prop('checked', false);
            $('#first-name-on').removeClass('btn-success active');
            $('#first-name-off').addClass('btn-danger active');
        }
        if (CCLICKS.changeble_elements[CCLICKS.selected_form_id]['use-phone']) {
            $('#mits-use-phone-checkbox').prop('checked', true);
            $('#phone-on').addClass('btn-success active');
            $('#phone-off').removeClass('btn-danger active');
        } else {
            $('#mits-use-phone-checkbox').prop('checked', false);
            $('#phone-on').removeClass('btn-success active');
            $('#phone-off').addClass('btn-danger active');
        }
        CCLICKS.showHideFirstNameOption();
        if (CCLICKS.checkIfCanShowFirstName()) {
            if (CCLICKS.changeble_elements[CCLICKS.selected_form_id]['use-name']) {
                $('#mits-use-first-name-checkbox').prop('checked', true);
            } else {
                $('#mits-use-first-name-checkbox').prop('checked', false);
            }
            CCLICKS.firstNameCheckboxChanged();
        }
        CCLICKS.showHidePhoneOption();
        if (CCLICKS.checkIfCanShowPhone()) {
            if (CCLICKS.changeble_elements[CCLICKS.selected_form_id]['use-phone']) {
                $('#mits-use-phone-checkbox').prop('checked', true);
            } else {
                $('#mits-use-phone-checkbox').prop('checked', false);
            }
            CCLICKS.phoneCheckboxChanged();
        }
    },
    editSubmitButton: function(element_id) {

        var $text_input = $('#id-mits-btn-text');
        var elements = CCLICKS.changeble_elements[element_id]['elements'];
        $('#mits-modal-header').html(CCLICKS.changeble_elements[element_id]['name']);
        CCLICKS.beforeOpenEditor(element_id);
        $('#mits-edit-btn-text form').validate().resetForm();
        $text_input.val(CCLICKS.changeble_elements[element_id]['text']);
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#mits-edit-btn-text form').submit();
        });
        $text_input.unbind('change');
        $text_input.unbind('keyup');
        var onchangeFn = function() {
            for (var i in elements) {
                $(elements[i]).val($text_input.val());
            }
        };
        $text_input.change(onchangeFn);
        $text_input.keyup(onchangeFn);
        CCLICKS.closeEditorListener = function(cancel_button) {
            if (cancel_button !== true) {
                CCLICKS.submitFormListener();
                return;
            }
            for (var i in elements) {
                $(elements[i]).val(CCLICKS.changeble_elements[element_id]['text']);
            }
        };
        CCLICKS.submitFormListener = function() {
            var val = $text_input.val();
            for (var i in elements) {
                $(elements[i]).val(val);
            }
            CCLICKS.changeble_elements[element_id]['text'] = val;
            $('#lb-button-' + element_id).addClass('success');
            CCLICKS.changeble_elements[element_id]['default_text'] = null;
            CCLICKS.removableCheckOnsubmit(element_id);
        };
        CCLICKS.openEditor($text_input, element_id);
    },
    editFadeInwBox: function(element_id) {

        $('#mits-modal-header').html(CCLICKS.changeble_elements[element_id]['name']);
        CCLICKS.beforeOpenEditor(element_id);
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#mits-edit-optin-box form').submit();
        });
        $('#mits-edit-optin-box form').validate().resetForm();
        if (!CCLICKS.changeble_elements[element_id]['time']) {
            $('#optin-box-sec').val(0);
            $('#optin-box-min').val(0);
        } else {
            var min = Math.floor(parseInt(CCLICKS.changeble_elements[element_id]['time'], 10) / 60);
            var sec = parseInt(CCLICKS.changeble_elements[element_id]['time'], 10) - min * 60;
            $('#optin-box-sec').val(sec);
            $('#optin-box-min').val(min);
        }
        CCLICKS.submitFormListener = function(cancel_button) {
            CCLICKS.changeble_elements[element_id]['time'] = parseInt($('#optin-box-min').val(), 10) * 60 + parseInt($('#optin-box-sec').val(), 10);
            CCLICKS.removableCheckOnsubmit(element_id);
        };
        CCLICKS.openEditor(null, element_id);
    },
    editText: function(elem, element_id) {
        var use_live_code = false;
        if (CCLICKS.nowEditingElement == element_id) {
            use_live_code = true;
        }
        CCLICKS.nowEditingElement = element_id;
        $('#mits-modal-header').html(CCLICKS.changeble_elements[element_id]['name']);
        var elements = CCLICKS.changeble_elements[element_id]['elements'];
        CCLICKS.beforeOpenEditor(element_id);
        $('#mits-form-submit').unbind('click');

        $('#mits-edit-rich-text form').validate().resetForm();

        CCLICKS.showElementAttribute(element_id);
        $('#mits-edit-rich-text').show();
        $('#mits-editor-tools-space').show();
        $('#mits-change-style').show();
        var old_html = CCLICKS.changeble_elements[element_id]['text'];
        var final_html = '<p>' + (use_live_code ? $(elem).html() : old_html) + '</p>';
        $('#mits-rich-text-basic').setCode(final_html);
        $('#mits-form-submit').click(function() {
            $('#mits-edit-rich-text form').submit();
        });
        var cleanAndSetCode = function(html_text) {
            html_text = html_text.replace(/<p><br><\/p>/g, '<br>');
            html_text = html_text.replace(/<\/?([a-z]+)[^>]*>/gi, function(match, tag) {
                return (tag === "a" || tag === "b" || tag === "i" || tag === "p" || tag === "br" || tag === "strike" || tag === "span" || tag === 'u' || tag === "ul" || tag === "ol" || tag === 'li') ? match : "";
            });
            html_text = html_text.replace(/^<p>/, '');
            html_text = html_text.replace(/<\/?p[^>]*>/gi, function(match) {
                return (match == "<p>") ? "<br>" : "";
            });
            for (var i in elements) {
                $(elements[i]).html(html_text);
                $(elements[i]).find('a').unbind('click');
                $(elements[i]).find('a').click(CCLICKS.return_false);
            }
            return html_text;
        };
        CCLICKS.closeEditorListener = function(cancel_button) {
            CCLICKS.resetElementAttribute(element_id);
            if (cancel_button !== true) {
                CCLICKS.submitFormListener();
                return;
            }
            cleanAndSetCode(old_html);
            CCLICKS.changeble_elements[element_id]['text'] = old_html;
            for (var i in elements) {
                $(elements[i]).html(old_html);
                $(elements[i]).find('a').unbind('click');
                $(elements[i]).find('a').click(CCLICKS.return_false);
            }
            CCLICKS.nowEditingElement = false;
        };
        CCLICKS.cleanAndSetBasicRichText = function() {
            cleanAndSetCode($('#mits-rich-text-basic').getCode());
        };
        CCLICKS.submitFormListener = function() {
            $("input.color-picker").each(function() {
                var $this = $(this);
                var value = $this.val();
                var key = $this.attr('data-lb-base-color');
                if (CCLICKS.changable_colors[key]['value'] != value) {
                    if (value === '') {
                        value = key;
                    }
                    CCLICKS.changable_colors[key]['value'] = value;
                }
            });

            CCLICKS.saveElementAttribute(element_id);
            CCLICKS.cleanAndSetBasicRichText = function() {
            };
            CCLICKS.closeEditorListener = function() {
            };
            var html_text = cleanAndSetCode($('#mits-rich-text-basic').getCode());
            CCLICKS.removableCheckOnsubmit(element_id);
            CCLICKS.changeble_elements[element_id]['default_text'] = null;
            CCLICKS.changeble_elements[element_id]['text'] = html_text;
            $('#lb-button-' + element_id).addClass('success');
            CCLICKS.doStyleChange();
        };
        CCLICKS.basicRichTextChangedResize();
        CCLICKS.openEditor(null, element_id);

    },
    editRichTextArea: function(elem, element_id) {

        var elements = CCLICKS.changeble_elements[element_id]['elements'];
        $('#mits-modal-header').html(CCLICKS.changeble_elements[element_id]['name']);
        CCLICKS.beforeOpenEditor(element_id);
        var old_html = CCLICKS.changeble_elements[element_id]['text'];
        $('#mits-richtext-area').setCode(old_html);
        $('#mits-edit-rich-text form').validate().resetForm();
        $('#mits-form-submit').click(function() {
            $('#mits-edit-richtext-area form').submit();
        });
        var cleanAndSetCode = function(html_text) {
            for (var i in elements) {
                $(elements[i]).html(html_text);
                $(elements[i]).find('a').unbind('click');
                $(elements[i]).find('a').click(CCLICKS.return_false);
            }
            return html_text;
        };
        CCLICKS.cleanAndSetRichTextArea = function() {
            cleanAndSetCode($('#mits-richtext-area').getCode());
        };
        CCLICKS.closeEditorListener = function(cancel_button) {
            if (cancel_button !== true) {
                CCLICKS.submitFormListener();
                return;
            }
            CCLICKS.changeble_elements[element_id]['text'] = cleanAndSetCode(old_html);
            for (var i in elements) {
                $(elements[i]).html(old_html);
                $(elements[i]).find('a').unbind('click');
                $(elements[i]).find('a').click(CCLICKS.return_false);
            }
        };
        CCLICKS.submitFormListener = function() {
            CCLICKS.closeEditorListener = function() {
            };
            CCLICKS.cleanAndSetRichTextArea = function() {
            };
            var html_text = cleanAndSetCode($('#mits-richtext-area').getCode());
            CCLICKS.removableCheckOnsubmit(element_id);
            CCLICKS.changeble_elements[element_id]['default_text'] = null;
            CCLICKS.changeble_elements[element_id]['text'] = html_text;
            $('#lb-button-' + element_id).addClass('success');
        };
        CCLICKS.basicRichTextChangedResize();
        CCLICKS.openEditor(null, element_id);
    },
    selectOptInForm: function(elem, element_id) {

        var elements = CCLICKS.changeble_elements[element_id]['elements'];
        $('#mits-modal-header').html(CCLICKS.changeble_elements[element_id]['name']);
        CCLICKS.beforeOpenEditor(element_id);
        var old_html = CCLICKS.changeble_elements[element_id]['text'];
        $('#mits-richtext-area').setCode(old_html);
        $('#mits-edit-rich-text form').validate().resetForm();
        $('#mits-form-submit').click(function() {
            $('#mits-edit-richtext-area form').submit();
        });
        var cleanAndSetCode = function(html_text) {
            for (var i in elements) {
                $(elements[i]).html(html_text);
                $(elements[i]).find('a').unbind('click');
                $(elements[i]).find('a').click(CCLICKS.return_false);
            }
            return html_text;
        };
        CCLICKS.cleanAndSetRichTextArea = function() {
            cleanAndSetCode($('#mits-richtext-area').getCode());
        };
        CCLICKS.closeEditorListener = function() {
            CCLICKS.changeble_elements[element_id]['text'] = cleanAndSetCode(old_html);
            for (var i in elements) {
                $(elements[i]).html(old_html);
                $(elements[i]).find('a').unbind('click');
                $(elements[i]).find('a').click(CCLICKS.return_false);
            }
        };
        CCLICKS.submitFormListener = function() {
            CCLICKS.closeEditorListener = function() {
            };
            CCLICKS.cleanAndSetRichTextArea = function() {
            };
            var html_text = cleanAndSetCode($('#mits-richtext-area').getCode());
            CCLICKS.removableCheckOnsubmit(element_id);
            CCLICKS.changeble_elements[element_id]['default_text'] = null;
            CCLICKS.changeble_elements[element_id]['text'] = html_text;
            $('#lb-button-' + element_id).addClass('success');
        };
        CCLICKS.basicRichTextChangedResize();
        CCLICKS.openEditor(null, element_id);
    },
    editPlaceHolder: function(element_id) {
        var $text_input = $('#id-mits-placeholder');
        var elements = CCLICKS.changeble_elements[element_id]['elements'];
        $('#mits-modal-header').html(CCLICKS.changeble_elements[element_id]['name']);
        CCLICKS.beforeOpenEditor(element_id);
        $('#mits-edit-placeholder form').validate().resetForm();
        $text_input.val(CCLICKS.changeble_elements[element_id]['title']);
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#mits-edit-placeholder form').submit();
        });
        $text_input.unbind('change');
        $text_input.unbind('keyup');
        var onchangeFn = function() {
            var val = $text_input.val();
            for (var i in elements) {
                $(elements[i]).attr('title', $.trim(val));
                $(elements[i]).val($.trim(val));
            }
        };
        $text_input.change(onchangeFn);
        $text_input.keyup(onchangeFn);
        CCLICKS.closeEditorListener = function(cancel_button) {
            if (cancel_button !== true) {
                CCLICKS.submitFormListener();
                return;
            }
            var val = CCLICKS.changeble_elements[element_id]['title'];
            for (var i in elements) {
                $(elements[i]).attr('title', $.trim(val));
                $(elements[i]).val($.trim(val));
            }
        };
        CCLICKS.submitFormListener = function() {
            var val = $text_input.val();
            for (var i in elements) {
                $(elements[i]).attr('title', $.trim(val));
                $(elements[i]).val($.trim(val));
            }
            CCLICKS.changeble_elements[element_id]['title'] = $.trim(val);
            CCLICKS.changeble_elements[element_id]['default_text'] = null;
            $('#lb-button-' + element_id).addClass('success');
            CCLICKS.removableCheckOnsubmit(element_id);
        };
        CCLICKS.openEditor($text_input, element_id);
    },
    editEmbedArea: function(element_id) {

        $('#mits-modal-header').html(CCLICKS.changeble_elements[element_id]['name']);
        $('#lb-area-width').text(CCLICKS.changeble_elements[element_id]['width']);
        $('#lb-area-height').text(CCLICKS.changeble_elements[element_id]['height']);
        var elements = CCLICKS.changeble_elements[element_id]['elements'];
        CCLICKS.beforeOpenEditor(element_id);
        $('#mits-edit-area form').validate().resetForm();
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#mits-edit-area form').submit();
        });
        $('#id-mits-area').val(unescape(CCLICKS.changeble_elements[element_id]['value']));
        CCLICKS.openEditor(null, element_id);
        CCLICKS.submitFormListener = function() {
            var val = $('#id-mits-area').val();
            CCLICKS.changeble_elements[element_id]['value'] = escape(val);
            for (var i in elements) {
                CCLICKS.insertEmbed(elements[i], element_id, val, null);
            }
            CCLICKS.removableCheckOnsubmit(element_id);
            $('#lb-button-' + element_id).addClass('success');
        };
    },
    editVideo: function(element_id, element) {

        $('#mits-modal-header').html(CCLICKS.changeble_elements[element_id]['name']);
        $('#lb-video-width').text(CCLICKS.changeble_elements[element_id]['width']);
        $('#lb-video-height').text(CCLICKS.changeble_elements[element_id]['height']);
        CCLICKS.beforeOpenEditor(element_id);
        var elements = CCLICKS.changeble_elements[element_id]['elements'];
        $('#mits-edit-video form').validate().resetForm();
        $('#mits-edit-video').show();
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#mits-edit-video form').submit();
        });
        $('#id-mits-video').val(unescape(CCLICKS.changeble_elements[element_id]['value']));
        $('#id-mits-video').bind('paste keyup', function() {
            $(this).change();
        });
        $('#id-mits-video').change(function() {
            if ($(this).val().indexOf("http://") !== -1) {
                $("#id-mits-video-http").show();
            } else {
                $("#id-mits-video-http").hide();
            }
        });
        var image_url = null;
        var image_id = null;
        var old_image_url = null;
        var old_image_id = null;
        var element_data = CCLICKS.changeble_elements[element_id];

        if (element_data['connected-image']) {
            $('#mits-video-or-image').show();
            if (element_data['video-or-image'] == true || typeof element_data['video-or-image'] == 'undefined') {
                $('#video-or-image-btn-yes').addClass('btn-success active');
                $('#video-or-image-btn-no').removeClass('btn-success active');
                $('#id-video-image').val('yes');
                $('#mits-edit-video').show();
                $("#mits-change-img").hide();
                if (element_data['connected-link']) {
                    $("#mits-change-link").hide();
                }
                element_data['video-or-image'] = true;
                $('#mits-comment-text').hide();
            } else {
                $('#video-or-image-btn-yes').removeClass('btn-success active');
                $('#video-or-image-btn-no').addClass('btn-success active');
                $('#id-video-image').val('no');
                $('#mits-edit-video').hide();
                $("#mits-change-img").show();
                if (element_data['connected-link']) {
                    $("#mits-change-link").show();
                }
                element_data['video-or-image'] = false;
                $('#mits-comment-text').show();
            }

            if (element_data['connected-link']) {
                var element_child_id = element_data['connected-link']['id'];
                var elements_child = CCLICKS.changeble_elements[element_child_id]['elements'];
                var new_window = (CCLICKS.changeble_elements[element_child_id]['new_window'] && CCLICKS.changeble_elements[element_child_id]['new_window'] === true) ? true : false;
                var nofollow = (CCLICKS.changeble_elements[element_child_id]['nofollow'] && CCLICKS.changeble_elements[element_child_id]['nofollow'] === true) ? true : false;

                $('#mits-change-link form').validate().resetForm();
                $('#id-mits-link-href').val(CCLICKS.changeble_elements[element_child_id]['href']);
                $('#id-mits-link-target').val((new_window) ? 'yes' : 'no');
                if (new_window) {
                    $('#mits-link-target-btn-yes').addClass('btn-success active');
                    $('#mits-link-target-btn-no').removeClass('btn-danger active');
                } else {
                    $('#mits-link-target-btn-yes').removeClass('btn-success active');
                    $('#mits-link-target-btn-no').addClass('btn-danger active');
                }
                $('#id-mits-link-nofollow').val((nofollow) ? 'yes' : 'no');
                if (nofollow) {
                    $('#mits-link-nofollow-btn-yes').addClass('btn-danger active');
                    $('#mits-link-nofollow-btn-no').removeClass('btn-success active');
                } else {
                    $('#mits-link-nofollow-btn-yes').removeClass('btn-danger active');
                    $('#mits-link-nofollow-btn-no').addClass('btn-success active');
                }
                $('#id-mits-link-text-space').hide();

            }
            $('#default-image').remove();
            old_image_id = CCLICKS.changeble_elements[element_data['connected-image']['id']]['id'];
            old_image_url = CCLICKS.changeble_elements[element_data['connected-image']['id']]['url'];
            $('#lb-my-images').prepend($('<li id="default-image">').html('<a href="#" class="image-choose-btn" onclick="CCLICKS.selectImage(this, \'' + CCLICKS.changeble_elements[element_data['connected-image']['id']]['default_url'] + '\', null);return false;"><img src="' + CCLICKS.changeble_elements[element_data['connected-image']['id']]['default_url'] + '" alt="Image"></a>'));
            CCLICKS.selectImage = function(btn, url, id) {
                for (var i in CCLICKS.changeble_elements[element_data['connected-image']['id']]['elements']) {
                    CCLICKS.setImage(CCLICKS.changeble_elements[element_data['connected-image']['id']]['elements'][i], element_data['connected-image']['id'], url, id);
                }
                $('.image-choose-btn.active').removeClass('active');
                $(btn).addClass('active');
                image_url = url;
                image_id = id;
            };
        }
        CCLICKS.openEditor(null, element_id);
        CCLICKS.submitFormListener = function() {
            if ($('#id-video-image').val() == 'yes') {
                var val = $('#id-mits-video').val();
                CCLICKS.changeble_elements[element_id]['value'] = escape(val);
                for (var i in elements) {
                    CCLICKS.insertEmbedVideo(elements[i], element_id, CCLICKS.changeble_elements[element_id], val, CCLICKS.changeble_elements[element_id]['default_text']);
                }
                element_data['video-or-image'] = true;
            }
            else {
                if (element_data['connected-link']) {
                    for (var i in elements_child) {
                        $(elements_child[i]).attr('href', $('#id-mits-link-href').val());
                    }
                    CCLICKS.changeble_elements[element_child_id]['href'] = $('#id-mits-link-href').val();
                    CCLICKS.changeble_elements[element_child_id]['default_text'] = null;
                    CCLICKS.changeble_elements[element_child_id]['new_window'] = ($('#id-mits-link-target').val() == 'yes') ? true : false;
                    CCLICKS.changeble_elements[element_child_id]['nofollow'] = ($('#id-mits-link-nofollow').val() == 'yes') ? true : false;
                }
                if (element_data['connected-image'] && image_url && image_id) {
                    CCLICKS.setImage(element_data['connected-image']['elem'], element_data['connected-image']['id'], image_url, image_id);
                }
                element_data['video-or-image'] = false;
            }
            CCLICKS.removableCheckOnsubmit(element_id);
            $('#lb-button-' + element_id).addClass('success');
        };
    },
    insertEmbedVideo: function(elem, element_id, element_data, value, default_text) {
        try {
            $(value);
        } catch (err) {
            value = '';
        }
        if (!value || !$.trim(value)) {
            $(elem).empty();
            if (default_text) {
                $(elem).html(default_text);
            }
        } else {
            var video_background = CCLICKS.player_logo_url['empty_your'];
            if (value.indexOf('.mitsplayer(') > 0) {
                video_background = CCLICKS.player_logo_url['mits'];
            } else if (value.indexOf('www.youtube') > 0) {
                video_background = CCLICKS.player_logo_url['yt'];
            } else if (value.indexOf('vimeo.com') > 0) {
                video_background = CCLICKS.player_logo_url['vimeo'];
            } else if (value.indexOf('wistia.') > 0) {
                video_background = CCLICKS.player_logo_url['wistia'];
            }
            var place_holder = '<div style="width:' + (parseFloat(element_data['width']) - 2) + 'px;height:' + (parseFloat(element_data['height']) - 2) + 'px;background:#fff url(\'' + video_background + '\') center center no-repeat; border: 1px solid #888;"></div>';
            $(elem).html(place_holder);
        }
    },
    insertEmbed: function(elem, element_id, value, default_text) {
        try {
            $(value);
        } catch (err) {
            value = '';
        }
        if (!value || !$.trim(value)) {
            $(elem).empty();
            if (default_text) {
                $(elem).html(default_text);
            }
        } else {
            var html_data = $(value);
            var scripts = [];
            html_data.each(function() {
                if ($(this).is('script[src]')) {
                    var script = document.createElement("script");
                    script.type = "text/javascript";
                    script.src = $(this).attr('src');
                    $('#mits-template-iframe').contents().find('head').get(0).appendChild(script);
                } else if ($(this).is('script')) {
                    scripts.push($(this));
                }
            });
            html_data = html_data.not('script');
            $(elem).empty();
            $(elem).append(html_data);
            for (var i in scripts) {
                var script = document.createElement("script");
                script.type = "text/javascript";
                script.text = scripts[i].text();
                $('#mits-template-iframe').contents().find('head').get(0).appendChild(script);
            }
            $('#lb-button-' + element_id).addClass('success');
        }
    },
    editImage: function(element_id) {

        $('#default-image').remove();
        $('#lb-my-images').prepend($('<li id="default-image">').html('<a href="#" class="image-choose-btn" onclick="CCLICKS.selectImage(this, \'' + CCLICKS.changeble_elements[element_id]['default_url'] + '\', null);return false;"><img src="' + CCLICKS.changeble_elements[element_id]['default_url'] + '" alt="Image"></a>'));
        $('#mits-modal-header').html(CCLICKS.changeble_elements[element_id]['name']);
        CCLICKS.beforeOpenEditor(element_id);
        var elements = CCLICKS.changeble_elements[element_id]['elements'];
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            CCLICKS.closeEditor();
            CCLICKS.removableCheckOnsubmit(element_id);
            CCLICKS.change_made = true;
            $('#lb-button-' + element_id).addClass('success');
        });
        CCLICKS.openEditor(null, element_id);
        CCLICKS.selectImage = function(btn, url, id) {
            for (var i in elements) {
                CCLICKS.setImage(elements[i], element_id, url, id);
            }
        };
    },
    selectDefaultImage: function() {
        if (CCLICKS.selected_element_id && CCLICKS.changeble_elements[CCLICKS.selected_element_id] && CCLICKS.changeble_elements[CCLICKS.selected_element_id]['type'] == 'image') {
            var elements = CCLICKS.changeble_elements[CCLICKS.selected_element_id]['elements'];
            for (var i in elements) {
                $(elements[i]).attr('src', CCLICKS.changeble_elements[CCLICKS.selected_element_id]['default_url']);
            }
            CCLICKS.changeble_elements[CCLICKS.selected_element_id]['url'] = CCLICKS.changeble_elements[CCLICKS.selected_element_id]['default_url'];
            CCLICKS.removableCheckOnsubmit(CCLICKS.selected_element_id);
            CCLICKS.markChange();
            CCLICKS.closeEditor();
        }
    },
    setImage: function(elem, element_id, url, id) {
        var video_parent = CCLICKS.changeble_elements[element_id]['video_parent'];
        var set_basic_size = function() {
            CCLICKS.changeble_elements[element_id]['url'] = url;
            CCLICKS.changeble_elements[element_id]['id'] = id;

            if (video_parent != false && video_parent.find('div').length > 0) {
                video_parent.html($(elem));
            }
            $(elem).attr('src', url);
        };
        if (!id && url == CCLICKS.changeble_elements[element_id]['default_url']) {
            set_basic_size();
            return;
        }
        if ($(elem).is('[max-image-size]') || ($(elem).is('[max-image-width]') && $(elem).is('[max-image-height]'))) {

            var size = $(elem).attr('max-image-size') ? $(elem).attr('max-image-size') : $(elem).attr('max-image-width');
            var height = $(elem).attr('max-image-height') ? $(elem).attr('max-image-height') : '';

            if (size) {
                if (video_parent != false) {
                    height = video_parent[0].clientHeight;
                }
                MAPI.ajaxcall({
                    data: {
                        "action": "api_call",
                        'request': 'get-image-url',
                        'image_id': id,
                        'size': size,
                        'height': height
                    },
                    type: 'GET',
                    async: true,
                    success: function(data) {
                        CCLICKS.changeble_elements[element_id]['url'] = data.body;
                        CCLICKS.changeble_elements[element_id]['id'] = id;
                        if (video_parent != false && video_parent.find('div').length > 0) {
                            video_parent.html($(elem));
                        }
                        $(elem).attr('src', data.body);
                    },
                    error: function() {
                        set_basic_size();
                    }
                });
            } else {
                set_basic_size();
            }
        } else {
            set_basic_size();
        }
    },
    editRemovable: function(elem, element_id) {
        $('#mits-modal-header').html(CCLICKS.changeble_elements[element_id]['name']);
        CCLICKS.beforeOpenEditor(element_id);
        CCLICKS.openEditor(null, element_id);
        $('#mits-form-submit').click(function() {
            CCLICKS.submitFormListener();
            CCLICKS.closeEditor();
            return false;
        });
        CCLICKS.submitFormListener = function() {
            CCLICKS.removableCheckOnsubmit(element_id);
            $('#lb-button-' + element_id).addClass('success');
        };
    },
    editLink: function(elem, element_id) {
        var link_only = ($(elem).is('[data-lb~="link-only"]')) ? true : false;
        var element_data = CCLICKS.changeble_elements[element_id];
        var new_window = (CCLICKS.changeble_elements[element_id]['new_window'] && CCLICKS.changeble_elements[element_id]['new_window'] === true) ? true : false;
        var nofollow = (CCLICKS.changeble_elements[element_id]['nofollow'] && CCLICKS.changeble_elements[element_id]['nofollow'] === true) ? true : false;
        var image_url = null;
        var image_id = null;
        var old_image_url = null;
        var old_image_id = null;
        var elements = CCLICKS.changeble_elements[element_id]['elements'];
        $('#mits-modal-header').html(CCLICKS.changeble_elements[element_id]['name']);
        CCLICKS.beforeOpenEditor(element_id);
        $('#mits-change-link form').validate().resetForm();
        $('#id-mits-link-text').val(CCLICKS.changeble_elements[element_id]['text']);
        $('#id-mits-link-href').val(CCLICKS.changeble_elements[element_id]['href']);
        $('#id-mits-link-target').val((new_window) ? 'yes' : 'no');
        if (new_window) {
            $('#mits-link-target-btn-yes').addClass('btn-success active');
            $('#mits-link-target-btn-no').removeClass('btn-danger active');
        } else {
            $('#mits-link-target-btn-yes').removeClass('btn-success active');
            $('#mits-link-target-btn-no').addClass('btn-danger active');
        }
        $('#id-mits-link-nofollow').val((nofollow) ? 'yes' : 'no');
        if (nofollow) {
            $('#mits-link-nofollow-btn-yes').addClass('btn-danger active');
            $('#mits-link-nofollow-btn-no').removeClass('btn-success active');
        } else {
            $('#mits-link-nofollow-btn-yes').removeClass('btn-danger active');
            $('#mits-link-nofollow-btn-no').addClass('btn-success active');
        }
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#mits-change-link form').submit();
        });
        if (link_only) {
            $('#id-mits-link-text-space').hide();
        } else {
            $('#id-mits-link-text-space').show();
        }
        if (element_data['connected-image']) {
            $('#mits-change-img').show();
            $('#default-image').remove();
            old_image_id = CCLICKS.changeble_elements[element_data['connected-image']['id']]['id'];
            old_image_url = CCLICKS.changeble_elements[element_data['connected-image']['id']]['url'];
            $('#lb-my-images').prepend($('<li id="default-image">').html('<a href="#" class="image-choose-btn" onclick="CCLICKS.selectImage(this, \'' + CCLICKS.changeble_elements[element_data['connected-image']['id']]['default_url'] + '\', null);return false;"><img src="' + CCLICKS.changeble_elements[element_data['connected-image']['id']]['default_url'] + '" alt="Image"></a>'));
            CCLICKS.selectImage = function(btn, url, id) {
                for (var i in CCLICKS.changeble_elements[element_data['connected-image']['id']]['elements']) {
                    CCLICKS.setImage(CCLICKS.changeble_elements[element_data['connected-image']['id']]['elements'][i], element_data['connected-image']['id'], url, id);
                }
                $('.image-choose-btn.active').removeClass('active');
                $(btn).addClass('active');
                image_url = url;
                image_id = id;
            };
        }
        if (CCLICKS.target_url) {
            $('#id-mits-link-sync').show().click(function(e) {
                e.preventDefault();
                if (confirm("This will replace the URL above with this page's tracking URL. Do you wish to proceed?")) {
                    $('#id-mits-link-href').val(CCLICKS.target_url);
                }
            });
        }
        CCLICKS.openEditor(null, element_id);
        CCLICKS.submitFormListener = function() {
            var href_val = $('#id-mits-link-href').val();
            var text_val = $('#id-mits-link-text').val();
            if (!link_only) {
                CCLICKS.changeble_elements[element_id]['text'] = text_val;
            }
            if (element_data['connected-image'] && image_url && image_id) {
                CCLICKS.setImage(element_data['connected-image']['elem'], element_data['connected-image']['id'], image_url, image_id);
            }
            for (var i in elements) {
                if (!link_only) {
                    $(elements[i]).text($('#id-mits-link-text').val());
                }
                $(elements[i]).attr('href', $('#id-mits-link-href').val());
            }
            CCLICKS.changeble_elements[element_id]['href'] = href_val;
            CCLICKS.changeble_elements[element_id]['default_text'] = null;
            CCLICKS.changeble_elements[element_id]['new_window'] = ($('#id-mits-link-target').val() == 'yes') ? true : false;
            CCLICKS.changeble_elements[element_id]['nofollow'] = ($('#id-mits-link-nofollow').val() == 'yes') ? true : false;
            $('#lb-button-' + element_id).addClass('success');
            CCLICKS.removableCheckOnsubmit(element_id);
        };
        var $text_input = $('#id-mits-link-text');
        $text_input.unbind('change');
        $text_input.unbind('keyup');
        var onchangeFn = function() {
            if (!link_only) {
                var val = $text_input.val();
                for (var i in elements) {
                    $(elements[i]).text(val);
                }
            }
        };
        $text_input.change(onchangeFn);
        $text_input.keyup(onchangeFn);
        CCLICKS.closeEditorListener = function() {
            if (!link_only) {
                var val = CCLICKS.changeble_elements[element_id]['text'];
                for (var i in elements) {
                    $(elements[i]).text(CCLICKS.changeble_elements[element_id]['text']);
                }
            }
            if (element_data['connected-image']) {
                for (var i in CCLICKS.changeble_elements[element_data['connected-image']['id']]['elements']) {
                    CCLICKS.setImage(CCLICKS.changeble_elements[element_data['connected-image']['id']]['elements'][i], element_data['connected-image']['id'], old_image_url, old_image_id);
                }
            }
        };
    },
    videoOrImage: function(is_video)
    {
        var have_link = CCLICKS.changeble_elements[CCLICKS.selected_element_id]['connected-link'];
        if (is_video) {
            $('#video-or-image-btn-yes').addClass('btn-success');
            $('#video-or-image-btn-no').removeClass('btn-success');
            $('#id-video-image').val('yes');
            $('#mits-edit-video').show();
            $('#mits-change-img').hide();
            if (have_link) {
                $('#mits-change-link').hide();
            }
            $('#mits-comment-text').hide();

        } else {
            $('#video-or-image-btn-yes').removeClass('btn-success');
            $('#video-or-image-btn-no').addClass('btn-success');
            $('#id-video-image').val('no');
            $('#mits-edit-video').hide();
            $('#mits-change-img').show();
            if (have_link)
                $('#mits-change-link').show();
            $('#mits-comment-text').show();

        }
        return false;
    },
    removableChanged: function(visible) {
        if (visible) {
            $('#mits-removable-btn-yes').addClass('btn-success');
            $('#mits-removable-btn-no').removeClass('btn-danger');
            $('#id-mits-to-remove').val('yes');
        } else {
            $('#mits-removable-btn-yes').removeClass('btn-success');
            $('#mits-removable-btn-no').addClass('btn-danger');
            $('#id-mits-to-remove').val('no');
        }
        if (CCLICKS.selected_element_id && CCLICKS.changeble_elements[CCLICKS.selected_element_id] && CCLICKS.changeble_elements[CCLICKS.selected_element_id]['removable']) {
            CCLICKS.showHideRemovable(CCLICKS.selected_element_id, visible);
        }
        return false;
    },
    removableCheckOnsubmit: function(element_id) {
        if (element_id && CCLICKS.changeble_elements[element_id] && CCLICKS.changeble_elements[element_id]['removable']) {
            CCLICKS.changeble_elements[element_id]['removed'] = ($('#id-mits-to-remove').val() != 'yes') ? true : false;
            CCLICKS.removableCheck(element_id);
        }
    },
    removableCheck: function(element_id) {
        if (element_id && CCLICKS.changeble_elements[element_id] && CCLICKS.changeble_elements[element_id]['removable']) {
            CCLICKS.showHideRemovable(element_id, !CCLICKS.changeble_elements[element_id]['removed']);
        }
    },
    showHideRemovable: function(element_id, visible) {
        for (var i in CCLICKS.changeble_elements[element_id]['elements']) {
            var editable_area = null;
            if (!visible) {
                $(CCLICKS.changeble_elements[element_id]['elements'][i]).hide();
                $(editable_area).hide();
                $('#mits-editor-tools-space').hide();
                if (CCLICKS.element_attribute['#' + element_id]) {
                    $('[form_element="' + element_id + '"]').hide();
                }
            } else {
                $(CCLICKS.changeble_elements[element_id]['elements'][i]).show();
                $(editable_area).show();
                $('#mits-editor-tools-space').show();
                if (CCLICKS.element_attribute['#' + element_id]) {
                    $('[form_element="' + element_id + '"]').show();
                }
            }
        }

    },
    beforeOpenEditor: function(element_id) {
        $('#mits-editor .mits-form-space').hide();
        CCLICKS.submitFormListener = function() {
        };
        CCLICKS.closeEditorListener = function() {
        };
        if (element_id && CCLICKS.changeble_elements[element_id] && CCLICKS.changeble_elements[element_id]['comment']) {
            $('#mits-comment-text p').text(CCLICKS.changeble_elements[element_id]['comment']);
            $('#mits-comment-text').show();
        }
        if (element_id && CCLICKS.changeble_elements[element_id] && CCLICKS.changeble_elements[element_id]['removable']) {
            if (CCLICKS.changeble_elements[element_id]['removed']) {
                $('#mits-removable-btn-yes').removeClass('btn-success active');
                $('#mits-removable-btn-no').addClass('btn-danger active');
                $('#id-mits-to-remove').val('no');
            } else {
                $('#mits-removable-btn-yes').addClass('btn-success active');
                $('#mits-removable-btn-no').removeClass('btn-danger active');
                $('#id-mits-to-remove').val('yes');
            }
            $('#mits-removable-elem').show();
        }
        $('#mits-form-submit').show();
    },
    openEditor: function($elem_to_focus, element_id) {
        if ($('#templete-settings').attr('data-hidden') === 'true') {
            CCLICKS.showEditor();
        }
        if (CCLICKS.changeble_elements[CCLICKS.selected_element_id]) {
            var elem = CCLICKS.changeble_elements[CCLICKS.selected_element_id];
            if (CCLICKS.editor_blocks_by_type[elem['type']]) {
                $('#' + CCLICKS.editor_blocks_by_type[elem['type']]).show();
            }
            if (!elem['removable'] || !elem['removed']) {
                $('#mits-editor-tools-space').show();
            }
        }

        $('.editor-overlay').fadeIn(300);
        $("#lb-submit-btns").show();
        $('#mits-editor').fadeIn(300, function() {
            if ($elem_to_focus) {
                $elem_to_focus.focus();
            }
        });
    },
    closeEditor: function() {
        CCLICKS.removableCheck(CCLICKS.selected_element_id);
        $('#mits-editor-tools-space').fadeOut(300);
        $('.editor-overlay').fadeOut(300);
        $('.mits-form-attribute').hide();

        CCLICKS.resetColors();
        CCLICKS.closeEditorListener(true); // true = cancel button pressed
        CCLICKS.selected_element_id = null;
        CCLICKS.closeEditorListener = function() {
        };
    },
    editorToggle: function() {
        if ($('#mits-hide-editor-button').hasClass('disabled')) {
            return false;
        }
        if ($('#template-settings').attr('data-hidden') === 'true') {
            CCLICKS.showEditor();
        } else {
            CCLICKS.hideEditor();
        }
    },
    hideEditor: function() {
        $('.editor-navigation#mits-top-navigation').css('position', 'absolute');
        $('#mits-hide-editor-button').addClass('disabled');
        var slide_px = $('#template-settings').width();
        $("#template-settings").resizable('disable');
        $('#template-settings').animate({
            left: -(slide_px)
        }, 400, function() {
            $('#mits-hide-editor-button .arrow-hide-btn').addClass('right');
            $('#mits-hide-editor-button').removeClass('disabled');
            $('#template-settings').attr('data-hidden', 'true');
            CCLICKS.windowResize();
        });
        $('#mits-iframe-wrapper').animate({
            'padding-left': 0,
            'width': '100%'
        }, 400);
    },
    showEditor: function() {
        $('.editor-navigation#mits-top-navigation').css('position', 'relative');
        $('#mits-hide-editor-button').addClass('disabled');
        var slide_px = $('#template-settings').width();
        $('#template-settings').animate({
            left: 0
        }, 400, function() {
            $("#template-settings").resizable('enable');
            $('#mits-hide-editor-button .arrow-hide-btn').removeClass('right');
            $('#mits-hide-editor-button').removeClass('disabled');
            $('#template-settings').attr('data-hidden', 'false');
            CCLICKS.windowResize();
        });
        $('#mits-iframe-wrapper').animate({
            'padding-left': slide_px + 'px',
            'width': ($(window).width() - slide_px) + 'px'
        }, 400);
    },
    cleanPageSimpleName: function(org_name) {
        var dic_map = defaultDiacriticsRemovalMap();
        var run = false;
        org_name = org_name.toLowerCase();
        for (var i = 0; i < dic_map.length; i++) {
            org_name = org_name.replace(dic_map[i].letters, dic_map[i].base);
        }
        org_name = org_name.replace(/[^\w\s-]/g, '');
        org_name = org_name.replace(/[-\s\_]+/g, '-');
        for (var k = 0; k < dic_map.length; k++) {
            org_name = org_name.replace(dic_map[k].letters, dic_map[k].base);
        }
        if (org_name.substring(0, 1) == '-') {
            org_name = org_name.substr(1);
            run = true;
        }
        return $.trim(org_name);
    },
    setPageUrlEditable: function() {
        CCLICKS.different_simple_name = true;
        $('#edit-url-btn').hide();
        $('#id_page_url').removeAttr('readonly');
        $('#change-name-url').val('y');
        var pageUrlChanged = function() {
            var pos = CCLICKS.getCaretPosition($('#id_page_url').get(0));
            $('#id_page_url').val(CCLICKS.cleanPageSimpleName($('#id_page_url').val()));
            CCLICKS.setCaretPosition($('#id_page_url').get(0), pos);
        };
        $('#id_page_url').change(pageUrlChanged);
        $('#id_page_url').keyup(pageUrlChanged);
    },
    getCaretPosition: function(ctrl) {
        var caretPos = 0;
        if (document.selection) {
            ctrl.focus();
            var sel = document.selection.createRange();
            sel.moveStart('character', -ctrl.value.length);
            caretPos = sel.text.length;
        } else if (ctrl.selectionStart || ctrl.selectionStart == '0') {
            caretPos = ctrl.selectionStart;
        }
        return caretPos;
    },
    setCaretPosition: function(ctrl, pos) {
        if (ctrl.setSelectionRange) {
            ctrl.focus();
            ctrl.setSelectionRange(pos, pos);
        } else if (ctrl.createTextRange) {
            var range = ctrl.createTextRange();
            range.collapse(true);
            range.moveEnd('character', pos);
            range.moveStart('character', pos);
            range.select();
        }
    },
    markChange: function() {
        CCLICKS.change_made = true;
        CCLICKS.notify("warning", "You have unsaved changes...");
    },
    hideNotifications: function() {
        $("#notification-splitter").stop().fadeOut({
            queue: false
        });
        $("#notifications").stop().fadeOut({
            queue: false
        });
        $("#notifications span").css("cursor", "default");
        $("#notifications span").popover("disable");

        setTimeout(function() {
            $('#template-settings').height($(window).height() - $('#mits-top-navigation').outerHeight(true) - 5);
            $('#white-box-left').height($(window).height() - $('#mits-top-navigation').outerHeight(true) - 20);
            CCLICKS.positionLeftPanel();
        }, 500);
    },
    notify: function(type, message, timeout) {
        if (message.length == 0)
            return;
        var naviWidth = $("#mits-top-navigation").width();
        var optionsWidth = $("ul.nav.options.pull-right").width() + 20 + 45;
        var $notifications = $("#notifications");
        var $notificationItems = $("#notifications li");
        var $notificationSpan = $("#notifications li." + type + " span");
        var $notificationElem = $("#notifications li." + type);
        var $splitter = $("#notification-splitter");
        $notifications.show();
        var pos = $notifications.position();
        $notifications.hide();
        var spaceLeft = naviWidth - optionsWidth - pos.left;
        $notifications.hide();
        $notificationItems.hide();
        $notificationSpan.css("cursor", "pointer");
        $notificationSpan.text(message);
        $notificationElem.show();
        $splitter.show();
        $notifications.show();
        $notifications.hide();
        $notificationSpan.parent().find("i").css({
            "display": "block",
            "float": "left",
            "line-height": "41px"
        });
        $notificationSpan.css({
            "display": "block",
            "float": "left",
            "overflow": "hidden",
            "max-width": "215px",
            "white-space": "nowrap",
            "text-overflow": "ellipsis"
        });
        $notificationSpan.popover({
            title: type[0].toUpperCase() + type.slice(1),
            content: message,
            placement: "bottom"
        });
        $notifications.show();
        $(window).resize();
        if (timeout)
            setTimeout(CCLICKS.hideNotifications, timeout);
    },
    validationError: function(message) {
        CCLICKS.notify("error", message);
        CCLICKS.hasValidationError = true;
    },
    hideValidationError: function() {
        CCLICKS.hideNotifications();
        CCLICKS.hasValidationError = false;
    },
    loadPreview: function() {
        $("#mits-top-navigation").hide();
        $('#template-settings').hide();
        $("<div/>").attr("id", "preview-iframe-wrapper").css("position", "absolute").appendTo("body");
        setTimeout(function() {
            var pub_url = window.location.href.replace("edit", "preview/html");
            $("<iframe/>").attr("src", pub_url).attr("id", "preview-iframe").css({
                "position": "absolute",
                "border": 0
            }).appendTo("#preview-iframe-wrapper").load();
            $("#remove-preview").fadeIn();
            $("#remove-preview").click(function() {
                $('#template-settings').show();
                $("li[data-state] button[data-state=edit]").click();
            });
        }, 500);
    },
    uiSave: function(callback) {
        var button = $("#save-button");

        if (!CCLICKS.edit && !CCLICKS.name_changed) {
            CCLICKS.notify("warning", "You must enter a name.");
            return;
        }
        if (!(button.attr("disabled"))) {
            button.attr("disabled", "disabled");
            function finishSaving(message, type) {
                if (type == "notice") {
                    CCLICKS.saveIt(function(success) {
                        button.removeAttr("disabled");
                        button.addClass("btn-primary");
                        if (!success) {
                            CCLICKS.notify("error", 'CTA with this url already exists!');
                        } else {
                            if (callback)
                                callback(success);
                            CCLICKS.notify("notice", "Success!", 1500);
                            if (success) {
                                var url = CCLICKS.baseUrl + "/ctas/" + CCLICKS.template_id + "/" + CCLICKS.capture_clicks_id;
                                window.history.pushState('Object', 'Title', url);
                            }
                        }
                    }, function() {
                        CCLICKS.notify("notice", "Saving...");
                    });
                } else {
                    CCLICKS.notify(type, message, 5000);
                    if (type == "error")
                        $("#mits-form-editor").show();
                    button.removeAttr("disabled");
                    button.addClass("btn-primary");
                }
            }
            var frame = $("#mits-form-editor")[0].contentWindow;
            if (frame.mainWindowRequestsSave) {
                frame.mainWindowRequestsSave(finishSaving);
            } else {
                finishSaving("", "notice");
            }
        }
    },
    toggleRunning: function(successCb, errorCb) {
        var request = CCLICKS.is_running ? "stop-test" : "start-test";
        MAPI.ajaxcall({
            data: {
                "action": "api_call",
                "request": request,
                "id": CCLICKS.capture_clicks_id
            },
            type: "POST",
            async: true,
            success: function(data) {
                switch (data.body) {
                    case "start-test":
                        CCLICKS.is_running = true;
                        break;
                    case "stop-test":
                        CCLICKS.is_running = false;
                        break;
                }
                if (successCb)
                    successCb();
            },
            error: function() {
                if (errorCb)
                    errorCb();
            }
        });
    },
    declareWinner: function(successCb, errorCb) {
        MAPI.ajaxcall({
            data: {
                "action": "api_call",
                "request": "declare-winner",
                "id": CCLICKS.capture_clicks_id
            },
            type: "POST",
            async: true,
            success: function(data) {
                if (successCb)
                    successCb(data);
            },
            error: function() {
                if (errorCb)
                    errorCb();
            }
        });
    },
    savePopupForm: function() {
        var trigger = $("#mits-form-editor")[0].contentWindow.mainWindowRequestsSave;
        if (trigger)
            trigger();
    }
};