<?php declare(strict_types=1);

namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\audio\tracks\AudioTrack;

class AudioListRenderer implements Renderer {

    private AudioList $liste;

    public function __construct(AudioList $liste) {
        $this->liste = $liste;
    }

    // Signature conforme: void
    public function render(int $selector): void {
        echo "<div class='playlist-container'>";

        // En-tête
        echo "<div class='playlist-header'>";
        echo "<h2>🎶 Playlist courante : <span>" . htmlspecialchars($this->liste->nom) . "</span></h2>";
        echo "</div>";

        // Liste des pistes
        echo "<div class='track-list'>";

        $audioDir = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . 'audio';
        $pistes = $this->liste->pistes ?? [];

        if (is_array($pistes) && count($pistes) > 0) {
            foreach ($pistes as $index => $piste) {
                echo "<div class='track'>";

                if (is_array($piste)) {
                    $titre = htmlspecialchars($piste['titre'] ?? 'Sans titre');
                    $duree = (int)($piste['duree'] ?? 0);
                    $nomFichierBrut = $piste['filename'] ?? '';
                    $fichier = htmlspecialchars($nomFichierBrut);

                    echo "<p><strong>" . sprintf('%02d', $index + 1) . " - $titre</strong> ";
                    echo "<span class='duree'>(" . gmdate("i:s", $duree) . ")</span></p>";

                    $realPath = $audioDir . DIRECTORY_SEPARATOR . $nomFichierBrut;

                    if (!empty($fichier) && file_exists($realPath)) {
                        echo "<audio controls preload='metadata' src='audio/$fichier'></audio>";
                    } else {
                        echo "<em>(Fichier audio manquant)</em>";
                    }
                } elseif ($piste instanceof AudioTrack) {
                    $titre = htmlspecialchars($piste->titre);
                    echo "<p><strong>" . sprintf('%02d', $index + 1) . " - $titre</strong></p>";
                    echo "<audio controls src='audio/" . htmlspecialchars($piste->chemin) . "'></audio>";
                }

                echo "</div>"; // .track
            }
        } else {
            echo "<p class='no-track'>Aucune piste pour cette playlist.</p>";
        }

        echo "</div>"; // .track-list

        // Infos de pied
        echo "<div class='playlist-info'>";
        echo "<p><b>Nombre de pistes :</b> " . (int)$this->liste->nbPistes .
             " &nbsp;|&nbsp; <b>Durée totale :</b> " . gmdate("i:s", (int)$this->liste->dureeTotale) . "</p>";
        echo "</div>";

        echo "</div>"; // .playlist-container
    }
}
