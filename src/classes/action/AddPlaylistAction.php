<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\exception\AuthException;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\render\Renderer;
use iutnc\deefy\auth\AuthnProvider;
class AddPlaylistAction extends Action {

    /**
     * @throws AuthException
     */
    public function execute() : string {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if ($this->http_method === 'GET') {
            return <<<HTML
            <form method="POST" action="main.php?action=add_playlist">
                <label for="playlist_name">Nom de la playlist :</label>
                <input type="text" id="playlist_name" name="playlist_name" required>
                <input type="submit" value="Ajouter la playlist">
            </form>
            HTML;
        }
        if ($this->http_method === 'POST') {

            $user = AuthnProvider::getInstance()->getSignedInUser();
            if (!isset($_POST['playlist_name']) || $_POST['playlist_name'] === '') {
                return "Le nom de la playlist ne peut pas être vide.";
            }


            $playlist_name = filter_var($_POST['playlist_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $playlist = new Playlist($playlist_name);

            $pdo = DeefyRepository::getInstance()->getPdo();

            $query = "INSERT INTO playlist (nom) VALUES (:nom)";
            $stmt = $pdo->prepare($query);
            $stmt->execute(['nom' => $playlist_name]);
            $id_pl = $pdo->lastInsertId();

            $query2 = "SELECT id FROM user WHERE email = :email";
            $stmt2 = $pdo->prepare($query2);
            $stmt2->execute(['email' => $user]);
            $id_user = $stmt2->fetchColumn();

            if (!$id_user) {
                return "Utilisateur introuvable en base.";
            }

            $query3 = "INSERT INTO user2playlist (id_user, id_pl) VALUES (:id_user, :id_pl)";
            $stmt3 = $pdo->prepare($query3);
            $stmt3->execute(['id_user' => $id_user, 'id_pl' => $id_pl]);

            $renderer = new AudioListRenderer($playlist);
            $renderer->render(Renderer::LONG);

            return <<<HTML
            <a href="?action=add_track&id={$id_pl}">Ajouter une piste</a>
            HTML;
        }

        return "Méthode HTTP non supportée.";
    }
}