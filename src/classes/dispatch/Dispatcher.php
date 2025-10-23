<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\Action;
use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\SignInAction;

class Dispatcher {
    public string $action;

    public function __construct(string $action) {
        $this->action = $action;
    }
    public function run() : void {

        switch ($this->action) {
            case 'add_playlist':
                $actionInstance = (new AddPlaylistAction())->execute();
                break;
            case 'playlist':
                $actionInstance = (new DisplayPlaylistAction())->execute();
                break;
            case 'add_track':
                $actionInstance = (new AddPodcastTrackAction())->execute();
                break;
            case 'signin':
                $actionInstance = (new SignInAction())->execute();
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
                    </head>
                    <body>
                        <ul>
                            <li><a href="main.php?action=add_playlist">Ajouter une playlist</a></li>
                            <li><a href="main.php?action=playlist">Afficher la playlist</a></li>
                            <li><a href="main.php?action=add_track">Ajouter une piste de podcast</a></li>
                            <li><a href="main.php?action=signin">Se connecter</a></li>
                        </ul>
                        $html
                    </body>
                </html>
HTML;

                return $page;
        }
}