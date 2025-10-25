<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\render\Renderer;
class AddPlaylistAction extends Action {
    
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
            if (!isset($_POST['playlist_name']) || $_POST['playlist_name'] === '') {
                return "Le nom de la playlist ne peut pas être vide.";
            }

            $playlist_name = filter_var($_POST['playlist_name'], FILTER_SANITIZE_STRING);
            $playlist = new Playlist($playlist_name);
            $query = "INSERT INTO playlist (id, nom) VALUES (:id, :nom)";
            $stmt = DeefyRepository::getInstance()->getPdo()->prepare($query);
            $id = DeefyRepository::getInstance()->getPdo()->lastInsertId();
            $stmt ->execute(['id' => $id, 'nom' => $playlist_name]);
            $renderer = new AudioListRenderer($playlist);
            $renderer->render(Renderer::LONG);
            return <<<HTML
            <a href="?action=add_track">Ajouter une piste</a>
            HTML;
        }
        return "Méthode HTTP non supportée.";
    }
    
}