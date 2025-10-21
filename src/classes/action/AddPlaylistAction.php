<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
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
            $_SESSION['playlist'] = $playlist;
            $renderer = new \iutnc\deefy\render\AudioListRenderer($playlist);
            $renderer->render(\iutnc\deefy\render\Renderer::LONG);
            return <<<HTML
            <a href="?action=add_track">Ajouter une piste</a>
            HTML;
        }
        return "Méthode HTTP non supportée.";
    }
    
}