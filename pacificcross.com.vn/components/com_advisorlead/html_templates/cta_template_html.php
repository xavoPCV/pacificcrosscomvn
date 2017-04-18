<?php if ($this->is_editing) { ?>
    <link type="text/css" href="<?php echo ASSETS_URL; ?>/css/cta.css" rel="stylesheet"/>
    <script src="<?php echo ASSETS_URL; ?>/js/jquery.min.js" type="text/javascript"></script>
    <script src="<?php echo ASSETS_URL; ?>/js/jquery-sortable.js" type="text/javascript"></script>
<?php } ?>
<div id="capture_clicks_wrap" <?php echo $this->wrap_style ?>>
    <?php if ($this->is_editing) { ?>
        <script>
            var is_edit = true;
            $(document).ready(function() {
                $("ul#form_fields").sortable({
                    handle: '.icon_drag',
                    pullPlaceholder: true,
                    // animation on drop
                    onDrop: function(item, targetContainer, _super) {
                        var clonedItem = $('<li/>').css({height: 0});
                        item.before(clonedItem);
                        clonedItem.animate({'height': item.height()});

                        item.animate(clonedItem.position(), function() {
                            clonedItem.detach();
                            _super(item);
                        });
                    },
                    // set item relative to cursor position
                    onDragStart: function($item, container, _super) {
                        var offset = $item.offset(),
                                pointer = container.rootGroup.pointer;

                        adjustment = {
                            left: pointer.left - offset.left,
                            top: pointer.top - offset.top
                        };

                        _super($item, container);
                    },
                    onDrag: function($item, position) {
                        $item.css({
                            left: position.left - adjustment.left,
                            top: position.top - adjustment.top
                        });
                    }
                });
            });
        </script>
        <?php
    }
    echo $this->template_html;
    ?>
</div>