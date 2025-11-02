<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class AddPodcastTrackAction extends Action {

    public function executeGet(): string {
        session_start();

        if (isset($_SESSION['playlist'])) {
            $html = '<h2>Ajouter une nouvelle piste à la playlist</h2>';
            $html .= '<form method="post" action="?action=add-track">';
            $html .= '<label for="titre">Titre :</label>';
            $html .= '<input type="text" name="titre" required>';

            $html .= '<br><label for="auteur">Auteur :</label>';
            $html .= '<input type="text" name="auteur" required>';

            $html .= '<br><label for="date">Date de sortie :</label>';
            $html .= '<input type="date" name="date" required>';

            $html .= '<br><label for="fichier">Fichier audio (MP3) :</label>';
            $html .= '<input type="file" name="fichier" required>';

            $html .= '<br><button type="submit">Ajouter la piste</button>';
            $html .= '</form>';

            return $html;
        } else {
            return '<p>Veuillez d\'abord créer une playlist.</p><a href="?action=add-playlist">Créer une playlist</a>';
        }
    }

    public function executePost(): string {
        session_start();
        $file = $_FILES['fichier'];

        if ($file['error'] != 0){
            return "<p>Erreur lors du transfert du fichier. Code d'erreur : {$file['error']}</p><a href='?action=add-track'>Réessayer</a>";
        }

        if ($file['type'] != "audio/mpeg" || substr($file['name'],-4) != '.mp3'){
           return "<p>Type de fichier non autorisé. Seuls les fichiers MP3 sont acceptés.</p><a href='?action=add-track'>Réessayer</a>";
        }

        $dest = "audio/" . uniqid('', true) . ".mp3";
        if (move_uploaded_file($file['tmp_name'], $dest)){
            $playlist = $_SESSION['playlist'];

            $titre = filter_var($_POST['titre'], FILTER_SANITIZE_SPECIAL_CHARS);
            $auteur = filter_var($_POST['auteur'], FILTER_SANITIZE_SPECIAL_CHARS);
            $date = filter_var($_POST['date'], FILTER_SANITIZE_SPECIAL_CHARS);

            $track = new PodcastTrack($titre, $dest);

            $track->setAuteur($auteur);
            $track->setDate($date);

            $playlist->ajouterPiste($track);
            $_SESSION['playlist'] = $playlist;

            $repo = DeefyRepository::getInstance();
            $repo->saveTrack($track);
            $repo->addTrackToPlaylist($playlist->id, $track->id);

            $renderer = new AudioListRenderer($playlist);
            $playlistHtml = $renderer->render();

            $html = "<h2>Piste ajoutée avec succès !</h2>";
            $html .= "<h3>Voici votre playlist mise à jour :</h3>";
            $html .= $playlistHtml;

            $html .= '<a href="?action=add-track">Ajouter encore une piste</a>';
            return $html;
        } else {
            return "<p>Hum, une erreur est survenue lors de la sauvegarde du fichier.</p><a href='?action=add-track'>Réessayer</a>";
        }
    }
}