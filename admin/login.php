<?php
session_start();
$erreur = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $config = parse_ini_file('../config/system_config.ini');
    if ($_POST['admin_pwd'] === $config['MOT_DE_PASSE_ADMIN']) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit();
    } else {
        $erreur = "Mot de passe administrateur incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Portail</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Administration</h1>
        <?php if($erreur) echo "<p style='color:red;'>$erreur</p>"; ?>
        <form action="login.php" method="POST">
            <div class="input-group">
                <input type="password" name="admin_pwd" required placeholder="Mot de passe Admin">
            </div>
            <button type="submit" class="btn">Accéder</button>
        </form>
    </div>
</body>
</html>