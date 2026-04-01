<?php
session_start();
if (empty($_SESSION['admin_logged_in'])) { header("Location: login.php"); exit(); }

$config_path = '../config/system_config.ini';
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $config = parse_ini_file($config_path, true); // true = garde les sections [WIFI], [ADMIN]...
    
    // Mettre à jour les variables
    $config['WIFI']['MOT_DE_PASSE_WIFI'] = $_POST['wifi_pwd'];
    $config['WIFI']['DUREE_SESSION_MINUTES'] = (int)$_POST['session_time'];
    
    // Réécrire le fichier INI
    $ini_content = "";
    foreach ($config as $section => $values) {
        $ini_content .= "[$section]\n";
        foreach ($values as $key => $val) {
            $val_str = is_numeric($val) || is_bool($val) ? (int)$val : "\"$val\"";
            $ini_content .= "$key = $val_str\n";
        }
        $ini_content .= "\n";
    }
    file_put_contents($config_path, $ini_content);

    // Gérer l'upload du logo
    if (!empty($_FILES['logo_upload']['tmp_name'])) {
        move_uploaded_file($_FILES['logo_upload']['tmp_name'], '../assets/img/logo.png');
    }
    
    $msg = "Paramètres mis à jour avec succès !";
}

$current_config = parse_ini_file($config_path);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Paramètres du Portail</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container" style="max-width: 500px; text-align: left;">
        <h1 style="text-align: center;">Paramètres Généraux</h1>
        <?php if($msg) echo "<p style='color:green; text-align:center;'>$msg</p>"; ?>
        
        <form action="settings.php" method="POST" enctype="multipart/form-data">
            <div style="margin-bottom: 15px;">
                <label><strong>Mot de passe du Wi-Fi (Invités) :</strong></label>
                <input type="text" name="wifi_pwd" value="<?php echo htmlspecialchars($current_config['MOT_DE_PASSE_WIFI']); ?>" required>
            </div>
            <div style="margin-bottom: 15px;">
                <label><strong>Durée de session (en minutes) :</strong></label>
                <input type="number" name="session_time" value="<?php echo (int)$current_config['DUREE_SESSION_MINUTES']; ?>" required>
            </div>
            <div style="margin-bottom: 25px;">
                <label><strong>Changer le logo (PNG) :</strong></label><br>
                <input type="file" name="logo_upload" accept=".png" style="margin-top: 10px;">
            </div>
            <button type="submit" class="btn">Sauvegarder les modifications</button>
        </form>
        <div style="text-align: center; margin-top: 20px;">
            <a href="dashboard.php" style="color: var(--primary-color); text-decoration: none;">Retour au tableau de bord</a>
        </div>
    </div>
</body>
</html>