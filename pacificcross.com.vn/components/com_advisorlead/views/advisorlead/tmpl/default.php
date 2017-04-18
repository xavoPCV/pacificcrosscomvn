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
<div class="row-fluid">
    <div class="span4">
        <h3>My Dashboard</h3>
    </div>
    <div class="span8">
        <div class="row-fluid">
            <div class="span4 no_fixed">
                <div class="panel">
                    <h3>Today's Leads</h3>
                    <span><?php echo $this->today_leads ?></span>
                </div>
            </div>
            <div class="span4 no_fixed">
                <div class="panel">
                    <h3>Yesterday's Leads</h3>
                    <span><?php echo $this->yesterday_leads ?></span>
                </div>
            </div>
            <div class="span4 no_fixed">
                <div class="panel">
                    <h3>Overall Leads</h3>
                    <span><?php echo $this->overall_leads ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if (1 == 0) { ?>
    <div class="row-fluid">
        <div class="span9">
            <ul id="video_category" class="nav nav-pills">
                <?php
                if (!empty($this->video_categories)) {
                    $count = 0;
                    foreach ($this->video_categories as $category) {
                        $count++;
                        $active_class = $count == 1 ? 'active' : '';
                        ?>
                        <li category="<?php echo $category->id ?>" class="<?php echo $active_class ?>"><a href="#"><?php echo $category->name ?></a></li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
        <div class="span3">
            <input id="video_search_text" class="span12" type="text" placeholder="Search Videos"/>
        </div>
    </div>
    <div class="row-fluid">
        <div id="video-gallery" class="royalSlider videoGallery rsUni">
            <?php
            if (!empty($this->videos)) {
                foreach ($this->videos as $video) {
                    $pos = strrpos($video->title, ' ', 55 - strlen($video->title));
                    ?>
                    <div class="rsContent video_category_<?php echo $video->category ?>">
                        <a class="rsImg" data-rsvideo="<?php echo $video->link ?>" href="<?php echo ASSETS_URL ?>/images/video.png">
                            <div class="rsTmb video_thumb_<?php echo $video->category ?>">
                                <h5><?php echo stripslashes(strlen($video->title) > 55 ? substr($video->title, 0, $pos) . ' ...' : $video->title) ?></h5>
                            </div>
                        </a>
                        <h3 class="rsABlock titleBlock"><?php echo stripslashes($video->title) ?></h3>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
<?php } ?>
<img src="<?php echo ASSETS_URL ?>/images/dashboard.jpg"/>

<script>
    $.expr[":"].contains = $.expr.createPseudo(function(arg) {
        return function(elem) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });

    $(document).ready(function() {
        $('#video_category li').click(function(event) {
            event.preventDefault();
            $('#video_category li').removeClass('active');
            $(this).addClass('active');
            var category = $(this).attr('category');
            $('.rsNavItem.rsThumb').addClass('rsNavItem_hidden').removeClass('rsNavItem').hide();
            $('.video_thumb_' + category).closest('.rsThumb').removeClass('rsNavItem_hidden').addClass('rsNavItem').fadeIn();
            $('#video_search_text').val('');
        });

        var search_timeout = false;
        $('#video_search_text').keyup(function() {
            var text = $(this).val();

            if (search_timeout) {
                clearTimeout(search_timeout);
            }

            search_timeout = setTimeout(function() {
                search_timeout = false;

                if (text != '') {
                    $('.rsNavItem.rsThumb').hide(0, function() {
                        $(".rsTmb h5:contains('" + text + "')").closest('.rsThumb').fadeIn();
                    });
                }
                else {
                    $('.rsNavItem.rsThumb').fadeIn();
                }

            }, 500);
        });
    });

</script>