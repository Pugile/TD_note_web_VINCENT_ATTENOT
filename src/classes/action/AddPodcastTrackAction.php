<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use getID3;

class AddPodcastTrackAction extends Action
{
    public function execute(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Vérifier qu'une playlist courante est bien sélectionnée
        if (!isset($_SESSION['playlist_courante'])) {
            return "❌ Aucune playlist courante sélectionnée. 
                    <a href='main.php?action=mes_playlists'>Voir mes playlists</a>";
        }

        $playlist_id = (int)$_SESSION['playlist_courante'];

        // --- AFFICHAGE DU FORMULAIRE ---
        if ($this->http_method === 'GET') {
            return <<<HTML
            <h2>Ajouter une piste à la playlist courante</h2>
            <form enctype="multipart/form-data" method="POST" action="main.php?action=add_track">
                <label for="id_piste_name">Titre :</label>
                <input type="text" id="id_piste_name" name="piste_name" required><br>

                <label for="id_genre">Genre :</label>
                <input type="text" id="id_genre" name="genre" required><br>

                <label for="id_auteur">Auteur :</label>
                <input type="text" id="id_auteur" name="auteur" required><br>

                <label for="titre_album">Titre de l'album :</label>
                <input type="text" id="titre_album" name="titre_album"><br>

                <label for="annee_album">Année de l'album :</label>
                <input type="number" id="annee_album" name="annee_album"><br>

                <label for="numero_album">Numéro de l'album :</label>
                <input type="number" id="numero_album" name="numero_album"><br>

                <label for="userfile">Fichier audio (.mp3) :</label>
                <input type="file" name="userfile" accept=".mp3" required><br><br>

                <input type="submit" value="Ajouter la piste">
            </form>
            HTML;
        }

        // --- TRAITEMENT DU FORMULAIRE ---
        if ($this->http_method === 'POST') {
            // Vérifier que tous les champs sont remplis
            if (empty($_POST['piste_name']) || empty($_FILES['userfile']['tmp_name'])) {
                return "Veuillez remplir tous les champs et choisir un fichier audio.";
            }

            // Nettoyage des champs
            $titre = filter_var($_POST['piste_name'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $genre = filter_var($_POST['genre'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $auteur = filter_var($_POST['auteur'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $titre_album = filter_var($_POST['titre_album'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $annee_album = (int)($_POST['annee_album'] ?? 0);
            $numero_album = (int)($_POST['numero_album'] ?? 0);

            // Vérification du fichier
            $fichier = $_FILES['userfile'];
            if (!str_ends_with($fichier['name'], '.mp3')) {
                return "Le fichier doit être au format .mp3.";
            }

            // Analyse avec getID3 pour récupérer la durée
            $get3ID = new getID3;
            $fileInfo = $get3ID->analyze($fichier['tmp_name']);
            $duree = (int)($fileInfo['playtime_seconds'] ?? 0);

            // --- Sauvegarde du fichier ---
            // Sur webetu, les fichiers doivent être accessibles via un sous-dossier public
            $uploadDir = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'audio';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

            $randomName = bin2hex(random_bytes(6)) . ".mp3";
            $destPath = "$uploadDir/$randomName";

            if (!move_uploaded_file($fichier['tmp_name'], $destPath)) {
                return "Erreur lors de la sauvegarde du fichier audio.";
            }

            // --- Insertion en base ---
            $pdo = DeefyRepository::getInstance()->getPdo();
            $stmt = $pdo->prepare("
                INSERT INTO track (titre, genre, duree, filename, type, artiste_album, titre_album, annee_album, numero_album)
                VALUES (:titre, :genre, :duree, :filename, 'A', :artiste_album, :titre_album, :annee_album, :numero_album)
            ");
            $stmt->execute([
                'titre' => $titre,
                'genre' => $genre,
                'duree' => $duree,
                'filename' => $randomName,
                'artiste_album' => $auteur,
                'titre_album' => $titre_album,
                'annee_album' => $annee_album,
                'numero_album' => $numero_album
            ]);
            $track_id = $pdo->lastInsertId();

            // --- Déterminer la position dans la playlist ---
            $stmtCount = $pdo->prepare("SELECT COUNT(*) as c FROM playlist2track WHERE id_pl = :id");
            $stmtCount->execute(['id' => $playlist_id]);
            $position = $stmtCount->fetch()['c'] + 1;

            // --- Lier la piste à la playlist courante ---
            $stmtLink = $pdo->prepare("
                INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste)
                VALUES (:id_pl, :id_track, :pos)
            ");
            $stmtLink->execute([
                'id_pl' => $playlist_id,
                'id_track' => $track_id,
                'pos' => $position
            ]);

            return "✅ Piste « $titre » ajoutée avec succès à la playlist courante.";
        }

        return "Méthode HTTP non supportée.";
    }
}
