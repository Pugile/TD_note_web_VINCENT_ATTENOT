<?php
namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\auth\Authnz;

class SetCurrentPlaylistAction extends Action {

    public function execute(): string {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // vérification param id en GET
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id <= 0) {
            return "ID de playlist invalide.";
        }

        // vérifier que l'utilisateur est bien connecté
        try {
            $email = AuthnProvider::getInstance()->getSignedInUser();
        } catch (\Exception $e) {
            return "Vous devez être connecté pour sélectionner une playlist.";
        }

        // vérifier la propriété (Authnz utilisé aussi dans DisplayPlaylistAction)
        if (!Authnz::getInstance()->checkPlaylistOwner($id)) {
            return "Vous n'êtes pas autorisé à sélectionner cette playlist.";
        }

        // définir la playlist courante en session
        $_SESSION['playlist_courante'] = (int)$id;

        // Maintenant on peut réutiliser DisplayPlaylistAction pour afficher la playlist :
        $displayAction = new DisplayPlaylistAction();
        
        header("Location: main.php?action=playlist_id");
        exit();
    }
}
