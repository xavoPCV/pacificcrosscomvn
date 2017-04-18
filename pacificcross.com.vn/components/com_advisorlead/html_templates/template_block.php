<div id="page-table" class="row-fluid paginator-ul">
    <ul id="mits-grid">
        <?php
        foreach ($templates as $template) {
            if ($template->category_id == 6) { // CTA
                $link = ADVISORLEAD_URL . "/ctas/$template->id";
                $slug = 'cta-templates';
            } else {
                $link = ADVISORLEAD_URL . "/pages/$template->id";
                $slug = 'page-templates';
            }
            $screenshot = ASSETS_URL . "/inc/$slug/$template->slug/screenshot.png";
            ?>
            <li class="mits-block">
                <img alt="<?php echo $template->name; ?>" src="<?php echo $screenshot ?>">
                <div class="mits-hover">
                    <a class="create-page-btn" href="<?php echo $link ?>/">Use Template</a>
                </div>
                <p><?php echo $template->name; ?></p>
            </li>
        <?php } ?>
        <div class="clearfix"></div>
    </ul>
</div>