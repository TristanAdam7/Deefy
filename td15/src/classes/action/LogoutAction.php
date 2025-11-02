<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

class LogoutAction extends Action {
    public function executeGet(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['user'])) {
            unset($_SESSION['user']);
            unset($_SESSION['playlist']);
            $html = '<p>Vous avez été déconnecté avec succès !</p>';
        } else {
            $html = '<p>Vous n\'êtes pas connecté ! <a href="?action=signin">Connectez vous</a></p>';
        }

        return $html;
    }

    public function executePost(): string {
        return $this->executeGet();
    }
}