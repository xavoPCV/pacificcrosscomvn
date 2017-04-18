var capture_clicks_wrap = '';
$ap(document).ready(function() {
    capture_clicks_wrap = $ap('#capture_clicks_wrap');
    $ap(document).on('click', '#capture_clicks_open', function(e) {
        e.preventDefault();
        show_capture_clicks();
    });

    if (popup_type != 'undefined') {
        switch (popup_type) {
            case "popup":
                if (popup_timeout != 'undefined') {
                    setTimeout(function() {
                        show_capture_clicks();
                    }, popup_timeout);
                }
                break;
        }
    }

    $ap('[event_behavior]').click(function(e) {
        e.preventDefault();
        var event = $ap(this).attr('event_behavior');
        switch (event) {
            case "cta_close":
                var action = $ap(this).attr('action');
                if (action == 'close_forever') {
                    hide_cta_forever();
                } else {
                    hide_capture_clicks();
                }
                break;
            case "cta_submit":
                cta_submit();
                break;
        }

    });

    capture_clicks_wrap.find('form button[type="submit"]').click(function(e) {
        e.preventDefault();
        var form = $ap(this).closest('form');
        var action_url = form.attr('action');

        if (vailidate_form(form)) {

            var form_data = form.serialize();

            var param = {
                form_data: form_data
            };

            remote_load(action_url, param, hide_cta_forever());
        }
    });

});

var remote_load = function(url, params, callback) {

    var finalURL = url;
    var image = document.createElement("img");
    var i = 0;
    for (key in params) {
        if (params[key] !== undefined && params[key] !== '') {
            if (i != 0)
                finalURL += "&" + encodeURIComponent(key) + "=" + encodeURIComponent(params[key]);
            else
                finalURL += "?" + encodeURIComponent(key) + "=" + encodeURIComponent(params[key]);
        }
        i++;
    }
    image.src = finalURL;

    if (callback)
        image.onload = callback;
};

function hide_cta_forever() {
    if (remote_url != 'undefined' && cta_id != 'undefined') {
        var url = remote_url + '/ctas/' + cta_id + '/close';
        remote_load(url);
        hide_capture_clicks();
    }
}

function hide_capture_clicks() {
    capture_clicks_wrap.css({
        visibility: 'hidden',
        opacity: 0
    });
}

function show_capture_clicks() {
    capture_clicks_wrap.css({
        visibility: 'visible',
        opacity: 1
    });
}

function vailidate_form(form) {
    var is_valid = false;
    form.find('input').each(function() {
        var $this = $ap(this);
        var parent = $this.closest('li');

        var is_required = $this.attr('is_required');
        var name = $this.attr('name').toLowerCase().trim();
        var value = $this.val();
        if (is_required == 1 && !value) {
            parent.find('span[type="required"]').fadeIn(500, function() {
                $ap(this).css('display', 'block');
            });
            is_valid = false;
            return false;
        } else {
            parent.find('span[type="required"]').hide();
            is_valid = true;
        }

        if (name == 'email' && !is_valid_email(value)) {
            parent.find('span[type="invalid"]').fadeIn(500, function() {
                $ap(this).css('display', 'block');
            });
            is_valid = false;
            return false;
        } else {
            parent.find('span[type="invalid"]').hide();
            is_valid = true;
        }
    });
    return is_valid;
}

function is_valid_email(value) {
    if (!value.match(/((([a-zA-Z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-zA-Z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-zA-Z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-zA-Z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-zA-Z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-zA-Z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-zA-Z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-zA-Z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-zA-Z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-zA-Z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?/)) {
        return false;
    }
    return true;
}