<?php 

namespace iutnc\deefy\action;
use iutnc\deefy\audio\tracks\PodcastTrack;
class AddPodcastTrackAction extends Action {

    public function execute() : string {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if ($this->http_method === 'GET') {
            return <<<HTML
            <form enctype="multipart/form-data" method="POST" action="main.php?action=add_track">
                <label for="id_piste_name">Nom de la piste :</label>
                <input type="text" id="id_piste_name" name="piste_name" required>
                <label for="id_auteur">Auteur :</label>
                <input type="text" id="id_auteur" name="auteur" required>
                <label for="id_Date">Date :</label>
                <input type="text" id="id_Date" name="Date" required>
                <label for="id_genre">Genre :</label>
                <input type="text" id="id_genre" name="genre" required>
                <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                <input name="userfile" type="file" />
                <input type="submit" value="Ajouter la piste">
            </form>
            HTML;
        }
        if ($this->http_method === 'POST') {
            if (isset($_POST['piste_name']) && !empty($_POST['piste_name'])) {
                $piste_name = filter_var($_POST['piste_name'], FILTER_SANITIZE_STRING);
                $auteur = filter_var($_POST['auteur'], FILTER_SANITIZE_STRING);
                $date = filter_var($_POST['Date'], FILTER_SANITIZE_STRING);
                $genre = filter_var($_POST['genre'], FILTER_SANITIZE_STRING);
                if (substr($_FILES['userfile']['name'], -4) === '.mp3') {
                    $randomname = bin2hex(random_bytes(8)) . '.mp3';
                    $destination = __DIR__ . "/../audio/$randomname";
                    var_dump($destination);
                    if (!move_uploaded_file($_FILES['userfile']['tmp_name'], $destination)) {
                        return "Erreur lors de l'enregistrement du fichier.";
                    }

                } else {
                    return "Le fichier doit être au format .mp3.";
                }
                $piste = new PodcastTrack($piste_name, $genre, $destination, $auteur, $date);

                if ($_SESSION['playlist'] instanceof \iutnc\deefy\audio\lists\Playlist) {
                    $_SESSION['playlist']->ajouterPiste($piste);
                    $renderer = new \iutnc\deefy\render\AudioListRenderer($_SESSION['playlist']);
                    $renderer->render(\iutnc\deefy\render\Renderer::LONG);
                    return <<<HTML
                    <a href="?action=add_track">Ajouter une autre piste</a>
                    HTML; 
                } else {
                    return "Aucune playlist en session. Veuillez d'abord créer une playlist.";
                }
        
            } else {
                return "La piste ne peut pas être vide.";
            }
        }
        return "Méthode HTTP non supportée.";
    }
    
}