<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\Authz;
use iutnc\deefy\exception\AuthnException;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\render\Renderer;

class DisplayPlaylistByIdAction extends Action {
    public function executeGet(): string {
        try {
            if (!isset($_GET['id'])) {
                $html = '<form action="index.php" method="GET">';
                $html .= '<h2>Afficher une playlist par ID</h2>';
                $html .= '<input type="hidden" name="action" value="display-playlist-by-id">';

                $html .= '<br><label for="id_playlist">Entrez l\'ID de la playlist :</label>';
                $html .= '<input type="number" name="id" required>';

                $html .= '<br><button type="submit">Afficher</button>';
                $html .= '</form>';
                return $html;
            }
            
            $playlistId = (int)$_GET['id'];

            Authz::checkPlaylistOwner($playlistId);

            $repo = DeefyRepository::getInstance();
            $playlist = $repo->findPlaylistById($playlistId); 

            if ($playlist === null) {
                throw new Exception("Erreur : La playlist (ID: {$playlistId}) n'existe pas.");
            }

            $renderer = new AudioListRenderer($playlist);
            $html = "<h2>Playlist : {$playlist->nom}</h2>";
            $html .= $renderer->render(Renderer::LONG); 
            
            return $html;

        } catch (AuthnException $e) {
            return "<p style='color:red;'>Erreur d'autorisation : " . $e->getMessage() . "</p>";
        } catch (Exception $e) {
            return "<p>" . $e->getMessage() . "</p>";
        }
    }

    public function executePost(): string {
        return $this->executeGet();
    }
}