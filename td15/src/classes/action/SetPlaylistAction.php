<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\Authz;
use iutnc\deefy\exception\AuthnException;

class SetPlaylistAction extends Action
{
    public function executeGet(): string
    {
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

            $_SESSION['playlist'] = $playlist;

            $html = "<p>La playlist '{$playlist->nom}' est maintenant votre playlist active.</p>";
            $html .= '<p><a href="?action=add-track">Ajouter une piste à cette playlist</a></p>';
            $html .= '<p><a href="?action=mes-playlists">Retourner à mes playlists</a></p>';
            
            return $html;

        } catch (AuthnException $e) {
            return "<p style='color:red;'>Erreur d'autorisation : " . $e->getMessage() . "</p>";
        } catch (\Exception $e) {
            return "<p style='color:red;'>Erreur : " . $e->getMessage() . "</p>";
        }
    }

    public function executePost(): string
    {
        return $this->executeGet();
    }
}