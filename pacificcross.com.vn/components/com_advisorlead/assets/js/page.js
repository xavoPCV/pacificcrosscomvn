var mits_exit_popup_enabled = '%EXIT_POPUP%',
        mits_exit_popup_shown = false;
function getSize(callback) {
    var body = window.document.body,
            doc = window.document.documentElement,
            documentWidth = Math.max(body.scrollWidth, doc.scrollWidth, body.offsetWidth, doc.offsetWidth, doc.clientWidth),
            documentHeight = Math.max(body.scrollHeight, doc.scrollHeight, body.offsetHeight, doc.offsetHeight, doc.clientHeight),
            viewportWidth = window.innerWidth || doc.clientWidth || body.clientWidth,
            viewportHeight = window.innerHeight || doc.clientWidth || body.clientWidth;
    var workaround = window.setTimeout(function() {
        workaround = null;
        callback(documentWidth, documentHeight, viewportWidth, viewportHeight);
    }, 100);
    if (typeof FB != "undefined" && FB.Canvas)
        FB.Canvas.getPageInfo(function(info) {
            if (workaround == null)
                return;
            window.clearTimeout(workaround);
            workaround = null;
            if (info.clientWidth - info.offsetLeft < viewportWidth)
                viewportWidth = info.clientWidth - info.offsetLeft;
            if (info.clientHeight - info.offsetTop < viewportHeight)
                viewportHeight = info.clientHeight - info.offsetTop;
            callback(documentWidth, documentHeight, viewportWidth, viewportHeight);
        });
}

function getScroll(callback) {
    var doc = document.documentElement,
            body = document.body,
            left = doc &&
            doc.scrollLeft || body &&
            body.scrollLeft || 0, top = doc &&
            doc.scrollTop || body &&
            body.scrollTop || 0;
    var workaround = window.setTimeout(function() {
        workaround = null;
        callback(left, top, 0, 0);
    }, 100);
    if (typeof FB != "undefined" && FB.Canvas)
        FB.Canvas.getPageInfo(function(info) {
            if (workaround == null)
                return;
            window.clearTimeout(workaround);
            workaround = null;
            callback(left, top, info.scrollLeft, info.scrollTop);
        });
}

function setScroll(left, top, fb_left, fb_top) {
    window.scrollTo(left, top);
    if (typeof FB != "undefined" && FB.Canvas)
        FB.Canvas.scrollTo(fb_left, fb_top);
}

function itPagesOnLoadFn() {
    function getUrlVars() {
        var vars = {};
        var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m, key, value) {
            vars[key] = value;
        });
        return vars;
    }
    var url_vars = getUrlVars();
    if (url_vars['itps-e']) {
        var elements = document.querySelectorAll('[data-lb-inputemail="true"]');
        for (var i in elements) {
            elements[i].value = decodeURIComponent(url_vars['itps-e']);
        }
    }
    if (url_vars['itps-fn']) {
        var elements = document.querySelectorAll('[data-lb-inputfirstname="true"]');
        for (var i in elements) {
            elements[i].value = decodeURIComponent(url_vars['itps-fn']);
        }
    }
    var mits_page_anchors = document.getElementsByTagName('a');
    for (var i = 0; i < mits_page_anchors.length; i++) {
        mits_page_anchors[i].oldonclick = mits_page_anchors[i].onclick;
        mits_page_anchors[i].onclick = function(e) {
            if (this.href.substring(0, 1) != '#')
                mits_exit_popup_enabled = 'false';
            if (this.oldonclick)
                return this.oldonclick(e);
        }
    }
    var mits_page_forms = document.getElementsByTagName('form');
    for (var i = 0; i < mits_page_forms.length; i++) {
        var oldOnSubmit = mits_page_forms[i].onsubmit;
        mits_page_forms[i].onsubmit = function() {
            var self = this;
            if ((typeof(itPagesIsValidEmail) != 'undefined' && !(itPagesIsValidEmail(self, 'email'))) || (typeof(itPagesIsValidName) != 'undefined' && !(itPagesIsValidName(self, 'name'))) || (typeof(itPagesIsValidFirstName) != 'undefined' && !(itPagesIsValidFirstName(self, 'name')))) {
                return false;
            }
            mits_exit_popup_enabled = 'false';
            if (oldOnSubmit.call(self)) {
                self.submit();
            }
            return false;
        };
    }

    window.onbeforeunload = function(ev) {
        if (mits_exit_popup_enabled == 'true' && !mits_exit_popup_shown) {
            if (/Firefox[\/\s](\d+)/.test(navigator.userAgent) && new Number(RegExp.$1) >= 4) {
                if (confirm("%EXIT_POPUP_MESSAGE%")) {
                    history.go();
                } else {
                    setTimeout(function() {
                        top.location.href = '%EXIT_POPUP_REDIRECT_URL%';
                    }, 1);
                }
            } else {
                mits_exit_popup_shown = true;
                setTimeout(function() {
                    top.location.href = '%EXIT_POPUP_REDIRECT_URL%';
                }, 1000);

                return '%EXIT_POPUP_MESSAGE%';
            }
        }
    };

    var enable_form = '%IS_OPTIN%';
    if (enable_form === 'true') {
        var form_frame = document.createElement("iframe"), background = document.createElement("div");

        background.style.position = "absolute";
        background.style.top = background.style.bottom = background.style.left = background.style.right = "0";
        background.style.zIndex = "2147483646";
        background.style.background = "black";
        background.style.width = "100%";
        background.style.opacity = "0.5";
        background.style.display = "none";

        var frame_src = "%SITE_URL%/mits/%PAGE_ID%/%TEMPLATE_ID%/forms/";
        form_frame.src = frame_src +
                (frame_src.indexOf("?") != -1 ? "&" : "?") + "mits-in-iframe=1";
        form_frame.style.position = "absolute";
        form_frame.style.left = form_frame.style.right = form_frame.style.top = "0";
        form_frame.style.width = "100%";
        form_frame.style.display = "none";
        form_frame.style.border = "none";
        form_frame.style.zIndex = "2147483647";
        var scroll_top = 0, scroll_left = 0, fb_scroll_top = 0, fb_scroll_left = 0, overflow = null;
        function resize() {
            getSize(function(documentWidth, documentHeight, width, height) {
                background.style.height = documentHeight + "px";
                form_frame.style.height = height + "px";
            });
        }
        if (window.addEventListener)
            window.addEventListener("resize", resize);
        else if (window.attachEvent)
            window.attachEvent("onresize", resize);
        else {
            var oldResize = window.onresize;
            window.onresize = function()
            {
                resize();
                if (typeof oldResize == "function")
                    oldResize();
            };
        }
        resize();
        document.body.appendChild(background);
        document.body.appendChild(form_frame);
        window.formFrameClosed = function() {
            window.enableExitPopup();
            form_frame.style.display = "none";
            background.style.display = "none";
            document.body.style.overflow = overflow;
            overflow = null;
        };
        window.disableExitPopup = function(enabled) {
            mits_exit_popup_enabled = 'false';
        }
        window.enableExitPopup = function(enabled) {
            mits_exit_popup_enabled = 'true';
        }
        try {
            var cb = function(e) {
                if (e.data == "close")
                    window.formFrameClosed();
                else if (e.data == "enable-exit-popup")
                    window.enableExitPopup();
                else if (e.data == "disable-exit-popup")
                    window.disableExitPopup();
            };
            if (window.addEventListener)
                window.addEventListener("message", cb);
            else if (window.attachEvent)
                window.attachEvent("onmessage", cb);
        }
        catch (e) {
        }
        window.triggerOptInForm = function() {
            window.disableExitPopup();
            if (typeof window.postMessage == "undefined" || /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent))
                window.location.href = frame_src.replace("mits-in-iframe=1", "mits-in-iframe=0");
            else {
                window.enableExitPopup();
                resize();
                getScroll(function(left, top, fb_left, fb_top) {
                    overflow = document.body.style.overflow;
                    setScroll(left, top, fb_left, fb_top);
                    document.body.style.overflow = "hidden";
                    form_frame.style.top = (top + fb_top) + "px";
                    form_frame.style.left = (left + fb_left) + "px";
                    form_frame.style.display = "block";
                    background.style.display = "block";
                });
            }
            return false;
        };
    }


//    $('iframe').each(function() {
//        var $this = $(this);
//        if ($this.attr('src').indexOf('fast.wistia.net') !== -1) {
//
//            var resize_interval = setInterval(function() {
//                if ($this.attr('style').indexOf('width') !== -1) {
//                    $this.attr('style', '');
////                    clearInterval(resize_interval);
//                }
//            }, 1000);
//        }
//    });
}

function itPagesIsValidEmail(main_form, field_name) {
    if (!main_form.elements.namedItem(field_name)) {
        return true;
    }
    var onEmailError = function() {
        main_form.elements.namedItem(field_name).focus();
        alert('Please enter valid email!');
    }
    var email = main_form.elements.namedItem(field_name).value;
    if (!email) {
        onEmailError();
        return false;
    }
    if (email == main_form.elements.namedItem(field_name).title) {
        onEmailError();
        return false;
    }

    if (!email.match(/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/)) {
        onEmailError();
        return false;
    }

    return true;
}

var facebook_share_url = "%FACEBOOK_SHARE_URL%";
var twitter_share_url = "%TWITTER_SHARE_URL%";
var google_share_url = "%GOOGLE_SHARE_URL%";