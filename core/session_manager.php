<?php
require_once 'router_manager.php';
$config = parse_ini_file('../config/system_config.ini');
$limite_minutes = (int)$config['DUREE_SESSION_MINUTES'];

echo "=== Nettoyage des sessions (Limite : $limite_minutes min) ===\n";

// 1. Reconstruire l'état actuel à partir du fichier CSV
$active_sessions = [];
if (($handle = fopen("../logs/connections.csv", "r")) !== FALSE) {
    fgetcsv($handle); // Ignorer l'en-tête
    while (($data = fgetcsv($handle)) !== FALSE) {
        if (count($data) >= 5) {
            $ip = $data[2];
            $mac = $data[3];
            $event = $data[4];
            
            if ($event === 'Connexion') {
                $active_sessions[$mac] = ['ip' => $ip, 'date' => $data[0], 'time' => $data[1]];
            } elseif (strpos($event, 'Deconnexion') !== false) {
                unset($active_sessions[$mac]); // Il s'est déconnecté
            }
        }
    }
    fclose($handle);
}

// 2. Vérifier chaque session active
$now = new DateTime();
foreach ($active_sessions as $mac => $info) {
    $conn_time = new DateTime($info['date'] . ' ' . $info['time']);
    $diff = $now->diff($conn_time);
    $minutes_passees = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;

    if ($minutes_passees >= $limite_minutes) {
        echo "Expiré : $mac ($minutes_passees min). Blocage...\n";
        
        // Bloquer via le routeur
        bloquer_appareil($info['ip'], $mac);
        
        // Tracer dans les logs
        $log_entry = date('Y-m-d') . "," . date('H:i:s') . ",{$info['ip']},$mac,Deconnexion_Auto_TempsEcoule\n";
        file_put_contents('../logs/connections.csv', $log_entry, FILE_APPEND);
    }
}
echo "Terminé.\n";
?>