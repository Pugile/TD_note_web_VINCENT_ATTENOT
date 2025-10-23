<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthException;
use iutnc\deefy\repository\DeefyRepository;

class AuthnProvider {
    public static function signin(string $email, string $passwd2check): void {
        $hash = DeefyRepository::getInstance()->getHashUser($email);
        if (!password_verify($passwd2check, $hash))
            throw new AuthException("Auth error : invalid credentials");
        $_SESSION['user'] = serialize($email);
        return ;
    }

    public static function register(string $email, string $passwd): void {
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new AuthException("Auth error : invalid email format");
        }
        $hash = password_hash($passwd, PASSWORD_DEFAULT, ['cost' => 12]);
        DeefyRepository::getInstance()->addUser($email, $hash);
        $_SESSION['user'] = serialize($email);
        return ;
    }

//     public static function getSignedInUser( ): User {
//         if ( !isset($_SESSION['user']))
//             throw new AuthException("Auth error : not signed in");
//         return unserialize($_SESSION['user'] ) ;
//     }
}