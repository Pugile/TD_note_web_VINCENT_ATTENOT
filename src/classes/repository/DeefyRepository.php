<?php

declare(strict_types=1);
namespace iutnc\deefy\repository;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\audio\tracks\AlbumTrack;

class DeefyRepository {

    static private array $config;
    static private ?DeefyRepository $instance = null;

    public static function setConfig(String $file) {
        if (!file_exists($file)) {
            throw new \Exception("Le fichier de configuration n'existe pas.");
        }
        $config = parse_ini_file($file, true);
        if ($config === false) {
            throw new \Exception("Erreur lors de la lecture du fichier de configuration.");
        }
        $driver = $config['driver'];
        $host = $config['host'];
        $database = $config['database'];
        $dsn = "$driver:host=$host;dbname=$database";
        self::$config = [
            'dsn' => $dsn,
            'user' => $config['username'],
            'password' => $config['password']
        ];
        
    }

    public static function getInstance() : DeefyRepository {
        if (self::$instance === null) {
            self::$instance = new DeefyRepository();
        }
        return self::$instance;
    }

    public function findPlaylistbyId(int $id) {
        // À implémenter plus tard
    }

    public function getListPlaylists(): array {
        
    }
}


