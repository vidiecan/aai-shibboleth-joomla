<?php

/**
 * User interface provided by the module.
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

if ( empty($link_html) ) {
    echo '<a href="' . JRoute::_('../shibboleth-login.php') . '">';
    echo 'LOGIN';
    echo '</a>';
}else {
    echo $link_html;
}
// <a href="https://idp.shibtest.clarin.eu/Shibboleth.sso/Login?SAMLDS=1&amp;target=https://sp.shibtest.clarin.eu/joomla/&amp;entityID=https://idp.shibtest.clarin.eu/idp/shibboleth"> Login IDP </a>

echo '<!--';
print_r($_SERVER);
echo '-->';

?>
