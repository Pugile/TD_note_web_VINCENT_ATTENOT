<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\tracks;
use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;

//public  PAS DE PUBLIC !!!
abstract class AudioTrack {
    
	protected string $titre;
	protected string $genre;
	protected int $duree = 0;
	protected string $chemin;
    
	public function __construct(string $titre, string $genre, string $chemin) {
		$this->titre = $titre;
		$this->chemin = $chemin;
		$this->genre = $genre;
		$this->duree = 0;
	}

	public function __get($name) {
		if (property_exists($this, $name)) {
			return $this->$name;
		}
		throw new InvalidPropertyNameException("invalid property : $name");
	}

	public function setDuree(int $duree) : void {
		if ($duree < 0) {
			throw new InvalidPropertyValueException("invalid value for duree: $duree");
		}
		$this->duree = $duree;
	}

	public function __toString() : string {
		return json_encode(get_object_vars($this)); 
	}
    
}
