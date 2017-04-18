</div>
</div>
</div>
</div>
<footer>
    <div class="container">
        <p>&copy; 2015 advisorproducts.com / All Rights Reserved</p>
    </div>
</footer>

<script type="text/javascript" src="<?php echo ASSETS_URL . "/js/jquery-ui.min.js" ?>"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL . "/js/bootstrap.min.js" ?>"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL . "/js/ajax_callback.js" ?>"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL . "/js/paginator.js" ?>"></script>
<script type="text/javascript" src="<?php echo ASSETS_URL . "/js/advisorlead.js" ?>"></script>

<?php
switch ($current_view) {
    case "advisorlead":
        ?>
        <script src="<?php echo ASSETS_URL; ?>/js/royalslider/royalslider.min.js" type="text/javascript"></script>
        <script>
            jQuery(document).ready(function($) {
                $('#video-gallery').royalSlider({
                    arrowsNav: false,
                    fadeinLoadedSlide: true,
                    controlNavigationSpacing: 0,
                    controlNavigation: 'thumbnails',
                    thumbs: {
                        autoCenter: false,
                        fitInViewport: true,
                        orientation: 'vertical',
                        spacing: 0,
                        paddingBottom: 0
                    },
                    keyboardNavEnabled: true,
                    imageScaleMode: 'fill',
                    imageAlignCenter: true,
                    slidesSpacing: 0,
                    loop: false,
                    loopRewind: true,
                    numImagesToPreload: 0,
                    usePreloader: true,
                    video: {
                        autoHideArrows: true,
                        autoHideControlNav: false,
                        autoHideBlocks: true
                    },
                    autoScaleSlider: true,
                    autoScaleSliderWidth: 960,
                    autoScaleSliderHeight: 450
                });
            });
        </script>
        <?php
        break;
    case "cta":
    case "pages":
        ?>
        <script type="text/javascript" src="<?php echo ASSETS_URL . "/js/users_objects.js" ?>"></script>
        <?php
        break;
}
?>
</body>
</html>