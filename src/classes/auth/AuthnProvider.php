<?php

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
//     public static function getSignedInUser( ): User {
//         if ( !isset($_SESSION['user']))
//             throw new AuthException("Auth error : not signed in");
//         return unserialize($_SESSION['user'] ) ;
//     }
}