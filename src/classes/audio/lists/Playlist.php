<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\lists;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\audio\lists\AudioList;

class Playlist extends AudioList {
    public function ajouterPiste(AudioTrack $piste) : void {
        foreach ($this->pistes as $p) {
            if ($p === $piste) return;
        }
        $this->pistes[] = $piste;
        $this->nbPistes++;
        $this->dureeTotale += $piste->duree;
    }

    public function supprimerPiste(int $indice) : void {
        if (isset($this->pistes[$indice])) {
            $piste = $this->pistes[$indice];
            $this->dureeTotale -= $piste->duree;
            array_splice($this->pistes, $indice, 1);
            $this->nbPistes = count($this->pistes);
        }
    }

    public function ajouterListePistes(array $pistesAAjouter) : void {
        foreach ($pistesAAjouter as $piste) {
            if ($piste instanceof AudioTrack) {
                $this->ajouterPiste($piste);
            }
        }
    }

    public function __get($name) {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        return parent::__get($name);
    }
}
