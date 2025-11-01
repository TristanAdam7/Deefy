<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class AddPlaylistAction extends Action {

    public function executeGet(): string {
        session_start();

        if (!isset($_SESSION["playlist"])) {
            $html = '<h2>Ajouter une nouvelle piste</h2>';
            $html .= '<form method="post" action="?action=add-playlist">';
            $html .= '<label for="nomP"> Nom de la playlist : </label>';
            $html .= '<input type="text" name="nomP" placeholder="Nom" required><br>';
            $html .= '<button type="submit" name="valider">Ajouter</button></form>';
            return $html;
        } else {
            return '<p> Une playlist a déjà été créée !</p><br><a href="?action=add-track">Ajouter une piste</a>';
        }
    }

    public function executePost(): string {
        session_start();

        $playlistName = filter_var($_POST['nomP'], FILTER_SANITIZE_SPECIAL_CHARS);
        $_SESSION['playlist'] = new Playlist($playlistName);

        $repo = DeefyRepository::getInstance();
        $repo->savePlaylist($_SESSION['playlist']);

        $render = new AudioListRenderer($_SESSION['playlist']);
        $playlistHtml = $render->render(1);

        $html = "<h2>Playliste ajoutée avec succès !</h2>";
        $html .= "<h3>Voici votre playlist :</h3>";
        $html .= $playlistHtml;

        $html .= '<a href="?action=add-track">Ajouter une piste</a>';
        return $html;
    }
}