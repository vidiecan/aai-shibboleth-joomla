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
	const PARAM_LOGIN_TEXT = 'logintext';
	const PARAM_LOGOUT_URL = 'logouturl';
	const JOOMLA_USER_USERNAME = 'username';
	const JOOMLA_USER_PASSWORD = 'password';

	static $params = null;

	/**
	 * Retrieve the url where the user should be returned after logging in
	 *
	 * @param   JRegistry  $params  module parameters
	 * @param   string     $type    return type
	 *
	 * @return string
	 */
	public static function getReturnURL($params, $type)
	{
		$app	= JFactory::getApplication();
		$router = $app->getRouter();
		$url = null;

		if ($itemid = $params->get($type))
		{
			$db		= JFactory::getDbo();
			$query	= $db->getQuery(true)
				->select($db->quoteName('link'))
				->from($db->quoteName('#__menu'))
				->where($db->quoteName('published') . '=1')
				->where($db->quoteName('id') . '=' . $db->quote($itemid));

			$db->setQuery($query);

			if ($link = $db->loadResult())
			{
				if ($router->getMode() == JROUTER_MODE_SEF)
				{
					$url = 'index.php?Itemid=' . $itemid;
				}
				else
				{
					$url = $link . '&Itemid=' . $itemid;
				}
			}
		}

		if (!$url)
		{
			// Stay on the same page
			$uri = clone JUri::getInstance();
			$vars = $router->parse($uri);
			unset($vars['lang']);

			if ($router->getMode() == JROUTER_MODE_SEF)
			{
				if (isset($vars['Itemid']))
				{
					$itemid = $vars['Itemid'];
					$menu = $app->getMenu();
					$item = $menu->getItem($itemid);
					unset($vars['Itemid']);

					if (isset($item) && $vars == $item->query)
					{
						$url = 'index.php?Itemid=' . $itemid;
					}
					else
					{
						$url = 'index.php?' . JUri::buildQuery($vars) . '&Itemid=' . $itemid;
					}
				}
				else
				{
					$url = 'index.php?' . JUri::buildQuery($vars);
				}
			}
			else
			{
				$url = 'index.php?' . JUri::buildQuery($vars);
			}
		}

		return base64_encode($url);
	}

	static function get_link_text() {
		return  self::$params->get(self::PARAM_LOGIN_TEXT, '');
	}

	static function get_logout_url() {
		return  self::$params->get(self::PARAM_LOGOUT_URL, '');
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
		$key = self::$params->get(self::ID);
		$result = isset($_SERVER[$key]) ? $_SERVER[$key] : $default;
		return $result;
	}

}
