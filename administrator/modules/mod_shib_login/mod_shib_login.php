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

$params 		  = ModShibLoginHelper::get_params();
$link_html		  = ModShibLoginHelper::get_link_html();
$result 		  = ModShibLoginHelper::login();


// if the user is logged in AND redirect was not from the same server => prob. IdP
// make life easier and redirect
if ( $result 
        && !empty($_SERVER['HTTP_REFERER']) 
            && strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false ) {
    $redirect = JRoute::_('administrator');
    $app->redirect($redirect);
}

require JModuleHelper::getLayoutPath('mod_shib_login', 'default');
