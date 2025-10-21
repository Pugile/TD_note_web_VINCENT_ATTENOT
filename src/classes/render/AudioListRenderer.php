<?php
declare(strict_types=1);
namespace iutnc\deefy\render;
use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;

class AudioListRenderer implements Renderer {
    private AudioList $liste;

    public function __construct(AudioList $liste) {
        $this->liste = $liste;
    }

    public function render(int $selector) : void {
        echo "<div class='audio-list'>";
        echo "<h3>" . htmlspecialchars($this->liste->nom) . "</h3>\n";
        foreach ($this->liste->pistes as $piste) {
            if ($piste instanceof AudioTrack) {
                if ($piste instanceof AlbumTrack) {
                    $renderer = new AlbumTrackRenderer($piste);
                    $renderer->render(Renderer::LONG);
                } elseif ($piste instanceof PodcastTrack) {
                    $renderer = new PodcastRenderer($piste);
                    $renderer->render(Renderer::LONG);
                } else {
                    echo "<div>" . htmlspecialchars($piste->titre) . "</div>\n";
                }
            }
        }
        echo "<div><b>Nombre de pistes :</b> " . $this->liste->nbPistes . ", <b>Durée totale :</b> " . $this->liste->dureeTotale . " sec</div>\n";
        echo "</div>\n";
    }
}
