$().ready(function() {

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
        $settingsMenu.height($window.height() - $topNavi.outerHeight(true) - 30);
        MITS.positionLeftPanel();
        $("#mits-form-editor").width(width).height($(window).height()).css("top", d + "px");
        $("#mits-form-editor .form-editor").css("height", "42px");
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
    window.markChangedCallback = MITS.markChange;
    $(".widget-modal").on("hidden", function() {
        var publishButton = $("li[data-action] button[data-action=publish]");
        publishButton.removeClass(publishButton.attr("data-active-class"));
        publishButton.removeClass("active");
    });
    MITS.return_false = function() {
        return false;
    };
    var simpleNameChanged = function(e) {
        var name = $('#id_page_name').val();
        if (!MITS.edit) {
            var url = MITS.cleanPageSimpleName(name);
            $('#id_page_url').val(url);
            $('#id_page_url-span').text(url);
        }
        $('#mits-page-menu-title').text(name);
        MITS.markChange();
        MITS.name_changed = true;
        if (e.type === "keyup" && e.keyCode === 13) {
            $(this).blur();
        }
    };
    $('#id_page_name').change(simpleNameChanged);
    $('#id_page_name').keydown(simpleNameChanged);
    $('#id_page_name').keyup(simpleNameChanged);
    $('#id_page_url').change(MITS.markChange);
    $('#id_page_url').keydown(MITS.markChange);
    $('#id_page_url').keyup(function(e) {
        MITS.markChange();
        if (e.keyCode === 13) {
            $(this).blur();
        }
    });
    $('#id_page_url').blur(function() {
        $(this).val(MITS.cleanPageSimpleName($(this).val()));
    });
    $(window).resize(MITS.windowResize);
    $(window).load(function() {
        if (!MITS.is_optin_form) {
            setTimeout(function()
            {
                if (AVAILABLE_SERVICES['aweber']) {
                    MITS.loadAWeberForms(false);
                }
                if (AVAILABLE_SERVICES['getresponse']) {
                    MITS.loadGetresponseForms(false);
                }
                if (AVAILABLE_SERVICES['mailchimp']) {
                    MITS.loadMailChimpForms(false);
                }
                if (AVAILABLE_SERVICES['gotowebinar']) {
                    MITS.loadGoToWebinarWebinars(false);
                }
                if (AVAILABLE_SERVICES['icontact']) {
                    MITS.loadIContactForms(false);
                }
                if (AVAILABLE_SERVICES['infusionsoft']) {
                    MITS.loadInfusionsoftForms(false);
                }
                if (AVAILABLE_SERVICES['constantcontact']) {
                    MITS.loadConstantContactForms(false);
                }
            }, 50);
        }
        MITS.loadPictures(null, null);
        $(window).on('beforeunload', function() {
            if (MITS.change_made) {
                return "Are you sure you want to leave without saving?";
            }
        });
    });
    $("a[data-edit-span]").each(function() {
        var button = $(this);
        var id = button.attr("data-edit-span");
        var inputEl = $("#" + id);
        var spanEl = $("#" + id + "-span");
        var onClick = function() {
            if (spanEl.is(":visible")) {
                inputEl.css("width", '90%');
                spanEl.hide();
                inputEl.show();
                inputEl.focus();
                button.addClass("active");
                button.parent().prev().css('width', '80%');
            } else {
                inputEl.blur();
                button.parent().prev().css('width', '35%');
            }
        };
        inputEl.blur(function() {
            var text = inputEl.val();
            if (text.length > 20) {
                text = text.substr(0, 20);
                spanEl.text(text);
                spanEl.html(spanEl.html() + "&hellip;");
            } else {
                spanEl.text(text);
            }
            inputEl.hide();
            spanEl.show();
            button.removeClass("active");
        });
        button.click(onClick);
        spanEl.click(onClick);
    });
    var navHeight = $("#mits-top-navigation").height();
    var windowWidth = $(window).width();
    var windowHeight = $(window).height();
    $("li[data-action] > button").each(function() {
        var button = $(this);
        var confirmationText = "You must save the page before you can publish it. Would you like to do this now?";
        var confirmationText2 = "Would you like to save your page before you publish it?";
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
                    MITS.uiSave();
                    break;
                case "analytics":
                    window.location.href = button.attr("data-href");
                    break;
                case "publish":
                    if (!MITS.edit) {
                        if (!confirm(confirmationText)) {
                            deactivate();
                            return;
                        }
                        MITS.uiSave(function(success) {
                            if (success) {
                                
								//var publishUrl = real_baseUrl + MITS.article_id + "-" + $('#id_page_url').val();
                                
								var publishUrl = real_baseUrl + $('#id_page_url').val();
								
								window.open(publishUrl, '_blank');
                            } else {
                                deactivate();
                            }
                        });
                    } else {
                        if (MITS.change_made && confirm(confirmationText2)) {
                            MITS.uiSave(function(success) {
                                if (success) {
                                    //var publishUrl = real_baseUrl + MITS.article_id + "-" + $('#id_page_url').val();
									
									
									var publishUrl = real_baseUrl + $('#id_page_url').val();
									
									
                                    window.open(publishUrl, '_blank');
                                } else {
                                    deactivate();
                                }
                            });
                        } else {
                            //var publishUrl = real_baseUrl + MITS.article_id + "-" + $('#id_page_url').val();
							
							var publishUrl = real_baseUrl + $('#id_page_url').val();
							
                            window.open(publishUrl, '_blank');
                        }
                    }
                    break;
            }
        });
    });
    $("li[data-state] > button").each(function() {
        var button = $(this);
        var confirmationText = "You must save the page before you can preview it. Would you like to do this now?";
        button.click(function() {
            $("li[data-state] > button").removeAttr("class").addClass("btn");
            button.addClass(button.attr("data-active-class"));
            button.addClass("active");
            switch (button.attr("data-state")) {
                case "edit":
                    $("#preview-iframe-wrapper").remove();
                    $("#preview-iframe").remove();
                    $("#remove-preview").hide();
                    $("#mits-top-navigation").show();
                    button.hide();
                    break;
                case "preview":
                    if (!MITS.edit || MITS.change_made) {
                        if (!confirm(confirmationText)) {
                            $("li[data-state] > button[data-state=edit]").click();
                            return;
                        }
                        MITS.uiSave(function(success) {
                            if (success) {
                                MITS.loadPreview();
                            } else {
                                $("li[data-state] > button[data-state=edit]").click();
                            }
                        });
                    } else {
                        MITS.loadPreview();
                    }
                    break;
            }
        });
    });
    $(".quit-button").click(function(e) {
        e.preventDefault();
        var anchor = $(this);
        if (MITS.change_made && confirm("Save changes?")) {
            
			//alert(3);
			//return;
			
			MITS.saveIt();
        }
        setTimeout(function() {
            window.onbeforeunload = null;
            window.location = anchor.attr("href");
        }, 500);
    });
    MITS.windowResize();

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
        allowedTags: ["a", "b", "i", "p", "br", "strike", "span", 'u', 'li', 'ul'],
        buttons: ['bold', 'italic', 'underline', 'deleted', '|', 'link', '|', 'fontcolor', '|'],
        buttonsAdd: ["fontsize"],
        buttonsCustom: {
            fontsize: {
                title: "Font Size",
                dropdown: custom_fontsize
            }
        },
        execCommandCallback: function(obj) {
            MITS.basicRichTextChanged();
        },
        keyupCallback: function(obj) {
            MITS.basicRichTextChanged();
        }
    });
    $('#mits-richtext-area').redactor({
        source: false,
        buttons: ['formatting', '|', 'bold', 'italic', 'underline', 'deleted', '|', 'link', '|', 'fontcolor', '|', 'unorderedlist', 'orderedlist', 'outdent', 'indent', '|'],
        formattingTags: ['p', 'blockquote', 'h1', 'h2', 'h3'],
        buttonsAdd: ["fontsize"],
        buttonsCustom: {
            fontsize: {
                title: "Font Size",
                dropdown: custom_fontsize
            }
        },
        execCommandCallback: function(obj) {
            MITS.richTextAreaChanged();
        },
        keyupCallback: function(obj) {
            MITS.richTextAreaChanged();
        }
    });
});
var def_exit_message = 'Are you sure you want to leave this page?\n';
def_exit_message += '**********************************************\n';
def_exit_message += '\n';
def_exit_message += ' H E Y   C H E C K   T H I S   O U T !\n';
def_exit_message += '\n';
def_exit_message += ' CLICK THE *Stay on this page* BUTTON OR\n';
def_exit_message += ' THE *CANCEL* BUTTON RIGHT NOW\n';
def_exit_message += ' TO RECEIVE (insert your exit offer here)\n';
def_exit_message += ' GET IT NOW FOR FREE!\n';
def_exit_message += '\n';
def_exit_message += '**********************************************\n';
var MITS = {
    page_background_type: {
        'type': '',
        'image_id': ''
    },
    background_button: '',
    parent_img: '',
    loadedAWeberForms: false,
    loadedMailChimpForms: false,
    loadedIContactForms: false,
    loadedInfusionsoftForms: false,
    loadedGetresponseForms: false,
    loadedGoToWebinarWebinars: false,
    loadedConstantContactForms: false,
    different_simple_name: false,
    saving: false,
    change_made: false,
    edit: false,
    iframe: null,
    openingEditWindow: false,
    nowEditingElement: false,
    aweber_copy_paste: false,
    getresponse_copy_paste: false,
    default_data: {
        'video_embed_code': '',
        'thankyou_url': ''
    },
    edit_data: {},
    color_data: {},
    font_data: {},
    font_options_str: '',
    page_title: '',
    page_keywords: '',
    page_description: '',
    user_analytics_code: '',
    user_head_code: '',
    target_url: null,
    target_page: null,
    exit_popup: false,
    exit_popup_message: def_exit_message,
    exit_popup_redirect: false,
    exit_popup_redirect_url: '',
    iframe_top_position: 0,
    js_variables: {},
    template_js_variables: {},
    changeble_elements: {},
    changable_colors: {},
    changeable_button_color: {},
    changeable_button_style: {
        'slider': {},
        'select': {}
    },
    default_attribute_button: {
        'border-radius': {
            'name': 'Border Radius',
            'default': 0,
            'min': 0,
            'max': 20
        },
        'letter-spacing': {
            'name': 'Letter Spacing',
            'default': 0,
            'min': 0,
            'max': 10
        },
        'border-top-width': {
            'name': 'Top Border Width',
            'default': 0,
            'min': 0,
            'max': 15
        },
        'border-bottom-width': {
            'name': 'Bottom Border Width',
            'default': 0,
            'min': 0,
            'max': 15
        },
        'border-left-width': {
            'name': 'Left Border Width',
            'default': 0,
            'min': 0,
            'max': 15
        },
        'border-right-width': {
            'name': 'Right Border Width',
            'default': 0,
            'min': 0,
            'max': 15
        }
    },
    video_style: {
        style_1: "background: url(" + ASSET_URL + "/images/tablet-big.png) no-repeat; background-size: 100%; border: none",
        style_2: "background: url(" + ASSET_URL + "/images/video.png) no-repeat; border: 5px solid #fffffc; background-size: 100%",
        style_3: "background: none; border: none"
    },
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
    is_running: false,
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
    loadData: function(url, player_logo_urls, template_id, page_id, article_id, template_js_variables, edit, template_name) {
        MITS.template_name = template_name;
        MITS.user_analytics_code = $('#id-mits-analytics').val();
        MITS.user_head_code = $('#id-mits-head-code').val();
        MITS.template_js_variables = template_js_variables;
        MITS.page_id = page_id;
        MITS.article_id = article_id;
        MITS.template_id = template_id;
        MITS.player_logo_url = player_logo_urls;
        if (edit) {
            MITS.edit = true;
            MAPI.ajaxcall({
                data: {
                    "action": "api_call",
                    'request': 'get-page-data',
                    page_id: page_id
                },
                type: 'POST',
                async: true,
                success: function(data) {
                    var page_data = data.body;
                    MITS.edit_data = page_data['edit_data'];
                    if (page_data['color_data']) {
                        MITS.color_data = page_data['color_data'];
                    }
                    if (page_data['font_data']) {
                        MITS.font_data = page_data['font_data'];
                    }
                    if (page_data['js_variables_data']) {
                        MITS.js_variables = page_data['js_variables_data'];
                    }
                    MITS.afterLoadData(url);
                    MITS.target_page = $('#mits-target-page-id').val();
                }
            });
            MITS.page_title = $('#id-page-title').val();
            MITS.page_keywords = $('#id-page-keywords').val();
            MITS.page_description = $('#id-page-description').val();
            MITS.target_url = $('#target-url').val();
            MITS.exit_popup = $('#id-exit-popup').is(':checked');
            MITS.exit_popup_message = $('#id-exit-popup-message').val();
            MITS.exit_popup_redirect = $('#id-exit-popup-redirect').is(':checked');
            MITS.exit_popup_redirect_url = $('#id-exit-popup-redirect-url').val();
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
            MITS.afterLoadData(url);
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
        MITS.iframe = $('<iframe>', {
            'src': url,
            'id': 'mits-template-iframe',
            'noresize': 'noresize',
            'frameborder': '0',
            'height': ($(window).outerHeight() - $('#mits-top-navigation').outerHeight())
        }).load(function() {
            MITS.afterTemplateLoaded();
            MITS.iframe_top_position = $('#mits-template-iframe').offset().top;
        }).appendTo('#mits-iframe-wrapper');
        MITS.iframe.hide();
        $('#basic-page-settings').click(function(event) {
            event.preventDefault();
            MITS.openSEOSettings();
        });
        $('#exit-popup-settings').click(function(event) {
            event.preventDefault();
            MITS.openExitPopupSettings();
        });
        $('#industry-settings').click(function(event) {
            event.preventDefault();
            MITS.openIndustrySettings();
        });
        $('#target-page-settings').click(function(event) {
            event.preventDefault();
            MITS.openTargetPageSettings();
        });
        $('#target-url-settings').click(function(event) {
            event.preventDefault();
            MITS.openTargetURLSettings();
        });
        $('#tracking-code-settings').click(function(event) {
            event.preventDefault();
            MITS.openTrackingCodeSettings();
        });
        $('#style-settings').click(function(event) {
            event.preventDefault();
            MITS.openStyleSettings();
        });
        $('#font-settings').click(function(event) {
            event.preventDefault();
            MITS.openFontSettings();
        });
        MITS.setListeners();
        MITS.setJsVariables();
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
        MITS.cleanAndSetBasicRichText();
        MITS.basicRichTextChangedResize();
    },
    richTextAreaChanged: function() {
        MITS.cleanAndSetRichTextArea();
        MITS.richTextChangedResize();
        clearTimeout(MITS.richtext_timeout);
        MITS.richtext_timeout = setTimeout(function() {
            MITS.cleanAndSetRichTextArea();
            MITS.richTextChangedResize();
        }, 200);
    },
    windowResize: function() {
        if (MITS.iframe) {
            if ($("#mits-top-navigation").is(":visible")) {
                MITS.iframe.height($(window).height() - $('#mits-top-navigation').height());
            } else {
                MITS.iframe.height($(window).height());
            }
        }
        var templete_settings_width = (($('#template-settings').attr('data-hidden') === 'true') ? 0 : $('#template-settings').width());
        $('#mits-iframe-wrapper').height($(window).height());
        $('#mits-iframe-wrapper #mits-template-iframe').height('100%');
        $('#mits-iframe-wrapper').width($(window).width() - templete_settings_width);
        $('#mits-iframe-wrapper').css('padding-left', templete_settings_width + 'px');
        $('#mits-iframe-wrapper').css('padding-top', $('#powered_block').height());
        $('#template-settings').height($(window).height() - $('#mits-top-navigation').outerHeight(true) - 5);
        $('#white-box-left').height($(window).height() - $('#mits-top-navigation').outerHeight(true) - 20);
        $('#mits-editor').css('max-height', ($('#white-box-left').height() + $('#mits-top-navigation').height() - 30) + 'px');
        $('#mits-hide-editor-button').height($('#mits-iframe-wrapper').height());
        MITS.basicRichTextChangedResize();
        MITS.richTextChangedResize();
    },
    saveThisTemplate: function() {
        if (MITS.edit) {
            MITS.saveIt();
        } else {
            $('#mits-save-modal').modal('show');
        }
    },
    loadPictures: function(id, url) {

        if (id && url) {

            MITS.selectImage(null, url, id);
        }
        $('#images-loader').show();
        MITS.loadPicturesHandler(true, null);
    }, loadPicturesHandler: function(first_time, cstr) {
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
                    $('#lb-my-images').append($('<li class="my-images">').html('<a href="#" class="image-choose-btn" onclick="MITS.selectImage(this, \'' + data.body.images[i]['url'] + '\',' + data.body.images[i]['id'] + ');return false;"><img src="' + data.body.images[i]['url'] + '" alt="Image"></a>'));
                }
                if (data.body.has_more) {
                    MITS.loadPicturesHandler(false, data.body.cstr);
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
        MITS.setFonts();
        MITS.setEditableText();
        MITS.setChangebleStyle();
        MITS.iframe.contents().scroll(function() {
            MITS.moveElementBorder();
        });
        MITS.windowResize();
    },
    moveElementBorder: function() {
        $('.lb-border-element').each(function() {
            var $this = $(this);
            $this.css('top', (parseFloat($this.attr('data-lb-top')) + MITS.iframe_top_position - MITS.iframe.contents().scrollTop()) + 'px');
        });
    },
    loadIContactForms: function(reset) {
        var data = {
            "action": "api_call",
            integration_type: 'icontact',
            request: 'page-integrations'
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
                    if (MITS.edit_data['opt-in']) {
                        $('#lb-icontact-forms-select').val(MITS.edit_data['opt-in']['value']);
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
                        MITS.loadIContactForms(false);
                    }, 3000);
                }
                MITS.loadedIContactForms = true;
                MITS.checkIntegrationLoadedStatus();
            }
        });
    },
    loadAWeberForms: function(reset) {
        var data = {
            "action": "api_call",
            integration_type: 'aweber',
            request: 'page-integrations'
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
                            group.append($('<option value="' + form['html_source_link'] + '">' + form['name'] + '</option>'));
                        }
                        $('#lb-aweber-forms-select').append(group);
                    }
                    if (MITS.edit_data['opt-in']) {
                        $('#lb-aweber-forms-select').val(MITS.edit_data['opt-in']['value']);
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
                        MITS.loadAWeberForms(false);
                    }, 3000);
                } else if (data.status == '203') {
                    MITS.aweber_copy_paste = true;
                    $('#lb-aweber-copy-paste').show();
                    $('#aweber-choice .form-help-text').hide();
                    $('#lb-aweber-forms').empty();
                    $('#lb-aweber-forms').hide();
                }
                MITS.loadedAWeberForms = true;
                MITS.checkIntegrationLoadedStatus();
            }
        });
    },
    loadConstantContactForms: function(reset) {
        var data = {
            "action": "api_call",
            integration_type: 'constantcontact',
            request: 'page-integrations'
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
                    if (MITS.edit_data['opt-in']) {
                        $('#lb-constantcontact-forms-select').val(MITS.edit_data['opt-in']['value']);
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
                        MITS.loadConstantContactForms(false);
                    }, 3000);
                }
                MITS.loadedConstantContactForms = true;
                MITS.checkIntegrationLoadedStatus();
            }
        });
    },
    loadGetresponseForms: function(reset) {
        var data = {
            "action": "api_call",
            integration_type: 'getresponse',
            request: 'page-integrations'

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
                    if (MITS.edit_data['opt-in']) {
                        $('#lb-getresponse-forms-select').val(MITS.edit_data['opt-in']['value']);
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
                        MITS.loadGetresponseForms(false);
                    }, 3000);
                } else if (data.status == '203') {
                    MITS.getresponse_copy_paste = true;
                    $('#lb-getresponse-copy-paste').show();
                    $('#getresponse-choice .form-help-text').hide();
                    $('#lb-getresponse-forms').empty();
                    $('#lb-getresponse-forms').hide();
                }
                MITS.loadedGetresponseForms = true;
                MITS.checkIntegrationLoadedStatus();
            }
        });
    },
    loadInfusionsoftForms: function(reset) {
        var data = {
            "action": "api_call",
            integration_type: 'infusionsoft',
            request: 'page-integrations'
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
                    if (MITS.edit_data['opt-in']) {
                        $('#lb-infusionsoft-forms-select').val(MITS.edit_data['opt-in']['value']);
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
                        MITS.loadInfusionsoftForms(false);
                    }, 3000);
                }
                MITS.loadedInfusionsoftForms = true;
                MITS.checkIntegrationLoadedStatus();
            }
        });
    },
    loadMailChimpForms: function(reset) {
        var data = {
            "action": "api_call",
            integration_type: 'mailchimp',
            request: 'page-integrations'
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
                    if (MITS.edit_data['opt-in']) {
                        $('#lb-mailchimp-forms-select').val(MITS.edit_data['opt-in']['value']);
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
                        MITS.loadMailChimpForms(false);
                    }, 3000);
                }
                MITS.loadedMailChimpForms = true;
                MITS.checkIntegrationLoadedStatus();
            }
        });
    },
    loadGoToWebinarWebinars: function(reset) {
        if (reset) {
            $('#lb-reload-btn-gotowebinar').hide();
            $('#lb-gotowebinar-forms-select').hide();
            $('#lb-gotowebinar-loading').show();
        }
        MITS.loadingGoToWebinarWebinars = true;
        MAPI.ajaxcall({
            data: {
                "action": "api_call",
                integration_type: 'gotowebinar',
                request: 'page-integrations'
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
                    if (MITS.edit_data['opt-in']) {
                        $('#lb-gotowebinar-forms-select').val(MITS.edit_data['opt-in']['value']);
                    }
                    $('#lb-reload-btn-gotowebinar').show();
                    $('#lb-gotowebinar-forms-select').show();
                    $('#lb-gotowebinar-loading').hide();
                    AVAILABLE_SERVICES['gotowebinar'] = true;
                    MITS.loadingGoToWebinarWebinars = false;
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
                        MITS.loadGoToWebinarWebinars(false);
                    }, 3000);
                }
                MITS.loadedGoToWebinarWebinars = true;
                MITS.checkIntegrationLoadedStatus();
            }
        });
    },
    webinarChange: function() {
        if ($('#mits-gotowebinar-check').is(':checked')) {
            $('#gotowebinar-choice').slideDown();
        } else {
            $('#gotowebinar-choice').slideUp();
        }
        MITS.formTypeChange(false);
    },
    firstNameCheckboxChanged: function() {
        if (!MITS.selected_form_id) {
            return;
        }
        var show = (MITS.checkIfCanShowFirstName() && $('#mits-use-first-name-checkbox').is(':checked')) ? true : false;
        MITS.showHideFirstName(show);
    },
    phoneCheckboxChanged: function() {
        if (!MITS.selected_form_id) {
            return;
        }
        var show = (MITS.checkIfCanShowPhone() && $('#mits-use-phone-checkbox').is(':checked')) ? true : false;
        MITS.showHidePhone(show);
    },
    showHideFirstNameOption: function() {
        if (MITS.checkIfCanShowFirstName()) {
            $('#mits-use-first-name-form').show();
            var show = (MITS.checkIfCanShowFirstName() && $('#mits-use-first-name-checkbox').is(':checked')) ? true : false;
            MITS.showHideFirstName(show);
        } else {
            $('#mits-use-first-name-form').hide();
            MITS.showHideFirstName(false);
        }
    },
    showHidePhoneOption: function() {
        if (MITS.checkIfCanShowPhone()) {
            $('#mits-use-phone-form').show();
            var show = (MITS.checkIfCanShowPhone() && $('#mits-use-phone-checkbox').is(':checked')) ? true : false;
            MITS.showHidePhone(show);
        } else {
            $('#mits-use-phone-form').hide();
            MITS.showHidePhone(false);
        }
    },
    saveFirstName: function() {
        MITS.changeble_elements[MITS.selected_form_id]['use-name'] = (MITS.checkIfCanShowFirstName() && $('#mits-use-first-name-checkbox').is(':checked')) ? true : false;
    },
    savePhone: function() {
        MITS.changeble_elements[MITS.selected_form_id]['use-phone'] = (MITS.checkIfCanShowPhone() && $('#mits-use-phone-checkbox').is(':checked')) ? true : false;
    },
    showHideFirstName: function(show) {
        for (var k in MITS.changeble_elements[MITS.selected_form_id]['name-elements']) {
            var elem = $(MITS.changeble_elements[MITS.selected_form_id]['name-elements'][k]);
            if (show) {
                $('#lb-button-' + elem.attr('data-lb-id')).show();
                elem.show();
            } else {
                $('#lb-button-' + elem.attr('data-lb-id')).hide();
                elem.hide();
            }
        }
    },
    showHidePhone: function(show) {
        for (var k in MITS.changeble_elements[MITS.selected_form_id]['phone-elements']) {
            var elem = $(MITS.changeble_elements[MITS.selected_form_id]['phone-elements'][k]);
            if (show) {
                $('#lb-button-' + elem.attr('data-lb-id')).show();
                elem.show();
            } else {
                $('#lb-button-' + elem.attr('data-lb-id')).hide();
                elem.hide();
            }
        }
    },
    checkIfCanShowFirstName: function() {
        var show = false;
        if (MITS.first_name_available.indexOf(MITS.selected_form_optin_type) != -1 && MITS.changeble_elements[MITS.selected_form_id]['name-opt-in'] === true) {
            show = true;
        }
        return show;
    },
    checkIfCanShowPhone: function() {
        var show = false;
        if (MITS.phone_available.indexOf(MITS.selected_form_optin_type) != -1 && MITS.changeble_elements[MITS.selected_form_id]['phone-opt-in'] === true) {
            show = true;
        }
        return show;
    },
    formTypeChange: function(not_animate) {
        var selected_radio = $('input[name="form-options"]:checked');
        var show_redirect_url = false;
        var val = $('#id-mits-integration-select').val();
        if (not_animate) {
            $('.integration-choice:not(#' + val + '-choice)').hide();
        } else {
            $('.integration-choice:not(#' + val + '-choice)').slideUp();
        }
        if (val) {
            if (not_animate) {
                $('#' + val + '-choice').show();
            } else {
                $('#' + val + '-choice').slideDown();
            }
        }
        $('.note-for-t-y-p').hide();
        if ((val == 'aweber' && !MITS.aweber_copy_paste) || val == 'mailchimp' || val == 'icontact' || (val == 'getresponse' && !MITS.getresponse_copy_paste) || val == 'constantcontact' || val == 'gotowebinar') {
            var service_name = 'service';
            show_redirect_url = true;
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
            if (service_name != 'service') {
                $('.note-for-t-y-p').show();
            }
        }
        if (show_redirect_url) {
            if (not_animate) {
                $('#mits-modal-forms-thankyou').show();
            } else {
                $('#mits-modal-forms-thankyou').slideDown();
            }
        } else {
            if (not_animate) {
                $('#mits-modal-forms-thankyou').hide();
            } else {
                $('#mits-modal-forms-thankyou').slideUp();
            }
        }
        if (val != MITS.selected_form_optin_type) {
            $('#' + MITS.selected_form_optin_type + '-choice .btn-success.active').removeClass('btn-success active');
            MITS.selected_form_optin_type = val;
            MITS.selected_form_optin = null;
        }
        if (MITS.selected_form_optin_type == 'gotowebinar') {
            $('#gtw-on-off-btn').hide();
            $('#gtw-title').text('Choose your webinar');
            $('#gtw-block').insertBefore('#mits-modal-forms-thankyou');
        } else {
            if ($('#mits-gotowebinar-check').is(':checked')) {
                $('#gotowebinar-choice').slideDown();
            } else {
                $('#gotowebinar-choice').slideUp();
            }
            $('#gtw-on-off-btn').show();
            if ($('#gtw-title').text() != 'Integrate with GoToWebinar') {
                $('#gtw-title').text('Integrate with GoToWebinar');
            }
        }
        MITS.showHideFirstNameOption();
        MITS.showHidePhoneOption();
    },
    openSEOSettings: function() {
        MITS.beforeOpenEditor(null);
        $('#mits-modal-header').text('');
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#basic-settings-form').submit();
        });
        $('#mits-seo-settings').show();
        MITS.openEditor(null);
        $('#id-page-title').val(MITS.page_title);
        $('#id-page-description').val(MITS.page_description);
        $('#id-page-keywords').val(MITS.page_keywords);
        $('#id-mits-head-code').val(MITS.user_head_code);
        $('#id-mits-analytics').val(MITS.user_analytics_code);
        MITS.submitFormListener = function() {
            MITS.saveSEOSettings();
        };
        MITS.closeEditorListener = function(cancel_button) {
            if (cancel_button !== true) {
                MITS.submitFormListener();
                return;
            }
            $('#id-page-title').val(MITS.page_title);
            $('#id-page-description').val(MITS.page_description);
            $('#id-page-keywords').val(MITS.page_keywords);
            $('#id-mits-head-code').val(MITS.user_head_code);
            $('#id-mits-analytics').val(MITS.user_analytics_code);
        };
    },
    openTrackingCodeSettings: function() {
        MITS.beforeOpenEditor(null);
        $('#mits-modal-header').html('Tracking Code');
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#tracking-code-form').submit();
        });
        $('#mits-tracking-code').show();
        MITS.openEditor(null);
        $('#id-mits-head-code').val(MITS.user_head_code);
        $('#id-mits-analytics').val(MITS.user_analytics_code);
        MITS.submitFormListener = function() {
            MITS.saveTrackingCodeSettings();
        };
        MITS.closeEditorListener = function(cancel_button) {
            if (cancel_button !== true) {
                MITS.submitFormListener();
                return;
            }
            $('#id-mits-head-code').val(MITS.user_head_code);
            $('#id-mits-analytics').val(MITS.user_analytics_code);
        };
    },
    openIndustrySettings: function() {
        var $industryWrapper = $("#mits-industry-settings");
        var $industrySettingsForm = $("#mits-industry-settings-form");
        var $industrySelector = $("#mits-industry-selector");
        MITS.beforeOpenEditor(null);
        $industryWrapper.show();
        MITS.openEditor(null);
        $industrySettingsForm.submit(function(e) {
            e.preventDefault();
            MITS.industry = $industrySelector.val();
            MITS.markChange();
            MITS.closeEditor();
        });
        $("#mits-form-submit").click(function() {
            $industrySettingsForm.submit();
        });
    },
    openTargetPageSettings: function() {
        var $targetPageButton = $("#target-page-settings");
        var $targetPageWrapper = $("#mits-target-page-settings");
        var $targetPageForm = $("#target-page-settings-form");
        var $targetPageMessage = $("#mits-target-page-message");
        var $targetPageChooseButton = $("#mits-target-page-choose");
        var $targetPageRemoveButton = $("#mits-target-page-remove");
        var $targetPageChooseModal = $("#mits-select-conversion-goal-modal");
        var targetPage = MITS.target_page;
        MITS.beforeOpenEditor(null);
        $targetPageWrapper.show();
        MITS.openEditor(null);
        $("#mits-modal-header").html('Conversion Goal Page');
        var choosePage = function() {
            window.selectConversionGoalCallback = function(id, name) {
                if (id == MITS.page_id) {
                    alert("You must select a page other than this one as your conversion goal.");
                    return;
                }
                $targetPageChooseModal.modal("hide");
                $targetPageMessage.html('Your current Conversion Goal Page is ' + '<span class="label label-info">' + name + '</span>' + '.');
                targetPage = id;
                $targetPageChooseButton.hide();
                $targetPageRemoveButton.show();
            };
            $targetPageChooseModal.modal("show");
        };
        var removePage = function() {
            targetPage = null;
            $targetPageRemoveButton.hide();
            $targetPageChooseButton.show();
            $targetPageMessage.html("You haven't selected a Conversion Goal Page yet.");
        };
        if (!targetPage) {
            choosePage();
        }
        $targetPageChooseButton.click(choosePage);
        $targetPageRemoveButton.click(removePage);
        $targetPageForm.submit(function(e) {
            e.preventDefault();
            MITS.target_page = targetPage;
            MITS.markChange();
            MITS.closeEditor();
            $targetPageButton.addClass("success");
            if (MITS.saveAfterConversionGoal) {
                MITS.ignoreConversionGoalModal = true;
                MITS.uiSave();
            }
        });
        $('#mits-form-submit').click(function() {
            $targetPageForm.submit();
        });
    },
    openTargetURLSettings: function() {
        var $targetURLButton = $("#target-url-settings");
        var $targetURLWrapper = $("#mits-target-url-settings");
        var $targetURLForm = $("#target-url-settings-form");
        var $targetURL = $("#target-url");
        MITS.beforeOpenEditor(null);
        $targetURLWrapper.show();
        MITS.openEditor(null);
        $targetURL.val(MITS.target_url || "");
        $("#mits-modal-header").html('Target URL');
        $targetURLForm.submit(function(e) {
            e.preventDefault();
            var targetURL = $targetURL.val().trim();
            if (targetURL.indexOf("://") === -1) {
                return;
            }
            MITS.target_url = targetURL;
            MITS.closeEditor();
            $targetURLButton.addClass("success");
        });
        $('#mits-form-submit').click(function() {
            $targetURLForm.submit();
        });
    },
    openExitPopupSettings: function() {
        MITS.beforeOpenEditor(null);
        $('#exit-popup-on').unbind('click');
        $('#exit-popup-off').unbind('click');
        $('#exit-popup-on').click(function() {
            MITS.toggleExitPopup(true);
            return false;
        });
        $('#exit-popup-off').click(function() {
            MITS.toggleExitPopup(false);
            return false;
        });
        $('#exit-popup-redirect-on').unbind('click');
        $('#exit-popup-redirect-off').unbind('click');
        $('#exit-popup-redirect-on').click(function() {
            MITS.toggleExitPopupRedirect(true);
            return false;
        });
        $('#exit-popup-redirect-off').click(function() {
            MITS.toggleExitPopupRedirect(false);
            return false;
        });
        $('#mits-modal-header').html('Exit Popup');
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#exit-popup-settings-form').submit();
        });
        $('#mits-exit-popup-settings').show();
        MITS.openEditor(null);
        MITS.populateDefaultExpitPopupValues();
        MITS.submitFormListener = function() {
            MITS.saveExitPopupSettings();
        };
        MITS.closeEditorListener = function(cancel_button) {
            if (cancel_button !== true) {
                MITS.submitFormListener();
                return;
            }
            MITS.populateDefaultExpitPopupValues();
        };
    },
    toggleExitPopup: function(enabled) {
        if (enabled) {
            $('#id-exit-popup').prop('checked', true);
            $('#exit-popup-off').removeClass('btn-danger active');
            $('#exit-popup-on').addClass('btn-success active');
            $('#id-exit-popup-message').attr('disabled', false);
            $('#exit-popup-redirect-on').removeClass('disabled');
            $('#exit-popup-redirect-off').removeClass('disabled');
        } else {
            $('#id-exit-popup').prop('checked', false);
            $('#exit-popup-on').removeClass('btn-success active');
            $('#exit-popup-off').addClass('btn-danger active');
            $('#id-exit-popup-message').attr('disabled', true);
            $('#exit-popup-redirect-on').addClass('disabled');
            $('#exit-popup-redirect-off').addClass('disabled');
        }
    },
    toggleExitPopupRedirect: function(enabled) {
        if (enabled) {
            $('#id-exit-popup-redirect').prop('checked', true);
            $('#exit-popup-redirect-off').removeClass('btn-danger active');
            $('#exit-popup-redirect-on').addClass('btn-success active');
            $('#id-exit-popup-redirect-url').attr('disabled', false);
        } else {
            $('#id-exit-popup-redirect').prop('checked', false);
            $('#exit-popup-redirect-on').removeClass('btn-success active');
            $('#exit-popup-redirect-off').addClass('btn-danger active');
            $('#id-exit-popup-redirect-url').attr('disabled', true);
        }
    },
    populateDefaultExpitPopupValues: function() {
        MITS.toggleExitPopup(MITS.exit_popup);
        $('#id-exit-popup-message').val(MITS.exit_popup_message);
        MITS.toggleExitPopupRedirect(MITS.exit_popup_redirect);
        $('#id-exit-popup-redirect-url').val(MITS.exit_popup_redirect_url);
    },
    saveSEOSettings: function() {
        MITS.page_title = $('#id-page-title').val();
        MITS.page_keywords = $('#id-page-keywords').val();
        MITS.page_description = $('#id-page-description').val();
        MITS.user_analytics_code = $('#id-mits-analytics').val();
        MITS.user_head_code = $('#id-mits-head-code').val();
        $('#basic-page-settings').addClass('success');
    },
    saveTrackingCodeSettings: function() {
        MITS.user_analytics_code = $('#id-mits-analytics').val();
        MITS.user_head_code = $('#id-mits-head-code').val();
        $('#tracking-code-settings').addClass('success');
    },
    saveExitPopupSettings: function() {
        MITS.exit_popup = $('#id-exit-popup').is(':checked');
        MITS.exit_popup_message = $('#id-exit-popup-message').val();
        MITS.exit_popup_redirect = $('#id-exit-popup-redirect').is(':checked');
        MITS.exit_popup_redirect_url = $('#id-exit-popup-redirect-url').val();
        $('#exit-popup-settings').addClass('success');
    },
    selectOptInFormSubmit: function() {
        if (MITS.selected_form_optin_type == 'gotowebinar') {
            MITS.selected_form_optin = ($('#lb-gotowebinar-forms-select').val()) ? $('#lb-gotowebinar-forms-select').val() : null;
        } else {
            MITS.selected_form_optin = $('#lb-' + MITS.selected_form_optin_type + '-forms-select').val();
        }
        MITS.selected_webinar_key = ($('#lb-gotowebinar-forms-select').val()) ? $('#lb-gotowebinar-forms-select').val() : null;
        if (!$('#mits-modal-forms-form').valid()) {
            return false;
        }
        if ($('#mits-gotowebinar-check').is(':checked') && !MITS.selected_webinar_key) {
            $('#gotowebinar-choice p.p-transition').addClass('text-error');
            return false;
        } else if ($('#mits-gotowebinar-check').is(':checked') && MITS.selected_webinar_key && MITS.selected_form_optin_type != 'gotowebinar') {
            MITS.changeble_elements[MITS.selected_form_id]['webinar'] = true;
            MITS.changeble_elements[MITS.selected_form_id]['webinar_key'] = MITS.selected_webinar_key;
        } else {
            MITS.changeble_elements[MITS.selected_form_id]['webinar'] = false;
            MITS.changeble_elements[MITS.selected_form_id]['webinar_key'] = null;
        }
        if (!MITS.selected_form_optin && (MITS.selected_form_optin_type == 'gotowebinar' || MITS.selected_form_optin_type == 'mailchimp' || MITS.selected_form_optin_type == 'icontact' || (MITS.selected_form_optin_type == 'getresponse' && !MITS.getresponse_copy_paste) || (MITS.selected_form_optin_type == 'aweber' && !MITS.aweber_copy_paste) || MITS.selected_form_optin_type == 'infusionsoft')) {
            return false;
        }
        if ((MITS.selected_form_optin_type == 'aweber' && !MITS.aweber_copy_paste) || MITS.selected_form_optin_type == 'mailchimp' || MITS.selected_form_optin_type == 'icontact' || (MITS.selected_form_optin_type == 'getresponse' && !MITS.getresponse_copy_paste) || MITS.selected_form_optin_type == 'constantcontact' || MITS.selected_form_optin_type == 'gotowebinar') {
            var redirect_url = $('#id-form-typ-url').val();
            if (!redirect_url) {
                return false;
            }
            MITS.changeble_elements[MITS.selected_form_id]['optin_type'] = MITS.selected_form_optin_type;
            MITS.changeble_elements[MITS.selected_form_id]['value'] = MITS.selected_form_optin;
            MITS.changeble_elements[MITS.selected_form_id]['redirect_url'] = redirect_url;
        } else if ((MITS.selected_form_optin_type == 'getresponse' && MITS.getresponse_copy_paste) || (MITS.selected_form_optin_type == 'aweber' && MITS.aweber_copy_paste) || MITS.selected_form_optin_type == 'other' || MITS.selected_form_optin_type == 'officeautopilot' || MITS.selected_form_optin_type == 'shoppingcart' || MITS.selected_form_optin_type == 'sendreach') {
            var val = null;
            var error_message = null;
            if (MITS.selected_form_optin_type == 'officeautopilot') {
                val = $('#id-mits-officeautopilot-copy-paste').val();
                error_message = $('#officeautopilot-error-msg');
            } else if (MITS.selected_form_optin_type == 'sendreach') {
                val = $('#id-mits-sendreach-copy-paste').val();
                error_message = $('#sendreach-error-msg');
            } else if (MITS.selected_form_optin_type == 'shoppingcart') {
                val = $('#id-mits-shoppingcart-copy-paste').val();
                error_message = $('#shoppingcart-error-msg');
            } else if (MITS.selected_form_optin_type == 'aweber') {
                val = $('#id-mits-aweber-copy-paste').val();
                error_message = $('#aweber-error-msg');
            } else if (MITS.selected_form_optin_type == 'getresponse') {
                val = $('#id-mits-getresponse-copy-paste').val();
                error_message = $('#getresponse-error-msg');
            } else if (MITS.selected_form_optin_type == 'other') {
                val = $('#id-mits-other-copy-paste').val();
                error_message = $('#other-error-msg');
            }

            if (!val) {
                error_message.addClass('error-msg');
                return false;
            }
            try {
                var $val = $(val);
                if (!$(val).is('form') && !$val.find('form').get(0)) {
                    error_message.addClass('error-msg');
                    return false;
                }
                if (val.indexOf("</form>") < 1) {
                    error_message.addClass('error-msg');
                    return false;
                }
            } catch (err) {
                error_message.addClass('error-msg');
                return false;
            }
            error_message.removeClass('error-msg');
            if (!val) {
                MITS.changeble_elements[MITS.selected_form_id]['optin_type'] = null;
                MITS.changeble_elements[MITS.selected_form_id]['value'] = null;
            } else {
                MITS.changeble_elements[MITS.selected_form_id]['optin_type'] = MITS.selected_form_optin_type;
                MITS.changeble_elements[MITS.selected_form_id]['value'] = escape(val);
            }
            MITS.changeble_elements[MITS.selected_form_id]['redirect_url'] = '';
        } else if (MITS.selected_form_optin_type == 'aweber' || MITS.selected_form_optin_type == 'getresponse' || MITS.selected_form_optin_type == 'infusionsoft') {
            MITS.changeble_elements[MITS.selected_form_id]['optin_type'] = MITS.selected_form_optin_type;
            MITS.changeble_elements[MITS.selected_form_id]['value'] = MITS.selected_form_optin;
            MITS.changeble_elements[MITS.selected_form_id]['redirect_url'] = '';
        }
        MITS.saveFirstName();
        MITS.savePhone();
        MITS.showHideFirstName(MITS.changeble_elements[MITS.selected_form_id]['use-name']);
        MITS.showHidePhone(MITS.changeble_elements[MITS.selected_form_id]['use-phone']);
        $('#lb-button-' + MITS.selected_form_id).addClass('success');
        MITS.closeEditor();
        MITS.removableCheckOnsubmit(MITS.selected_form_id);
        MITS.selected_form_id = null;
        MITS.selected_form_optin = null;
        MITS.selected_form_optin_type = null;
        MITS.markChange();
    },
    saveIt: function(callback, beforeAPICb) {
        
		
		
		if (MITS.saving) {
            return;
        }
      
        var request = 'save-page';
        if (MITS.edit) {
            $('#save-btn-space button').button('loading');
        } else {
            $('#mits-save-submit').button('loading');
        }
        MITS.iframe.contents().find('.mits-text-editable').attr('contentEditable', null);
        var element_data = $.extend(true, {}, MITS.changeble_elements);
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

        var button_styles = {
            'button_styles': MITS.changeable_button_style
        };
        var page_background_type = {
            'page_background_type': MITS.page_background_type
        };
        var total_data = $.extend(element_data, button_styles, page_background_type);
        MITS.saving = true;
        if (MITS.hasValidationError)
            MITS.hideValidationError();
        if (beforeAPICb)
            beforeAPICb();
        var color_data = $.extend(MITS.changable_colors, MITS.changeable_button_color);

        MAPI.ajaxcall({
            data: {
                "action": "api_call",
                'request': request,
                'page_id': MITS.page_id,
                'template_id': MITS.template_id,
                'page_name': $('#id_page_name').val(),
                'page_url': $('#id_page_url').val(),
                'domain_url': $('#id_domain_url').val(),
                'data': JSON.stringify(total_data),
                'color_data': JSON.stringify(color_data),
                'font_data': JSON.stringify(MITS.changable_fonts),
                'js_data': JSON.stringify(MITS.js_variables),
                'page_title': MITS.page_title,
                'page_keywords': MITS.page_keywords,
                'page_description': MITS.page_description,
                'user_end_code': MITS.user_analytics_code,
                'user_head_code': MITS.user_head_code,
                'exit_popup': MITS.exit_popup,
                'exit_popup_message': MITS.exit_popup_message,
                'exit_popup_redirect': MITS.exit_popup_redirect,
                'exit_popup_redirect_url': MITS.exit_popup_redirect_url,
            },
            type: 'POST',
            async: true,
            success: function(data) {
               //   alert(data);
		//return;
                var id = data.body.id;
                var article_id = data.body.article_id;
                var onSuccess = function() {
                    MITS.change_made = false;
                    $("#save-button").removeClass("disabled");
                    $("#save-button").removeClass("has-changes");
                    MITS.saving = false;
                    MITS.page_id = id;
                    MITS.article_id = article_id;
                    MITS.edit = true;
					
					//#HT
					// $("#id_page_url").removeClass("editable");
					// $("#id_page_url").addClass("readonly");
					// $("#id_page_url").attr("readonly", 'true');
					 
					
                    if (callback)
                        callback(true);
                };
                onSuccess();
            },
            error: function() {
                MITS.saving = false;
                if (callback)
                    callback(false);
            }});
    },
    setListeners: function() {
        var pre_submit = function() {
            if (!$(this).valid()) {
                return false;
            }
            MITS.submitFormListener();
            MITS.closeEditorListener = function() {
            };
            MITS.closeEditor();
            MITS.markChange();
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
            MITS.selectOptInFormSubmit();
            return false;
        });
        $('#mits-change-js-variables form').submit(pre_submit);
        $('#mits-edit-analytics form').submit(function() {
            if (!$(this).valid()) {
                return false;
            }
            MITS.submitFormListener();
            MITS.markChange();
            return false;
        });
        $('#id-mits-integration-select').click(function() {
            MITS.formTypeChange(false);
        });
        $('#id-mits-integration-select').change(function() {
            MITS.formTypeChange(false);
        });
        $('#lb-gotowebinar-forms-select').change(function() {
            $('#selected-webinar-dont-exist').fadeOut();
        });
    },
    setJsVariables: function() {
        var variables = false;
        var load_js = [];
        for (var k in MITS.template_js_variables) {
            variables = true;
            var variable = MITS.template_js_variables[k];
            var html_form = '';
            var value = variable['dafault'];
            var name = variable['name'];
            var validate = variable['validate'];
            var desc = '';

            var elem_id = 'id-lpjs-' + k;

            if (variable['desc']) {
                desc = variable['desc'];
            }

            if (variable['load_js']) {

                js_arr = {
                    'js_type': variable['load_js'],
                    'elem': elem_id
                };

                load_js.push(js_arr);
            }

            if (MITS.js_variables[k]) {
                value = MITS.js_variables[k]['value'];
            }

            html_form = ' <div class="control-group">';
            html_form += '  <label class="control-label" for="' + elem_id + '">' + name + '</label>' + desc;
            html_form += '  <div class="input-space controls">';
            if (variable['load_js'] == 'datetimepicker') {
                html_form += '<div id="' + elem_id + '" class="input-append">';
                html_form += '  <input class="input-w84 js-variable-input" data-id="' + k + '" name="lpjs-' + k + '" value="' + value + '" readonly data-format="MM/dd/yyyy HH:mm:ss PP" type="text"></input>';
                html_form += '  <span class="add-on">';
                html_form += '      <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>';
                html_form += '  </span>';
                html_form += '</div>';
            } else {
                html_form += '      <input id="' + elem_id + '" data-id="' + k + '" class="input-w90 js-variable-input ' + validate + '" type="text" value="' + value + '" name="lpjs-' + k + '">';
            }

            html_form += '  </div>';
            html_form += '</div>';
            $('#js-var-space').append(html_form);

        }
        if (variables) {
            $('#js-settings').click(function() {
                MITS.editJavasCriptVariables();
                return false;
            });

            $('#js-settings').show();

            if (load_js) {
                $('#mits-systemtime').show();
                for (var i in load_js) {
                    MITS.loadAltJs(load_js[i]['js_type'], load_js[i]['elem']);
                }
            }
        }
    },
    loadAltJs: function(js_type, elem) {
        switch (js_type) {
            case "datetimepicker":
                $('#' + elem).datetimepicker({
                    pick12HourFormat: true
                });
                break;
        }
    },
    setFonts: function() {
        MITS.font_options_str = '<option value="">Default font</option>';
        for (var k in GOOGLE_FONTS) {
            $('head').append($('<link href="' + GOOGLE_FONTS[k]['url'] + '" rel="stylesheet" type="text/css" />'));
            MITS.iframe.contents().find('head').append($('<link href="' + GOOGLE_FONTS[k]['url'] + '" rel="stylesheet" type="text/css" />'));
            MITS.font_options_str = MITS.font_options_str + '<option value="' + GOOGLE_FONTS[k]['name'] + '">' + GOOGLE_FONTS[k]['name'] + '</option>';
        }
    },
    setChangebleStyle: function() {
        /* Fonts */
        var $this = $(this);
        var font = 'Arial';
        var name = 'Main Font';
        var value = font;
        if (MITS.font_data[font] && MITS.font_data[font]['value']) {
            value = MITS.font_data[font]['value'];
        }
        MITS.changable_fonts[font] = {'font': font, 'name': name, 'value': value};
        for (var k in MITS.changable_fonts) {
            var font_form_html = '';
            font_form_html = font_form_html + '<div class="control-group">';
            //                font_form_html = font_form_html + '   <label class="control-label">' + MITS.changable_fonts[k]['name'] + '</label>';
            font_form_html = font_form_html + '   <div class="input-space controls">';
            font_form_html = font_form_html + '       <select data-lb-base-font="' + k + '" class="font-picker input-large">' + MITS.font_options_str + '</select>';
            font_form_html = font_form_html + '   </div>';
            font_form_html = font_form_html + '</div>';
            var ffh = $(font_form_html);
            ffh.find('option[value=""]').val(k);
            $('#fonts-inp-space').append(ffh);
            $('.font-picker[data-lb-base-font="' + k + '"]').val(MITS.changable_fonts[k]['value']);
            ffh.find('select').change(MITS.doStyleQuickChange);
        }

        /* Colors */
        var colors = false;
        $('#mits-template-iframe').contents().find('head meta[name="mp-customizable-color"]').each(function() {
            colors = true;
            var $this = $(this);
            var color = $.trim($(this).attr('content').split(';')[0]);
            var name = $.trim($(this).attr('content').split(';')[1]);
            var button_id = $this.attr('button_id');
            var has_background_color = $this.attr('background_color');
            if (typeof has_background_color != 'undefined') {
                $("#background_type_wrap").show();
                $('#background_type_group').attr('background_color', color);
            }

            var value = color;
            if (MITS.color_data[color] && MITS.color_data[color]['value']) {
                value = MITS.color_data[color]['value'];
            }

            if (button_id) {
                MITS.changeable_button_color[color] = {
                    'color': color,
                    'name': name,
                    'value': value,
                    'button': button_id
                };
            } else {
                MITS.changable_colors[color] = {
                    'color': color,
                    'name': name,
                    'value': value
                };
            }
        });
        if (colors) {
            for (var c in MITS.changable_colors) {
                var color_form_html = '';
                color_form_html = color_form_html + '<div class="control-group">';
                color_form_html = color_form_html + ' <label class="control-label">' + MITS.changable_colors[c]['name'] + '</label>';
                color_form_html = color_form_html + ' <div class="input-space controls">';
                color_form_html = color_form_html + '     <input type="minicolors" data-lb-base-color="' + c + '" value="' + MITS.changable_colors[c]['value'] + '" class="color-picker input-mini" />';
                color_form_html = color_form_html + ' </div>';
                color_form_html = color_form_html + '</div>';
                $('#colors-inp-space').append($(color_form_html));
            }
            for (var c in MITS.changeable_button_color) {
                var color_form_html = '';
                color_form_html = color_form_html + '<div class="control-group" button_id="' + MITS.changeable_button_color[c]['button'] + '">';
                color_form_html = color_form_html + ' <label class="control-label">' + MITS.changeable_button_color[c]['name'] + '</label>';
                color_form_html = color_form_html + ' <div class="input-space controls">';
                color_form_html = color_form_html + '     <input type="minicolors" data-lb-base-color="' + c + '" value="' + MITS.changeable_button_color[c]['value'] + '" class="color-picker input-mini button-color" />';
                color_form_html = color_form_html + ' </div>';
                color_form_html = color_form_html + '</div>';
                $('#mits-button-colors').append($(color_form_html));
            }
            $("input.color-picker").minicolors({
                change: function() {
                    MITS.doStyleQuickChange();
                },
                changeDelay: 200
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
        MITS.doStyleChange();
    },
    editJavasCriptVariables: function() {
        MITS.beforeOpenEditor(null);
        $('#mits-modal-header').html('Dynamic Options');
        $('#mits-change-js-variables').show();
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#mits-change-js-variables form').submit();
        });
        MITS.openEditor();
        MITS.submitFormListener = function() {
            $('.js-variable-input').each(function() {
                var $this = $(this);
                var value = $this.val();
                var name = $this.attr('data-id');
                MITS.js_variables[name] = {
                    'name': name,
                    'value': value
                };
            });
            $('#js-settings').addClass('success');
        };
    },
    openStyleSettings: function() {
        MITS.beforeOpenEditor(null);
        $('#mits-modal-header').html('Color Settings');
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#mits-change-style form').submit();
        });
        MITS.openEditor();
        $('#mits-change-style').show();
        MITS.submitFormListener = function() {
            $("input.color-picker").not('.button-color').each(function() {
                var $this = $(this);
                var value = $this.val();
                var key = $this.attr('data-lb-base-color');
                if (MITS.changable_colors[key]['value'] != value) {
                    if (value === '') {
                        value = key;
                    }
                    MITS.changable_colors[key]['value'] = value;
                }
            });
            $('#style-settings').addClass('success');
            MITS.doStyleChange();
        };
        MITS.closeEditorListener = function() {
            $("input.color-picker").not('.button-color').each(function() {
                var $this = $(this);
                var key = $this.attr('data-lb-base-color');

                $this.val(MITS.changable_colors[key]['value'].toLowerCase());
                $this.minicolors('value', MITS.changable_colors[key]['value'].toLowerCase());
            });
            MITS.doStyleChange();
        };
    },
    openFontSettings: function() {
        MITS.beforeOpenEditor(null);
        $('#mits-modal-header').html('Font Settings');
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#mits-change-font form').submit();
        });
        MITS.openEditor();
        $('#mits-change-font').show();
        MITS.submitFormListener = function() {
            $("select.font-picker").each(function() {
                var $this = $(this);
                var value = $this.val();
                var key = $this.attr('data-lb-base-font');
                if (MITS.changable_fonts[key]['value'] != value) {
                    MITS.changable_fonts[key]['value'] = value;
                }
            });
            $('#style-settings').addClass('success');
            MITS.doStyleChange();
        };
        MITS.closeEditorListener = function() {
            $("select.font-picker").each(function() {
                var $this = $(this);
                var key = $this.attr('data-lb-base-font');
                $this.val(MITS.changable_fonts[key]['value']);
            });
            MITS.doStyleChange();
        };
    },
    resetColors: function() {
        $("input.color-picker").each(function() {
            var $this = $(this);
            var key = $this.attr('data-lb-base-color');
            $this.val(MITS.changable_colors[key]['color'].toLowerCase());
            $this.minicolors('value', MITS.changable_colors[key]['color'].toLowerCase());
        });
        MITS.doStyleQuickChange();
    },
    doStyleChange: function() {
        $('#mits-template-iframe').contents().find('head style[type="text/css"]').each(function() {
            var $this = $(this);
            $this.attr('data-lb-changing', $this.attr('data-lb-original'));
        });
        MITS.doColorChange();
        MITS.doFontChange();
        $('#mits-template-iframe').contents().find('head style[type="text/css"]').each(function() {
            var $this = $(this);
            $this.text($this.attr('data-lb-changing'));
            $this.attr('data-lb-changing', '');
        });
    },
    doStyleQuickChange: function() {
        $('#mits-template-iframe').contents().find('head style[type="text/css"]').each(function() {
            var $this = $(this);
            $this.attr('data-lb-changing', $this.attr('data-lb-original'));
        });
        MITS.doColorQuickChange();
        MITS.doFontQuickChange();
        $('#mits-template-iframe').contents().find('head style[type="text/css"]').each(function() {
            var $this = $(this);
            $this.text($this.attr('data-lb-changing'));
            $this.attr('data-lb-changing', '');
        });
    },
    doColorQuickChange: function() {
        $("input.color-picker").each(function() {
            var $this = $(this);
            var key = $this.attr('data-lb-base-color');

            if ($this.hasClass('button-color')) {
                var old_color = MITS.changeable_button_color[key]['color'];
            } else {
                var old_color = MITS.changable_colors[key]['color'];
            }
            var new_color = $this.val();
            MITS.executeColorChangeInCss(old_color, new_color);
        });
    },
    doFontQuickChange: function() {
        $("select.font-picker").each(function() {
            var $this = $(this);
            var new_font = "'" + $this.val().replace(new RegExp("'", 'g'), '') + "'";
            var key = $this.attr('data-lb-base-font');
            var old_font = MITS.changable_fonts[key]['font'].replace(new RegExp("'", 'g'), '');
            MITS.executeFontChangeInCss(old_font, new_font);
        });
    },
    doColorChange: function() {
        for (var k in MITS.changable_colors) {
            if (MITS.changable_colors[k]['value']) {
                var old_color = MITS.changable_colors[k]['color'];
                var new_color = MITS.changable_colors[k]['value'];
                MITS.executeColorChangeInCss(old_color, new_color);
            }
        }
        for (var k in MITS.changeable_button_color) {
            if (MITS.changeable_button_color[k]['value']) {
                var old_color = MITS.changeable_button_color[k]['color'];
                var new_color = MITS.changeable_button_color[k]['value'];
                MITS.executeColorChangeInCss(old_color, new_color);
            }
        }
    },
    doFontChange: function() {
        for (var k in MITS.changable_fonts) {
            if (MITS.changable_fonts[k]['value']) {
                var old_font = MITS.changable_fonts[k]['font'].replace(new RegExp("'", 'g'), '');
                var new_font = "'" + MITS.changable_fonts[k]['value'].replace(new RegExp("'", 'g'), '') + "'";
                MITS.executeFontChangeInCss(old_font, new_font);
            }
        }
    },
    executeColorChangeInCss: function(old_color, new_color) {
        $('#mits-template-iframe').contents().find('head style[type="text/css"]').each(function() {
            var $this = $(this);
            $this.attr('data-lb-changing', $this.attr('data-lb-changing').replace(new RegExp(old_color.toUpperCase(), 'g'), new_color));
            $this.attr('data-lb-changing', $this.attr('data-lb-changing').replace(new RegExp(old_color.toLowerCase(), 'g'), new_color));
        });
    }, executeFontChangeInCss: function(old_font, new_font) {
        $('#mits-template-iframe').contents().find('head style[type="text/css"]').each(function() {
            var $this = $(this);
            $this.attr('data-lb-changing', $this.attr('data-lb-changing').replace(new RegExp("'" + old_font + "'", 'g'), new_font));
        });
    },
    setEditableText: function() {
        $('#mits-template-iframe').contents().find('form').submit(MITS.return_false);
        $('#mits-template-iframe').contents().find('a').click(function(e) {
            e.preventDefault();
        });
        $('#mits-template-iframe').contents().find('label').click(MITS.return_false);

        $('#mits-template-iframe').contents().find('[data-lb*="editable"]').each(function() {
            var elem = this;
            var $this = $(this);
            var element_id = null;
            var no_clicking = false;
            var exist = false;
            var element_data = {};
            var function_to_call = function() {
            };
            if ($this.attr('data-lb-id')) {
                element_id = $this.attr('data-lb-id');
            }
            if (!MITS.changeble_elements[element_id]) {
                element_data = {
                    'lb-id': element_id,
                    'removable': false,
                    'removed': false,
                    'hidden': false,
                    'comment': ($this.attr('data-lb-comment')) ? $this.attr('data-lb-comment') : null,
                    'elements': [elem],
                    'type': '',
                    'data-lb': $this.attr('data-lb'),
                    'name': $this.attr('data-lb-name'),
                    'icon': ''
                };
            } else {
                element_data = MITS.changeble_elements[element_id];
                exist = true;
                element_data = MITS.changeble_elements[element_id];
                element_data['elements'].push(elem);
            }
            if ($this.is('a[data-lb~="editable-link"]') && (!exist || element_data['type'] == 'link')) {
                if (!exist) {
                    function_to_call = function() {
                        MITS.editLink(elem, element_id);
                    };
                    element_data['type'] = 'link';
                    element_data['href'] = '';
                    element_data['new_window'] = false;
                    element_data['nofollow'] = false;
                    element_data['default_text'] = $this.text();
                }
                if ((MITS.edit || exist) && MITS.edit_data[element_id] && MITS.edit_data[element_id]['type'] == 'link' && (MITS.edit_data[element_id]['href'] || MITS.edit_data[element_id]['text'] || MITS.edit_data[element_id]['nofollow'] || MITS.edit_data[element_id]['new_window'])) {
                    if (!$this.is('a[data-lb~="link-only"]')) {
                        $this.text(MITS.edit_data[element_id]['text']);
                    }
                    if (MITS.edit_data[element_id]['new_window']) {
                        element_data['new_window'] = true;
                    }
                    if (MITS.edit_data[element_id]['nofollow']) {
                        element_data['nofollow'] = true;
                    }
                    $this.attr('href', MITS.edit_data[element_id]['href']);
                    element_data['href'] = $this.attr('href');
                    element_data['default_text'] = null;
                }
                element_data['connected-image'] = null;
                element_data['icon'] = 'link';
                if ($this.is('[data-lb~="link-only"]') && $this.children().length == 1 && $($this.children()[0]).is('img[data-lb~="editable-image"]')) {
                    element_data['connected-image'] = {
                        'elem': $($this.children()[0]),
                        'id': $($this.children()[0]).attr('data-lb-id')
                    };
                    element_data['icon'] = 'image';
                }
                element_data['text'] = $this.text();

                var $parent = $this.parent();
                if ($parent.is('[data-lb~="editable-video"]')) {
                    element_data['hidden'] = true;
                    no_clicking = true;
                }

            } else if ($this.is('input[type="submit"][data-lb~="editable-submit"]') && (!exist || element_data['type'] == 'submit')) {
                if (!exist) {
                    function_to_call = function() {
                        MITS.editSubmitButton(element_id);
                    };
                    element_data['type'] = 'submit';
                    element_data['default_text'] = $this.val();
                }
                if ((MITS.edit || exist) && MITS.edit_data[element_id] && MITS.edit_data[element_id]['type'] == 'submit' && MITS.edit_data[element_id]['text']) {
                    $this.val(MITS.edit_data[element_id]['text']);
                    element_data['default_text'] = null;
                }
                if (!exist) {
                    element_data['text'] = $this.val();
                }
                element_data['icon'] = 'text';
            } else if ($this.is('[data-lb~="editable-fadein-box"]') && (!exist || element_data['type'] == 'fadin-box')) {
                if (!exist) {
                    function_to_call = function() {
                        MITS.editFadeInwBox(element_id);
                    };
                    no_clicking = true;
                    element_data['type'] = 'fadin-box';
                    element_data['time'] = (MITS.edit_data[element_id] && MITS.edit_data[element_id]['time']) ? MITS.edit_data[element_id]['time'] : 0;
                }
                element_data['icon'] = 'fadein';
            } else if ($this.is('[data-lb~="editable-s1"]') && (!exist || element_data['type'] == 'fadin-box')) {
               if (!exist) {
                    function_to_call = function() {
                        MITS.onoffbox(element_id);                       
                    };
                    no_clicking = true;
                    element_data['type'] = 'fadin-box';
                    element_data['time'] = (MITS.edit_data[element_id] && MITS.edit_data[element_id]['time']) ? MITS.edit_data[element_id]['time'] : 0;
                }
                element_data['icon'] = 'fadein'; 
           }else if ($this.is('[data-lb~="editable-video"]') && (!exist || element_data['type'] == 'video')) {
                if (!exist) {
                    function_to_call = function() {
                        MITS.editVideo(element_id, $this);
                    };
                    element_data['type'] = 'video';
                    element_data['video_style'] = (MITS.edit_data[element_id] && typeof MITS.edit_data[element_id]['video_style'] != 'undefined') ? MITS.edit_data[element_id]['video_style'] : '';
                    element_data['default_text'] = ($this.html()) ? $this.html() : null;
                    element_data['value'] = (MITS.edit_data[element_id] && MITS.edit_data[element_id]['value']) ? MITS.edit_data[element_id]['value'] : MITS.default_data['video_embed_code'];
                    element_data['video-or-image'] = (MITS.edit_data[element_id] && typeof MITS.edit_data[element_id]['video-or-image'] != 'undefined') ? MITS.edit_data[element_id]['video-or-image'] : true;
                    var data_lb = $this.attr('data-lb').split(' ');
                    var video_width = 0;
                    var video_height = 0;
                    for (var k in data_lb) {
                        if (data_lb[k].substr(0, 12) == 'video-width=' && data_lb[k].split('=')[1]) {
                            video_width = data_lb[k].split('=')[1];
                        } else if (data_lb[k].substr(0, 13) == 'video-height=' && data_lb[k].split('=')[1]) {
                            video_height = data_lb[k].split('=')[1];
                        }
                    }
                    element_data['width'] = video_width;
                    element_data['height'] = video_height;
                    if (element_data['video_style']) {
                        $('#video-player-style-' + element_data['video_style']).addClass('btn-success active');
                    }
                }

                element_data['connected-image'] = null;
                if ($this.children().length == 1 && $this.children().is('a[data-lb~="link-only"]') && $this.children().find('img').is('[data-lb~="editable-image"]')) {
                    element_data['connected-link'] = {
                        'elem': $this.children(),
                        'id': $this.children().attr('data-lb-id')
                    };
                    element_data['connected-image'] = {
                        'elem': $this.children().find('img'),
                        'id': $this.children().find('img').attr('data-lb-id')
                    };
                    if (typeof $this.children().attr('data-lb-comment') != 'undefined')
                        element_data['comment'] = $this.children().attr('data-lb-comment');
                    else
                        element_data['comment'] = $this.children().find('img').attr('data-lb-comment');
                } else if ($this.children().length == 1 && $this.children().is('img[data-lb~="editable-image"]')) {
                    element_data['connected-image'] = {
                        'elem': $this.children(),
                        'id': $this.children().attr('data-lb-id')};
                    element_data['comment'] = $this.children().attr('data-lb-comment');
                }

                if (!$.trim(element_data['default_text'])) {
                    $this.html('<div style="width:' + (parseFloat(element_data['width']) - 2) + 'px;height:' + (parseFloat(element_data['height']) + 22) + 'px;background: url(\'' + MITS.player_logo_url['empty'] + '\') center center no-repeat; background-size: 80%"></div>');
                    element_data['default_text'] = $this.html();
                }
                if (element_data['value'] && element_data['video-or-image']) {
                    MITS.insertEmbedVideo(elem, element_id, element_data, unescape(element_data['value']), element_data['default_text']);
                }

                element_data['icon'] = 'videos';
            } else if ($this.is('[data-lb~="editable-embed-area"]') && (!exist || element_data['type'] == 'embed-area')) {
                if (!exist) {
                    function_to_call = function() {
                        MITS.editEmbedArea(element_id);
                    };
                    element_data['type'] = 'embed-area';
                    element_data['default_text'] = $this.html();
                    element_data['value'] = (MITS.edit_data[element_id] && MITS.edit_data[element_id]['value']) ? MITS.edit_data[element_id]['value'] : '';
                    var data_lb = $this.attr('data-lb').split(' ');
                    var area_width = 0;
                    var area_height = 0;
                    for (var i in data_lb) {
                        if (data_lb[i].substr(0, 11) == 'area-width=' && data_lb[i].split('=')[1]) {
                            area_width = data_lb[i].split('=')[1];
                        } else if (data_lb[i].substr(0, 12) == 'area-height=' && data_lb[i].split('=')[1]) {
                            area_height = data_lb[i].split('=')[1];
                        }
                    }
                    element_data['width'] = area_width;
                    element_data['height'] = area_height;
                }
                if (!$.trim(element_data['default_text'])) {
                    $this.html('<div style="width:' + (parseFloat(element_data['width']) - 2) + 'px;height:' + (parseFloat(element_data['height']) - 2) + 'px;background:#fff url(\'' + MITS.player_logo_url['embed_area'] + '\') center center no-repeat; border: 1px solid #888;"></div>');
                    element_data['default_text'] = $this.html();
                }
                if (element_data['value']) {
                    MITS.insertEmbed(elem, element_id, unescape(element_data['value']), null);
                }
                element_data['icon'] = 'embedhtml';
            } else if ($this.is('input[type="text"][data-lb~="editable-text-input"]') && (!exist || element_data['type'] == 'text_input')) {
                if (!exist) {
                    function_to_call = function() {
                        $this.blur();
                        MITS.editPlaceHolder(element_id);
                    };
                    element_data['type'] = 'text_input';
                    element_data['default_text'] = $this.attr('title');
                }
                if ((MITS.edit || exist) && MITS.edit_data[element_id] && MITS.edit_data[element_id]['type'] === 'text_input' && (MITS.edit_data[element_id]['title'] || MITS.edit_data[element_id]['title'] === '')) {
                    $this.attr('title', MITS.edit_data[element_id]['title']);
                    $this.val(MITS.edit_data[element_id]['title']);
                    element_data['default_text'] = null;
                }
                element_data['title'] = ($this.attr('title')) ? $this.attr('title') : '';
                $this.val(element_data['title']);
                element_data['icon'] = 'text';
            } else if ($this.is('img[data-lb~="editable-image"]') && (!exist || element_data['type'] == 'image')) {
                if (!exist) {
                    function_to_call = function() {
                        MITS.editImage(element_id);
                    };
                    element_data['type'] = 'image';
                    element_data['default_url'] = $this.attr('src');
                }
                var img_id = '';
                if ((MITS.edit || exist) && MITS.edit_data[element_id] && MITS.edit_data[element_id]['type'] == 'image' && MITS.edit_data[element_id]['url']) {
                    $this.attr('src', MITS.edit_data[element_id]['url']);
                    img_id = MITS.edit_data[element_id]['id'];
                }

                element_data['url'] = $this.attr('src');
                element_data['id'] = img_id;
                var $parent = $this.parent();
                if ($parent.is('a[data-lb~="editable-link"]') && $parent.is('a[data-lb~="link-only"]')) {
                    element_data['hidden'] = true;
                    no_clicking = true;
                }
                var video_parent = $this.closest('div,section');
                if (video_parent.is('[data-lb~=editable-video]')) {
                    element_data['video_parent'] = video_parent;
                } else {
                    element_data['video_parent'] = false;
                }
                element_data['icon'] = 'image';

                MITS.page_background_type['type'] = (MITS.edit_data['page_background_type'] && typeof MITS.edit_data['page_background_type'] != 'undefined') ? MITS.edit_data['page_background_type']['type'] : '';

                if (element_id == 'background' || $(elem).attr('id') == 'bgimg') {
                    $('#background_type_group').attr('element_id', $(elem).attr('id'));
                    $('#background_type_group *').removeClass('btn-success active');
                    $('#background_type_image').addClass('btn-success active');
                    MITS.background_button = 'lb-button-' + element_id;
                    MITS.page_background_type['image_id'] = element_id;
                }

                var background_color = $('#background_type_group').attr('background_color');

                if (MITS.page_background_type['type'] == 'image') {
                    $(elem).show();
                    $('.color-picker[data-lb-base-color="' + background_color + '"]').closest('.control-group').hide();
                    $('#' + MITS.background_button).hide();
                } else if (MITS.page_background_type['type'] == 'color') {
                    $(elem).hide();
                    $('.color-picker[data-lb-base-color="' + background_color + '"]').closest('.control-group').show();
                    $('#' + MITS.background_button).show();
                }

            } else if ($this.is('form[data-lb~="editable-opt-in-form"]') && (!exist || element_data['type'] == 'opt-in-form')) {
                function_to_call = function() {
                    MITS.editOptinForm(element_id);
                };
                no_clicking = true;
                if (!exist) {
                    element_data['type'] = 'opt-in-form';
                    element_data['redirect_url'] = MITS.default_data['thankyou_url'];
                    element_data['webinar'] = false;
                    element_data['use-name'] = false;
                    element_data['use-phone'] = false;
                    element_data['name-opt-in'] = false;
                    element_data['phone-opt-in'] = false;
                    element_data['name-elements'] = [];
                    element_data['phone-elements'] = [];
                    element_data['webinar_key'] = null;
                    if ($this.find('input[data-lb~="opt-in-name"]').get(0)) {
                        element_data['name-opt-in'] = true;
                    }
                    if ($this.find('input[data-lb~="opt-in-phone"]').get(0)) {
                        element_data['phone-opt-in'] = true;
                    }

                    if (MITS.edit && MITS.edit_data[element_id] && MITS.edit_data[element_id]['type'] == 'opt-in-form') {
                        if (MITS.edit_data[element_id]['optin_type'] && MITS.edit_data[element_id]['value']) {
                            element_data['optin_type'] = MITS.edit_data[element_id]['optin_type'];
                            element_data['value'] = MITS.edit_data[element_id]['value'];
                            if (element_data['optin_type'] == 'aweber' || element_data['optin_type'] == 'mailchimp' || element_data['optin_type'] == 'icontact' || element_data['optin_type'] == 'getresponse' || element_data['optin_type'] == 'constantcontact' || element_data['optin_type'] == 'gotowebinar') {
                                element_data['redirect_url'] = MITS.edit_data[element_id]['redirect_url'];
                            }
                            if (element_data['name-opt-in'] && MITS.edit_data[element_id]['use-name']) {
                                element_data['use-name'] = true;
                            }
                            if (element_data['phone-opt-in'] && MITS.edit_data[element_id]['use-phone']) {
                                element_data['use-phone'] = true;
                            }
                            if (!AVAILABLE_SERVICES[element_data['optin_type']]) {
                                element_data['optin_type'] = '';
                                element_data['value'] = '';
                            }
                            if (MITS.edit_data[element_id]['webinar'] && MITS.edit_data[element_id]['webinar_key'] && AVAILABLE_SERVICES['gotowebinar']) {
                                element_data['webinar'] = MITS.edit_data[element_id]['webinar'];
                                element_data['webinar_key'] = MITS.edit_data[element_id]['webinar_key'];
                            }
                        } else {
                            element_data['optin_type'] = '';
                            element_data['value'] = '';
                        }
                    } else {
                        element_data['optin_type'] = '';
                        element_data['value'] = '';
                    }
                }
                element_data['icon'] = 'optinform';
            } else if ($this.is('[data-lb~="editable-button"]')) {
                if (!exist) {
                    function_to_call = function() {
                        MITS.editButton(elem, element_id);
                    };
                    element_data['type'] = 'button';

                    element_data['default_text'] = $this.html();
                }
                if ((MITS.edit || exist) && MITS.edit_data[element_id] && MITS.edit_data[element_id]['type'] == 'button' && MITS.edit_data[element_id]['text']) {
                    $this.html(MITS.edit_data[element_id]['text']);
                    element_data['default_text'] = null;
                }
                if (!exist) {
                    element_data['text'] = $this.html();
                }
                element_data['icon'] = 'button';

                $(elem).css('transition', '0.5s all');
                //SLIDERS TYPE
                if (typeof MITS.edit_data['button_styles'] == 'undefined' || typeof MITS.edit_data['button_styles']['slider'][element_id] == 'undefined') {
                    var button_attribute = {};
                    for (var att in MITS.default_attribute_button) {
                        button_attribute[att] = {
                            'name': MITS.default_attribute_button[att]['name'],
                            'value': MITS.default_attribute_button[att]['default'],
                            'min': MITS.default_attribute_button[att]['min'],
                            'max': MITS.default_attribute_button[att]['max']
                        };
                    }
                    MITS.changeable_button_style['slider'][element_id] = button_attribute;
                } else {
                    MITS.changeable_button_style['slider'][element_id] = MITS.edit_data['button_styles']['slider'][element_id];
                }

                var slider_attribute = MITS.changeable_button_style['slider'][element_id];

                for (var att in slider_attribute) {
                    var id = element_id + '-' + att;
                    var min = parseFloat(slider_attribute[att]['min']);
                    var max = parseFloat(slider_attribute[att]['max']);
                    var value = slider_attribute[att]['value'];

                    var style_html = '';
                    style_html += '<div class="control-group" button_id="' + element_id + '">';
                    style_html += '  <label>' + slider_attribute[att]['name'] + '</label>';
                    style_html += '  <div class="input-space controls">';
                    style_html += '      <input type="text" id="' + id + '" name="' + id + '" value="' + slider_attribute[att]['value'] + '" attribute="' + att + '" />';
                    style_html += '  </div>';
                    style_html += '</div>';
                    $('#mits-button-styles').append(style_html);

                    var slider = $('#' + id).slider({
                        min: min,
                        max: max,
                        value: parseFloat(value),
                        formatter: function(value) {
                            return value + 'px';
                        }
                    });
                    //Init value
                    $(elem).css(att, value + 'px');

                    slider.on('slide slideStop', function(slide) {
                        var attribute = $(this).attr('attribute');
                        $(elem).css(attribute, slide.value + 'px');
                        MITS.changeable_button_style['slider'][element_id][attribute]['value'] = slide.value;
                    });
                }

                //SELECT TYPE
                if (typeof MITS.edit_data['button_styles'] == 'undefined' || typeof MITS.edit_data['button_styles']['select'][element_id] == 'undefined') {
                    MITS.changeable_button_style['select'][element_id] = {
                        'border-style': {
                            'name': 'Border Style',
                            'value': 'solid'
                        }
                    };
                } else {
                    MITS.changeable_button_style['select'][element_id] = MITS.edit_data['button_styles']['select'][element_id];
                }

                var border_style = MITS.changeable_button_style['select'][element_id]['border-style'];
                var border_style_name = border_style['name'];
                var border_style_value = border_style['value'];
                var border_id = element_id + '-border-style';
                var font_form_html = '';
                font_form_html += '<div button_id="' + element_id + '" class="control-group">';
                font_form_html += '   <label>' + border_style_name + '</label>';
                font_form_html += '   <div class="input-space controls">';
                font_form_html += '       <select id="' + border_id + '" class="input-large">';
                font_form_html += '           <option value="solid">Solid</option>';
                font_form_html += '           <option value="dashed">Dashed</option>';
                font_form_html += '           <option value="dotted">Dotted</option>';
                font_form_html += '           <option value="double">Double</option>';
                font_form_html += '       </select>';
                font_form_html += '   </div>';
                font_form_html += '</div>';
                $('#mits-button-styles').prepend(font_form_html);

                //Init value
                $('#' + border_id).val(border_style_value);
                $(elem).css('border-style', border_style_value);

                $('#' + border_id).change(function() {
                    var value = $(this).val();
                    $(elem).css('border-style', value);
                    MITS.changeable_button_style['select'][element_id]['border-style']['value'] = value;
                });


            } else if ($this.is('[data-lb~="editable-text"]') || $this.is('[data-lb~="editable-rich-text"]') && (!exist || element_data['type'] == 'text')) {
                if (!exist) {
                    function_to_call = function() {
                        MITS.editText(elem, element_id);
                    };
                    element_data['type'] = 'text';
                    if ($this.is('[data-lb~="editable-rich-text"]')) {
                        element_data['default_text'] = $this.html();
                    } else {
                        element_data['default_text'] = $this.text();
                    }
                }
                if ((MITS.edit || exist) && MITS.edit_data[element_id] && MITS.edit_data[element_id]['type'] == 'text' && MITS.edit_data[element_id]['text']) {
                    if ($this.is('[data-lb~="editable-rich-text"]')) {
                        $this.html(MITS.edit_data[element_id]['text']);
                    } else {
                        $this.text(MITS.edit_data[element_id]['text']);
                    }
                    element_data['default_text'] = null;
                }
                if (!exist) {
                    if ($this.is('[data-lb~="editable-rich-text"]')) {
                        element_data['text'] = $this.html();
                    } else {
                        element_data['text'] = $this.text();
                    }
                }
                element_data['icon'] = 'text';
            } else if ($this.is('[data-lb~="editable-richtext-area"]') && (!exist || element_data['type'] == 'richtext-area')) {
                if (!exist) {
                    function_to_call = function() {
                        MITS.editRichTextArea(elem, element_id);
                    };
                    element_data['type'] = 'richtext-area';
                    element_data['default_text'] = $this.html();
                }
                if ((MITS.edit || exist) && MITS.edit_data[element_id] && MITS.edit_data[element_id]['type'] === 'richtext-area' && MITS.edit_data[element_id]['text']) {
                    $this.html(MITS.edit_data[element_id]['text']);
                    element_data['default_text'] = null;
                } else if (MITS.edit && !MITS.edit_data[element_id] && $this.attr('data-lb-migrate')) {
                    var text = '';
                    var ids = $this.attr('data-lb-migrate').split(' ');
                    for (i in ids) {
                        var id = ids[i];
                        if (!MITS.iframe.contents().find('[data-lb-id="' + id + '"]').get(0) && MITS.edit_data[id] && MITS.edit_data[id]['text']) {
                            text += '<p>' + MITS.edit_data[id]['text'] + '</p>';
                        }
                    }
                    if (text) {
                        element_data['text'] = text;
                        $this.html(text);
                    }
                }
                if (!exist) {
                    element_data['text'] = $this.html();
                }
                element_data['icon'] = 'text';
            } else if ($this.is('[data-lb~="editable-likebox"]') && !$.isEmptyObject(MITS.js_variables)) {
                $this.attr('data-href', MITS.js_variables['facebooklikebox']['value']);
            }

            if (($this.is('[data-lb~="editable-removable"]') || $this.is('[data-lb~="editable-optional"]')) || element_data['removable']) {
                element_data['removable'] = true;
                if (!exist && !element_data['type']) {
                    function_to_call = function() {
                        MITS.editRemovable(elem, element_id);
                    };
                }
                if (!element_data['type']) {
                    element_data['type'] = 'removable';
                }
                if (MITS.edit && MITS.edit_data[element_id] && MITS.edit_data[element_id]['removed'] === true) {
                    element_data['removed'] = true;
                }
               
                if (element_data['removed'] === true) {
                     $this.hide();
                }
                if (!element_data['icon']) {
                    element_data['icon'] = 'removable';
                }
            }
            if (!exist) {
                element_data['function_to_call'] = function() {
                    if (MITS.selected_element_id && (MITS.selected_element_id != element_id || (MITS.nowEditingElement === false || MITS.nowEditingElement != MITS.selected_element_id))) {
                        MITS.closeEditorListener();
                    }
                    MITS.selected_element_id = element_id;
                    function_to_call();
                };
            }
            if (element_id) {
                MITS.changeble_elements[element_id] = element_data;
            }
            if (element_data['type'] != 'opt-in-form' && element_data['type'] != 'fadin-box') {
                $this.css('cursor', 'pointer');
            }
            if (!no_clicking) {
                $this.click(function(event) {
                    if (MITS.openingEditWindow) {
                        return;
                    }
                    MITS.openingEditWindow = true;
                    element_data['function_to_call']();
                    setTimeout(function() {
                        MITS.openingEditWindow = false;
                    }, 100);
                });
            }
        });
        for (var k in MITS.changeble_elements) {
            var addclass = 'content_accordion_btn';
            if (MITS.changeble_elements[k]['lb-id'] === 'opt-in')
                addclass = 'accordion_btn';
            var button_elem = $('<a>', {
                'id': 'lb-button-' + MITS.changeble_elements[k]['lb-id'], 'class': 'btn-block ' + addclass,
                'href': '#popup_settings'
            }).html(MITS.changeble_elements[k]['name'] + '<span></span>');
            button_elem.click(MITS.changeble_elements[k]['function_to_call']);
            var mouseOver = function(key) {
                return function() {
                    var this_elems = MITS.changeble_elements[key]['elements'];
                    if (MITS.changeble_elements[key]['removable'] === true && MITS.changeble_elements[key]['removed'] === true) {
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
                            'class': 'lb-border-element', 'data-lb-top': offset.top
                        });
                        $('body').append(border_top, border_bottom, border_left, border_right);
                        border_top.width(width);
                        border_bottom.width(width);
                        border_left.height(height);
                        border_right.height(height);
                        var elem_top = offset.top + MITS.iframe_top_position - MITS.iframe.contents().scrollTop();
                        var elem_left = offset.left + (($('#template-settings').attr('data-hidden') === 'true') ? 0 : $('#template-settings').width());
                        border_top.css('top', elem_top + 'px');
                        border_top.css('left', elem_left + 'px');
                        border_left.css('top', elem_top + 'px');
                        border_left.css('left', elem_left + 'px');
                        border_bottom.css('top', (elem_top + height - 1) + 'px');
                        border_bottom.css('left', elem_left + 'px');
                        border_right.css('top', elem_top + 'px');
                        border_right.css('left', (elem_left + width) + 'px');
                        if ((MITS.changeble_elements[key]['type'] != 'image' && MITS.changeble_elements[key]['lb-id'] != 'background') && MITS.changeble_elements[key]['type'] != 'opt-in-form') {
                            var zindex = '100';
                            if (elem.parent().hasClass('fancybox-pop')) {
                                zindex = '-1';
                            }
                            var background_clicable = $('<div></div>', {
                                'class': 'lb-border-element',
                                'data-lb-top': offset.top,
                                'style': 'background: rgba(255,255,225,0.4);box-shadow: none;cursor:pointer;z-index:' + zindex
                            });
                            background_clicable.css('top', elem_top + 'px');
                            background_clicable.css('left', elem_left + 'px');
                            background_clicable.width(width);
                            background_clicable.height(height);
                            background_clicable.click(MITS.changeble_elements[key]['function_to_call']);
                            $('body').append(background_clicable);
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
            if (MITS.changeble_elements[k])
                var mouseOut = function(key) {
                    return function() {
                        $('.lb-border-element').remove();
                        $('#editable-elements a.hover').removeClass('hover');
                    };
                };
            if (!MITS.changeble_elements[k]['hidden']) {
                button_elem.mouseover(mouseOver(k));
                if (MITS.changeble_elements[k]['type'] != 'opt-in-form' && MITS.changeble_elements[k]['type'] != 'fadin-box') {
                    for (var i in MITS.changeble_elements[k]['elements']) {
                        $(MITS.changeble_elements[k]['elements'][i]).mouseover(mouseOver(k));
                    }
                }
                if (MITS.changeble_elements[k]['type'] == 'opt-in-form') {
                    button_elem.css({
                        'color': '#cccccc !important'
                    });
                    $('#gen-parameters-item').after($('<li>').append(button_elem));
                } else {
                    $('#editable-elements').append($('<li>').append(button_elem));
                }
            }
        }
        for (var k in MITS.changeble_elements) {
            if (MITS.changeble_elements[k]['type'] == 'opt-in-form' && MITS.changeble_elements[k]['name-opt-in']) {
                for (i in MITS.changeble_elements[k]['elements']) {
                    var elems = $(MITS.changeble_elements[k]['elements'][i]).find('input[data-lb~="opt-in-name"]');
                    MITS.changeble_elements[k]['name-elements'].push(elems);
                    var parent_elements = $(MITS.changeble_elements[k]['elements'][i]).find('[data-lb~="opt-in-name-wrapper"]');
                    MITS.changeble_elements[k]['name-elements'].push(parent_elements);
                    if (!MITS.changeble_elements[k]['use-name']) {
                        elems.hide();
                        parent_elements.hide();
                        $('#lb-button-' + elems.attr('data-lb-id')).hide();
                    }
                }
            }
            if (MITS.changeble_elements[k]['type'] == 'opt-in-form' && MITS.changeble_elements[k]['phone-opt-in']) {
                for (i in MITS.changeble_elements[k]['elements']) {
                    var elems = $(MITS.changeble_elements[k]['elements'][i]).find('input[data-lb~="opt-in-phone"]');
                    MITS.changeble_elements[k]['phone-elements'].push(elems);
                    var parent_elements = $(MITS.changeble_elements[k]['elements'][i]).find('[data-lb~="opt-in-phone-wrapper"]');
                    MITS.changeble_elements[k]['phone-elements'].push(parent_elements);
                    if (!MITS.changeble_elements[k]['use-phone']) {
                        elems.hide();
                        parent_elements.hide();
                        $('#lb-button-' + elems.attr('data-lb-id')).hide();
                    }
                }
            }
        }
        MITS.positionLeftPanel();
        $('#template-settings').fadeIn();
        MITS.iframe.fadeIn();
        $('#mits-iframe-wrapper').removeClass('iframe-loading');
    },
    positionLeftPanel: function() {
        $('#template-settings').css('top', $('#mits-top-navigation').outerHeight() + 'px');
    },
    checkIntegrationLoadedStatus: function() {
        if (MITS.allIntegrationsLoaded()) {
            $('#lb-button-opt-in').css({
                'color': ''
            });
        }
    },
    allIntegrationsLoaded: function() {
        return (MITS.loadedAWeberForms === true && MITS.loadedMailChimpForms === true && MITS.loadedIContactForms === true && MITS.loadedInfusionsoftForms === true && MITS.loadedGetresponseForms === true && MITS.loadedGoToWebinarWebinars === true && MITS.loadedConstantContactForms === true);
    },
    //    allIntegrationsLoaded: function() {
    //        return (MITS.loadedBribeMailFiles === true && MITS.loadedAWeberForms === true && MITS.loadedMailChimpForms === true && MITS.loadedIContactForms === true && MITS.loadedInfusionsoftForms === true && MITS.loadedGetresponseForms === true && MITS.loadedGoToWebinarWebinars === true && MITS.loadedConstantContactForms === true);
//    },
    editOptinForm: function(element_id) {

        $('#mits-modal-header').text('Opt-in Form Integration');
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
        MITS.selected_form_id = null;
        MITS.selected_form_optin = null;
        MITS.selected_form_optin_type = null;
        MITS.selected_webinar_key = null;
        $('#id-mits-aweber-copy-paste').val('');
        $('#id-mits-getresponse-copy-paste').val('');
        $('#id-mits-other-copy-paste').val('');
        $('#id-mits-officeautopilot-copy-paste').val('');
        $('#id-mits-sendreach-copy-paste').val('');
        $('#id-mits-shoppingcart-copy-paste').val('');
        var integration_type = MITS.changeble_elements[element_id]['optin_type'];
        MITS.selected_form_id = element_id;
        if (integration_type && MITS.changeble_elements[element_id]['value']) {
            MITS.selected_form_optin = MITS.changeble_elements[element_id]['value'];
            MITS.selected_form_optin_type = integration_type;
        }
        //        $('#mits-modal-header').html(MITS.changeble_elements[element_id]['name'] + ' <i class="editico ic-' + MITS.changeble_elements[element_id]['icon'] + ' pull-right"></i>');
        MITS.beforeOpenEditor(element_id);
        $('#mits-edit-optin-form').show();
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            MITS.selectOptInFormSubmit();
        });
        MITS.closeEditorListener = function() {
            MITS.showHideFirstName(MITS.changeble_elements[MITS.selected_form_id]['use-name']);
            MITS.showHidePhone(MITS.changeble_elements[MITS.selected_form_id]['use-phone']);
            MITS.selected_form_id = null;
            MITS.selected_form_optin = null;
            MITS.selected_form_optin_type = null;
        };
        $('#id-form-typ-url').val(MITS.changeble_elements[element_id]['redirect_url']);
        /* GoToWebinar */
        if (MITS.changeble_elements[element_id]['webinar'] && integration_type != 'gotowebinar') {
            $('#gtw-on').addClass('btn-success active');
            $('#gtw-off').removeClass('btn-danger active');
            $('#mits-gotowebinar-check').prop('checked', true);
            $('#gotowebinar-choice').show();
            MITS.selected_webinar_key = MITS.changeble_elements[element_id]['webinar_key'];
            $('#lb-gotowebinar-forms-select').val(MITS.selected_webinar_key);
            $('#gotowebinar-choice').show();
            $('#selected-webinar-dont-exist').hide();
            if ($('#lb-gotowebinar-forms-select').val() != MITS.selected_webinar_key) {
                if (MITS.loadedGoToWebinarWebinars) {
                    $('#selected-webinar-dont-exist').show();
                    MITS.changeble_elements[element_id]['webinar_key'] = null;
                    MITS.selected_webinar_key = null;
                } else {
                    $('#lb-reload-btn-gotowebinar').hide();
                    $('#lb-gotowebinar-forms-select').hide();
                    $('#lb-gotowebinar-loading').show();
                    var intId = setInterval(function() {
                        if (MITS.loadedGoToWebinarWebinars) {
                            $('#lb-gotowebinar-forms-select').val(MITS.selected_webinar_key);
                            clearInterval(intId);
                        }
                        else {
                            if (!MITS.loadingGoToWebinarWebinars)
                                MITS.loadGoToWebinarWebinars(false);
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
            if (MITS.aweber_copy_paste) {
                MITS.changeble_elements[MITS.selected_form_id]['optin_type'] = 'aweber';
                if (MITS.changeble_elements[MITS.selected_form_id]['value']) {
                    $('#id-mits-aweber-copy-paste').val(unescape(MITS.changeble_elements[MITS.selected_form_id]['value']));
                }
            } else {
                $('#lb-' + integration_type + '-forms-select').val(MITS.changeble_elements[element_id]['value']);
            }
        } else if (integration_type == 'getresponse') {
            $('#id-mits-integration-select').val(integration_type);
            if (MITS.getresponse_copy_paste) {
                MITS.changeble_elements[MITS.selected_form_id]['optin_type'] = 'getresponse';
                if (MITS.changeble_elements[MITS.selected_form_id]['value']) {
                    $('#id-mits-getresponse-copy-paste').val(unescape(MITS.changeble_elements[MITS.selected_form_id]['value']));
                }
            } else {
                $('#lb-' + integration_type + '-forms-select').val(MITS.changeble_elements[element_id]['value']);
            }
        } else if (integration_type == 'mailchimp' || integration_type == 'infusionsoft' || integration_type == 'icontact' || integration_type == 'constantcontact' || integration_type == 'gotowebinar') {
            $('#id-mits-integration-select').val(integration_type);
            $('#lb-' + integration_type + '-forms-select').val(MITS.changeble_elements[element_id]['value']);
        } else if (integration_type == 'other') {
            $('#id-mits-integration-select').val(integration_type);
            MITS.changeble_elements[MITS.selected_form_id]['optin_type'] = 'other';
            if (MITS.changeble_elements[MITS.selected_form_id]['value']) {
                $('#id-mits-other-copy-paste').val(unescape(MITS.changeble_elements[MITS.selected_form_id]['value']));
            }
        }
        if ($('#id-mits-integration-select option.integration').length == 1) {
            var one_form = $('#id-mits-integration-select option.integration').attr('value');
            $('#id-mits-integration-select').val(one_form);
            MITS.selected_form_optin_type = one_form;
        }
        MITS.selected_form_id = element_id;
        if (integration_type) {
            MITS.selected_form_optin_type = integration_type;
        }
        if (MITS.changeble_elements[element_id]['value']) {
            MITS.selected_form_optin = MITS.changeble_elements[element_id]['value'];
        }
        MITS.openEditor(null, element_id);
        MITS.formTypeChange(true);
        if (MITS.changeble_elements[MITS.selected_form_id]['use-name']) {
            $('#mits-use-first-name-checkbox').prop('checked', true);
            $('#first-name-on').addClass('btn-success active');
            $('#first-name-off').removeClass('btn-danger active');
        } else {
            $('#mits-use-first-name-checkbox').prop('checked', false);
            $('#first-name-on').removeClass('btn-success active');
            $('#first-name-off').addClass('btn-danger active');
        }
        if (MITS.changeble_elements[MITS.selected_form_id]['use-phone']) {
            $('#mits-use-phone-checkbox').prop('checked', true);
            $('#phone-on').addClass('btn-success active');
            $('#phone-off').removeClass('btn-danger active');
        } else {
            $('#mits-use-phone-checkbox').prop('checked', false);
            $('#phone-on').removeClass('btn-success active');
            $('#phone-off').addClass('btn-danger active');
        }
        MITS.showHideFirstNameOption();
        if (MITS.checkIfCanShowFirstName()) {
            if (MITS.changeble_elements[MITS.selected_form_id]['use-name']) {
                $('#mits-use-first-name-checkbox').prop('checked', true);
            } else {
                $('#mits-use-first-name-checkbox').prop('checked', false);
            }
            MITS.firstNameCheckboxChanged();
        }
        MITS.showHidePhoneOption();
        if (MITS.checkIfCanShowPhone()) {
            if (MITS.changeble_elements[MITS.selected_form_id]['use-phone']) {
                $('#mits-use-phone-checkbox').prop('checked', true);
            } else {
                $('#mits-use-phone-checkbox').prop('checked', false);
            }
            MITS.phoneCheckboxChanged();
        }
    },
    editSubmitButton: function(element_id) {
//begin
        var find_id = "#lb-button-" + element_id;
        $pactive = $(find_id).parent("li").hasClass("active");
        if ($pactive) {
            MITS.closeEditor();
            $(find_id).parent("li").removeClass("active");
            return;
        }
        //end cooldevvn  
        var $text_input = $('#id-mits-btn-text');
        var elements = MITS.changeble_elements[element_id]['elements'];
        $('#mits-modal-header').html(MITS.changeble_elements[element_id]['name']);
        MITS.beforeOpenEditor(element_id);
        $('#mits-edit-btn-text form').validate().resetForm();
        $text_input.val(MITS.changeble_elements[element_id]['text']);
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
        MITS.closeEditorListener = function(cancel_button) {
            if (cancel_button !== true) {
                MITS.submitFormListener();
                return;
            }
            for (var i in elements) {
                $(elements[i]).val(MITS.changeble_elements[element_id]['text']);
            }
        };
        MITS.submitFormListener = function() {
            var val = $text_input.val();
            for (var i in elements) {
                $(elements[i]).val(val);
            }
            MITS.changeble_elements[element_id]['text'] = val;
            $('#lb-button-' + element_id).addClass('success');
            MITS.changeble_elements[element_id]['default_text'] = null;
            MITS.removableCheckOnsubmit(element_id);
        };
        MITS.openEditor($text_input, element_id);
    },
    editFadeInwBox: function(element_id) {
//begin
        var find_id = "#lb-button-" + element_id;
        $pactive = $(find_id).parent("li").hasClass("active");
        if ($pactive) {
            MITS.closeEditor();
            $(find_id).parent("li").removeClass("active");
            return;
        }
//end cooldevvn  

        $('#mits-modal-header').html(MITS.changeble_elements[element_id]['name']);
        MITS.beforeOpenEditor(element_id);
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#mits-edit-optin-box form').submit();
        });
        $('#mits-edit-optin-box form').validate().resetForm();
        if (!MITS.changeble_elements[element_id]['time']) {
            $('#optin-box-sec').val(0);
            $('#optin-box-min').val(0);
        } else {
            var min = Math.floor(parseInt(MITS.changeble_elements[element_id]['time'], 10) / 60);
            var sec = parseInt(MITS.changeble_elements[element_id]['time'], 10) - min * 60;
            $('#optin-box-sec').val(sec);
            $('#optin-box-min').val(min);
        }
        MITS.submitFormListener = function(cancel_button) {
            MITS.changeble_elements[element_id]['time'] = parseInt($('#optin-box-min').val(), 10) * 60 + parseInt($('#optin-box-sec').val(), 10);
            MITS.removableCheckOnsubmit(element_id);
        };
        MITS.openEditor(null, element_id);
    },
    
    onoffbox: function(element_id) {
//begin
        var find_id = "#lb-button-" + element_id;
        $pactive = $(find_id).parent("li").hasClass("active");
        if ($pactive) {
            MITS.closeEditor();
            $(find_id).parent("li").removeClass("active");
            return;
        }
//end cooldevvn  
        $('#mits-edit-optin-box form').hide();
        
        $('#mits-modal-header').html(MITS.changeble_elements[element_id]['name']);
        MITS.beforeOpenEditor(element_id);
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#mits-edit-optin-box form').submit();
        });
        $('#mits-edit-optin-box form').validate().resetForm();
        if (!MITS.changeble_elements[element_id]['time']) {
            $('#optin-box-sec').val(0);
            $('#optin-box-min').val(0);
        } else {
            var min = Math.floor(parseInt(MITS.changeble_elements[element_id]['time'], 10) / 60);
            var sec = parseInt(MITS.changeble_elements[element_id]['time'], 10) - min * 60;
        
            $('#optin-box-sec').val(sec);
            $('#optin-box-min').val(min);
        }
        MITS.submitFormListener = function(cancel_button) {
           
           var a =  ($('#id-mits-to-remove').val() != 'yes') ? true : false;
           if (a == true){
                MITS.changeble_elements[element_id]['time'] = 1000000;
           } else {
                MITS.changeble_elements[element_id]['time'] = 0;
           }
            MITS.removableCheckOnsubmit(element_id);
           
        };
        MITS.openEditor(null, element_id);
        
    },
    editButton: function(elem, element_id) {
        var use_live_code = false;
        if (MITS.nowEditingElement == element_id) {
            use_live_code = true;
        }
        MITS.nowEditingElement = element_id;
        $('#mits-modal-header').html(MITS.changeble_elements[element_id]['name']);

        var elements = MITS.changeble_elements[element_id]['elements'];
        MITS.beforeOpenEditor(element_id);

        $('#mits-button-colors').show();
        $('#mits-button-colors .control-group').hide();
        $('#mits-button-colors [button_id="' + element_id + '"]').show();

        $('#mits-button-styles').show();
        $('#mits-button-styles .control-group').hide();
        $('#mits-button-styles [button_id="' + element_id + '"]').show();


        $('#mits-form-submit').unbind('click');
        $('#mits-edit-rich-text form').validate().resetForm();
        $('#mits-edit-rich-text').show();
        var old_html = MITS.changeble_elements[element_id]['text'];
        var final_html = '<p>' + (use_live_code ? $(elem).html() : old_html) + '</p>';
        $('#mits-rich-text-basic').setCode(final_html);
        $('#mits-form-submit').click(function() {
            $('#mits-edit-rich-text form').submit();
        });
        var cleanAndSetCode = function(html_text) {
            html_text = html_text.replace(/<p><br><\/p>/g, '<br>');
            html_text = html_text.replace(/<\/?([a-z]+)[^>]*>/gi, function(match, tag) {
                return (tag === "a" || tag === "b" || tag === "i" || tag === "p" || tag === "br" || tag === "strike" || tag === "span" || tag === 'u' || tag === 'li' || tag === 'ul') ? match : "";
            });
            html_text = html_text.replace(/^<p>/, '');
            html_text = html_text.replace(/<\/?p[^>]*>/gi, function(match) {
                return (match == "<p>") ? "<br>" : "";
            });
            for (var i in elements) {
                $(elements[i]).html(html_text);
                $(elements[i]).find('a').unbind('click');
                $(elements[i]).find('a').click(MITS.return_false);
            }
            return html_text;
        };
        MITS.closeEditorListener = function(cancel_button) {
            if (cancel_button !== true) {
                MITS.submitFormListener();
                return;
            }
            cleanAndSetCode(old_html);
            MITS.changeble_elements[element_id]['text'] = old_html;
            for (var i in elements) {
                $(elements[i]).html(old_html);
                $(elements[i]).find('a').unbind('click');
                $(elements[i]).find('a').click(MITS.return_false);
            }
            MITS.nowEditingElement = false;

            $("input.color-picker.button-color").each(function() {
                var $this = $(this);
                var key = $this.attr('data-lb-base-color');

                $this.val(MITS.changeable_button_color[key]['value'].toLowerCase());
                $this.minicolors('value', MITS.changeable_button_color[key]['value'].toLowerCase());
            });
            MITS.doStyleChange();
        };
        MITS.cleanAndSetBasicRichText = function() {
            cleanAndSetCode($('#mits-rich-text-basic').getCode());
        };
        MITS.submitFormListener = function() {
            MITS.cleanAndSetBasicRichText = function() {
            };
            MITS.closeEditorListener = function() {
            };
            var html_text = cleanAndSetCode($('#mits-rich-text-basic').getCode());
            MITS.removableCheckOnsubmit(element_id);
            MITS.changeble_elements[element_id]['default_text'] = null;
            MITS.changeble_elements[element_id]['text'] = html_text;
            $('#lb-button-' + element_id).addClass('success');

            $("input.color-picker.button-color").each(function() {
                var $this = $(this);
                var value = $this.val();
                var key = $this.attr('data-lb-base-color');
                if (MITS.changeable_button_color[key]['value'] != value) {
                    if (value === '') {
                        value = key;
                    }
                    MITS.changeable_button_color[key]['value'] = value;
                }
            });
            $('#style-settings').addClass('success');
            MITS.doStyleChange();
        };
        MITS.basicRichTextChangedResize();
        MITS.openEditor(null, element_id);
    },
    editText: function(elem, element_id) {
        var use_live_code = false;
        if (MITS.nowEditingElement == element_id) {
            use_live_code = true;
        }
        MITS.nowEditingElement = element_id;
        $('#mits-modal-header').html(MITS.changeble_elements[element_id]['name']);
        var elements = MITS.changeble_elements[element_id]['elements'];
        MITS.beforeOpenEditor(element_id);
        $('#mits-form-submit').unbind('click');
        if ($(elem).is('[data-lb~="editable-rich-text"]')) {
            $('#mits-edit-rich-text form').validate().resetForm();
            $('#mits-edit-rich-text').show();
            var old_html = MITS.changeble_elements[element_id]['text'];
            var final_html = '<p>' + (use_live_code ? $(elem).html() : old_html) + '</p>';
            $('#mits-rich-text-basic').setCode(final_html);
            $('#mits-form-submit').click(function() {
                $('#mits-edit-rich-text form').submit();
            });
            var cleanAndSetCode = function(html_text) {
                html_text = html_text.replace(/<p><br><\/p>/g, '<br>');
                html_text = html_text.replace(/<\/?([a-z]+)[^>]*>/gi, function(match, tag) {
                    return (tag === "a" || tag === "b" || tag === "i" || tag === "p" || tag === "br" || tag === "strike" || tag === "span" || tag === 'u' || tag === 'li' || tag === 'ul') ? match : "";
                });
                html_text = html_text.replace(/^<p>/, '');
                html_text = html_text.replace(/<\/?p[^>]*>/gi, function(match) {
                    return (match == "<p>") ? "<br>" : "";
                });
                for (var i in elements) {
                    $(elements[i]).html(html_text);
                    $(elements[i]).find('a').unbind('click');
                    $(elements[i]).find('a').click(MITS.return_false);
                }
                return html_text;
            };
            MITS.closeEditorListener = function(cancel_button) {
                if (cancel_button !== true) {
                    MITS.submitFormListener();
                    return;
                }
                cleanAndSetCode(old_html);
                MITS.changeble_elements[element_id]['text'] = old_html;
                for (var i in elements) {
                    $(elements[i]).html(old_html);
                    $(elements[i]).find('a').unbind('click');
                    $(elements[i]).find('a').click(MITS.return_false);
                }
                MITS.nowEditingElement = false;
            };
            MITS.cleanAndSetBasicRichText = function() {
                cleanAndSetCode($('#mits-rich-text-basic').getCode());
            };
            MITS.submitFormListener = function() {
                MITS.cleanAndSetBasicRichText = function() {
                };
                MITS.closeEditorListener = function() {
                };
                var html_text = cleanAndSetCode($('#mits-rich-text-basic').getCode());
                MITS.removableCheckOnsubmit(element_id);
                MITS.changeble_elements[element_id]['default_text'] = null;
                MITS.changeble_elements[element_id]['text'] = html_text;
                $('#lb-button-' + element_id).addClass('success');
            };
            MITS.basicRichTextChangedResize();
            MITS.openEditor(null, element_id);
        } else {
            var $text_input = $('#id-mits-text');
            $('#mits-edit-text form').validate().resetForm();
            $('#mits-edit-text').show();
            $text_input.val(MITS.changeble_elements[element_id]['text']);
            $('#mits-form-submit').click(function() {
                $('#mits-edit-text form').submit();
            });
            $text_input.unbind('change');
            $text_input.unbind('keyup');
            var onchangeFn = function() {
                for (var i in elements) {
                    $(elements[i]).text($text_input.val());
                }
            };
            $text_input.change(onchangeFn);
            $text_input.keyup(onchangeFn);
            MITS.submitFormListener = function() {
                var val = $text_input.val();
                for (var i in elements) {
                    $(elements[i]).text(val);
                }
                MITS.changeble_elements[element_id]['text'] = val;
                MITS.changeble_elements[element_id]['default_text'] = null;
                MITS.removableCheckOnsubmit(element_id);
                $('#lb-button-' + element_id).addClass('success');
            };
            MITS.closeEditorListener = function(cancel_button) {
                if (cancel_button !== true) {
                    MITS.submitFormListener();
                    return;
                }
                for (var i in elements) {
                    $(elements[i]).text(MITS.changeble_elements[element_id]['text']);
                }
            };
            MITS.openEditor($text_input, element_id);
        }
    },
    editRichTextArea: function(elem, element_id) {
//begin
        var find_id = "#lb-button-" + element_id;
        $pactive = $(find_id).parent("li").hasClass("active");
        if ($pactive) {
            MITS.closeEditor();
            $(find_id).parent("li").removeClass("active");
            return;
        }
//end cooldevvn  

        var elements = MITS.changeble_elements[element_id]['elements'];
        $('#mits-modal-header').html(MITS.changeble_elements[element_id]['name']);
        MITS.beforeOpenEditor(element_id);
        var old_html = MITS.changeble_elements[element_id]['text'];
        $('#mits-richtext-area').setCode(old_html);
        $('#mits-edit-rich-text form').validate().resetForm();
        $('#mits-form-submit').click(function() {
            $('#mits-edit-richtext-area form').submit();
        });
        var cleanAndSetCode = function(html_text) {
            for (var i in elements) {
                $(elements[i]).html(html_text);
                $(elements[i]).find('a').unbind('click');
                $(elements[i]).find('a').click(MITS.return_false);
            }
            return html_text;
        };
        MITS.cleanAndSetRichTextArea = function() {
            cleanAndSetCode($('#mits-richtext-area').getCode());
        };
        MITS.closeEditorListener = function(cancel_button) {
            if (cancel_button !== true) {
                MITS.submitFormListener();
                return;
            }
            MITS.changeble_elements[element_id]['text'] = cleanAndSetCode(old_html);
            for (var i in elements) {
                $(elements[i]).html(old_html);
                $(elements[i]).find('a').unbind('click');
                $(elements[i]).find('a').click(MITS.return_false);
            }
        };
        MITS.submitFormListener = function() {
            MITS.closeEditorListener = function() {
            };
            MITS.cleanAndSetRichTextArea = function() {
            };
            var html_text = cleanAndSetCode($('#mits-richtext-area').getCode());
            MITS.removableCheckOnsubmit(element_id);
            MITS.changeble_elements[element_id]['default_text'] = null;
            MITS.changeble_elements[element_id]['text'] = html_text;
            $('#lb-button-' + element_id).addClass('success');
        };
        MITS.basicRichTextChangedResize();
        MITS.openEditor(null, element_id);
    },
    selectOptInForm: function(elem, element_id) {
//begin
        var find_id = "#lb-button-" + element_id;
        $pactive = $(find_id).parent("li").hasClass("active");
        if ($pactive) {
            MITS.closeEditor();
            $(find_id).parent("li").removeClass("active");
            return;
        }
//end cooldevvn  

        var elements = MITS.changeble_elements[element_id]['elements'];
        $('#mits-modal-header').html(MITS.changeble_elements[element_id]['name']);
        MITS.beforeOpenEditor(element_id);
        var old_html = MITS.changeble_elements[element_id]['text'];
        $('#mits-richtext-area').setCode(old_html);
        $('#mits-edit-rich-text form').validate().resetForm();
        $('#mits-form-submit').click(function() {
            $('#mits-edit-richtext-area form').submit();
        });
        var cleanAndSetCode = function(html_text) {
            for (var i in elements) {
                $(elements[i]).html(html_text);
                $(elements[i]).find('a').unbind('click');
                $(elements[i]).find('a').click(MITS.return_false);
            }
            return html_text;
        };
        MITS.cleanAndSetRichTextArea = function() {
            cleanAndSetCode($('#mits-richtext-area').getCode());
        };
        MITS.closeEditorListener = function() {
            MITS.changeble_elements[element_id]['text'] = cleanAndSetCode(old_html);
            for (var i in elements) {
                $(elements[i]).html(old_html);
                $(elements[i]).find('a').unbind('click');
                $(elements[i]).find('a').click(MITS.return_false);
            }
        };
        MITS.submitFormListener = function() {
            MITS.closeEditorListener = function() {
            };
            MITS.cleanAndSetRichTextArea = function() {
            };
            var html_text = cleanAndSetCode($('#mits-richtext-area').getCode());
            MITS.removableCheckOnsubmit(element_id);
            MITS.changeble_elements[element_id]['default_text'] = null;
            MITS.changeble_elements[element_id]['text'] = html_text;
            $('#lb-button-' + element_id).addClass('success');
        };
        MITS.basicRichTextChangedResize();
        MITS.openEditor(null, element_id);
    },
    editPlaceHolder: function(element_id) {
        //begin
        var find_id = "#lb-button-" + element_id;
        $pactive = $(find_id).parent("li").hasClass("active");
        if ($pactive) {
            MITS.closeEditor();
            $(find_id).parent("li").removeClass("active");
            return;
        }
        //end cooldevvn  

        var $text_input = $('#id-mits-placeholder');
        var elements = MITS.changeble_elements[element_id]['elements'];
        $('#mits-modal-header').html(MITS.changeble_elements[element_id]['name']);
        MITS.beforeOpenEditor(element_id);
        $('#mits-edit-placeholder form').validate().resetForm();
        $text_input.val(MITS.changeble_elements[element_id]['title']);
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
        MITS.closeEditorListener = function(cancel_button) {
            if (cancel_button !== true) {
                MITS.submitFormListener();
                return;
            }
            var val = MITS.changeble_elements[element_id]['title'];
            for (var i in elements) {
                $(elements[i]).attr('title', $.trim(val));
                $(elements[i]).val($.trim(val));
            }
        };
        MITS.submitFormListener = function() {
            var val = $text_input.val();
            for (var i in elements) {
                $(elements[i]).attr('title', $.trim(val));
                $(elements[i]).val($.trim(val));
            }
            MITS.changeble_elements[element_id]['title'] = $.trim(val);
            MITS.changeble_elements[element_id]['default_text'] = null;
            $('#lb-button-' + element_id).addClass('success');
            MITS.removableCheckOnsubmit(element_id);
        };
        MITS.openEditor($text_input, element_id);
    },
    editEmbedArea: function(element_id) {
//begin
        var find_id = "#lb-button-" + element_id;
        $pactive = $(find_id).parent("li").hasClass("active");
        if ($pactive) {
            MITS.closeEditor();
            $(find_id).parent("li").removeClass("active");
            return;
        }
        //end cooldevvn  

        $('#mits-modal-header').html(MITS.changeble_elements[element_id]['name']);
        $('#lb-area-width').text(MITS.changeble_elements[element_id]['width']);
        $('#lb-area-height').text(MITS.changeble_elements[element_id]['height']);
        var elements = MITS.changeble_elements[element_id]['elements'];
        MITS.beforeOpenEditor(element_id);
        $('#mits-edit-area form').validate().resetForm();
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#mits-edit-area form').submit();
        });
        $('#id-mits-area').val(unescape(MITS.changeble_elements[element_id]['value']));
        MITS.openEditor(null, element_id);
        MITS.submitFormListener = function() {
            var val = $('#id-mits-area').val();
            MITS.changeble_elements[element_id]['value'] = escape(val);
            for (var i in elements) {
                MITS.insertEmbed(elements[i], element_id, val, null);
            }
            MITS.removableCheckOnsubmit(element_id);
            $('#lb-button-' + element_id).addClass('success');
        };
    },
    editVideo: function(element_id, element) {
        $('#video-player-group').attr('element_id', element_id);
        $('#mits-modal-header').html(MITS.changeble_elements[element_id]['name']);
        $('#lb-video-width').text(MITS.changeble_elements[element_id]['width']);
        $('#lb-video-height').text(MITS.changeble_elements[element_id]['height']);
        MITS.beforeOpenEditor(element_id);
        var elements = MITS.changeble_elements[element_id]['elements'];
        $('#mits-edit-video form').validate().resetForm();
        $('#mits-edit-video').show();
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            $('#mits-edit-video form').submit();
        });

        $('#id-mits-video').val(unescape(MITS.changeble_elements[element_id]['value']));
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
        var element_data = MITS.changeble_elements[element_id];

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
                var elements_child = MITS.changeble_elements[element_child_id]['elements'];
                var new_window = (MITS.changeble_elements[element_child_id]['new_window'] && MITS.changeble_elements[element_child_id]['new_window'] === true) ? true : false;
                var nofollow = (MITS.changeble_elements[element_child_id]['nofollow'] && MITS.changeble_elements[element_child_id]['nofollow'] === true) ? true : false;

                $('#mits-change-link form').validate().resetForm();
                $('#id-mits-link-href').val(MITS.changeble_elements[element_child_id]['href']);
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
            old_image_id = MITS.changeble_elements[element_data['connected-image']['id']]['id'];
            old_image_url = MITS.changeble_elements[element_data['connected-image']['id']]['url'];
            $('#lb-my-images').prepend($('<li id="default-image">').html('<a href="#" class="image-choose-btn" onclick="MITS.selectImage(this, \'' + MITS.changeble_elements[element_data['connected-image']['id']]['default_url'] + '\', null);return false;"><img src="' + MITS.changeble_elements[element_data['connected-image']['id']]['default_url'] + '" alt="Image"></a>'));
            MITS.selectImage = function(btn, url, id) {
                for (var i in MITS.changeble_elements[element_data['connected-image']['id']]['elements']) {
                    MITS.setImage(MITS.changeble_elements[element_data['connected-image']['id']]['elements'][i], element_data['connected-image']['id'], url, id);
                }
                $('.image-choose-btn.active').removeClass('active');
                $(btn).addClass('active');
                image_url = url;
                image_id = id;
            };
        }
        MITS.openEditor(null, element_id);
        MITS.submitFormListener = function() {
            if ($('#id-video-image').val() == 'yes') {
                var val = $('#id-mits-video').val();
                MITS.changeble_elements[element_id]['value'] = escape(val);
                for (var i in elements) {
                    MITS.insertEmbedVideo(elements[i], element_id, MITS.changeble_elements[element_id], val, MITS.changeble_elements[element_id]['default_text']);
                }
                element_data['video-or-image'] = true;
            }
            else {
                if (element_data['connected-link']) {
                    for (var i in elements_child) {
                        $(elements_child[i]).attr('href', $('#id-mits-link-href').val());
                    }
                    MITS.changeble_elements[element_child_id]['href'] = $('#id-mits-link-href').val();
                    MITS.changeble_elements[element_child_id]['default_text'] = null;
                    MITS.changeble_elements[element_child_id]['new_window'] = ($('#id-mits-link-target').val() == 'yes') ? true : false;
                    MITS.changeble_elements[element_child_id]['nofollow'] = ($('#id-mits-link-nofollow').val() == 'yes') ? true : false;
                }
                if (element_data['connected-image'] && image_url && image_id) {
                    MITS.setImage(element_data['connected-image']['elem'], element_data['connected-image']['id'], image_url, image_id);
                }
                element_data['video-or-image'] = false;
            }
            MITS.removableCheckOnsubmit(element_id);
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
            var video_background = MITS.player_logo_url['empty_your'];
            if (value.indexOf('.mitsplayer(') > 0) {
                video_background = MITS.player_logo_url['mits'];
            } else if (value.indexOf('www.youtube') > 0) {
                video_background = MITS.player_logo_url['yt'];
            } else if (value.indexOf('vimeo.com') > 0) {
                video_background = MITS.player_logo_url['vimeo'];
            } else if (value.indexOf('wistia.') > 0) {
                video_background = MITS.player_logo_url['wistia'];
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
        //        $('#mits-image-iframe').attr('src', baseUrl + '/iframe/upload-image/');
        $('#lb-my-images').prepend($('<li id="default-image">').html('<a href="#" class="image-choose-btn" onclick="MITS.selectImage(this, \'' + MITS.changeble_elements[element_id]['default_url'] + '\', null);return false;"><img src="' + MITS.changeble_elements[element_id]['default_url'] + '" alt="Image"></a>'));
        $('#mits-modal-header').html(MITS.changeble_elements[element_id]['name']);
        MITS.beforeOpenEditor(element_id);
        var elements = MITS.changeble_elements[element_id]['elements'];
        $('#mits-form-submit').unbind('click');
        $('#mits-form-submit').click(function() {
            MITS.closeEditor();
            MITS.removableCheckOnsubmit(element_id);
            MITS.change_made = true;
            $('#lb-button-' + element_id).addClass('success');
        });
        MITS.openEditor(null, element_id);
        MITS.selectImage = function(btn, url, id) {
            for (var i in elements) {
                MITS.setImage(elements[i], element_id, url, id);
            }
        };
    },
    selectDefaultImage: function() {
        if (MITS.selected_element_id && MITS.changeble_elements[MITS.selected_element_id] && MITS.changeble_elements[MITS.selected_element_id]['type'] == 'image') {
            var elements = MITS.changeble_elements[MITS.selected_element_id]['elements'];
            for (var i in elements) {
                $(elements[i]).attr('src', MITS.changeble_elements[MITS.selected_element_id]['default_url']);
            }
            MITS.changeble_elements[MITS.selected_element_id]['url'] = MITS.changeble_elements[MITS.selected_element_id]['default_url'];
            MITS.removableCheckOnsubmit(MITS.selected_element_id);
            MITS.markChange();
            MITS.closeEditor();
        }
    },
    setImage: function(elem, element_id, url, id) {

        var video_parent = MITS.changeble_elements[element_id]['video_parent'];
        var set_basic_size = function() {
            MITS.changeble_elements[element_id]['url'] = url;
            MITS.changeble_elements[element_id]['id'] = id;
            if (video_parent != false && video_parent.find('div').length > 0) {
                video_parent.html($(elem));
            }
            $(elem).attr('src', url);
        };
        if (!id && url == MITS.changeble_elements[element_id]['default_url']) {
            set_basic_size();
            return;
        }
        if ($(elem).is('[data-lb*="max-image-size="]') || ($(elem).is('[data-lb*="max-image-width="]') && $(elem).is('[data-lb*="max-image-height="]'))) {
            var data_lb = $(elem).attr('data-lb').split(' ');
            var size = null;
            var height = 0;
            for (var k in data_lb) {
                if (data_lb[k].substr(0, 15) == 'max-image-size=' && data_lb[k].split('=')[1]) {
                    size = data_lb[k].split('=')[1];
                }
                if (data_lb[k].substr(0, 16) == 'max-image-width=' && data_lb[k].split('=')[1]) {
                    size = data_lb[k].split('=')[1];
                }
                if (data_lb[k].substr(0, 17) == 'max-image-height=' && data_lb[k].split('=')[1]) {
                    height = data_lb[k].split('=')[1];
                }

            }
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
                        MITS.changeble_elements[element_id]['url'] = data.body;
                        MITS.changeble_elements[element_id]['id'] = id;
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
        //begin
        var find_id = "#lb-button-" + element_id;
        $pactive = $(find_id).parent("li").hasClass("active");
        if ($pactive) {
            MITS.closeEditor();
            $(find_id).parent("li").removeClass("active");
            return;
        }
//end cooldevvn   

        var elements = MITS.changeble_elements[element_id]['elements'];
        $('#mits-modal-header').html(MITS.changeble_elements[element_id]['name']);
        MITS.beforeOpenEditor(element_id);
        MITS.openEditor(null, element_id);
        $('#mits-form-submit').click(function() {
            MITS.submitFormListener();
            MITS.closeEditor();
            return false;
        });
        MITS.submitFormListener = function() {
            MITS.removableCheckOnsubmit(element_id);
            $('#lb-button-' + element_id).addClass('success');
        };
    },
    editLink: function(elem, element_id) {

        //begin
        var find_id = "#lb-button-" + element_id;
        $pactive = $(find_id).parent("li").hasClass("active");
        if ($pactive) {
            MITS.closeEditor();
            $(find_id).parent("li").removeClass("active");
            return;
        }
//end cooldevvn   

        var link_only = ($(elem).is('[data-lb~="link-only"]')) ? true : false;
        var element_data = MITS.changeble_elements[element_id];
        var new_window = (MITS.changeble_elements[element_id]['new_window'] && MITS.changeble_elements[element_id]['new_window'] === true) ? true : false;
        var nofollow = (MITS.changeble_elements[element_id]['nofollow'] && MITS.changeble_elements[element_id]['nofollow'] === true) ? true : false;
        var image_url = null;
        var image_id = null;
        var old_image_url = null;
        var old_image_id = null;
        var elements = MITS.changeble_elements[element_id]['elements'];
        $('#mits-modal-header').html(MITS.changeble_elements[element_id]['name']);
        MITS.beforeOpenEditor(element_id);
        $('#mits-change-link form').validate().resetForm();
        $('#id-mits-link-text').val(MITS.changeble_elements[element_id]['text']);
        $('#id-mits-link-href').val(MITS.changeble_elements[element_id]['href']);
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
            old_image_id = MITS.changeble_elements[element_data['connected-image']['id']]['id'];
            old_image_url = MITS.changeble_elements[element_data['connected-image']['id']]['url'];
            $('#lb-my-images').prepend($('<li id="default-image">').html('<a href="#" class="image-choose-btn" onclick="MITS.selectImage(this, \'' + MITS.changeble_elements[element_data['connected-image']['id']]['default_url'] + '\', null);return false;"><img src="' + MITS.changeble_elements[element_data['connected-image']['id']]['default_url'] + '" alt="Image"></a>'));
            MITS.selectImage = function(btn, url, id) {
                for (var i in MITS.changeble_elements[element_data['connected-image']['id']]['elements']) {
                    MITS.setImage(MITS.changeble_elements[element_data['connected-image']['id']]['elements'][i], element_data['connected-image']['id'], url, id);
                }
                $('.image-choose-btn.active').removeClass('active');
                $(btn).addClass('active');
                image_url = url;
                image_id = id;
            };
        }
        if (MITS.target_url) {
            $('#id-mits-link-sync').show().click(function(e) {
                e.preventDefault();
                if (confirm("This will replace the URL above with this page's tracking URL. Do you wish to proceed?")) {
                    $('#id-mits-link-href').val(MITS.target_url);
                }
            });
        }
        MITS.openEditor(null, element_id);
        MITS.submitFormListener = function() {
            var href_val = $('#id-mits-link-href').val();
            var text_val = $('#id-mits-link-text').val();
            if (!link_only) {
                MITS.changeble_elements[element_id]['text'] = text_val;
            }
            if (element_data['connected-image'] && image_url && image_id) {
                MITS.setImage(element_data['connected-image']['elem'], element_data['connected-image']['id'], image_url, image_id);
            }
            for (var i in elements) {
                if (!link_only) {
                    $(elements[i]).text($('#id-mits-link-text').val());
                }
                $(elements[i]).attr('href', $('#id-mits-link-href').val());
            }
            MITS.changeble_elements[element_id]['href'] = href_val;
            MITS.changeble_elements[element_id]['default_text'] = null;
            MITS.changeble_elements[element_id]['new_window'] = ($('#id-mits-link-target').val() == 'yes') ? true : false;
            MITS.changeble_elements[element_id]['nofollow'] = ($('#id-mits-link-nofollow').val() == 'yes') ? true : false;
            $('#lb-button-' + element_id).addClass('success');
            MITS.removableCheckOnsubmit(element_id);
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
        MITS.closeEditorListener = function() {
            if (!link_only) {
                var val = MITS.changeble_elements[element_id]['text'];
                for (var i in elements) {
                    $(elements[i]).text(MITS.changeble_elements[element_id]['text']);
                }
            }
            if (element_data['connected-image']) {
                for (var i in MITS.changeble_elements[element_data['connected-image']['id']]['elements']) {
                    MITS.setImage(MITS.changeble_elements[element_data['connected-image']['id']]['elements'][i], element_data['connected-image']['id'], old_image_url, old_image_id);
                }
            }
        };
    },
    backgroundType: function(type) {
        $("#background_type").val(type);

        var background_group = $('#background_type_group');
        background_group.find('*').removeClass('btn-success active');
        $("#background_type_" + type).addClass('btn-success active');

        var element_id = background_group.attr('element_id');
        var background_color = background_group.attr('background_color');
        var image_element = MITS.iframe.contents().find('#' + element_id);

        var color_element = $('.color-picker[data-lb-base-color="' + background_color + '"]').closest('.control-group');
        if (type == 'color') {
            image_element.hide();
            color_element.show();
            $('#' + MITS.background_button).hide();
        } else {
            image_element.fadeIn();
            color_element.hide();
            $('#' + MITS.background_button).show();
        }
        MITS.page_background_type['type'] = type;
    },
    videoPlayerStyle: function(type) {
        $("#video-player-style").val(type);
        $('#video-player-group *').removeClass('btn-success active');
        $('#video-player-style-' + type).addClass('btn-success active');
        var element_id = $('#video-player-group').attr('element_id');
        var style = MITS.video_style['style_' + type];
        var video_element = MITS.iframe.contents().find('[data-lb-id="' + element_id + '"]');
        video_element.attr('style', style);
        video_element.find('*').hide();

        MITS.changeble_elements[element_id]['video_style'] = type;
    },
    videoOrImage: function(is_video) {
        var have_link = MITS.changeble_elements[MITS.selected_element_id]['connected-link'];
        var video_element = MITS.iframe.contents().find('[data-lb-id="' + MITS.selected_element_id + '"]');
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
            video_element.attr('style', MITS.video_style['style_1']);
            video_element.find('*').hide();
        } else {
            $('#video-or-image-btn-yes').removeClass('btn-success');
            $('#video-or-image-btn-no').addClass('btn-success');
            $('#id-video-image').val('no');
            $('#mits-edit-video').hide();
            $('#mits-change-img').show();
            if (have_link)
                $('#mits-change-link').show();
            $('#mits-comment-text').show();
            video_element.attr('style', '');
            video_element.find('*').show();
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
        if (MITS.selected_element_id && MITS.changeble_elements[MITS.selected_element_id] && MITS.changeble_elements[MITS.selected_element_id]['removable']) {
            MITS.showHideRemovable(MITS.selected_element_id, visible);
        }
        return false;
    },
    removableCheckOnsubmit: function(element_id) {
        if (element_id && MITS.changeble_elements[element_id] && MITS.changeble_elements[element_id]['removable']) {
            MITS.changeble_elements[element_id]['removed'] = ($('#id-mits-to-remove').val() != 'yes') ? true : false;
            MITS.removableCheck(element_id);
        }
    },
    removableCheck: function(element_id) {
        if (element_id && MITS.changeble_elements[element_id] && MITS.changeble_elements[element_id]['removable']) {
            MITS.showHideRemovable(element_id, !MITS.changeble_elements[element_id]['removed']);
        }
    },
    showHideRemovable: function(element_id, visible) {
        for (var i in MITS.changeble_elements[element_id]['elements']) {
            var editable_area = null;
            if (!visible) {
                $(MITS.changeble_elements[element_id]['elements'][i]).hide();
                $(editable_area).hide();
                $('#mits-editor-tools-space').hide();
            } else {
                $(MITS.changeble_elements[element_id]['elements'][i]).show();
                $(editable_area).show();
                $('#mits-editor-tools-space').show();
            }
        }
    },
    beforeOpenEditor: function(element_id) {
        $('#mits-editor .mits-form-space').hide();
        MITS.submitFormListener = function() {
        };
        MITS.closeEditorListener = function() {
        };
        if (element_id && MITS.changeble_elements[element_id] && MITS.changeble_elements[element_id]['comment']) {
            $('#mits-comment-text p').text(MITS.changeble_elements[element_id]['comment']);
            $('#mits-comment-text').show();
        }
        if (element_id && MITS.changeble_elements[element_id] && MITS.changeble_elements[element_id]['removable']) {
            if (MITS.changeble_elements[element_id]['removed']) {
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
    }, openEditor: function($elem_to_focus, element_id) {
        if ($('#templete-settings').attr('data-hidden') === 'true') {
            MITS.showEditor();
        }
        if (MITS.changeble_elements[MITS.selected_element_id]) {
            var elem = MITS.changeble_elements[MITS.selected_element_id];
            if (MITS.editor_blocks_by_type[elem['type']]) {
                $('#' + MITS.editor_blocks_by_type[elem['type']]).show();
            }
            if (!elem['removable'] || !elem['removed']) {
                $('#mits-editor-tools-space').show();
            }
        }

//        $('#mits-settings-menu').hide();

        $('.editor-overlay').fadeIn(400);
        $("#lb-submit-btns").show();
        $('#mits-editor').fadeIn(400, function() {
            if ($elem_to_focus) {
                $elem_to_focus.focus();
            }
        });
        //        $('.accordion_btn').next('div').slideUp('slow');
//        $('.accordion_btn').parent('li').removeClass('active', 'slow');
        //        $('.content_accordion_btn').not('#lb-button-' + element_id).next('div').hide();
        //        $('.content_accordion_btn').parent('li').removeClass('active', 'slow');
        //        $('#lb-button-' + element_id).parent('li').addClass('active', 'slow');
        //        $('#lb-button-' + element_id).parent('li').append($('#mits-editor').slideDown('slow', function() {
        //            $(this).append($('#lb-submit-btns'));
//        }));
    },
    closeEditor: function() {
        MITS.removableCheck(MITS.selected_element_id);
        //        $('#mits-editor').hide();
        $('#mits-editor-tools-space').fadeOut(400);
        $('.editor-overlay').fadeOut(400);
        //'slide', {direction: 'right'}, 400, function(){$('#mits-editor-tools-space').hide();});

        //        $('#mits-settings-menu').fadeIn();//.show('slide', {direction: 'left'}, 400);         //$('#mits-settings-menu').show();
        //$('#mits-editor-tools-space').hide();
        MITS.closeEditorListener(true); // true = cancel button pressed
        MITS.selected_element_id = null;
        MITS.closeEditorListener = function() {
        };
    },
    editorToggle: function() {
        if ($('#mits-hide-editor-button').hasClass('disabled')) {
            return false;
        }
        if ($('#template-settings').attr('data-hidden') === 'true') {
            MITS.showEditor();
        } else {
            MITS.hideEditor();
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
            MITS.windowResize();
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
            MITS.windowResize();
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
        MITS.different_simple_name = true;
        $('#edit-url-btn').hide();
        $('#id_page_url').removeAttr('readonly');
        $('#change-name-url').val('y');
        var pageUrlChanged = function() {
            var pos = MITS.getCaretPosition($('#id_page_url').get(0));
            $('#id_page_url').val(MITS.cleanPageSimpleName($('#id_page_url').val()));
            MITS.setCaretPosition($('#id_page_url').get(0), pos);
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
        MITS.change_made = true;
        MITS.notify("warning", "You have unsaved changes...");
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
            "display": "block", "float": "left",
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
            setTimeout(MITS.hideNotifications, timeout);
    },
    validationError: function(message) {
        MITS.notify("error", message);
        MITS.hasValidationError = true;
    },
    hideValidationError: function() {
        MITS.hideNotifications();
        MITS.hasValidationError = false;
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
        if (!$('#id_page_url').val()) {
            MITS.notify("error", "You must provide a URL for your page.");
            $("#id_page_url").val("page-url");
            $("#id_page_url-span").text("page-url").click();
            return;
        }
        if (!MITS.edit && !MITS.name_changed) {
            MITS.notify("warning", "You must choose a name for your page.");
            $("#id_page_name-span").click();
            return;
        }
        if (!(button.attr("disabled"))) {
            button.attr("disabled", "disabled");
            function finishSaving(message, type) {
                if (type == "notice") {
                    
					//alert(1);
					//return;
					
					
					MITS.saveIt(function(success) {
                        button.removeAttr("disabled");
                        button.addClass("btn-primary");
                        if (!success) {
                            MITS.notify("error", 'Page with this url already exists!');
                        } else {
                            if (callback)
                                callback(success);
                            MITS.notify("notice", "Success!", 1500);
                            if (success) {
                                var url = MITS.baseUrl + "/pages/" + MITS.template_id + "/" + MITS.page_id;
                                  $('#id_domain_url').attr('readonly','');
                                window.history.pushState('Object', 'Title', url);
                              
                            }
                        }
                    }, function() {
                        MITS.notify("notice", "Saving...");
                    });
                } else {
                    MITS.notify(type, message, 5000);
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
    savePopupForm: function() {
        var trigger = $("#mits-form-editor")[0].contentWindow.mainWindowRequestsSave;
        if (trigger)
            trigger();
    }
};