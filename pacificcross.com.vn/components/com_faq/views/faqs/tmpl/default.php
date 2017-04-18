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
$lang = JFactory::getLanguage(); 
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
				jQuery(this).text('- Select Category -');return;
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

.clearfix:before, .clearfix:after {
    content: "";
    display: table;
}
.clearfix:after {
    clear: both;
}
.clearfix:before, .clearfix:after {
    content: "";
    display: table;
}
#filter_search {
    background: none repeat scroll 0 0 #FFFFFF;
    border: 1px solid #D1D1D1;
    border-radius: 99px;
    position: relative;
    width: 60%;
	height:40px;
	padding-left:20px;
}
#live-search #filter_search {
    background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
    border: medium none;
    box-shadow: none;
    color: #595959;
    float: left;
    font-size: 16px;
    padding: 20px 25px;
    position: relative;
    width: 60%;
}

#live-search #searchsubmit, input[type="submit"] {
    background-color: #A03717;
}
#live-search #searchsubmit {
    border: medium none;
    border-radius: 99px;
    color: #FFFFFF;
    margin: 12px 25px 0 0;
    padding: 10px 18px;
    position: absolute;
    right: 0;
    text-transform: uppercase;
    top: 0;
}
h2.accord {
	cursor:pointer;
	font-size:16px;
}
	
	<?php if ($lang->getTag() == "vi-VN" ) {  ?> 
	.shsuj .sercal {
				width:25px!important;
	}
	
	<?php } ?>
</style>
<script type="text/javascript">
jQuery('document').ready(function(){
	jQuery('h2.accord').click(function(){
							if(jQuery(this).hasClass('active')==true)
							{
											jQuery(this).removeClass("active").next().slideUp('slow');
																				
								}						
								else{
												jQuery(this).addClass('active').next().slideDown();				
																					
									}
		});	
	jQuery('h2.accord:last + .description').after('<div class="h2-last"></div>');
		
		
	jQuery('.q-a h2.accord').append('<span class="pullet-ac"></span>');
		
	jQuery('a.who-we-are + ul').addClass('sub-who');
	jQuery('a.resources+ul').addClass('sub-resources');
		
	jQuery('#ja-menu ul li ul').hover(function(){
		jQuery(this).parent().addClass('sfHover');								
	},function(){jQuery(this).parent().removeClass('sfHover')});
});	
</script>

<?php if ($this->params->get('show_page_heading')!=0):?>
<h2 class="avatar-article-heading"><?php echo $this->params->get('page_heading')?$this->params->get('page_heading'):$this->params->get('page_title');?></h2>
<?php endif;?>
<?php if ($lang->getTag() == "vi-VN" ) { $lang = 'Tìm kiếm câu hỏi'; }else { $lang = "Have a question? Ask or enter a search term."; } ?>
<form action="<?php echo JRoute::_('index.php?option=com_faq&view=faqs'); ?>" method="post" name="adminForm" id="adminForm">
    <input type="text" name="filter_search" id="filter_search"  title="<?php echo JText::_('Search'); ?>" value="<?php echo $this->escape($this->state->get('filter.search'))?$this->escape($this->state->get('filter.search')):NULL;?>" placeholder="<?php echo $lang ?>" />
    <button class="button" type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
    <button class="button" type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
</form>
<div id="slider" class="article">
	<?php $show = false; ?>
    <?php foreach ($this->items as $item) : ?>
		<?php if($item->state == 1 || ($item->state == 0 && JFactory::getUser()->authorise('core.edit.own',' com_faq'))):
                $show = true;
                ?>
                    <h2 class="accord">
                        <?php echo $item->faq_question; ?>
                    </h2>
        <?php endif; ?>
        <div style="display: none;" class="description">
            <p><?php echo $item->faq_answer; ?></p>
        </div>

    <?php endforeach; ?>
    <?php
    if (!$show):
        echo JText::_('COM_FAQ_NO_ITEMS');
    endif;
    ?>
   
</div>

<?php if ($show): ?>
    <div class="pagination">
        <p class="counter">
            <?php echo $this->pagination->getPagesCounter(); ?>
        </p>
        <?php echo $this->pagination->getPagesLinks(); ?>
    </div>
<?php endif; ?>

