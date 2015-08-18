# Joomla shibboleth integration

This project allows to authenticate users from their home organisation SAML IdP and if they match a user in joomla database they are authorised. 

It is *not* meant to be simply reusable but rather used to inspect how it works.

## How does it work (simplified)

There is one `site` module - mod_shib_login - showing a `Login` button that redirects to Administrator login screen.

There is one `administrator` module - mod_shib_login - showing the real `Login` buttons of IdP(s) e.g., below the default Login module from joomla depending on the position (/administrator/). The link(s) are either manually built or an external protected file can be used that serves the purpose of redirecting to a discovery page (e.g., through shibboleth configuration). Nevertheless, both solutions use the raw html enabled textarea in the configuration...

Finally, the way shibboleth (or alternative) service provider integration usually works (using web SSO) is that it inserts environmental variables into your session. We need `authentication plugin` which checks these variables and creates a user with username that is then checked against the joomla user database (we do not really need it for our specific case but it is the standard solution).

The authentication is triggered from the `administrator` `mod_shib_login` modules so we do not need to do specific posts to trigger the default joomla authentication (specific html form with login). 

The logout of services using SSO is not trivial. In our particular case, we are fine with removing the session from joomla backend by joomla default mechanism.


## Used configuration

The important ones are:

* `plugin` shibboleth
  * id, email - keys in $_SESSION that contain the shibboleth provided values
  * allowed IdPs - if your service provider consumes feeds with many IdPs but you want only a few to be able to authenticate to particular application you can either do an application override in your SP or just filter it programatically
* `administrator` mod_shib_login
  * htmllinks - raw html with links to IdP(s)
  * id - key in $_SESSION that contains the shibboleth provided value

## Few notes

There are some crucial parts commented out like automated authentication from `site` `mod_shib_login` because a user having a valid session from another application on the same service provider would be automatically authenticated, the user would be automatically authenticted to both frontend/backend and a proper logout would be less trivial.

There are also parts of the system *not used* because of specific decisions but they were included to give a bigger picture.

My testing environment consisted of a virtualbox virtual machine with IdP + SP already set up (from CLARIN/ERIC probably not publicly available), joomla with xdebug specifying the host IP, phpStorm, phpmyadmin, Xdebug in chrome.



