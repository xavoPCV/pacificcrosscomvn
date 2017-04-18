<?php defined('_JEXEC') or die('Restricted access');

function modChrome_LTdefault($module, &$params, &$attribs)
{
	$headerLevel = isset($attribs['headerLevel']) ? (int) $attribs['headerLevel'] : 3;
	if (!empty ($module->content)) : ?>
		<div class="moduletable<?php echo $params->get('moduleclass_sfx'); ?>">
				<?php if ($module->showtitle) : ?>
					<h<?php echo $headerLevel; ?>><?php 
					$title = $module->title;
                    $title = explode(' ', $title);
                    $title[0] = '<span>'.$title[0].'</span>';
                    $title= join(' ', $title);
                    echo $title; ?></h<?php echo $headerLevel; ?>>
					
				<?php endif; ?>
				<div class="module-content"><?php echo $module->content; ?></div>
		</div>
	<?php endif;
}


/**
 * beezTabs chrome.
 *
 * @since   3.0
 */
function modChrome_Tabs($module, $params, $attribs)
{
	$area = isset($attribs['id']) ? (int) $attribs['id'] :'1';
	$area = 'area-'.$area;

	static $modulecount;
	static $modules;

	if ($modulecount < 1)
	{
		$modulecount = count(JModuleHelper::getModules($module->position));
		$modules = array();
	}

	if ($modulecount == 1)
	{
		$temp = new stdClass;
		$temp->content = $module->content;
		$temp->title = $module->title;
		$temp->params = $module->params;
		$temp->id = $module->id;
		$modules[] = $temp;
		// list of moduletitles
		// list of moduletitles
		echo '<div id="'. $area.'" class="tabouter"><ul class="tabs">';

		foreach ($modules as $rendermodule)
		{
			echo '<li class="tab"><a href="#" id="link_'.$rendermodule->id.'" class="linkopen" onclick="tabshow(\'module_'. $rendermodule->id.'\');return false">'.$rendermodule->title.'</a></li>';
		}
		echo '</ul>';
		$counter = 0;
		// modulecontent
		foreach ($modules as $rendermodule)
		{
			$counter ++;

			echo '<div tabindex="-1" class="tabcontent tabopen" id="module_'.$rendermodule->id.'">';
			echo $rendermodule->content;
			if ($counter != count($modules))
			{
			echo '<a href="#" class="unseen" onclick="nexttab(\'module_'. $rendermodule->id.'\');return false;" id="next_'.$rendermodule->id.'">'.JText::_('TPL_BEEZ3_NEXTTAB').'</a>';
			}
			echo '</div>';
		}
		$modulecount--;
		echo '</div>';
	} else {
		$temp = new stdClass;
		$temp->content = $module->content;
		$temp->params = $module->params;
		$temp->title = $module->title;
		$temp->id = $module->id;
		$modules[] = $temp;
		$modulecount--;
	}
}


function modChrome_slides($module, &$params, &$attribs)
{
$headerLevel = isset($attribs['headerLevel']) ? (int) $attribs['headerLevel'] : 3;
if (!empty ($module->content)) : ?>
<div>
<?php if ($module->showtitle) : ?><h<?php echo $headerLevel; ?> class="slide-header"><?php echo $module->title; ?></h<?php echo $headerLevel; ?>><?php endif; ?>
<?php echo $module->content; ?>
</div>
<?php endif;
}


