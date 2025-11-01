<?php

namespace iutnc\deefy\repository;

use iutnc\deefy\audio\tracks\AudioTrack;
use \PDO;
use iutnc\deefy\audio\lists\PlayList;
use iutnc\deefy\audio\tracks\AlbumTrack;
use iutnc\deefy\audio\tracks\PodcastTrack;

class DeefyRepository {

    private static ?array $config = null;
    private static ?self $instance = null;
    private PDO $db;

    public static function setConfig(string $file): void {
        self::$config = parse_ini_file($file);
    }

    public static function getInstance(): self {
        if (is_null(self::$instance)) {
            self::$instance = new DeefyRepository();
        }
        return self::$instance;
    }

    private function __construct() {
        if (is_null(self::$config)) {
            throw new \Exception("Configuration de la base de données non chargée. Appelez setConfig() d'abord.");
        }

        $dsn = "mysql:host=" . self::$config['host'] . ";dbname=" . self::$config['db'] . ";charset=utf8";

        try {
            $this->db = new PDO($dsn, self::$config['user'], self::$config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        } catch (\PDOException $e) {
            print $e->getMessage();
        }
    }

    public function getPlaylists(): array {
        $stmt = $this->db->query("SELECT id, nom FROM playlist");
        $playlists = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pl = new Playlist($row['nom']);
            $pl->setId($row['id']);
            $playlists[] = $pl;
        }
        return $playlists;
    }

    public function savePlaylist(Playlist $playlist): Playlist {
        $stmt = $this->db->prepare("INSERT INTO playlist (nom) VALUES (:nom)");
        $stmt->execute(['nom' => $playlist->nom]);
        $playlist->setId($this->db->lastInsertId());
        return $playlist;
    }

    public function saveTrack(AudioTrack $track): AudioTrack {
        if ($track instanceof AlbumTrack) {
            $sql = "INSERT INTO track (titre, genre, duree, filename, type, artiste_album, titre_album, annee_album, numero_album) VALUES (?, ?, ?, ?, 'A', ?, ?, ?, ?)";
            $params = [
                $track->titre,
                $track->genre,
                $track->duree,
                $track->nomFichier,
                $track->artiste,
                $track->album,
                $track->annee,
                $track->numPiste
            ];
        } elseif ($track instanceof PodcastTrack) {
            $sql = "INSERT INTO track (titre, genre, duree, filename, type, auteur_podcast, date_posdcast) VALUES (?, ?, ?, ?, 'P', ?, ?)";
            $params = [
                $track->titre,
                $track->genre,
                $track->duree,
                $track->nomFichier,
                $track->auteur,
                $track->date
            ];
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $track->setId($this->db->lastInsertId());
        return $track;
    }

    public function addTrackToPlaylist(int $playlistId, int $trackId) : void{
        $stmtNum = $this->db->prepare("SELECT MAX(no_piste_dans_liste) as max_num FROM playlist2track WHERE id_pl = ?");
        $stmtNum->execute([$playlistId]);
        $result = $stmtNum->fetch(PDO::FETCH_ASSOC);

        $nextNum = ($result['max_num'] ?? 0) + 1;

        $stmtInsert = $this->db->prepare("INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste) VALUES (?, ?, ?)");
        $stmtInsert->execute([
            $playlistId,
            $trackId,
            $nextNum
        ]);
    }

    public function findUserByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM User WHERE email = ?");
        $stmt->execute([$email]);

        $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($resultat == false) ? null : $resultat;
    }

    public function saveUser(string $email, string $hash): bool {
        $stmt = $this->db->prepare("INSERT INTO User (email, passwd, role) VALUES (?, ?, ?)");
        return $stmt->execute([$email, $hash, 1]);
    }
}