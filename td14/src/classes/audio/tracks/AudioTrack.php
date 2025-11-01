<?php
declare(strict_types = 1);
namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\InvalidPropertyValueException;

class AudioTrack{

    private ?int $id = null;
	private string $titre;
	private string $genre;
	private int $duree;
	private string $nomFichier;

	public function __construct(
							string $t,
							string $chemin){
		$this->titre = $t;

		$this->nomFichier = $chemin;

		$this->duree = 0;
		$this->genre = "";
	}

    public function __get(string $attr) {
        if (property_exists($this, $attr)) return $this->$attr;
        throw new InvalidPropertyNameException("invalid property : $attr");
    }

    public function setGenre(string $genre): void {
        $this->genre = $genre;
    }

    public function setDuree(int $duree): void {
        if ($duree < 0) throw new InvalidPropertyValueException("invalid value for property 'duree' : $duree");
        $this->duree = $duree;
    }

	public function __toString(): string {
        return json_encode(get_object_vars($this));
    }

    public function setId(int $id): void {
        $this->id = $id;
    }
}