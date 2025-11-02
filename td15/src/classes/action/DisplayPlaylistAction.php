<?php

namespace iutnc\deefy\action;

use iutnc\deefy\render\AudioListRenderer;
class DisplayPlaylistAction extends Action{

    public function executeGet(): string{
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['playlist'])) {
            $render = new AudioListRenderer($_SESSION['playlist']);
            $html = $render->render();
        } else {
            $html = "<p>Veuillez d'abord créer ou selectionner une playlist.</p><a href='?action=add-playlist'>Créez-en une !</a>";
        }
        return $html;
    }

    public function executePost(): string {
        return $this->executeGet();
    }
}