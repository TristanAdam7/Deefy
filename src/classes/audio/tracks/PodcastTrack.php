<?php
declare(strict_types = 1);

namespace iutnc\deefy\audio\tracks;

class PodcastTrack extends AudioTrack{

	private string $auteur;
	private string $date;

	public function __construct(
							string $t,
							string $chemin){
		parent::__construct($t, $chemin);
		$this->auteur = "";
		$this->date = "";
	}

	public function __get(string $attr) {
        if (property_exists($this, $attr)) return $this->$attr;
        return parent::__get($attr);
    }

    public function setAuteur(string $auteur): void {
        $this->auteur = $auteur;
    }

    public function setDate(string $date): void {
        $this->date = $date;
    }
}