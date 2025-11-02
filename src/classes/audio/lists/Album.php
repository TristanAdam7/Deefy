<?php
declare(strict_types = 1);

namespace iutnc\deefy\audio\lists;

class Album extends AudioList {
    
    private string $artiste;
    private string $dateSortie;

    public function __construct(string $nom, array $pistes) {
        if (empty($pistes)) {
            throw new Exception("Un album doit contenir au moins une piste");
        }
        parent::__construct($nom, $pistes);
        $this->artiste = "";
        $this->dateSortie = "";
    }

    public function setArtiste(string $a): void {
        $this->artiste = $a;
    }

    public function setDateSortie(string $d): void {
        $this->dateSortie = $d;
    }

    public function __get(string $attr) {
        if (property_exists($this, $attr)) return $this->$attr;
        return parent::__get($attr);
    }
}
