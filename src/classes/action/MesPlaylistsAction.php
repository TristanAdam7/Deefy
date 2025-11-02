<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

class MesPlaylistsAction extends Action {
    public function executeGet(): string {
        $html = "<h2>Mes playlists</h2>";
        try {
            $user = AuthnProvider::getSignedInUser();
            
            $repo = DeefyRepository::getInstance();
            $playlists = $repo->findPlaylistsForUser((int)$user['id']);

            if (empty($playlists)) {
                $html .= "<p>Vous n'avez encore aucune playlist. <a href='?action=add-playlist'>En créer une ?</a></p>";
                return $html;
            }

            foreach ($playlists as $pl) {
                $html .= "<p><a href=\"?action=set-playlist&id={$pl->id}\" class='cliquable'>{$pl->nom} (ID: {$pl->id})</a></p>";
            }
            return $html;

        } catch (AuthnException $e) {
            $html .= "<p class='error'>Erreur : Vous devez être connecté pour voir vos playlists. <a href='?action=signin'>Se connecter</a></p>";
            return $html;
        } catch (\Exception $e) {
            $html .= "<p>Erreur lors de la récupération des playlists : " . $e->getMessage() . "</p>";
            return $html;
        }
    }

    public function executePost(): string  {
        return $this->executeGet();
    }
}