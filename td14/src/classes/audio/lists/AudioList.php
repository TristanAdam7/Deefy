<?php
declare(strict_types = 1);

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\exception\InvalidPropertyNameException;

class AudioList {
    
    protected string $nom;
    protected array $pistes;
    protected int $nbPistes;
    protected int $dureeTotale;

    public function __construct(string $nom, array $pistes = []) {
        $this->nom = $nom;
        $this->pistes = $pistes;
        $this->nbPistes = count($pistes);
        $this->dureeTotale = 0;

        foreach ($pistes as $p) {
            $this->dureeTotale += $p->__get("duree");
        }
    }

    public function __get(string $attr) {
        if (property_exists($this, $attr)) return $this->$attr;
        throw new InvalidPropertyNameException($attr);
    }
}