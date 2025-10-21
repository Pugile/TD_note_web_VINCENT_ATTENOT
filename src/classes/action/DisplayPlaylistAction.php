<?php

namespace iutnc\deefy\action;
use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\render\AudioListRenderer;

class DisplayPlaylistAction extends Action {

    public function execute() : string {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (isset($_SESSION['playlist']) && $_SESSION['playlist'] instanceof AudioList) {
            ob_start();
            $renderer = new AudioListRenderer($_SESSION['playlist']);
            $renderer->render(AudioListRenderer::LONG);
            $html = ob_get_clean();
            return $html;
        }

        return "Aucune playlist en session.";
    }
    
}