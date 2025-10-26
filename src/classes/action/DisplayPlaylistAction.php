<?php

namespace iutnc\deefy\action;
use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\render\Renderer;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\Authnz;

class DisplayPlaylistAction extends Action {

//Display d'un td précédent

//    public function execute(): string {
//        if (session_status() !== PHP_SESSION_ACTIVE) {
//            session_start();
//        }
//
//        $pdo = DeefyRepository::getInstance()->getPdo();
//        // Requête qui récupère les playlists avec le nombre de pistes et la durée totale
//        $query = <<<SQL
//            SELECT p.id, p.nom, COUNT(p2t.id_track) as nb_pistes, SUM(t.duree) as duree_totale
//            FROM playlist AS p
//            LEFT JOIN playlist2track AS p2t ON p.id = p2t.id_pl
//            LEFT JOIN track AS t ON p2t.id_track = t.id
//            GROUP BY p.id, p.nom
//        SQL;
//
//        $stmt = $pdo->prepare($query);
//        $stmt->execute();
//        $playlistsData = $stmt->fetchAll(\PDO::FETCH_ASSOC);
//
//        if ($playlistsData) {
//            $output = "<h1>Playlists</h1>";
//            foreach ($playlistsData as $plData) {
//                // Crée une AudioList avec les données agrégées
//                $playlist = new Playlist($plData['nom']);
//                $playlist->setNbTracks((int) $plData['nb_pistes']);
//                // Assure que la durée n'est pas NULL si la playlist est vide
//                $playlist->setDuration((int)$plData['duree_totale'] ?? 0);
//
//                $renderer = new AudioListRenderer($playlist);
//                $renderer->render(Renderer::COMPACT);
//            }
//            return $output;
//        }
//
//        return "Aucune playlist trouvée.";
//    }

    /**
     * @throws \Exception
     */
    public function execute(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }



        if ($this->http_method === 'GET') {
            return <<<HTML
            <form method="POST" action="main.php?action=playlist_id">
                <label for="id_playlist">ID de la playlist :</label>
                <input type="number" id="id_playlist" name="id_playlist" required>
                <input type="submit" value="Afficher la playlist">
            </form>
            HTML;
        }

        if ($this->http_method === 'POST') {
            $id = filter_input(INPUT_POST, 'id_playlist', FILTER_VALIDATE_INT);
            if ($id === false || $id === null || $id <= 0) {
                return "ID de playlist invalide.";
            }

            if (!Authnz::getInstance()->checkPlaylistOwner($id)) {
                throw new \Exception("Vous n'êtes pas autorisé à accéder à cette playlist.");
            }


            $playlist = DeefyRepository::getInstance()->findPlaylistById((int)$id);

            $output = "<h1>Playlists</h1>";
            $renderer = new AudioListRenderer($playlist);

            $renderer->render(Renderer::LONG);

            return $output;
        }

        return "Méthode HTTP non supportée.";
    }
}
