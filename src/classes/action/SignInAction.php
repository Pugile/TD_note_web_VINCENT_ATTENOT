<?php

use iutnc\deefy\action\Action;

class SignInAction extends Action {
    public function execute() : string {
        if ($this->http_method === 'POST') {
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
                
            } else {
                return "Échec de la connexion. Veuillez vérifier vos informations d'identification.";
            }
        }
    }
}