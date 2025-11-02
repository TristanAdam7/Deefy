<?php

namespace iutnc\deefy\action;

use iutnc\deefy\render\AudioListRenderer;

class DisplayPlaylistAction extends Action{

    public function executeGet(): string{
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $html = "<h2>Affichage de la playlist en session</h2>";

        if (isset($_SESSION['playlist'])) {
            $render = new AudioListRenderer($_SESSION['playlist']);
            $html .= $render->render();
        } else {
            $html .= "<p>Veuillez d'abord créer ou selectionner une playlist.</p>";
            $html .= '<a href="?action=add-playlist" class="cliquable">Créer une playlist</a><br>';
            $html .= '<a href="?action=mes-playlists" class="cliquable">Voir mes playlists</a>';
        }
        return $html;
    }

    public function executePost(): string {
        return $this->executeGet();
    }
}