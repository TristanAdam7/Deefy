<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class AddPodcastTrackAction extends Action {

    public function executeGet(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['playlist'])) {
            $html = '<h2>Ajouter un nouveau podcast à la playlist</h2>';
            $html .= '<form method="post" action="?action=add-Podcasttrack" enctype="multipart/form-data">';
            $html .= '<label for="titre">Titre :</label>';
            $html .= '<input type="text" name="titre" required>';

            $html .= '<br><label for="auteur">Auteur :</label>';
            $html .= '<input type="text" name="auteur" required>';

            $html .= '<br><label for="genre">Genre :</label>';
            $html .= '<input type="text" name="genre" required>';

            $html .= '<br><label for="duree">Durée :</label>';
            $html .= '<input type="number" name="duree" required>';

            $html .= '<br><label for="date">Date de sortie :</label>';
            $html .= '<input type="date" name="date" required>';

            $html .= '<br><label for="fichier">Fichier audio (MP3) :</label>';
            $html .= '<input type="file" name="fichier" required>';

            $html .= '<br><button type="submit">Ajouter le podcast</button>';
            $html .= '</form>';

            return $html;
        } else {
            $html = '<h2>Ajouter un nouveau podcast à la playlist</h2>';
            $html .= '<p>Veuillez d\'abord créer ou selectionner une playlist.</p>';
            $html .= '<a href="?action=add-playlist" class="cliquable">Créer une playlist</a><br>';
            $html .= '<a href="?action=mes-playlists" class="cliquable">Voir mes playlists</a>';
            return $html;
        }
    }

    public function executePost(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $file = $_FILES['fichier'];

        if ($file['error'] != 0){
            return "<p>Erreur lors du transfert du fichier. Code d'erreur : {$file['error']}</p><a href='?action=add-Podcasttrack' class='cliquable'>Réessayer</a>";
        }

        if ($file['type'] != "audio/mpeg" || substr($file['name'],-4) != '.mp3'){
           return "<p>Type de fichier non autorisé. Seuls les fichiers MP3 sont acceptés.</p><a href='?action=add-Podcasttrack' class='cliquable'>Réessayer</a>";
        }

        $dest = "audio/" . uniqid('', true) . ".mp3";
        if (move_uploaded_file($file['tmp_name'], $dest)){
            $playlist = $_SESSION['playlist'];

            $titre = filter_var($_POST['titre'], FILTER_SANITIZE_SPECIAL_CHARS);
            $auteur = filter_var($_POST['auteur'], FILTER_SANITIZE_SPECIAL_CHARS);
            $genre = filter_var($_POST['genre'], FILTER_SANITIZE_SPECIAL_CHARS);
            $duree = filter_var($_POST['duree'], FILTER_SANITIZE_NUMBER_INT);
            $date = filter_var($_POST['date'], FILTER_SANITIZE_SPECIAL_CHARS);

            $track = new PodcastTrack($titre, $dest);
            $track->setGenre($genre);
            $track->setDuree($duree);
            $track->setAuteur($auteur);
            $track->setDate($date);

            $playlist->ajouterPiste($track);
            $_SESSION['playlist'] = $playlist;

            $repo = DeefyRepository::getInstance();
            $repo->saveTrack($track);
            $repo->addTrackToPlaylist($playlist->id, $track->id);

            $renderer = new AudioListRenderer($playlist);
            $playlistHtml = $renderer->render();

            $html = "<h2>Podcast ajouté avec succès !</h2>";
            $html .= "<h3>Voici votre playlist mise à jour :</h3>";
            $html .= $playlistHtml;

            $html .= '<a href="?action=add-Podcasttrack" class="cliquable">Ajouter encore un podcast</a>';
            return $html;
        } else {
            return "<p>Hum, une erreur est survenue lors de la sauvegarde du fichier.</p><a href='?action=add-Podcasttrack' class='cliquable'>Réessayer</a>";
        }
    }
}