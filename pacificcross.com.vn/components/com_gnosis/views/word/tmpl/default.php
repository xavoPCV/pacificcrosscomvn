<?php
/**
 * @version     1.0.7
 * @package     com_gnosis
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Lander Compton <lander083077@gmail.com> - http://www.hypermodern.org
 */
// no direct access
defined('_JEXEC') or die;


// allow content plugins?
JPluginHelper::importPlugin('content');
//$module->content = JHtml::_('content.prepare', $module->content, '', 'mod_custom.content');


//Load admin language file
$lang = JFactory::getLanguage();
$document = & JFactory::getDocument();
$base_url = JURI::base();
$app = JFactory::getApplication();
$document->addStyleSheet($base_url . 'components/com_gnosis/css/gnosis.css');
$lang->load('com_gnosis', JPATH_ADMINISTRATOR);
$menus = $app->getMenu();
$menu = $menus->getActive();
$canEdit = JFactory::getUser()->authorise('core.edit', 'com_gnosis.' . $this->item->id);


// field parameters
$gwshowcreatedby = $this->params->get('gwshowcreatedby', 1);
$gwshowpronounciation = $this->params->get('gwshowpronounciation', 1);
$gwshowdefinition = $this->params->get('gwshowdefinition', 1);
$gwshowexample = $this->params->get('gwshowexample', 1);
$gwshowetymology = $this->params->get('gwshowetymology', 1);
$gwshowcategory = $this->params->get('gwshowcategory', 1);
$gwcreatedbydisplay = $this->params->get('gwcreatedbydisplay', 1);
$gwshowtags = $this->params->get('gwshowtags', 1);
$gwshowsource = $this->params->get('gwshowsource', 1);
$gwshowcreateddate = $this->params->get('gwshowcreateddate', 1);
$gwshowmodifieddate = $this->params->get('gwshowmodifieddate', 1);
$dateformat = $this->params->get('dateformat', 1);

if ($gwcreatedbydisplay == 0) {
    $gwusername = 'name';
} else {
    $gwusername = $gwusername = 'username';
}

if (!$canEdit && JFactory::getUser()->authorise('core.edit.own', 'com_gnosis' . $this->item->id)) {
    $canEdit = JFactory::getUser()->id == $this->item->created_by;
}

?>

<div class="page-header">
    <h2>
        <?php
        if ($this->params->get('gwpageheading') == '0') {
            $gheading = $this->item->word;
        } else if ($this->params->get('gwpageheading') == '1') {
            $gheading = JText::_('COM_GNOSIS');
        } else {
            if ($menu) {
                $gheading = $menu->title;
            } else {
                $gheading = $this->item->word;
            }
        }


        echo $gheading;
        //echo $this->item->word;
        //echo $this->params->get('gwpageheading')

        ?>
    </h2>

    <?php if ($canEdit): ?>
        <a href="<?php echo JRoute::_('index.php?option=com_gnosis&task=word.edit&id=' . $this->item->id); ?>"><?php echo JText::_("COM_GNOSIS_EDIT_ITEM"); ?></a>
    <?php endif; ?>

    <?php if (JFactory::getUser()->authorise('core.delete', 'com_gnosis.word.' . $this->item->id)): ?>
        <a href="javascript:document.getElementById('form-word-delete-<?php echo $this->item->id ?>').submit()"><?php echo JText::_("COM_GNOSIS_DELETE_ITEM"); ?></a>
        <form id="form-word-delete-<?php echo $this->item->id; ?>" style="display:inline"
              action="<?php echo JRoute::_('index.php?option=com_gnosis&task=word.remove'); ?>" method="post"
              class="form-validate" enctype="multipart/form-data">
            <input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>"/>
            <input type="hidden" name="jform[word]" value="<?php echo $this->item->word; ?>"/>
            <input type="hidden" name="jform[category]" value="<?php echo $this->item->category; ?>"/>
            <input type="hidden" name="option" value="com_gnosis"/>
            <input type="hidden" name="task" value="word.remove"/>
            <?php echo JHtml::_('form.token'); ?>
        </form>
    <?php endif; ?>

</div>


<?php if ($this->item) : ?>

    <div class="gnosis_word_fields">
        <div class="gnosis_word"><?php echo $this->item->word; ?>


            <?php if ($gwshowpronounciation == 1) : ?>
                <?php if ($this->item->pronounciation) :
                    echo '<span class="gnosis_pronounce">/' . $this->item->pronounciation . '/</span>';
                endif;
                ?>
            <?php endif; ?></div>

        <?php if ($gwshowdefinition == 1) : ?>
            <?php if ($this->item->definition) : ?>
                <div class="gnosis_definition"><span class="gnosis_label"><?php echo JText::_('COM_GNOSIS_WORD_DEFINITION'); ?></span><?php echo $this->item->definition; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($gwshowexample == 1) : ?>
            <?php if ($this->item->examples) : ?>
                <div class="gnosis_examples"><span class="gnosis_label"><?php echo JText::_('COM_GNOSIS_WORD_EXAMPLE'); ?></span><?php echo $this->item->examples; ?>
				</div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($gwshowetymology == 1) : ?>
            <?php if ($this->item->etymology) : ?>
                <div class="gnosis_etymology"><span class="gnosis_label"><?php echo JText::_('COM_GNOSIS_WORD_ETYMOLOGY'); ?></span><?php echo $this->item->etymology; ?>
				</div>
            <?php endif; ?>
        <?php endif; ?>
		
		<?php if ($gwshowsource == 1) : ?>
			<?php if ($this->item->source) : ?>
				<div class="gnosis_source"><span class="gnosis_label"><?php echo JText::_('COM_GNOSIS_WORD_SOURCE'); ?></span><?php echo $this->item->source; ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		
		
        <?php if ($gwshowcreatedby == 1) : ?>
            <?php if ($this->item->created_by) : ?>
                <?php
                if ($this->item->created_by != ''):
                    $array = array();
                    foreach ((array)$this->item->created_by as $value):
                        if (!is_array($value)):
                            $array[] = $value;
                        endif;
                    endforeach;
                    $data = array();
                    foreach ($array as $value):
                        $db = JFactory::getDbo();
                        $query = $db->getQuery(true);
                        $query
                            ->select($gwusername)
                            ->from('`#__users`')
                            ->where('id = ' . $value);
                        $db->setQuery($query);
                        $results = $db->loadObjectList();
                        if ($results) {
                            if ($gwusername == 'name') {
                                $data[] = $results[0]->name;
                            } else {
                                $data[] = $results[0]->username;
                            }

                        }
                    endforeach;
                    $this->item->created_by = implode(',', $data);
                endif;
                ?>
                <div class="gnosis_author"><span class="gnosis_label"><?php echo JText::_('COM_GNOSIS_WORD_CREATED_BY'); ?></span><?php echo $this->item->created_by ?>
                </div>

            <?php endif; ?>
        <?php endif; ?>
		

        <?php if ($gwshowcreateddate == 1) : ?>
            <?php if ($this->item->creation_date) : ?>
                <div class="gnosis_created"><span class="gnosis_label"><?php echo JText::_('COM_GNOSIS_WORD_CREATED_DATE'); ?></span><?php echo date($dateformat, strtotime($this->item->creation_date)); ?>
				</div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if ($gwshowmodifieddate == 1) : ?>
            <?php if ($this->item->modified_date) : ?>
                <div class="gnosis_modified"><span class="gnosis_label"><?php echo JText::_('COM_GNOSIS_WORD_MODIFIED_DATE'); ?></span><?php echo date($dateformat, strtotime($this->item->modified_date)); ?>
				</div>
            <?php endif; ?>
        <?php endif; ?>		
		
		
		
        <?php if ($gwshowtags == 1) : ?>
            <?php if ($this->item->tags) : ?>
		<div class="tags">
	
				
			<?php 
				//array explode ( string $delimiter , string $string [, int $limit ] )
				
				$tags = explode(',', $this->item->tags);
				$tagsId = explode(',', $this->item->tagsId);
				$counttags = 0;
				foreach ($tags as $tag) {
					
					if ($tag) {
					echo '<span class="tag-' . $tagsId[$counttags] . 'tag-list' . $counttags . '"><a class="label label-info" href="' . JRoute::_('index.php?option=com_gnosis&view=tags&id=' . $tagsId[$counttags]) . '">' . $tag  . '</a></span>&nbsp;';
					$counttags = $counttags + 1;
					}
				}
				
			
			?>
	
		 </div>
		    <?php endif; ?>
        <?php endif; ?>	
		 
		 
    </div>

<?php else:
    echo JText::_('COM_GNOSIS_ITEM_NOT_LOADED');
endif;
?>
