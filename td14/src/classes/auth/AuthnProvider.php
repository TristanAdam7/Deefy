<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\repository\DeefyRepository;
use iutnc\deefy\exception\AuthnException;
use \PDO;

class AuthnProvider {

    public static function signin(string $email, string $passwd_clair): void {
        $repo = DeefyRepository::getInstance();
        $user = $repo->findUserByEmail($email);

        if ($user === null) {
            throw new AuthnException("Utilisateur inconnu");
        }

        if (!password_verify($passwd_clair, $user['passwd'])) {
            throw new AuthnException("Mot de passe invalide");
        }

        session_start();
        unset($user['passwd']);
        $_SESSION['user'] = $user;
    }

    public static function register(string $email, string $pass): void {
        $repo = DeefyRepository::getInstance();

        if (strlen($pass) < 10) {
            throw new AuthnException("Le mot de passe est trop court (10 caractères minimum).");
        }

        if ($repo->findUserByEmail($email) !== null) {
            throw new AuthnException("Un compte existe déjà avec cet email.");
        }

        $hash = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 12]);
        $repo->saveUser($email, $hash);
    }
}