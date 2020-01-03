<?php

class LoginFactory extends Factory
{
    /** ==========================================================================
     * Constructor
     * 
     * @param $id the id of the object in the database (null if not stored yet)
     * 
     * @return void
     */
    function __construct($id = null) {
        parent::__construct($id);
    }

    /** ==========================================================================
     * Destructor
     *
     * @return void
     */
    function __destruct() {
        parent::__destruct();
    }

    /** =========================================================================
     * loginOn()
     * 
     * @param array $payload
     *
     * @return array
     */
    function loginOn($payload) {

        // get Data from content-- pas de parametres
        $email          = isset($payload->email) ?      $payload->email : null;
        $password       = isset($payload->password) ?   $payload->password : null;
        $server         = isset($payload->server) ?   $payload->server : null;
    
        if (!$email || !$password || !$server) 
                return self::setReturn(400, "Bad Request", "Requête REST invalide");
    
        // instantiate user object
        $user = new Users(null);
    
        // set product property values
        $user->email = $email;
    
        $id = $user->emailExists();
    
        if ($id) {
            $user = new Users($id, false);
    
            if ($user->isActifUser() && password_verify($password, $user->password)) {
                global $folder;
                require_once "{$folder}tools/createJWT.php";
                // set response code
                return self::setReturn(200, "OK", "Utilisateur connecté.",  createToken($user, $user->isAdminUser(), $server), "jwt");

            } else {
                // set response code
                return self::setReturn(403, "INVALID", "Accès refusé.");

            }
            
        } else {
            // set response code
            return self::setReturn(403, "INVALID", "Accès refusé.");
        
        }
    }


}
