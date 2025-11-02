<?php

namespace iutnc\deefy\action;

use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\render\AudioListRenderer;
use iutnc\deefy\repository\DeefyRepository;

class AddAlbumTrackAction extends Action {

    public function executeGet(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['playlist'])) {
            $html = '<h2>Ajouter une nouvelle piste d\'album à la playlist</h2>';
            $html .= '<form method="post" action="?action=add-Albumtrack" enctype="multipart/form-data">';
            $html .= '<label for="titreP">Titre de la piste :</label>';
            $html .= '<input type="text" name="titreP" required>';

            $html .= '<br><label for="titreA">Titre de l\'album :</label>';
            $html .= '<input type="text" name="titreA" required>';

            $html .= '<br><label for="num">Numéro dans l\'album :</label>';
            $html .= '<input type="number" name="num" required>';

            $html .= '<br><label for="artiste">Artiste :</label>';
            $html .= '<input type="text" name="artiste" required>';

            $html .= '<br><label for="genre">Genre :</label>';
            $html .= '<input type="text" name="genre" required>';

            $html .= '<br><label for="duree">Durée :</label>';
            $html .= '<input type="number" name="duree" required>';

            $html .= '<br><label for="annee">Annee de sortie :</label>';
            $html .= '<input type="number" name="annee" required>';

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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $file = $_FILES['fichier'];

        if ($file['error'] != 0){
            return "<p>Erreur lors du transfert du fichier. Code d'erreur : {$file['error']}</p><a href='?action=add-Albumtrack'>Réessayer</a>";
        }

        if ($file['type'] != "audio/mpeg" || substr($file['name'],-4) != '.mp3'){
           return "<p>Type de fichier non autorisé. Seuls les fichiers MP3 sont acceptés.</p><a href='?action=add-Albumtrack'>Réessayer</a>";
        }

        $dest = "audio/" . uniqid('', true) . ".mp3";
        if (move_uploaded_file($file['tmp_name'], $dest)){
            $playlist = $_SESSION['playlist'];

            $titreP = filter_var($_POST['titreP'], FILTER_SANITIZE_SPECIAL_CHARS);
            $titreA = filter_var($_POST['titreA'], FILTER_SANITIZE_SPECIAL_CHARS);
            $num = filter_var($_POST['num'], FILTER_SANITIZE_NUMBER_INT);
            $artiste = filter_var($_POST['artiste'], FILTER_SANITIZE_SPECIAL_CHARS);
            $genre = filter_var($_POST['genre'], FILTER_SANITIZE_SPECIAL_CHARS);
            $duree = filter_var($_POST['duree'], FILTER_SANITIZE_NUMBER_INT);
            $annee = filter_var($_POST['annee'], FILTER_SANITIZE_NUMBER_INT);

            $track = new AlbumTrack($titreP, $dest, $titreA, $num);
            $track->setGenre($genre);
            $track->setDuree($duree);
            $track->setArtiste($artiste);
            $track->setAnnee($annee);

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

            $html .= '<a href="?action=add-Albumtrack">Ajouter encore une piste</a>';
            return $html;
        } else {
            return "<p>Hum, une erreur est survenue lors de la sauvegarde du fichier.</p><a href='?action=add-Albumtrack'>Réessayer</a>";
        }
    }
}