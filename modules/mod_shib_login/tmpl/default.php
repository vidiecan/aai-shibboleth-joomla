<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_SITE . '/components/com_users/helpers/route.php';
?>

<div style="position: absolute !important; opacity: 1 !important;" class="navbar-fixed-top text-right" id="userbox">
	<div style="margin: 5px 15px 5px 5px; padding: 5px 10px 5px 10px; font-size: 16px;" class="label label-important">
		<a class="signon" style="color: #FFF;" href="<?php
if ( $user->guest ) {
	echo JRoute::_('administrator');
}else {
	echo $logout_url.'return='.JRoute::_('index.php');
}
?>">
			<i class="fa fa-sign-in fa-lg">&nbsp;</i><?php
if ( $user->guest ) {
	echo $login_text;
}else {
	echo 'Logout '.htmlspecialchars($user->get('name'));
}
?></a>

	</div>
</div>