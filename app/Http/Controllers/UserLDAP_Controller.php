<?php

namespace arsatapi\Http\Controllers;

use Illuminate\Http\Request;
use Adldap\Laravel\Facades\Adldap;
use Adldap\AdldapInterface;

class UserLDAP_Controller extends Controller
{
    public function index(){

    }
    public function validar($user, $pass){
        $conn = new \Adldap\Adldap();
        $config = [
            // An array of your LDAP hosts. You can use either
            // the host name or the IP address of your host.
            'hosts'    => ['arsat.com.ar', env('LDAP_HOSTS')],

            // The base distinguished name of your domain to perform searches upon.
            'base_dn'  => env('LDAP_TREE').','. env('LDAP_BASE_DN'),      // 'OU=Users,OU=GTI,DC=arsat,DC=com,DC=ar',

            // The account to use for querying / modifying LDAP records. This
            // does not need to be an admin account. This can also
            // be a full distinguished name of the user account.
            'username' => $user,
            'password' => $pass,
          ];
          $conn->addProvider($config);

          try {
            // If a successful connection is made to your server, the provider will be returned.
            $provider = $conn->connect();

            // Performing a query.
            $results = $provider->search()->find($user);

            // Finding a record.
            return $results;

        } catch (\Adldap\Auth\BindException $e) {

            return null;

        }


    }

}
