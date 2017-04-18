<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(dirname(__FILE__) . '/table.php');

class EasyBlogTableFeedHistory extends EasyBlogTable
{
	public $id = null;
	public $feed_id = null;
	public $post_id = null;
	public $uid = null;
	public $created = null;

	public function __construct(& $db )
	{
		parent::__construct( '#__easyblog_feeds_history' , 'id' , $db );
	}

	public function isExists($feedId, $uid)
	{
		$model = EB::model('Feeds');

		return $model->isFeedItemImported($feedId, $uid);
	}
}
