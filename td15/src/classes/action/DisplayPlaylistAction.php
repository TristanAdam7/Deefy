<?php

namespace iutnc\deefy\action;

use iutnc\deefy\render\AudioListRenderer;
class DisplayPlaylistAction extends Action{

    public function executeGet(): string{
        session_start();

        if (isset($_SESSION['playlist'])) {
            $render = new AudioListRenderer($_SESSION['playlist']);
            $html = $render->render(1);
        } else {
            $html = "<p>Veuillez d'abord créer une playlist.</p><a href='?action=add-playlist'>Créez-en une !</a>";
        }
        return $html;
    }

    public function executePost(): string {
        return $this->executeGet();
    }
}