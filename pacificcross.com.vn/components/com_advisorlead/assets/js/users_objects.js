MP = {
    page_id: null,
    duplicating: false,
    timeout_check: 0,
    different_simple_name: true,
    old_page_name: false,
    old_page_simple_name: false,
    checkIfReady: function() {
        MAPI.ajaxcall({
            data: {
                'action': 'api_call',
                'request': 'my-page-ready',
                'my_page': PAGE_INFO['id']
            },
            type: 'GET',
            async: true,
            success: function(data) {
                if (data.status == '200') {
                    window.location.reload();
                } else {
                    MP.timeout_check = MP.timeout_check + 1000;
                    setTimeout(function() {
                        MP.checkIfReady();
                    }, MP.timeout_check);
                }
            }
        });
    },
    deletePage: function(elem) {
        $('#deleteModal').modal('show');
        $('#cont-del-url').unbind('click');
        $('#cont-del-url').click(function() {
            $('#cont-del-url').attr('disabled', 'disabled');
            $.get($(elem).attr('href'), {}, function() {
                setTimeout(function() {
                    $('#deleteModal').modal('hide');
                    $('#cont-del-url').removeAttr('disabled');
                    window.rePaginate();
                }, 1000);
            });
            return false;
        });
    },
    setPageUrlEditable: function() {
        MP.different_simple_name = true;
        $('#edit-url-btn').hide();
        $('#id_page_url').attr('disabled', null);
        $('#change-name-url').val('y');
        var pageUrlChanged = function() {
            $('#id_page_url').val(MP.cleanPageSimpleName($('#id_page_url').val()));
        };
        $('#id_page_url').blur(pageUrlChanged);
    },
    renamePage: function(id, old_name, old_simple_name) {
        $('#rename-page-form').validate({
            rules: {
                id_page_name: {
                    required: true,
                    remote: {
                        url: API_URL,
                        type: "POST",
                        data: {
                            action: 'api_call',
                            request: 'test-page-name',
                            page_id: id
                        }
                    }
                },
                id_page_url: {
                    required: true,
                    remote: {
                        url: API_URL,
                        type: "POST",
                        data: {
                            action: 'api_call',
                            request: 'test-page-url',
                            page_id: id
                        }
                    }
                }
            },
            messages: {
                id_page_name: {
                    remote: 'Page with this name already exists!'
                },
                id_page_url: {
                    remote: 'Page with this public URL already exists!'
                }
            },
            submitHandler: function(form) {
                $this = $(form);
                $('#id_page_url').val(MP.cleanPageSimpleName($('#id_page_url').val()));
                $('#page-rename-submit').button('loading');
                MAPI.ajaxcall({
                    data: {
                        'action': 'api_call',
                        'request': 'rename-page',
                        'page_id': id,
                        'change_also_url': $('#change-name-url').val(),
                        'page_name': $('#id_page_name').val(),
                        'page_url': $('#id_page_url').val()
                    },
                    type: 'POST',
                    async: true,
                    success: function(data) {
                        window.location = window.location;
                    },
                    error: function() {
                        $('#page-rename-submit').button('reset');
                    }
                });
                return false;
            }
        });
        MP.old_name = old_name;
        MP.old_simple_name = old_simple_name;
        MP.setPageUrlEditable();
        $('#rename-page-form').validate().resetForm();
        $('#rename-page-form input[type="text"]').removeClass('error');
        $('#id_page_name').val(old_name);
        $('#id_page_url').val(old_simple_name);
        $('#renameModal').modal('show');
    },
    duplicatePage: function(id, name) {
        $('#id_duplicate_page_name').val(name);
        $('#duplicate-page-id').val(id);
        $('#duplicate-page-form').submit(function() {
            $this = $(this);
            if ($('#duplicate-page-form').valid()) {
                if (!MP.duplicating) {
                    var pageName = $('#id_duplicate_page_name').val()
                    MP.duplicating = true;
                    $('#page-duplicate-submit').button('loading');
                    MAPI.ajaxcall({
                        data: {
                            'action': 'api_call',
                            'request': 'duplicate-page',
                            'page_id': id,
                            'page_name': pageName
                        },
                        type: 'POST',
                        async: true,
                        success: function(data) {

//                            analytics.track("Duplicate Page", {
//                                "OldPage": name,
//                                "NewPage": pageName
//                            });
                            setTimeout(function() {
                                window.location = data.body;
                            }, 500);
                        },
                        error: function() {
                            $('#page-duplicate-submit').button('reset');
                            MP.duplicating = false;
                        }
                    });
                }
            }
            return false;
        });
        $('#duplicateModal').modal('show');
    },
    editRedirect: function(id) {
        MAPI.ajaxcall({
            data: {
                'action': 'api_call',
                'request': 'my-page-redirect-data',
                'page_id': id
            },
            type: 'GET',
            async: true,
            success: function(data) {
                if (data.status = 200) {
                    data = data.body;
                    $("#redirect-public-url").attr("href", data.public_url);
                    $("#redirect-public-url").text(data.public_url);
                    $("#redirect-active").val(data.redirect_active ? "on" : "off");
                    $("#redirect-mobile").val(data.redirect_mobile ? "on" : "off");
                    $("#redirect-target").val(data.redirect_target);
                    $("#page-redirect-submit").click(function(e) {
                        e.preventDefault();
                        MP.redirectSave(data.save_url);
                    });
                    if (data.redirect_active) {
                        $("#redirect-on").addClass("btn-success");
                        $("#redirect-on").addClass("active");
                    } else {
                        $("#redirect-off").addClass("btn-danger");
                        $("#redirect-off").addClass("active");
                        $("#redirect-url-options").hide();
                        $("#mobile-redirect-options").hide();
                    }
                    if (data.redirect_mobile) {
                        $("#redirect-mobile-on").addClass("btn-success");
                        $("#redirect-mobile-on").addClass("active");
                    } else {
                        $("#redirect-mobile-off").addClass("btn-danger");
                        $("#redirect-mobile-off").addClass("active");
                    }
                    $('#redirectModal').modal('show');
                }
            }
        });
    },
    redirectChanged: function(element, status) {
        if (status) {
            $('#redirect-active').val('on');
            $('#redirect-off').removeClass('btn-danger');
            $(element).addClass('btn-success');
            $('#redirect-url-options input').removeAttr("disabled");
            $('#mobile-redirect-options a').removeClass('disabled');
            $('#redirect-target').addClass('required');
        } else {
            $('#redirect-active').val('off');
            $('#redirect-on').removeClass('btn-success');
            $(element).addClass('btn-danger');
            $('#redirect-url-options input').attr("disabled", "disabled");
            $('#mobile-redirect-options a').addClass('disabled');
            $('#redirect-target').removeClass('required');
        }
        if (typeof callOnResizeCb !== "undefined")
            callOnResizeCb();
    },
    redirectMobileChanged: function(element, status) {
        if (status) {
            $('#redirect-mobile').val('on');
            $('#redirect-mobile-off').removeClass('btn-danger');
            $(element).addClass('btn-success');
        } else {
            $('#redirect-mobile').val('off');
            $('#redirect-mobile-on').removeClass('btn-success');
            $(element).addClass('btn-danger');
        }
    },
    redirectSave: function(url) {
        var form = $('#redirect-page-form');
        form.validate();
        var res = form.valid();
        if (!res)
            return;
        var data = {};
        data.action = 'api_call';
        data.redirect_active = $('#redirect-active').val();
        data.redirect_mobile = $('#redirect-mobile').val();
        data.redirect_target = $('#redirect-target').val();
        if (!(data.redirect_target.substr(0, 7).toLowerCase() == 'http://' || data.redirect_target.substr(0, 8).toLowerCase() == 'https://')) {
            alert('Invalid redirect url! Redirect url must begin with http:// or https://');
            return;
        }
        MAPI.ajaxcall({
            data: data,
            type: 'POST',
            async: true,
            url: url,
            success: function() {
                var html = '<div class="red-msg alert alert-success"><button type="button" class="close"">&times;</button>MITSPage redirect options saved!</div>';
                $('#alert_placeholder').append(html);
                setTimeout(function() {
                    $('.red-msg').remove();
                    window.location.reload(true);
                }, 1000);
                $('#redirectModal').modal('hide');
            }
        });
    },
    regeneratePage: function(id, name) {
        $('.edit-icon-' + id).hide();
        $('.regenerate-icon-' + id).show();
        $('.regenerate-icon-' + id).addClass('rotate');
        if (analytics) {
            analytics.track("Regenerate Page", {
                "Page": name
            });
        }
        MAPI.ajaxcall({
            data: {
                'action': 'api_call',
                'request': 'my-page-regenerate',
                'my_page': id
            },
            type: 'GET',
            async: true,
            success: function(data) {
                MP.timeout_check = 0;
            },
            error: function() {
                $('.edit-icon-' + id).show();
                $('.regenerate-icon-' + id).hide();
                $('.regenerate-icon-' + id).removeClass('rotate');
            }
        });
    },
    regeneratePageForHTMLdownload: function() {
        MAPI.ajaxcall({
            data: {
                'action': 'api_call',
                'request': 'my-page-regenerate',
                'my_page': PAGE_INFO['id']
            },
            type: 'GET',
            async: true,
            success: function(data) {
                MP.timeout_check = 0;
            }
        });
    },
    cleanPageSimpleName: function(org_name) {
        var dic_map = defaultDiacriticsRemovalMap();
        var run = false;
        org_name = org_name.toLowerCase();
        for (var i = 0; i < dic_map.length; i++) {
            org_name = org_name.replace(dic_map[i].letters, dic_map[i].base);
        }
        org_name = org_name.replace(/[^\w\s-]/g, '');
        org_name = org_name.replace(/[-\s]+/g, '-');
        for (var k = 0; k < dic_map.length; k++) {
            org_name = org_name.replace(dic_map[k].letters, dic_map[k].base);
        }
        if (org_name.substring(0, 1) == '-') {
            org_name = org_name.substr(1);
            run = true;
        }
        return $.trim(org_name);
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
    }
};

if (!String.prototype.trim)
    String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g, '');
    };
var preventDef = function(event) {
    if (!event)
        event = window.event;
    if (event.stopPropagation) {
        event.stopPropagation();
    } else {
        event.cancelBubble = true;
    }
};
var openLabelSelector = function(pid) {
    $('#custom-labels-dropdown-' + pid + ' input').hide();
    $('#custom-labels-dropdown-' + pid + ' .custom-label-wrap.custom-label-wrap').show();
    for (var i = 0; i < 7; i++) {
        var slot = (i + 1);
        $('#custom-labels-dropdown-' + pid + ' input.custom-label-input-' + slot).val(custom_label_texts[i]);
        setLabel($('#custom-labels-dropdown-' + pid + ' .custom-label.custom-label-' + slot), slot);
    }
    return false;
};
var setPageLabel = function(pid, slot) {
    var an = $('#custom-labels-toggle-' + pid);
    an.removeClass('add-custom-label custom-label custom-label-1 custom-label-2 custom-label-3 custom-label-4 custom-label-5 custom-label-6 custom-label-7');
    if (slot === 0) {
        an.addClass('add-custom-label');
        setLabel(an, 0);
    } else {
        an.addClass('custom-label custom-label-' + slot);
        setLabel(an, slot);
    }
    MAPI.ajaxcall({url: custom_my_page_label_update_url, type: 'POST', data: {'slot': slot, 'pid': pid}});
    return false;
};
var editLabel = function(event, pid, slot) {
    var inp = $('#custom-labels-dropdown-' + pid + ' .custom-label-input-' + slot);
    inp.show();
    inp.focus();
    inp.select();
    $('#custom-labels-dropdown-' + pid + ' .custom-label-wrap.custom-label-wrap-' + slot).hide();
    preventDef(event);
    return false;
};
var saveLabel = function(pid, slot) {
    var inp = $('#custom-labels-dropdown-' + pid + ' .custom-label-input-' + slot);
    updateLabel(pid, slot, inp.val());
    return false;
};

var editInput = function(event) {
    preventDef(event);
    return false;
};

var updateLabel = function(pid, slot, text) {
    custom_label_texts[slot - 1] = text;
    setLabel($('.custom-label.custom-label-' + slot), slot);
    setPageLabel(pid, slot);
    MAPI.ajaxcall({url: custom_label_update_url, type: 'POST', data: {'slot': slot, 'text': text}});
};
var setLabel = function(element, slot) {
    var text;
    if (slot === 0) {
        text = 'add label';
    } else {
        text = custom_label_texts[slot - 1];
    }
    if (text.trim() === '') {
        text = '&nbsp;';
    }
    element.html(text);
    element.attr('data-label-slot', slot);
};
var PaginatorAPICallback = function(from_cache) {
    var clab = $('.custom-label.dropdown-toggle, .add-custom-label.dropdown-toggle');
    clab.each(function(no, item) {
        var slot = $(item).attr('data-label-slot');
        slot = parseInt(slot, 10);
        setLabel($(item), slot);
    });
    fLabel();
};
var fLabel = function() {
    $('#filter-selector .custom-label-filter').each(function(no, item) {
        var fa = $(item);
        var slot = parseInt(fa.attr('data-label-slot'), 10);
        setLabel(fa, slot);
        fa.addClass('custom-label custom-label-' + slot);
    });
};