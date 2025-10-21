<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\lists;
use iutnc\deefy\audio\lists\AudioList;

class Album extends AudioList {
    protected string $artiste;
    protected string $dateSortie;

    public function __construct(string $nom, array $pistes, string $artiste = '', string $dateSortie = '') {
        if (empty($pistes)) {
            throw new \InvalidArgumentException('Un album doit contenir au moins une piste.');
        }
        parent::__construct($nom, $pistes);
        $this->artiste = $artiste;
        $this->dateSortie = $dateSortie;
    }

    public function setArtiste(string $artiste) : void {
        $this->artiste = $artiste;
    }
    public function setDateSortie(string $dateSortie) : void {
        $this->dateSortie = $dateSortie;
    }

    public function __get($name) {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        return parent::__get($name);
    }
}
