<?php
namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\repository\DeefyRepository;

class MesPlaylistsAction extends Action {

    public function execute(): string {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        
        try {
            $email = AuthnProvider::getInstance()->getSignedInUser();
        } catch (\Exception $e) {
            return "Vous devez être connecté pour voir vos playlists.";
        }

        $repo = DeefyRepository::getInstance();
        $playlists = $repo->findPlaylistsByUser($email);

        $html = "<h1>Mes playlists</h1>";
        if (empty($playlists)) {
            $html .= "<p>Aucune playlist trouvée pour votre compte.</p>";
            return $html;
        }

        $html .= "<ul>";
        foreach ($playlists as $pl) {
            $id = (int)$pl['id'];
            $nom = htmlspecialchars($pl['nom'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            // lien qui va définir la playlist courante puis l'afficher
            $html .= "<li><a href=\"main.php?action=set_current_playlist&id={$id}\">{$nom}</a></li>";
        }
        $html .= "</ul>";

        return $html;
    }
}
