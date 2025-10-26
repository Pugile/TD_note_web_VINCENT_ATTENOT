<?php

namespace iutnc\deefy\action;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\repository\DeefyRepository;
use getID3;

class AddPodcastTrackAction extends Action {

    public function execute() : string {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        if ($this->http_method === 'GET') {
            return <<<HTML
            <form enctype="multipart/form-data" method="POST" action="main.php?action=add_track">
                <label for="id_piste_name">Titre :</label>
                <input type="text" id="id_piste_name" name="piste_name" required>
                <label for="id_genre">Genre :</label>
                <input type="text" id="id_genre" name="genre" required>
                <label for="id_auteur">Auteur :</label>
                <input type="text" id="id_auteur" name="auteur" required>
                <label for="titre_album">Titre de l'album :</label>
                <input type="text" id="titre_album" name="titre_album" required>
                <label for="annee_album">Année de l'album :</label>
                <input type="text" id="annee_album" name="annee_album" required>
                <label for="numero_album">Numéro de l'album :</label>
                <input type="text" id="numero_album" name="numero_album" required>
                <input type="hidden" name="MAX_FILE_SIZE" value="10000000" />
                <input name="userfile" type="file" />
                <label for="Nom de la playlist">Nom de la playlist :</label>
                <input type="text" id="playlist_name" name="playlist_name" required>
                <input type="submit" value="Ajouter la piste">
            </form>
            HTML;
        }
        if ($this->http_method === 'POST') {
            if (isset($_POST['piste_name']) && !empty($_POST['piste_name'])) {
                $piste_name = filter_var($_POST['piste_name'], FILTER_SANITIZE_STRING);
                $auteur = filter_var($_POST['auteur'], FILTER_SANITIZE_STRING);
                $genre = filter_var($_POST['genre'], FILTER_SANITIZE_STRING);
                $titre_album = filter_var($_POST['titre_album'], FILTER_SANITIZE_STRING);
                $annee_album = filter_var($_POST['annee_album'], FILTER_SANITIZE_STRING);
                $numero_album = filter_var($_POST['numero_album'], FILTER_SANITIZE_STRING);
                $playlist_name = filter_var($_POST['playlist_name'], FILTER_SANITIZE_STRING);
                $get3ID = new getID3;
                $fileInfo = $get3ID->analyze($_FILES['userfile']['tmp_name']);
                $duree = (int)($fileInfo['playtime_seconds'] ?? 0);
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


                $query = "INSERT INTO track (titre, genre, duree, filename, artiste_album, titre_album, annee_album, numero_album) VALUES (:titre, :genre, :duree, :filename, :artiste_album, :titre_album, :annee_album, :numero_album)";
                $stmt = DeefyRepository::getInstance()->getPdo()->prepare($query);// Durée par défaut, à ajuster selon les besoins
                $stmt ->execute([
                    'titre' => $piste_name,
                    'genre' => $genre,
                    'duree' => $duree,
                    'filename' => $randomname,
                    'artiste_album' => $auteur,
                    'titre_album' => $titre_album,
                    'annee_album' => $annee_album,
                    'numero_album' => $numero_album
                ]);
                $id = DeefyRepository::getInstance()->getPdo()->lastInsertId();

                $query = "select id from playlist where nom = :nom";
                $stmt = DeefyRepository::getInstance()->getPdo()->prepare($query);
                $stmt ->execute(['nom' => $playlist_name]);

                if ($row = $stmt->fetch()) {
                    $playlist_id = $row['id'];
                    $query = "INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste) VALUES (:id_pl, :id_track, :no_piste_dans_liste)";
                    $stmt = DeefyRepository::getInstance()->getPdo()->prepare($query);
                    $no_piste = "SELECT COUNT(*) as count FROM playlist2track WHERE id_pl = :id_pl";
                    $stmt_count = DeefyRepository::getInstance()->getPdo()->prepare($no_piste);
                    $stmt_count->execute(['id_pl' => $playlist_id]);
                    $result = $stmt_count->fetch();
                    $no_piste_dans_liste = $result['count'] + 1;
                    $stmt ->execute([
                        'id_pl' => $playlist_id,
                        'id_track' => $id,
                        'no_piste_dans_liste' => $no_piste_dans_liste
                    ]);
                    return "Piste ajoutée avec succès à la playlist.";
                } else {
                    return "Playlist non trouvée.";
                }

            } else {
                return "La piste ne peut pas être vide.";
            }
        }
        return "Méthode HTTP non supportée.";
    }

}
