<?php
declare(strict_types = 1);

namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks\AlbumTrack;

class AlbumTrackRenderer extends AudioTrackRenderer {

    private AlbumTrack $piste;

    public function __construct(AlbumTrack $piste) {
        $this->piste = $piste;
    }

    protected function renderCompact(): string {
        $html  = '<div class="piste compact">';
        $html .= '<p>' . $this->piste->artiste . ' - ' . $this->piste->titre . '</p>';
        $html .= '<audio controls src="' . $this->piste->nomFichier . '"></audio>';
        $html .= '</div>';
        return $html;
    }

    protected function renderLong(): string {
        $html  = '<div class="piste long">';
        $html .= '<h3>' . $this->piste->titre . '</h3>';
        $html .= '<p><strong>Artiste :</strong> ' . $this->piste->artiste . '</p>';
        $html .= '<p><strong>Album :</strong> ' . $this->piste->album . '</p>';
        $html .= '<p><strong>Année :</strong> ' . $this->piste->annee . '</p>';
        $html .= '<p><strong>Piste n° :</strong> ' . $this->piste->numPiste . '</p>';
        $html .= '<p><strong>Genre :</strong> ' . $this->piste->genre . '</p>';
        $html .= '<p><strong>Durée :</strong> ' . $this->piste->duree . ' secondes</p>';
        $html .= '<audio controls src="' . $this->piste->nomFichier . '"></audio>';
        $html .= '</div>';
        return $html;
    }
}