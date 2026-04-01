<?php
session_start();
// Sécurité : si l'utilisateur n'est pas connecté, on le renvoie à l'accueil
if (!isset($_SESSION['authenticated'])) {
    header("Location: index.html");
    exit();
}
$mac_address = $_SESSION['user_mac'] ?? 'Inconnue';
$minutes_restantes = isset($_SESSION['expire_time']) ? round(($_SESSION['expire_time'] - time()) / 60) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statut de la connexion</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <img src="../assets/img/logo.png" alt="Logo" class="logo">
        <h1 id="statut_connecte">Vous êtes en ligne ! 🎉</h1>
        <p class="subtitle" id="statut_message">Votre connexion est sécurisée. Vous pouvez naviguer librement.</p>
        
        <div style="background: rgba(0,0,0,0.05); padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: left; font-size: 0.9rem;">
            <p>🌐 <strong>IP :</strong> <?php echo $_SERVER['REMOTE_ADDR']; ?></p>
            <p>💻 <strong>MAC :</strong> <?php echo htmlspecialchars($mac_address); ?></p>
            <p>⏱️ <strong>Temps restant :</strong> <span id="compte_a_rebours"><?php echo $minutes_restantes; ?></span> minutes</p>
        </div>
        
        <form action="../core/admin_handler.php" method="POST">
            <input type="hidden" name="action" value="disconnect">
            <button type="submit" id="bouton_deconnexion" class="btn btn-danger">Me déconnecter</button>
        </form>
    </div>
    <script src="../assets/js/app.js"></script>
    
    <script>
        let tempsRestant = <?php echo $minutes_restantes; ?>;
        const affichage = document.getElementById('compte_a_rebours');

        // Se déclenche toutes les 60 secondes (60000 millisecondes)
        setInterval(() => {
            tempsRestant--; 
            if(tempsRestant >= 0) {
                affichage.innerText = tempsRestant;
            } else {
                // Si le temps tombe à zéro, on recharge la page pour forcer la déconnexion
                window.location.reload(); 
            }
        }, 60000);
    </script>
</body>
</html>