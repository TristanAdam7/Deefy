<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\AddAlbumTrackAction;
use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcastTrackAction;
use iutnc\deefy\action\DefaultAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\SignInAction;
use iutnc\deefy\action\DisplayPlaylistByIdAction;
use iutnc\deefy\action\MesPlaylistsAction;
use iutnc\deefy\action\SetPlaylistAction;
use iutnc\deefy\action\LogoutAction;

class Dispatcher {

    private string $action;

    public function __construct() {
        $this->action = isset($_GET["action"]) ? $_GET["action"] : "default";
    }

    public function run(): void {
        switch ($this->action) {
            case 'add-playlist':
                $actionMethode = new AddPlaylistAction();
                break;

            case 'add-Podcasttrack':
                $actionMethode = new AddPodcastTrackAction();
                break;

            case 'add-Albumtrack':
                $actionMethode = new AddAlbumTrackAction();
                break;

            case 'playlist':
                $actionMethode = new DisplayPlaylistAction();
                break;

            case 'add-user':
                $actionMethode = new AddUserAction();
                break;

            case 'signin':
                $actionMethode = new SigninAction();
                break;

            case 'logout':
                $actionMethode = new LogoutAction();
                break;

            case 'mes-playlists':
                $actionMethode = new MesPlaylistsAction();
                break;

            case 'set-playlist':
                $actionMethode = new SetPlaylistAction();
                break;

            case 'display-playlist-by-id':
                $actionMethode = new DisplayPlaylistByIdAction();
                break;

            default:
                $actionMethode = new DefaultAction();
                break;
        }

        $this->renderPage($actionMethode());
    }

    private function renderPage(string $html): void {
        $affichage = <<<HTML
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <title>DeefyApp</title>
            <style>
                body { font-family: sans-serif; }
                h1 { text-align: center; }
                nav { background-color: #f2f2f2; padding: 10px; margin-bottom: 20px; text-align: center; }
                nav a { margin-right: 15px; text-decoration: none; color: #333; }
            </style>
        </head>
        <body>
            <header>
                <h1>DeefyApp</h1>
                <nav>
                    <a href="?action=default">Accueil</a>
                    <a href="?action=add-playlist">Créer une playlist</a>
                    <a href="?action=add-Podcasttrack">Ajouter un podcast</a>
                    <a href="?action=add-Albumtrack">Ajouter une piste d'album</a>
                    <a href="?action=playlist">Afficher la Playlist en Session</a>
                    <a href="?action=display-playlist-by-id">Voir une playlist (par ID)</a>
                    <a href="?action=mes-playlists">Voir mes Playlists</a>
                    <a href="?action=add-user">Inscription</a>
                    <a href="?action=signin">Se connecter</a>
                    <a href="?action=logout">Se déconnecter</a>
                </nav>
            </header>
            <main>
                $html
            </main>
        </body>
        </html>
        HTML;

        echo $affichage;
    }
}