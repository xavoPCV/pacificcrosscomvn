<?php
/**
 * @version     1.0.0
 * @package     com_faq
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      sang <thanhsang52@gmail.com> - http://
 */
// no direct access
defined('_JEXEC') or die;
var_dump();
?>
<script type="text/javascript">
    function deleteItem(item_id){
        if(confirm("<?php echo JText::_('COM_FAQ_DELETE_MESSAGE'); ?>")){
            document.getElementById('form-faq-delete-' + item_id).submit();
        }
    }
	jQuery(document).ready(function(e) {
        jQuery('select#filter_category').find('option').each(function() {
			if(jQuery(this).val()=='0')
			{
				jQuery(this).text('- Select Category -');
				return;
			}
				//alert(jQuery(this).val());
		})
    });
</script>
<style type="text/css">
.fltlft {
    float: left;
}
.fltrt {
    float: right;
}
</style>
<div class="blog">  
<form action="<?php echo JRoute::_('index.php?option=com_faq&view=updates'); ?>" method="post" name="adminForm" id="adminForm">
<fieldset id="filter-bar" style="margin-bottom:10px">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('Search'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		
       

		<div class='filter-select fltrt'>
			<?php //Filter for the field category
			//$selected_category = JRequest::getVar('filter_category');
			
			$selected_category = $this->state->get('filter.category');
			
			jimport('joomla.form.form');
			JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
			$form = JForm::getInstance('com_faq.update', 'update');
			echo $form->getLabel('filter_category').'&nbsp;'; 
			echo $form->getInput('filter_category', null, $selected_category);
			//var_dump($form->getInput('filter_category'));
			?>
		</div>

	</fieldset>
	<div class="clr"> </div>
  	<hr />
	<?php $show = false; ?>
    <?php foreach ($this->items as $item) : ?>
		
        <p>
            <span style="color:#990000;font-weight:bold;"><?php echo $item->update_title.'-'?></span><span><?php echo $item->update_category_id_title?></span><span style="color:Gray;font-size:X-Small;"><?php echo '-'.date('m/d/Y',strtotime($item->update_date)); ?></span>
        </p>
        <p><?php echo $item->update_desc ?></p>

    <?php endforeach; ?>
    <?php
    if (!count($this->items)):
        echo JText::_('COM_FAQ_NO_ITEMS');
    endif;
    ?>
   

</form>
</div>
<?php if (count($this->items)): ?>
    <div class="pagination">
        <p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>

