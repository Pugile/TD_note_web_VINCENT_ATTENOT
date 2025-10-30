<?php

declare(strict_types=1);
namespace iutnc\deefy\repository;
use iutnc\deefy\audio\lists\Playlist;
use PDO;

class DeefyRepository{
    private \PDO $pdo;
    private static ?DeefyRepository $instance = null;
    private static array $config = [ ];

    private function __construct(array $conf) {
        $this->pdo = new \PDO($conf['dsn'], $conf['user'], $conf['pass'],
        [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
    }
    public static function getInstance(): DeefyRepository
    {
        if (is_null(self::$instance)) {
        self::$instance = new DeefyRepository(self::$config);
        }
        return self::$instance;
    }
    public static function setConfig(string $file) {
        $conf = parse_ini_file($file);
        if ($conf === false) {
        throw new \Exception("Error reading configuration file");
        }
        $driver = $conf['driver'];
        $host = $conf['host'];
        $database = $conf['database'];
        self::$config = [ 
            'dsn'=> "$driver:host=$host;dbname=$database;charset=utf8mb4",
            'user'=> $conf['username'],
            'pass'=> $conf['password'] ];
    }
public function findPlaylistById(int $id): Playlist {
    $query = "SELECT * FROM playlist WHERE id = :id";
    $stmt = $this->pdo->prepare($query);
    $stmt->execute(['id' => $id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$result) throw new \Exception("Playlist non trouvée.");

    // 🔹 Récupération des pistes associées
    $query2 = <<<SQL
        SELECT t.id, t.titre, t.genre, t.duree, t.filename, t.type,
               t.artiste_album, t.titre_album, t.annee_album, t.numero_album
        FROM track t
        JOIN playlist2track p2t ON t.id = p2t.id_track
        WHERE p2t.id_pl = :id_pl
        ORDER BY p2t.no_piste_dans_liste
    SQL;
    $stmt2 = $this->pdo->prepare($query2);
    $stmt2->execute(['id_pl' => $id]);
    $tracks = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    // 🧮 Calcul durée totale
    $dureeTotale = 0;
    foreach ($tracks as $t) {
        $dureeTotale += (int)($t['duree'] ?? 0);
    }

    // 🔹 Création de la playlist complète
    $playlist = new Playlist($result['nom'], $tracks);
    $playlist->setNbTracks(count($tracks));
    $playlist->setDuration($dureeTotale);

    return $playlist;
}


    
    public function findPlaylistsByUser(string $email): array {
        $query = <<<SQL
            SELECT p.id, p.nom
            FROM playlist p
            JOIN user2playlist u2p ON p.id = u2p.id_pl
            JOIN `User` u ON u.id = u2p.id_user
            WHERE u.email = :email
            ORDER BY p.nom
        SQL;

        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['email' => $email]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $rows ?: [];
    }
        
    //  }
    public function getHashUser(String $email): ?String {
            $query = "SELECT passwd FROM User WHERE email = :email";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['email' => $email]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (isset($result['passwd'])) ? $result['passwd']:null;

     }

     public function addUser(string $email, string $hash): void {
            $query = "INSERT INTO User (email, passwd) VALUES (:email, :passwd)";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['email' => $email, 'passwd' => $hash]);
     }

     public function userExists(string $email): bool {
            $query = "SELECT COUNT(*) as count FROM User WHERE email = :email";
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(['email' => $email]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result['count'] > 0);
     }

     public function getPdo(): PDO {
        return $this->pdo;
     }


}