<?php

defined('_JEXEC') or die;

/**
 * Shibboleth server authentication.
 *
 * @version	1.0.0
 */
class plgAuthenticationShibboleth extends JPlugin {

    const EMAIL = "email";
    const ID = "id";
    const IDP = "idp";
    const PARAM_ALLOWED_IDPS = "allowedidps";

    public $name = "plgAuthenticationShibboleth";

    /**
     * Constructor
     */
    function plgAuthenticationShibboleth(& $subject, $config) {
        parent::__construct($subject, $config);
    }

    /**
     * This method should handle any authentication and report back to the subject
     *
     * @param   array   $credentials  Array holding the user credentials
     * @param   array   $options      Array of extra options
     * @param   object  &$response    Authentication response object
     *
     * @return  boolean

     * @since 1.5
     */
    public function onUserAuthenticate($credentials, $options, &$response) {
        if ($this->is_authenticated()) {
            if ( !$this->is_idp_allowed() ) {
                $response->status = JAuthentication::STATUS_FAILURE;
                $response->error_message = JText::_('JGLOBAL_AUTH_USER_BLACKLISTED');
                return false;
            }
            $response->status = JAuthentication::STATUS_SUCCESS;
            $response->error_message = '';
            $response->email = $this->get_email();
            $response->username = $this->get_id();
            $response->password_clear = uniqid('', true);
            return true;
        }
        $response->status = JAuthentication::STATUS_FAILURE;
        $response->error_message = JText::_('JGLOBAL_AUTH_NO_USER');
        return false;
    }

    /*
     * If available returns the server provided value for $name. If not returns $default.
     */
    public function get($name, $default = '') {
        $key = $this->params->get($name);
        return isset($_SERVER[$key]) ? $_SERVER[$key] : $default;
    }

    /**
     * Returns true if the user is authenticated. False otherwise.
     *
     * @return bool
     */
    public function is_authenticated(){
        $id = $this->get_id();
        return !empty($id);
    }

    public function is_idp_allowed(){
        $idp = $this->get_idp();
        if ( !empty($idp) ) {
            $allowed_idps = $this->params->get(self::PARAM_ALLOWED_IDPS);
            foreach (preg_split('/\r\n|\r|\n/', $allowed_idps) as $allowed) {
                if (false !== strpos($idp, $allowed)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Returns email.
     *
     * @return string
     */
    public function get_email() {
        return $this->get(self::EMAIL);
    }

    /**
     * Returns email.
     *
     * @return string
     */
    public function get_id() {
        return $this->get(self::ID);
    }

    public function get_idp() {
        return $this->get(self::IDP);
    }

}
