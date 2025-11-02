<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\Playlist;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\Authz;
use iutnc\deefy\exception\AuthnException;

class SetPlaylistAction extends Action {
    public function executeGet(): string {
        try {
            if (!isset($_GET['id'])) {
                throw new \Exception("ID de la playlist manquant.");
            }

            $playlistId = (int)$_GET['id'];

            Authz::checkPlaylistOwner($playlistId);

            $repo = DeefyRepository::getInstance();
            $playlist = $repo->findPlaylistById($playlistId);

            if ($playlist === null) {
                throw new \Exception("Playlist non trouvée.");
            }

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $_SESSION['playlist'] = $playlist;
            $render = new AudioListRenderer($playlist);
            $html = $render->render();

            $html .= "<p>La playlist '{$playlist->nom}' est maintenant votre playlist active.</p>";
            $html .= '<p><a href="?action=add-Podcasttrack" class="cliquable">Ajouter un podcast à cette playlist</a></p>';
            $html .= '<p><a href="?action=add-Albumtrack" class="cliquable">Ajouter une piste d\'album à cette playlist</a></p>';
            $html .= '<p><a href="?action=mes-playlists" class="cliquable">Retourner à mes playlists</a></p>';
            
            return $html;

        } catch (AuthnException $e) {
            return "<p class='error'>Erreur d'autorisation : " . $e->getMessage() . "</p>";
        } catch (\Exception $e) {
            return "<p class='error'>Erreur : " . $e->getMessage() . "</p>";
        }
    }

    public function executePost(): string
    {
        return $this->executeGet();
    }
}