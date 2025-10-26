<?php

namespace iutnc\deefy\action;
use iutnc\deefy\action\Action;
use iutnc\deefy\auth\AuthnProvider;
use iutnc\deefy\exception\AuthException;

class SignInAction extends Action {
    /**
     * @throws AuthException
     */
    public function execute() : string {

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($this->http_method === 'GET') {
            return <<<FIN
            <div>
                <form method="POST" action="main.php?action=signin">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" id="username" name="username" required>
                    <br>
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                    <br>
                    <input type="submit" value="Se connecter">
                </form>
        </div>
        FIN;
        } else {
            $mail = $_POST['username']; //pas besoin de filtrer

            if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                AuthnProvider::signin($mail, $_POST['password']);

            } else {
                return "Échec de la connexion. Veuillez vérifier vos informations d'identification.";
            }
            $_SESSION['user'] = $mail;
            return "Connexion réussie !";
        }
    }
}