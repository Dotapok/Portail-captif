<?php
/**
 * VERSION DE DÉMONSTRATION (MOCK) - POUR DÉPLOIEMENT CLOUD
 * Ce fichier simule les interactions réseau pour une démo sur Railway.
 */

function autoriser_appareil($ip, $mac, $minutes) {
    // SIMULATION : On fait juste semblant que le routeur travaille en ajoutant un petit délai
    usleep(500000); // Pause de 0.5 seconde pour l'effet "traitement réseau"
    return true; 
}

function bloquer_appareil($ip, $mac) {
    // SIMULATION : On fait semblant de bloquer
    return true;
}

// Fonction utilitaire pour générer une FAUSSE adresse MAC réaliste dans le Cloud
function obtenir_mac_depuis_ip($ip) {
    // Dans le cloud, on ne peut pas lire la vraie MAC. 
    // On crée donc un hash de l'IP pour générer une MAC fictive mais constante pour cet utilisateur.
    $hash = md5($ip . "cle_secrete_demo");
    $fake_mac = sprintf('%02s:%02s:%02s:%02s:%02s:%02s',
        substr($hash, 0, 2),
        substr($hash, 2, 2),
        substr($hash, 4, 2),
        substr($hash, 6, 2),
        substr($hash, 8, 2),
        substr($hash, 10, 2)
    );
    return strtolower($fake_mac);
}
?>