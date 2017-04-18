<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div class="eb-composer-views" data-eb-composer-views>
	<?php echo $this->output('site/composer/document'); ?>
	<?php echo $this->output('site/composer/blocks'); ?>
	<?php echo $this->output('site/composer/media'); ?>
	<?php echo $this->output('site/composer/posts'); ?>
    <?php echo $this->output('site/composer/revisions'); ?>
</div>