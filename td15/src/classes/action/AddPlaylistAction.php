<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\PlayList;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\render\Renderer;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

class AddPlaylistAction extends Action {

    public function executeGet(): string {
        return "
        <h2>Création d'un playlist</h2>
        <form method='post' action='?action=add-playlist'>
            <label for='playlist_name'>Nom de la playlist :</label>
            <input type='text' name='playlist_name' required><br>
            <button type='submit'>Créer la Playlist</button>
        </form>";
    }

    public function executePost(): string {
        $playlistN = filter_var($_POST['playlist_name'], FILTER_SANITIZE_SPECIAL_CHARS);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $playlist = new PlayList($playlistN);

        $repo = DeefyRepository::getInstance();
        $repo->savePlaylist($playlist);

        try {
            $user = AuthnProvider::getSignedInUser();
            $userId = (int)$user['id'];
            $repo->linkPlaylistToUser($playlist->id, $userId);
        } catch (AuthnException $e) {
            return "<p>Erreur lors de l'enregistrement de la playlist, veuillez <a href='?action=add-playlist' class='cliquable'>réessayer</a></p>";
        }

        $_SESSION['playlist'] = $playlist;

        $renderer = new AudioListRenderer($playlist);
        $html = "<h2>Playlist '{$playlist->nom}' créée avec succès (ID: {$playlist->id})</h2>"; 
        $html .= $renderer->render(Renderer::COMPACT);
        $html .= '<p><a href="?action=add-track" class="cliquable">Ajouter une piste</a></p>';

        return $html;
    }
}