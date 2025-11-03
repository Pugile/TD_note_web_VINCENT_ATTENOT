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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DeeFy</title>
    <link rel="stylesheet" href="/~attenot12u/BUT2/S2/ProjetWeb/css/style.css">
  </head>
  <body>
    <header class="app-header">
      <div class="brand">🎵 DeeFy</div>
      <nav class="main-nav">
        <a href="main.php?action=add_playlist">Ajouter une playlist</a>
        <a href="main.php?action=add_track">Ajouter une piste</a>
        <a href="main.php?action=mes_playlists">Mes playlists</a>
        <a href="main.php?action=playlist_id">Playlist courante</a>
        <a href="main.php?action=inscription">S'inscrire</a>
        <a href="main.php?action=signin" class="btn">Se connecter</a>
      </nav>
    </header>

    <main class="container">
      $html
    </main>

    <footer class="app-footer">
      <small>&copy; <?php echo date('Y'); ?> DeeFy — Projet IUT. </small>
    </footer>
  </body>
</html>
HTML;

                return $page;
        }
}
