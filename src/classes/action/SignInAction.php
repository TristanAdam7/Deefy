<?php

namespace iutnc\deefy\action;

use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthnException;

class SigninAction extends Action {
    public function executeGet(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user'])) {
            $html = '<h2>Connexion</h2>';
            $html .= '<form method="post" action="?action=signin">';

            $html .= '<br><label for="email">Email :</label>';
            $html .= '<input type="email" id="email" name="email" required>';

            $html .= '<br><label for="passwd">Mot de passe :</label>';
            $html .= '<input type="password" id="passwd" name="passwd" required>';

            $html .= '<br><button type="submit">Se connecter</button>';
            $html .= '</form>';
        } else {
            $html = '<h2>Vous êtes déjà connecté !</h2>';
            $html .= '<p>Vous voulez changez de compte ? <a href="?action=logout">Déconnectez-vous</a></p>';
        }

        return $html;
    }

    public function executePost(): string {
        $html = '';
        try {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $passwd = $_POST['passwd']; 

            if (empty($email) || empty($passwd)) {
                throw new AuthnException("Veuillez remplir tous les champs.");
            }
            
            AuthnProvider::signin($email, $passwd);
            
            $html = "<h2>Connexion réussie. Vous êtes maintenant connecté.</h2>";
            
            $html .= '<p><a href="?action=mes-playlists">Voir vos playlists</a> pour en sélectionner une.</p>';
            
        } catch (AuthnException $e) {
            $html = $this->executeGet();
            $html .= "<p class='error'>Échec de la connexion : " . $e->getMessage() . "</p>";
        }
        
        return $html;
    }
}