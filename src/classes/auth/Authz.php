<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\exception\AuthnException; 

class Authz {
    public const ADMIN_ROLE = 100;

    public static function checkRole(int $expectedRole): void {
        $user = AuthnProvider::getSignedInUser();

        if ($user['role'] < $expectedRole) {
            throw new AuthnException("Accès refusé : privilèges insuffisants.");
        }
    }

    public static function checkPlaylistOwner(int $playlistId): void {
        $user = AuthnProvider::getSignedInUser();

        if ($user['role'] === self::ADMIN_ROLE) {
            return;
        }

        $repo = DeefyRepository::getInstance();
        $isOwner = $repo->isPlaylistOwner($playlistId, (int)$user['id']);

        if (!$isOwner) {
            throw new AuthnException("Accès refusé : vous n'êtes pas le propriétaire de cette playlist.");
        }
    }
}