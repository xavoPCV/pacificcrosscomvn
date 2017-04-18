<?php

if ($this->template_type == 'page') {
    $item_text = 'Pages';
} else {
    $item_text = 'CTA';
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Advisor <?php echo $item_text ?>&trade;</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="AdvisorPages" name="description">
        <style type="text/css">
            html, body {
                height: 100%;
            }
            body {
                padding-bottom: 0px;
            }
        </style>
        <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/bootstrap.min.css" rel="stylesheet"/>
        <!--<link type="text/css" href="<?php echo ASSETS_URL; ?>/css/jquery-ui.min.css" rel="stylesheet"/>-->
        <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/redactor.css" rel="stylesheet"/>
        <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/jquery.miniColors.css" rel="stylesheet"/>
        <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/template.css" rel="stylesheet"/>
        <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/bootstrap-slider.min.css" rel="stylesheet"/>
        <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/bootstrap-datetimepicker.min.css" rel="stylesheet"/>
        <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/font-awesome.min.css" rel="stylesheet"/>

		<!--
		<link type="text/css" href="<?php echo ASSETS_URL; ?>/js/fancybox/jquery.fancybox.css?v=2.1.5" rel="stylesheet" />
		-->
		<link type="text/css" href="<?php echo ASSETS_URL; ?>/js/magnific/css/magnific-popup.css" rel="stylesheet" />
		<link type="text/css" href="<?php echo ASSETS_URL; ?>/js/magnific/css/style.css" rel="stylesheet" />
		
		
        <!--[if lt IE 9]>
        <script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <script type="text/javascript">
            var API_URL = '<?php echo "$this->baseurl/index.php?option=com_advisorlead&task=templates.api_call" ?>';
            var ASSET_URL = '<?php echo ASSETS_URL ?>';
            var GOOGLE_FONTS = <?php echo AdvisorleadHelper::get_option('google_fonts') ?>;
            var AVAILABLE_SERVICES = <?php echo json_encode($this->template->integrations) ?>;
            analytics = false;
        </script>
		
		
		<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/json.js"></script>
        <script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/jquery.min.js"></script>
        <!--<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/jquery-ui.min.js"></script>-->
        <script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/jquery.validate.min.js"></script>
        <script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/ajax_callback.js"></script>
        <script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/redactor.js"></script>
        <script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/jquery.minicolors.js"></script>
        <script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/bootstrap-datetimepicker.min.js"></script>
        <script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/bootstrap-slider.min.js"></script>
		
		<!--
		<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/fancybox/jquery.fancybox.js?v=2.1.5"></script>
		-->
		<script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/magnific/js/jquery.magnific-popup.min.js"></script>
		
    </head>
    <body id="edit-template-body">
        <?php
        if ($this->template_type == 'page') {
            include_once HTML_TEMPLATES_PATH . 'page_left_menu.php';
            $modal_class = 'p_modal';
        } else {
            include_once HTML_TEMPLATES_PATH . 'cta_left_menu.php';
            $modal_class = 'cc_modal';
        }
        ?>
        <div id="mits-publish-modal" class="modal hide fade widget-modal <?php echo $modal_class ?>">
            <div class="modal-body">
                <iframe src=""></iframe>
            </div>
        </div>
        <iframe class="hidden" id="mits-form-editor" src="about:blank"></iframe>
        <div class="iframe-loading" id="mits-iframe-wrapper"></div>

       
        <?php if ($this->template_type == 'page') { ?>
            <script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/editor_page.js"></script>
        <?php } else { ?>
            <script type="text/javascript" src="<?php echo ASSETS_URL; ?>/js/editor_cta.js"></script>
        <?php } ?>
        <script type="text/javascript">
            var is_edit = <?php echo $this->template->is_edit ? 'true' : 'false' ?>;
            var real_baseUrl = '<?php echo JURI::base() ?>';
            var baseUrl = '<?php echo ADVISORLEAD_URL; ?>';
            var template_id = <?php echo $this->template_id ?>;
            var template_uri = '<?php echo ASSETS_URL; ?>';
            var template_players = {
                'empty': template_uri + '/images/players/video.png',
                'empty_your': template_uri + '/images/players/unknown_video.png',
                'vimeo': template_uri + '/images/players/vimeo_video.png',
                'wistia': template_uri + '/images/players/wistia_video.png',
                'yt': template_uri + '/images/players/yt_video.png',
                'mits': template_uri + '/images/players/itp_player.png',
                'embed_area': template_uri + '/images/players/embed_area_logo.png'
            };

<?php if ($this->template_type == 'page') { ?>
                var page_id = '<?php echo $this->template->page_id ?>';
                var article_id = '<?php echo $this->template->article_id ?>';
                var page_name = '<?php echo $this->template->page_name; ?>';
                var template_dynamic = <?php echo!empty($this->template->js_variables) ? $this->template->js_variables : '{}' ?>;
                var template_url = baseUrl + "/pages/" + template_id + '/html/';


                $(document).ready(function() {

                    MITS.page_id = page_id;
                    MITS.baseUrl = baseUrl;

                    MITS.loadData(template_url, template_players, template_id, page_id, article_id, template_dynamic, is_edit, page_name);

                    $('.mits-letters-counter').LbLettersCount();
                });
<?php } else { ?>
                var cta_id = <?php echo $this->template->cta_id; ?>;
                var cta_name = '<?php echo $this->template->cta_name; ?>';
                var cta_slug = '<?php echo $this->template->cta_slug ?>';
                var selected_integration = '<?php echo $this->template->selected_integration ?>';
                var selected_form = '<?php echo $this->template->selected_form ?>';
                var template_url = baseUrl + "/ctas/" + template_id + '/html/';

                $(document).ready(function() {
                    CCLICKS.capture_clicks_id = cta_id;
                    CCLICKS.baseUrl = baseUrl;
                    CCLICKS.loadData(template_url, template_players, template_id, cta_id, is_edit, cta_slug, cta_name);
                });
<?php } ?>

        </script>

    </body>
</html>