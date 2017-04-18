<?php
/**
 * @version     1.0.0
 * @package     com_gnosis
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Lander Compton <lander083077@gmail.com> - http://www.hypermodern.org
 */

// No direct access
defined('_JEXEC') or die;

/**
 * @param    array    A named array
 * @return    array
 */
function GnosisBuildRoute(&$query)
{
    $segments = array();
    if (isset($query['view'])) {
        $segments[] = implode('/', explode('.', $query['view']));
        unset($query['view']);
    }
    if (isset($query['task'])) {
        $segments[] = implode('/', explode('.', $query['task']));
        unset($query['task']);
    }
    if (isset($query['id'])) {
        $segments[] = $query['id'];
        unset($query['id']);
    }
    //if (isset($query['Itemid'])) {
    //	$segments[] = $query['Itemid'];
    //	unset($query['Itemid']);
    //}

    return $segments;
}

/**
 * @param    array    A named array
 * @param    array
 *
 * Formats:
 *
 * index.php?/gnosis/task/id/Itemid
 *
 * index.php?/gnosis/id/Itemid
 */
function GnosisParseRoute($segments)
{
    $vars = array();

    // view is always the first element of the array
    $count = count($segments);
    $vars['view'] = $segments[0];

    if ($count) {
        $count--;
        $segment = array_pop($segments);
        if (is_numeric($segment)) {
            $vars['id'] = $segment;
        } else {
            $count--;
            $vars['task'] = array_pop($segments) . '.' . $segment;
        }
    }

    if ($count) {
        $vars['task'] = implode('.', $segments);
    }
    return $vars;
}
