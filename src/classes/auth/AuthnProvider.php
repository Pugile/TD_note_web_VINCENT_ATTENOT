<?php

use iutnc\deefy\exception\AuthException;

class AuthnProvider {
    public static function signin(string $email, string $passwd2check): void {
        $user = "select passwd from User where email = ? ";
        if (!password_verify($passwd2check, $user->pass))
            throw new AuthException("Auth error : invalid credentials");
        $_SESSION['user'] = serialize($user);
        return ;
    }
//     public static function getSignedInUser( ): User {
//         if ( !isset($_SESSION['user']))
//             throw new AuthException("Auth error : not signed in");
//         return unserialize($_SESSION['user'] ) ;
//     }
}