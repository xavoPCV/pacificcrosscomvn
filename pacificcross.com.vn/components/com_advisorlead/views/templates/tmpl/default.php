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
<div class="span12 template-paginator loaded" id="paginator">
    <p class="loading"></p>
</div>

<script type="text/javascript">
    var ajax_url = API_URL + '&request=get_templates';
    $(document).ready(function() {
        var default_html = $('#paginator').html();
        var order_by_conversion_rate = null;
        var order_by = null;

        var $sliderUl = $("#template-slider ul");
        var $sliderElements = $("#template-slider > a, #template-slider #template-slider-wrapper, #template-slider #template-meta-wrapper");

        var emptySlider = function() {
            var oldTransition = $sliderUl.css("transition");
            $sliderElements.hide();
            $sliderUl.find("li").remove();
            $sliderUl.css("transition", "none");
            $sliderUl.css("margin-left", "0px");
            setTimeout(function() {
                $sliderUl.css("transition", oldTransition);
            }, 500);
        }

        var postSlide = function(position) {
            var children = $sliderUl.find("li");
            var current = Math.abs(position / 930);
            var slide = $(children[current]);
            var isMobileResponsive = !!(slide.attr("data-is-mobile-responsive") * 1);
            var hasMultipleFormFields = !!(slide.attr("data-has-multiple-form-fields") * 1);

            $("#template-slider").show();

            if (children.length == 0) {
                $("#template-slider").hide();
            }

            $("#template-slider-right-arrow").removeClass("disabled");
            $("#template-slider-left-arrow").removeClass("disabled");

            if (current == 0)
                $("#template-slider-left-arrow").addClass("disabled");

            if (current == children.length - 1)
                $("#template-slider-right-arrow").addClass("disabled");

            $("#template-slider-button a").attr("href", slide.attr("data-url"));
            $("#template-slider-title h4").text(slide.attr("data-title"));

            if (isMobileResponsive) {
                $("#template-slider .mobile-responsive").show();
            } else {
                $("#template-slider .mobile-responsive").hide();
            }

            if (hasMultipleFormFields) {
                $("#template-slider .multiple-form-fields").show();
            } else {
                $("#template-slider .multiple-form-fields").hide();
            }
        };

        var slide = function(direction) {
            var current = $sliderUl.css("margin-left").split("px")[0] * 1;

            if (current % 930 !== 0)
                return;

            var children = $sliderUl.find("li").length;
            var min = -930 * (children - 1);
            var value = current + direction * 930;

            if (value < min)
                return;
            if (value > 0)
                return;

            $sliderUl.css("margin-left", value + "px");

            postSlide(value);
        };

        var mkSlider = function(direction) {
            return function() {
                return slide(direction);
            }
        }

        var sliderButtonsRegistered = false;
        var registerSliderButtons = function() {
            if (!sliderButtonsRegistered) {
                $("#template-slider-left-arrow").click(mkSlider(1));
                $("#template-slider-right-arrow").click(mkSlider(-1));

                sliderButtonsRegistered = true;
            }

            $sliderElements.fadeIn();
        };

        var paginate = function(query, cb) {
            var $category = $('#category-select li.active a');
            query.category = $category.attr('data-category');

            $('#paginator').html(default_html);

            var postLoad = function() {

                emptySlider();

                $(".mits-block").each(function() {
                    var $li = $(this);
                    var $title = $li.find("h4");
                    var $anchor = $($li.find(".create-page-btn")[0]);
                    var $image = $li.find("img");
                    var $mobileResponsive = $li.find(".feature.mobile-responsive");
                    var $multipleFormFields = $li.find(".feature.multiple-form-fields");
                    var $newEl = $("<li/>").
                            attr("data-url", $anchor.attr("href")).
                            attr("data-title", $title.text()).
                            attr("data-is-mobile-responsive", $mobileResponsive.is(":visible") * 1).
                            attr("data-has-multiple-form-fields", $multipleFormFields.is(":visible") * 1);

                    var thumbnail = $image.attr("data-slider-thumbnail");

                    if (thumbnail !== "None") {
                        $newEl.addClass("existing");
                        $newEl.append($("<img/>").attr("src", thumbnail));
                    } else {
                        $newEl.append($("<img/>"));
                    }

                    $sliderUl.append($newEl);
                });

                postSlide(0);
                registerSliderButtons();
            };

            if (query.order == "conversion-rate") {
                var onClick = function() {
                    order_by_conversion_rate = false;
                    order_by = 'name';
                    $('#order-by-box-button').removeClass('active');
                    paginate({order: order_by});
                };

                $('#paginator').paginate(ajax_url, query, 11, function() {
                    if (cb)
                        cb();
                    postLoad();
                });
            } else {
                $('#paginator').paginate(ajax_url, query, 11, function() {
                    if (cb)
                        cb();
                    postLoad();
                });
            }

            return false;
        };

        $('#category-select a').click(function() {
            $('#category-select li.active').removeClass('active');
            $(this).parent().addClass('active');

            emptySlider();

            if (order_by !== null) {
                return paginate({order: order_by});
            }

            return paginate({});
        });

        $('#order-by-box-button').click(function() {
            if (!order_by_conversion_rate) {
                order_by = 'conversion-rate';
                $('#order-by-box-button').addClass('active');
            } else {
                order_by = 'name';
                $('#order-by-box-button').removeClass('active');
            }
            order_by_conversion_rate = !order_by_conversion_rate;
            return paginate({order: order_by});
        });
        
        paginate({});

    });
</script>

