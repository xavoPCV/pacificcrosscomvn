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
<link href="<?php echo ASSETS_URL; ?>/css/daterangepicker.css" rel="stylesheet" type="text/css">
<h2 class="page-header2 centered">Advisor Lead Analytics</h2>
<h3 id="dynamic_page_name" class="centered"></h3>
<form class="form-horizontal">
    <div class="input-prepend">
        <div class="btn-group">
            <span class="add-on"><i class="icon-calendar"></i></span>
            <input id="picker" name="picker" type="text" value="Pick a date...">
        </div>
    </div>
    <div id="compare_chart_wrap">
        <div class="input-append">
            <div class="btn-group">
                <button class="btn dropdown-toggle" data-toggle="dropdown">
                    <span class="dropdown_title">Choose</span> 
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu metric_menu">
                    <li data-preset="0"><a href="#">Views</a></li>
                    <li data-preset="2"><a href="#">Unique views</a></li>
                    <li data-preset="1"><a href="#">Optins</a></li>
                    <li data-preset="3"><a href="#">Conversion Rate</a></li>
                </ul>
            </div>
        </div>
        &nbsp;vs.&nbsp;
        <div class="input-append">
            <div class="btn-group">
                <button class="btn dropdown-toggle" data-toggle="dropdown">
                    <span class="dropdown_title">Select a metric</span> 
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu metric_menu">
                    <li data-preset="0"><a href="#">Views</a></li>
                    <li data-preset="2"><a href="#">Unique views</a></li>
                    <li data-preset="1"><a href="#">Optins</a></li>
                    <li data-preset="3"><a href="#">Conversion Rate</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="input-append">
        <div class="btn-group">
            <button id="view_all_optins_btn" showing="stats" class="btn btn-info dropdown-toggle" data-toggle="dropdown">View All Stats <span class="caret"></span></button>
            <ul class="dropdown-menu" id="show_optin_option">
                <li show_optin_view="stats"><a href="#">Basic</a></li>
                <li show_optin_view="details"><a href="#">Details</a></li>
            </ul>
        </div>
    </div>
    <div id="tracking_type_wrap" class="btn-group fright">
        <button id="view_tracking_type_btn" showing="all" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Tracking Type: <b class="selected_text">All</b> <span class="caret"></span></button>
        <ul class="dropdown-menu" id="show_tracking_type_option">
            <li show_tracking_view="all"><a href="#">All</a></li>
            <li show_tracking_view="page"><a href="#">Pages</a></li>
            <li show_tracking_view="cta"><a href="#">CTAs</a></li>
        </ul>
    </div>
</form>
<div id="paginator" class="loaded">
    <p class="loading"></p>
</div>
<div id="chart" style="width: 100%; height: 500px; visibility: hidden;"></div>

<script src="<?php echo ASSETS_URL; ?>/js/date.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/daterangepicker.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/highcharts.js"></script>
<script src="<?php echo ASSETS_URL; ?>/js/analytics.js"></script>
<script>
    var baseUrl = '<?php echo ADVISORLEAD_URL; ?>';
    var page_id = 0;
    var purl = API_URL + '&request=show-all';

    jQuery(document).ready(function($) {

        $('#paginator').paginate(purl + '&type=stats');

        var chart, viewsIdx, optinsIdx, uniquesIdx, crateIdx;

        chart = pageAnalytics.chart("chart", {
            tooltip: {
                shared: true
            },
            yAxis: [{
                    min: 0
                }, {
                    min: 0,
                    max: 100,
                    labels: {
                        formatter: function() {
                            return this.value + '%';
                        },
                    },
                    title: {
                        text: 'Conversion Rate'
                    },
                    opposite: true
                }]
        });

        viewsIdx = 0;
        optinsIdx = 1;
        uniquesIdx = 2;
        crateIdx = 3;

        pageAnalytics.picker("picker", function(start, end) {

            var parameters = pageAnalytics.timeParameters(start, end);

            var showing = $('#view_all_optins_btn').attr('showing');
            var showing_type = $('#view_tracking_type_btn').attr('showing');
            switch (showing) {
                case "stats":
                    var url = purl + '&type=stats&tracking_type=' + showing_type;
                    $('#paginator').paginate(url, parameters);
                    break;

                case "details":
                    var url = purl + '&type=details&tracking_type=' + showing_type;
                    $('#paginator').paginate(url, parameters);
                    break;

                case "charts":
                    if (page_id != 0) {
                        show_chart(page_id, parameters);
                    }
                    break;
            }
        });

        $('.metric_menu li').click(function() {
            $(this).parent().find('li').removeAttr('selected');
            $(this).attr('selected', '1');

            var title = $(this).find('a').text();
            $(this).closest('.btn-group').find('.dropdown_title').html(title);

            for (var i = 0; i < chart.series.length; i++) {
                chart.series[i].hide();
            }

            $('.metric_menu li[selected]').each(function() {
                var selected_metric = $(this).attr("data-preset");
                chart.series[selected_metric].show();
            });
        });

        $('#show_optin_option li').click(function(e) {
            e.preventDefault();
            var show_optin_view = $(this).attr('show_optin_view');
            var showing_type = $('#view_tracking_type_btn').attr('showing');

            $('#picker').val('Pick a date...');

            $('#show_optin_option li[show_optin_view="exit"]').show();
            var url = purl + '&tracking_type=' + showing_type + '&type=' + show_optin_view;
            $('#paginator').paginate(url);
            $('#chart').css('visibility', 'hidden');
            $('#compare_chart_wrap').hide();
            $('#paginator').slideDown();

            $('#dynamic_page_name').text('');
            $('#view_all_optins_btn').attr('showing', show_optin_view);
        });

        $('#show_tracking_type_option li').click(function(e) {
            e.preventDefault();
            var showing_type = $(this).attr('show_tracking_view');
            var selected_text = $(this).find('a').text();
            $('#view_tracking_type_btn .selected_text').text(selected_text);
            $('#view_tracking_type_btn').attr('showing', showing_type);

        });

        $(document).on('click', '.set_page_id_analytics', function() {
            var set_page_id = $(this).attr('page_id');
            var set_page_name = $(this).attr('page_name');
            var tracking_type = $(this).attr('tracking_type');
            object_id = set_page_id;

            $('#view_tracking_type_btn').attr('showing', tracking_type);

            var parameters = pageAnalytics.timeParameters(Date.today(), Date.today().add({days: 1}));
            show_chart(object_id, parameters);

            $('#dynamic_page_name').text(set_page_name);
            $('#chart').slideDown(400, function() {
                $(this).css('visibility', 'visible');
            });
            $('#compare_chart_wrap').css('display', 'inline-block');
            $('#paginator').slideUp();
            $('#view_all_optins_btn').attr('showing', 'charts');

            $('#picker').val('Pick a date...');
        });

        window.exitSelection = function() {
        };

        function show_chart(object_id, parameters) {
            chart.showLoading();

            var showing_type = $('#view_tracking_type_btn').attr('showing');
            var url = API_URL + '&request=chart&object_id=' + object_id + '&tracking_type=' + showing_type;
            MAPI.ajaxcall({
                data: parameters,
                url: url,
                type: 'GET',
                success: function(response) {
                    if (response.status == 200) {
                        chart.hideLoading();
                        var viewsSeries = [], optinsSeries = [], uniquesSeries = [], conversionRateSeries = [];
                        for (var i = 0; i < response.body.length; i++) {
                            var date = response.body[i][0] * 1000 + parameters.timezoneOffset;

                            viewsSeries.push([date, response.body[i][1]]);
                            optinsSeries.push([date, response.body[i][2]]);
                            uniquesSeries.push([date, response.body[i][3]]);
                            conversionRateSeries.push([date, response.body[i][4]]);
                        }
                        chart.series[viewsIdx].setData(viewsSeries);
                        chart.series[optinsIdx].setData(optinsSeries);
                        chart.series[uniquesIdx].setData(uniquesSeries);
                        chart.series[crateIdx].setData(conversionRateSeries);
                    }
                }
            });
        }
    });


</script>
