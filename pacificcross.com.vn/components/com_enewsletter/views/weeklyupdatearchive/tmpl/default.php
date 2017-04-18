<?php
defined('_JEXEC') or die;
?>
<div class="com_enews">
<?php if ($this->params->get('show_page_heading')!=0):?>
<h2 class="h2_title"><?php echo $this->params->get('page_heading')?$this->params->get('page_heading'):$this->params->get('page_title');?></h2>
<?php endif;?>
<?php
foreach ($this->rows as $year => $rows_weekly_arr) {
	echo "<h1 class='h1title'>$year</h1>";
	echo "<ul class='archivelist'>";
	foreach ($rows_weekly_arr as $rows_weekly_item) {
		echo "<li><a href='".$this->baseurl."/index.php?option=com_enewsletter&view=weeklyupdatearchive&format=raw&id={$rows_weekly_item->id}' target='_blank'><img src='".$this->baseurl."/media/com_enewsletter/screens/{$rows_weekly_item->id}.jpg' title='$rows_weekly_item->subject' alt='$rows_weekly_item->subject'></a></li>";
	}//for
	echo "</ul>";
	echo "<div class=\"clr\"></div>";
}//for
?>
	<div class="clr"></div>
</div><!--com_enews-->