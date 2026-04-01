<?php
session_start();
require_once 'router_manager.php'; // On importe notre super traducteur

$config = parse_ini_file('../config/system_config.ini');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_password = $_POST['password'];

    if ($input_password === $config['MOT_DE_PASSE_WIFI']) {
        
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $mac_address = obtenir_mac_depuis_ip($ip_address); // Vraie adresse MAC
        $minutes = (int)$config['DUREE_SESSION_MINUTES'];
        
        // 1. Appel du VRAI code de production (Universel)
        autoriser_appareil($ip_address, $mac_address, $minutes);

        // 2. Enregistrer dans les logs (Format propre)
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $log_entry = "$date,$time,$ip_address,$mac_address,Connexion\n";
        file_put_contents('../logs/connections.csv', $log_entry, FILE_APPEND);

        // 3. Validation de session PHP et redirection
        $_SESSION['authenticated'] = true;
        $_SESSION['user_mac'] = $mac_address;
        $_SESSION['expire_time'] = time() + ($minutes * 60);
        
        header("Location: ../public/status.php");
        exit();

    } else {
        // Redirection avec erreur silencieuse
        header("Location: ../public/index.html?error=password");
        exit();
    }
}
?>