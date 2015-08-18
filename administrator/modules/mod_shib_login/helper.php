<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_login
 *
 * @package     Joomla.Site
 * @subpackage  mod_login
 *
 * @since       1.5
 */
class ModShibLoginHelper
{
	const ID = 'id';
	const PARAM_REMEMBER = 'remember';
	const PARAM_LINK_HTML = 'htmllinks';
	const JOOMLA_USER_USERNAME = 'password';
	const JOOMLA_USER_PASSWORD = 'password';

	static $params = null;


	static function init() {
		if(self::$params){
			return;
		}

		$modules = array();

		$db = & JFactory::getDBO();
		/**
		 * client_id = 1 means admin access
		 * client_id = 0 means frontend access
		 */
		$query = 'SELECT * '
			. ' FROM #__modules AS m'
			. " WHERE m.module = 'mod_shib_login' AND client_id=1";
		$db->setQuery($query);

		$modules = $db->loadObjectList();
		$params = is_array($modules) ? reset($modules)->params : false;
		self::$params = new JRegistry($params);
	}

	/**
	 * Returns the module's parameters.
	 *
	 * @return JParameter
	 */
	static function get_params() {
		self::init();
		return self::$params;
	}

	static function get_link_html() {
		self::init();
		return self::$params->get(self::PARAM_LINK_HTML, '');
	}

	static function is_authenticated() {
		$user = & JFactory::getUser();
		return!$user->get('guest');
	}

	static function can_authenticate() {
		if (self::is_authenticated()) {
			return false;
		}

		$id = self::get_id();
		return !empty($id);
	}

	/* use joomla authentication mechanism and if one of them is the shib login it will work */
	static function login() {
		if (!self::can_authenticate()) {
			return false;
		}

		$credentials = array(
			self::JOOMLA_USER_USERNAME => self::get_id(),
			self::JOOMLA_USER_PASSWORD => uniqid('', true)
		);

		$options = array(self::PARAM_REMEMBER => true);

		global $app;
		$result = $app->login($credentials, $options);
		return $result;
	}

	/**
	 * Returns the user name provied by the web server.
	 *
	 * @param string $default
	 * @return string
	 */
	static function get_id($default = '') {
		self::init();
		$key = self::$params->get(self::ID, '');
		$result = isset($_SERVER[$key]) ? $_SERVER[$key] : $default;
		return $result;
	}

}
