<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Advisor Leads&trade;</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">

        <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/bootstrap.min.css" rel="stylesheet">
        <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/template.css" rel="stylesheet">
        <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/popup_function.css" rel="stylesheet">

        <!--[if lt IE 9]>
            <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

        <script>
            var API_URL = '<?php echo JURI::base() . "/index.php?option=com_advisorlead&task=pages.api_call" ?>';
            var analytics = false;
        </script>

    </head>
    <body>
        <div id="fb-root"></div>
        <script>
            var app_id = '<?php echo '719478438075635' ?>';
            window.fbAsyncInit = function() {
                FB.init({
                    appId: app_id,
                    version: 'v2.0',
                    status: false,
                    cookie: true,
                    xfbml: false
                });
            };

            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) {
                    return;
                }
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.com/en_US/sdk.js";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <div id="publish-widget" class="widget">
            <i class="icon-remove" id="close-button"></i>
            <div id="main-content">
                <h1 id="title">How would you like to publish this page?</h1>
                <span id="subtitle"><?php echo $this->page_name; ?></span>
                <div class="row-fluid">
                    <div class="span3">
                        <ul id="tabs" class="vertical_tab">
                            <li class="active"><a href="javascript:;" data-tab="publish-itps-tab"><i class="icon-publish-itps"></i>Advisor Leads</a></li>
                            <li><a href="javascript:;" data-tab="publish-facebook-tab"><i class="icon-publish-facebook"></i>Facebook</a></li>
                        </ul>
                    </div>
                    <div class="span9" >
                        <div style="overflow-y: auto;padding-bottom: 20px; height: 490px">
                            <div class="tab active vertialtab_content" id="publish-itps-tab">
                                <h2>Your page has been published, and you can start using it immediately.</h2>
                                <form class="form-inline">
                                    <div class="row-fluid view_page_block">
                                        <input type="text" readonly id="mp-url" class="view_page_text" value="<?php echo $this->page_url; ?>">
                                        <a target="_blank" class="mits-btn view_page_link" href="<?php echo $this->page_url; ?>"><i class="icon-eye-open"></i> View Page</a>
                                    </div>
                                </form>
                            </div>
                            <div class="tab vertialtab_content " id="publish-facebook-tab">
                                <h2>I want to add this page as a tab on my Facebook page</h2>
                                <p>Publish your page to Facebook, with full Advisor Leads&trade; functionality and performance.</p>
                                <p class="note">
                                    <strong>Select Page Tab to use:</strong>
                                </p>
                                <select id="fb_tab_select">
                                    <option value="719478438075635">Advisor Leads 1</option>
                                    <option value="288977064611115">Advisor Leads 2</option>
                                    <option value="240970879432777">Advisor Leads 3</option>
                                    <option value="806638152710055">Advisor Leads 4</option>                                  
                                </select>
                                <p class="mt2">
                                    <a href="#" id="add-to-facebook-button" class="mits-btn mits-btn-gray" target="_top"><i class="icon-facebook-sign"></i> Add to Facebook page</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div> 
        </div>
        <script src="<?php echo ASSETS_URL; ?>/js/jquery.min.js"></script>
        <script src="<?php echo ASSETS_URL; ?>/js/bootstrap.min.js"></script>
        <script src="<?php echo ASSETS_URL; ?>/js/ajax_callback.js"></script>
        <script src="<?php echo ASSETS_URL; ?>/js/popup_function.js"></script>
        <script src="<?php echo ASSETS_URL; ?>/js/jquery.validate.min.js"></script>
        <script src="<?php echo ASSETS_URL; ?>/js/users_objects.js"></script>

        <script>
            $(function() {
                var defer = function(loc) {
                    setTimeout(function() {
                        window.location.href = loc;
                    }, 500);
                };

                $('#fb_tab_select').change(function()
                {
                    app_id = $(this).val();
                    FB.init({
                        appId: app_id,
                        version: 'v2.0',
                        status: false,
                        cookie: true,
                        xfbml: false
                    });
                });

                addPageTabCallback = function(response) {
                    var tabs_added = Object.keys(response.tabs_added).join(','),
                            query, link;
                    if (tabs_added) {
                        query = 'tabs_added[' + tabs_added + ']'
                        query = encodeURIComponent(query) + '=1';
                        url = '<?php echo ADVISORLEAD_URL . '/pages/' . $this->page_id ?>/addfb/' + app_id + '/?' + query;
                        try {
                            parent.window.location.href = url;
                        } catch (e) {
                            window.location.href = url;
                        }
                    }
                };

                $('#add-to-facebook-button').click(function(e) {
                    FB.ui({method: 'pagetab'}, addPageTabCallback);
                    return false;
                });
            });
        </script>
    </body>
</html>
