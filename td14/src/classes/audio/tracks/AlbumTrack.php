<?php
declare(strict_types = 1);

namespace iutnc\deefy\audio\tracks;

class AlbumTrack extends AudioTrack{

	private string $artiste;
	private string $album;
	private int $numPiste;
	private int $annee;

	public function __construct(
							string $t, 
							string $chemin, 
							string $nomA, 
							int $numP){
		parent::__construct($t, $chemin);
		$this->album = $nomA;
		$this->numPiste = $numP;
		$this->artiste = "";
        $this->annee = 0;
	}

	public function __get(string $attr) {
        if (property_exists($this, $attr)) return $this->$attr;
        return parent::__get($attr);
    }

    public function setArtiste(string $artiste): void {
        $this->artiste = $artiste;
    }

    public function setAnnee(int $annee): void {
        $this->annee = $annee;
    }
}