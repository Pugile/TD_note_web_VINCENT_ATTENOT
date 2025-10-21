<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\tracks;
use iutnc\deefy\exception\InvalidPropertyNameException;

class AlbumTrack extends AudioTrack {
    
	protected string $artiste;
	protected string $album;
	protected int $numero_piste; 
    
	public function __construct(string $titre, string $genre, string $chemin, string $album, int $numero_piste) {
		$this->album = $album;
		$this->numero_piste = $numero_piste;
		$this->genre = $genre;
		parent::__construct($titre, $genre, $chemin);
	}

	public function __get($name) {
		if (property_exists($this, $name)) {
			return $this->$name;
		}
	throw new \iutnc\deefy\exception\InvalidPropertyNameException("invalid property : $name");
	}

	// Seul artiste est modifiable après construction
	public function setArtiste(string $artiste) : void {
		$this->artiste = $artiste;
	}
}
