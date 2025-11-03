<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\repository\DeefyRepository;
use mysql_xdevapi\Result;

class Authnz {
    /**
     * @throws AuthException
     */
    public function checkRole() : int {
        $user = AuthnProvider::getInstance()->getSignedInUser();
        $query = "Select role from User where email = :email";
        $stmt = DeefyRepository::getInstance()->getPdo()->prepare($query);
        $stmt->execute(['email' => $user]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return (int)$result['role'];
    }

    /**
     * @throws AuthException
     */
    public function checkPlaylistOwner(int $playlist_id) : bool {
        $user = AuthnProvider::getInstance()->getSignedInUser();
        $query = "Select count(*) from user2playlist where id_pl = :id_pl and id_user = (Select id from User where email = :email)";
        $stmt = DeefyRepository::getInstance()->getPdo()->prepare($query);
        $stmt->execute(['id_pl' => $playlist_id, 'email' => $user]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['count(*)'] > 0 or $this->checkRole() === 100;
    }

    public static function getInstance(): Authnz
    {
        static $instance = null;
        if (is_null($instance)) {
            $instance = new Authnz();
        }
        return $instance;
    }
}
