<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\tracks;
use iutnc\deefy\exception\InvalidPropertyNameException;

//public  PAS DE PUBLIC !!!
class PodcastTrack extends AudioTrack {
    
	protected string $auteur;
	protected string $date;

	protected string $genre;
    
	public function __construct(string $titre, string $genre, string $chemin, string $auteur, string $date) {
		$this->auteur = $auteur;
		$this->date = $date;
		$this->genre = $genre;
		parent::__construct($titre, $genre, $chemin);
	}

	public function __get($name) {
		if (property_exists($this, $name)) {
			return $this->$name;
		}
	throw new \iutnc\deefy\exception\InvalidPropertyNameException("invalid property : $name");
	}

	public function setAuteur(string $auteur) : void {
		$this->auteur = $auteur;
	}
	public function setDate(string $date) : void {
		$this->date = $date;
	}
}
