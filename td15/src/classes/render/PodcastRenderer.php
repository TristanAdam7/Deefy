<?php
declare(strict_types = 1);

namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks\PodcastTrack;

class PodcastRenderer extends AudioTrackRenderer {

    private PodcastTrack $podcast;

    public function __construct(PodcastTrack $podcast) {
        $this->podcast = $podcast;
    }

    protected function renderCompact(): string {
        $html  = '<div class="podcast compact">';
        $html .= '<p>' . $this->podcast->auteur . ' - ' . $this->podcast->titre . '</p>';
        $html .= '<audio controls src="' . $this->podcast->nomFichier . '"></audio>';
        $html .= '</div>';
        return $html;
    }

    protected function renderLong(): string {
        $html  = '<div class="podcast long">';
        $html .= '<h3>' . $this->podcast->titre . '</h3>';
        $html .= '<p><strong>Auteur :</strong> ' . $this->podcast->auteur . '</p>';
        $html .= '<p><strong>Date :</strong> ' . $this->podcast->date . '</p>';
        $html .= '<p><strong>Genre :</strong> ' . $this->podcast->genre . '</p>';
        $html .= '<p><strong>Durée :</strong> ' . $this->podcast->duree . ' secondes</p>';
        $html .= '<audio controls src="' . $this->podcast->nomFichier . '"></audio>';
        $html .= '</div>';
        return $html;
    }
}
