<?php
declare(strict_types = 1);

namespace iutnc\deefy\render;

use iutnc\deefy\audio\lists\AudioList;
use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\audio\tracks\AlbumTrack;

class AudioListRenderer implements Renderer {

    private AudioList $liste;

    public function __construct(AudioList $liste) {
        $this->liste = $liste;
    }

    public function render(int $selector = 0): string {
        $html  = '<div class="audiolistrenderer">';
        $html .= '<h3>' . $this->liste->nom . '</h3>';

        foreach ($this->liste->pistes as $p) {
            if ($p instanceof AlbumTrack) {
                $renderer = new AlbumTrackRenderer($p);
                $html .= $renderer->render(Renderer::COMPACT);
            } elseif ($p instanceof PodcastTrack) {
                $renderer = new PodcastRenderer($p);
                $html .= $renderer->render(Renderer::COMPACT);
            }
        }

        $html .= '<p><strong>Nombre de pistes :</strong> ' . $this->liste->nbPistes . '</p>';
        $html .= '<p><strong>Durée totale :</strong> ' . $this->liste->dureeTotale . ' secondes</p>';

        $html .= '</div>';
        return $html;
    }
}