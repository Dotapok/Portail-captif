<?php
session_start();
if (empty($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit(); }

// Extraire les sessions actives depuis le CSV (même logique que le session_manager)
$active_sessions = [];
if (($handle = fopen("../logs/connections.csv", "r")) !== FALSE) {
    fgetcsv($handle);
    while (($data = fgetcsv($handle)) !== FALSE) {
        if(count($data) >= 5) {
            if ($data[4] === 'Connexion') $active_sessions[$data[3]] = $data[2];
            elseif (strpos($data[4], 'Deconnexion') !== false) unset($active_sessions[$data[3]]);
        }
    }
    fclose($handle);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h1>Appareils Connectés (<?php echo count($active_sessions); ?>)</h1>
        <table style="width: 100%; text-align: left; margin-bottom: 20px; border-collapse: collapse;">
            <tr style="border-bottom: 2px solid #ddd;">
                <th style="padding: 10px;">IP</th>
                <th style="padding: 10px;">MAC</th>
                <th style="padding: 10px;">Action</th>
            </tr>
            <?php foreach ($active_sessions as $mac => $ip): ?>
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;"><?php echo htmlspecialchars($ip); ?></td>
                <td style="padding: 10px;"><?php echo htmlspecialchars($mac); ?></td>
                <td style="padding: 10px;">
                    <form action="../core/admin_handler.php" method="POST" style="margin:0;">
                        <input type="hidden" name="action" value="kick">
                        <input type="hidden" name="ip_cible" value="<?php echo $ip; ?>">
                        <input type="hidden" name="mac_cible" value="<?php echo $mac; ?>">
                        <button type="submit" class="btn btn-danger" style="padding: 5px 10px;">Éjecter</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($active_sessions)) echo "<tr><td colspan='3' style='padding: 10px;'>Aucun appareil connecté.</td></tr>"; ?>
        </table>
        <a href="settings.php" class="btn" style="text-decoration:none; display:inline-block; margin-bottom: 10px;">Aller aux Paramètres</a>
        <a href="login.php?logout=1" style="color:var(--text-muted); display:block; font-size: 0.9em;">Quitter l'administration</a>
    </div>
</body>
</html>