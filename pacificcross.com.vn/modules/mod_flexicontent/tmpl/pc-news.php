<?php // no direct access
defined('_JEXEC') or die('Restricted access');

$tooltip_class = FLEXI_J30GE ? 'hasTooltip' : 'hasTip';
$container_id = $module->id . (count($catdata_arr)>1 && $catdata ? '_'.$catdata->id : '');
?>
	
	<?php
	// Display FavList Information (if enabled)
	include(JPATH_SITE.'/modules/mod_flexicontent/tmpl_common/favlist.php');
	
	// Display Category Information (if enabled)
	include(JPATH_SITE.'/modules/mod_flexicontent/tmpl_common/category.php');
	
	$ord_titles = array(
		'popular'=>JText::_( 'FLEXI_MOST_POPULAR'),
		'commented'=>JText::_( 'FLEXI_MOST_COMMENTED'),
		'rated'=>JText::_( 'FLEXI_BEST_RATED' ),
		'added'=>	JText::_( 'FLEXI_RECENTLY_ADDED'),
		'addedrev'=>JText::_( 'FLEXI_RECENTLY_ADDED_REVERSE' ),
		'updated'=>JText::_( 'FLEXI_RECENTLY_UPDATED'),
		'alpha'=>	JText::_( 'FLEXI_ALPHABETICAL'),
		'alpharev'=>JText::_( 'FLEXI_ALPHABETICAL_REVERSE'),
		'id'=>JText::_( 'FLEXI_HIGHEST_ITEM_ID'),
		'rid'=>JText::_( 'FLEXI_LOWEST_ITEM_ID'),
		'catorder'=>JText::_( 'FLEXI_CAT_ORDER'),
		'random'=>JText::_( 'FLEXI_RANDOM' ),
		'field'=>JText::_( 'FLEXI_CUSTOM_FIELD' ),
		 0=>'Default' );
	
	$separator = "";
	
	foreach ($ordering as $ord) :
  	echo $separator;
	  if (isset($list[$ord]['featured']) || isset($list[$ord]['standard'])) {
  	  $separator = "<div class='ordering_separator' ></div>";
    } else {
  	  $separator = "";
  	  continue;
  	}
	?>
		<?php if (isset($list[$ord]['featured'])) : ?>
			<ul class="two-col-list">
				<?php foreach ($list[$ord]['featured'] as $item) : ?>
					<li>
						<?php if ($add_tooltips) : ?>
							<a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>"><?php echo $item->title; ?></a>
						<?php else : ?>
							<a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>"><?php echo $item->title; ?></a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if (isset($list[$ord]['standard'])) : ?>
			<ul class="two-col-list clearfix">
				<?php foreach ($list[$ord]['standard'] as $item) : ?>
					<li>
						<?php if ($add_tooltips) : ?>
						<a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>"><?php echo $item->title; ?></a>
						<?php else : ?>
						<a href="<?php echo $item->link; ?>" title="<?php echo $item->title; ?>"><?php echo $item->title; ?></a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

	<?php endforeach; ?>