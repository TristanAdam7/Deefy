<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

class AddUserAction extends Action {

    public function executeGet(): string {
        $html = '<h2>Inscription</h2>';
        $html .= '<form method="post" action="?action=add-user">';

        $html .= '<br><label for="email">Email :</label>';
        $html .= '<input type="email" id="email" name="email" required>';

        $html .= '<br><label for="pass1">Mot de passe (10 caractères min) :</label>';
        $html .= '<input type="password" id="pass1" name="pass1" required>';

        $html .= '<br><label for="pass2">Confirmer le mot de passe :</label>';
        $html .= '<input type="password" id="pass2" name="pass2" required>';

        $html .= '<br><button type="submit">S\'inscrire</button>';
        $html .= '</form>';

        return $html;
    }

    public function executePost(): string {
        try {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $pass1 = $_POST['pass1'];
            $pass2 = $_POST['pass2'];

            if ($pass1 !== $pass2) {
                throw new AuthnException("Les mots de passe ne correspondent pas.");
            }

            AuthnProvider::register($email, $pass1);

            $html = "<h2>Inscription réussie</h2>";
            $html .= "<p>Votre compte pour '$email' a bien été créé.</p>";
            $html .= '<a href="?action=signin" class="cliquable">Se connecter</a>';

            return $html;

        } catch (AuthnException $e) {
            $html = $this->executeGet();
            $html .= "<p class='error'>Échec de l'inscription : " . $e->getMessage() . "</p>";

            return $html;
        }
    }
}