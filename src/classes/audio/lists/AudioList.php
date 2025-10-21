<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\lists;
use iutnc\deefy\audio\tracks\AudioTrack;
use iutnc\deefy\exception\InvalidPropertyNameException;

class AudioList {
    protected string $nom;
    protected int $nbPistes;
    protected int $dureeTotale;
    protected array $pistes; // tableau d'AudioTrack

    public function __construct(string $nom, array $pistes = []) {
        $this->nom = $nom;
        $this->pistes = $pistes;
        $this->nbPistes = count($pistes);
        $this->dureeTotale = 0;
        foreach ($pistes as $piste) {
            if ($piste instanceof AudioTrack) {
                $this->dureeTotale += $piste->duree;
            }
        }
    }

    public function __get($name) {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new InvalidPropertyNameException("invalid property : $name");
    }
}
