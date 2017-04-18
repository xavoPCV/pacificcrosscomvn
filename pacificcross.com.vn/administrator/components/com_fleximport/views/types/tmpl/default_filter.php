<?php
defined('_JEXEC') or die;
?>
<table class="adminform">
	<tbody><tr>
		<td>
			<div class="filter-select fltrt">
				<?php foreach ($this->filters as $filterName => $filter ){
					echo $filter;
				}?>
				<button type="submit" class="btn"><?= JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			</div>
		</td>
	</tr></tbody>
</table>