(function() {
    var queryString = document.location.href.match(/(.*)#!(.*)/);
    var parameters = {};

    if (queryString !== null) {
        var pairs = queryString[2].split("&");

        for (var i = 0; i < pairs.length; i++) {
            var pair = pairs[i].split("=");

            parameters[pair[0]] = pair[1];
        }
    }

    window.callOnResizeCb = function() {
        if (parent) {
            var cb = parent.window.widgetResizeCallback,
                    widget = $(".widget"),
                    width = widget.width(),
                    height = widget.height(),
                    min = parseInt(widget.css("min-height"));

            if (isNaN(min))
                min = 490;

            if (height < min)
                height = min;

            if (cb)
                cb(width, height);
        }
    };

    $(function() {
        $("#full-html-options").click(function() {
            $("#publish-full-html").show();
            $("#main-content").hide();
            $("#close-button").addClass("dark-close");
        });

        $("#close-button").click(function() {
            if (parent) {
                var cb = parent.window.widgetCloseCallback;

                if (cb)
                    cb();
            }
        });

        $("#mp-url").click(function() {
            $(this).select();
        });

        $("a[data-tab]").each(function() {
            var tabButton = $(this);
            var tab = $("#" + tabButton.attr("data-tab"));
            var hideTabs = tabButton.attr("data-hide-tabs") || false;
            var showTabs = tabButton.attr("data-show-tabs") || false;
            var title = tabButton.attr("data-title") || "";

            tabButton.click(function(e) {
                e.preventDefault();

//                if (tabButton.attr("data-tab") == 'publish-facebook-tab')
//                {
//                    if (optin_redirect_url) {
//                        $('#check_ssl').show();
//                        var data = {
//                            "action": "api_call",
//                            'request': 'check_ssl',
//                            'url': optin_redirect_url
//                        };
//                        MAPI.ajaxcall({
//                            data: data,
//                            type: 'GET',
//                            async: true,
//                            success: function(data) {
//                                $('#check_ssl').hide();
//                                if (data.status == '200') {
//                                    $('#alert_placeholder').html('<div class="alert alert-success"><span>The optin redirect url is working on Facebook!</span><a data-dismiss="alert" class="close">×</a>');
//                                } else {
//                                    $('#alert_placeholder').html('<div class="alert alert-error"><span>The optin redirect url won\'t work on Facebook! Please check it on your optin setting!</span><a data-dismiss="alert" class="close">×</a>');
//                                }
//                            }
//                        });
//                    }
//                }

                if (hideTabs)
                    $("#tabs").hide();
                if (showTabs)
                    $("#tabs").show();

                if (title)
                    $("#title").text(title);

                $("#publish-full-html").hide();
                $("#main-content").show();
                $("#close-button").removeClass("dark-close");

                var parentEl = $(this).parent();

                $("#tabs li").each(function() {
                    $(this).removeClass("active");
                    $(this).removeClass("no-lb");
                    $(this).removeClass("no-rb");
                });

                $(".tab").hide();
                parentEl.addClass("active");
                parentEl.prev().addClass("no-rb");
                parentEl.next().addClass("no-lb");

                tab.show();

                if (window.onTabChange)
                    window.onTabChange(tabButton.attr("data-tab"));

                callOnResizeCb();
            });
        });

        if ("tab" in parameters) {
            var tab = parameters["tab"];
            var $el = $("a[data-tab=" + tab + "]");

            if ($el.length) {
                $el.click();
            } else {
                tab = "publish-" + parameters["tab"] + "-tab";
                $("a[data-tab=" + tab + "]").click();
            }
        }

        callOnResizeCb();
    });
})();
