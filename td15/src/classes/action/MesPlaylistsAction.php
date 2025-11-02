<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

class MesPlaylistsAction extends Action {
    public function executeGet(): string {
        try {
            $user = AuthnProvider::getSignedInUser();
            
            $repo = DeefyRepository::getInstance();
            $playlists = $repo->findPlaylistsForUser((int)$user['id']);

            if (empty($playlists)) {
                return "<h2>Vous n'avez encore aucune playlist.</h2><p><a href='?action=add-playlist'>En créer une ?</a></p>";
            }

            $html = "<h2>Mes playlists (Cliquez pour en faire la playlist active)</h2>";
            $html .= "<ul>";

            foreach ($playlists as $pl) {
                $html .= "<li><a href=\"?action=set-playlist&id={$pl->id}\">{$pl->nom} (ID: {$pl->id})</a></li>";
            }

            $html .= "</ul>";
            return $html;

        } catch (AuthnException $e) {
            return "<p style='color:red;'>Erreur : Vous devez être connecté pour voir vos playlists. <a href='?action=signin'>Se connecter</a></p>";
        } catch (\Exception $e) {
            return "<p>Erreur lors de la récupération des playlists : " . $e->getMessage() . "</p>";
        }
    }

    public function executePost(): string  {
        return $this->executeGet();
    }
}