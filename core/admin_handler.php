<?php
session_start();
require_once 'router_manager.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    
    // Action déclenchée par le bouton rouge "Se déconnecter"
    if ($_POST['action'] === 'disconnect') {
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $mac_address = $_SESSION['user_mac'] ?? obtenir_mac_depuis_ip($ip_address);

        // 1. Couper VRAIMENT l'accès internet sur le routeur
        bloquer_appareil($ip_address, $mac_address);

        // 2. Historisation
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $log_entry = "$date,$time,$ip_address,$mac_address,Deconnexion_Manuelle\n";
        file_put_contents('../logs/connections.csv', $log_entry, FILE_APPEND);

        // 3. Fermer la session web
        session_destroy();
        header("Location: ../public/index.html");
        exit();
    }
}

// Action déclenchée par l'administrateur depuis le dashboard
if ($_POST['action'] === 'kick' && !empty($_SESSION['admin_logged_in'])) {
    $ip_cible = $_POST['ip_cible'];
    $mac_cible = $_POST['mac_cible'];
    
    bloquer_appareil($ip_cible, $mac_cible);
    
    $log_entry = date('Y-m-d') . "," . date('H:i:s') . ",$ip_cible,$mac_cible,Deconnexion_Forcee_Admin\n";
    file_put_contents('../logs/connections.csv', $log_entry, FILE_APPEND);
    
    header("Location: ../admin/dashboard.php");
    exit();
}
?>