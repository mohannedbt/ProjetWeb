<?php
class config
{
    private static $pdo = null;

    public static function getConnexion()
    {
        if (!isset(self::$pdo)) {
            $servername = "localhost";
            $username = "root";
            $password = "mynameismohanned";
            $dbname = "projetweb";  // Assurez-vous que la base de données existe

            try {
                self::$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

                // Message pour confirmer que la connexion a réussi
            } catch (Exception $e) {
                // Si une erreur se produit, affiche le message d'erreur
                die('Erreur: ' . $e->getMessage());
            }
        }
        return self::$pdo;
    }
}

// Appel de la fonction de connexion pour tester
?>