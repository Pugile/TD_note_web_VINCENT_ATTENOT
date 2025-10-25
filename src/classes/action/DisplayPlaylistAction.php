<?php

namespace iutnc\deefy\action;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\render\Renderer;
use iutnc\deefy\repository\DeefyRepository;

class DisplayPlaylistAction extends Action {

    public function execute(): string {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $pdo = DeefyRepository::getInstance()->getPdo();
        // Requête qui récupère les playlists avec le nombre de pistes et la durée totale
        $query = <<<SQL
            SELECT p.id, p.nom, COUNT(p2t.id_track) as nb_pistes, SUM(t.duree) as duree_totale
            FROM playlist AS p
            LEFT JOIN playlist2track AS p2t ON p.id = p2t.id_pl
            LEFT JOIN track AS t ON p2t.id_track = t.id
            GROUP BY p.id, p.nom
        SQL;

        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $playlistsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if ($playlistsData) {
            $output = "<h1>Playlists</h1>";
            foreach ($playlistsData as $plData) {
                // Crée une AudioList avec les données agrégées
                $playlist = new Playlist($plData['nom']);
                $playlist->setNbTracks((int) $plData['nb_pistes']);
                // Assure que la durée n'est pas NULL si la playlist est vide
                $playlist->setDuration((int)$plData['duree_totale'] ?? 0);

                $renderer = new AudioListRenderer($playlist);
                $renderer->render(Renderer::COMPACT);
            }
            return $output;
        }

        return "Aucune playlist trouvée.";
    }
}
