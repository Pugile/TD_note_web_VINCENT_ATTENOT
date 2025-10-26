<?php

namespace iutnc\deefy\auth;

use Couchbase\User;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\repository\DeefyRepository;

class AuthnProvider {
    public static function signin(string $email, string $passwd2check): void {
        $hash = DeefyRepository::getInstance()->getHashUser($email);
        if (!password_verify($passwd2check, $hash))
            throw new AuthException("Auth error : invalid credentials");
        $_SESSION['user'] = $email;
        return ;
    }

    public static function register(string $email, string $passwd): void {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new AuthException("Auth error : format invalide");
        }
        if (DeefyRepository::getInstance()->getHashUser($email) !== null) {
            throw new AuthException("Auth error : l'utilisateur existe déjà");
        }
        $hash = password_hash($passwd, PASSWORD_DEFAULT, ['cost' => 12]);
        DeefyRepository::getInstance()->addUser($email, $hash);
        $_SESSION['user'] = ($email);
        return ;
    }

    /**
     * @throws AuthException
     */
    public static function getSignedInUser( ): string {
         if (session_status() !== PHP_SESSION_ACTIVE) {
             session_start();
         }
         if ( !isset($_SESSION['user']))
             throw new AuthException("Auth error : not signed in");
         return ($_SESSION['user'] ) ;
    }

    public static function getInstance(): AuthnProvider
    {
        static $instance = null;
        if (is_null($instance)) {
            $instance = new AuthnProvider();
        }
        return $instance;
    }
}