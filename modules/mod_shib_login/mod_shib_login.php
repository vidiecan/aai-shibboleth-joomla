<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the login functions only once
require_once __DIR__ . '/helper.php';

$params->def('greeting', 1);

$language = & JFactory::getLanguage();
$language->load('com_user');

ModShibLoginHelper::$params = $params;
// do not automatically try to authenticate (e.g., a user with valid session would come to joomla would be
// tried for authentication)
//ModShibLoginHelper::login();

// always redirect to admin section
//$return	      = ModShibLoginHelper::getReturnURL($params, $type);
$login_text		  = ModShibLoginHelper::get_link_text();
$logout_url		  = ModShibLoginHelper::get_logout_url();
$user	          = JFactory::getUser();
$layout           = $params->get('layout', 'default');

require JModuleHelper::getLayoutPath('mod_shib_login', $layout);
