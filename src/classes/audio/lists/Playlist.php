<?php
declare(strict_types = 1);

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\audio\tracks\AudioTrack;

class Playlist extends AudioList {

    private ?int $id = null;
    public function __construct(string $nom, array $pistes = []) {
        parent::__construct($nom, $pistes);
    }

    public function ajouterPiste(AudioTrack $piste): void {
        $this->pistes[] = $piste;
        $this->nbPistes++;
        $this->dureeTotale += $piste->__get("duree");
    }

    public function supprimerPiste(int $index): void {
        if (isset($this->pistes[$index])) {
            $this->dureeTotale -= $this->pistes[$index]->__get("duree");
            $this->nbPistes--;

            $nb = count($this->pistes);
            $nouvellesPistes = [];
            for ($i = 0; $i < $nb; $i++) {
                if ($i !== $index) {
                    $nouvellesPistes[] = $this->pistes[$i];
                }
            }
            $this->pistes = $nouvellesPistes;
        } else {
            throw new Exception("Indice $index invalide");
        }
    }

    public function ajouterPistes(array $nouvellesPistes): void {
        foreach ($nouvellesPistes as $p) {
            $dejaPresent = false;
            foreach ($this->pistes as $existante) {
                if ($p->__get("titre") === $existante->__get("titre") && $p->__get("nomFichierAudio") === $existante->__get("nomFichierAudio")) {
                    $dejaPresent = true;
                }
            }

            if (!$dejaPresent) {
                $this->ajouterPiste($p);
            }
        }
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function __get(string $attr) {
        if (property_exists($this, $attr)) return $this->$attr;
        return parent::__get($attr);
    }
}
