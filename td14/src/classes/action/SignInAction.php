<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

class SignInAction extends Action {

    public function executeGet(): string {
        $html = '<h2>Connexion</h2>';
        $html .= '<form method="post" action="?action=signin">';

        $html .= '<br><label for="email">Email :</label>';
        $html .= '<input type="email" id="email" name="email" required>';

        $html .= '<br><label for="passwd">Mot de passe :</label>';
        $html .= '<input type="password" id="passwd" name="passwd" required>';

        $html .= '<br><button type="submit">Se connecter</button>';
        $html .= '</form>';

        return $html;
    }

    public function executePost(): string {
        try {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $pass = $_POST['passwd'];

            AuthnProvider::signin($email, $pass);

            $html = "<h2>Connexion réussie</h2>";
            $html .= "<p>Bienvenue, $email !</p>";
            return $html;

        } catch (AuthnException $e) {
            $html = $this->executeGet();
            $html .= "<p>Échec de la connexion : " . $e->getMessage() . "</p>";
            return $html;
        }
    }
}