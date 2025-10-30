<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\Action;
use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\SignInAction;
use iutnc\deefy\action\InscriptionAction;

class Dispatcher {
    public string $action;

    public function __construct(string $action) {
        $this->action = $action;
    }

    /**
     * @throws \Exception
     */
    public function run() : void {

        switch ($this->action) {
            case 'add_playlist':
                $actionInstance = (new AddPlaylistAction())->execute();
                break;
            case 'add_track':
                $actionInstance = (new AddPodcastTrackAction())->execute();
                break;
            case 'signin':
                $actionInstance = (new SignInAction())->execute();
                break;
            case 'inscription':
                $actionInstance = (new InscriptionAction())->execute();
                break;
            case 'playlist_id':
                $actionInstance = (new DisplayPlaylistAction())->execute();
                break;
            case 'mes_playlists':
                $actionInstance = (new \iutnc\deefy\action\MesPlaylistsAction())->execute();
                break;
            case 'set_current_playlist':
                $actionInstance = (new \iutnc\deefy\action\SetCurrentPlaylistAction())->execute();
                break;

            default:
                $actionInstance = (new DefaultAction())->execute();
                break;
        }
        echo $this->renderPage($actionInstance);
    }

        public function renderPage(string $html) : string {
                $page = <<<HTML
                <!DOCTYPE html>
                <html lang="fr">
                    <head>
                        <title>DeeFy</title>
                        <link rel="stylesheet" href="css/style.css">

                    </head>
                    <body>
                        <ul>
                            <li><a href="main.php?action=add_playlist">Ajouter une playlist</a></li>
                            
                            <li><a href="main.php?action=add_track">Ajouter une piste</a></li>
                            <li><a href="main.php?action=mes_playlists">Mes playlists</a></li>
                            <li><a href="main.php?action=playlist_id">Playlist Courante</li>
                            <li><a href="main.php?action=inscription">S'inscrire</a></li>
                            <li><a href="main.php?action=signin">Se connecter</a></li>
                        </ul>
                        $html
                    </body>
                </html>
HTML;

                return $page;
        }
}