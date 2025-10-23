<?php

namespace iutnc\deefy\action;
use iutnc\deefy\action\Action;
use iutnc\deefy\auth\AuthnProvider;

class InscriptionAction extends Action {
    public function execute() : string {
        if ($this->http_method === 'GET') {
            return <<<FIN
            <div>
                <form method="POST" action="main.php?action=inscription">
                    <label for="username">Nom d'utilisateur :</label>
                    <input type="text" id="username" name="username" required>
                    <br>
                    <label for="password">Mot de passe :</label>
                    <input type="password" id="password" name="password" required>
                    <br>
                    <input type="submit" value="S'inscrire">
                </form>
        </div>
        FIN;
        } else {
            $mail = $_POST['username']; //pas besoin de filtrer

            if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                AuthnProvider::register($mail, $_POST['password']);
                
            } else {
                return "Échec de l'inscription. Veuillez vérifier vos informations d'identification.";
            }
            return "Inscription réussi !";
        }
    }
}