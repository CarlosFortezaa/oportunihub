<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>OportuniHub</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css">
</head>
<body>

<header>
   <a href="<?= BASE_URL ?>/index.php?action=opportunities_list"><img class="logo" src="<?= BASE_URL ?>/images/logo.png" alt="OportuniHub Logo"></a>

    <nav style="float:left; margin-left:40px;">
        <a href="<?= BASE_URL ?>/index.php?action=opportunities_list" class="<?= ($action === 'opportunities_list') ? 'active' : '' ?>">Oportunidades</a>
        <a href="<?= BASE_URL ?>/index.php?action=subscriptions_list"class="<?= ($action === 'subscriptions_list') ? 'active' : '' ?>">Lista de E-mails</a>

        <!-- el ciclo if verifica si hay session y si el rol es admin parar mostrar diferentes encabezados -->
        <?php if(!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') { ?>
            <a href="<?= BASE_URL ?>/index.php?action=register" class="<?= ($action === 'register') ? 'active' : '' ?>">Crear Cuentas</a>
            <a href="<?= BASE_URL ?>/index.php?action=manage_users" class="<?= ($action === 'manage_users') ? 'active' : '' ?>">Manejar Cuentas</a>
       <?php } ?>


        
 <?php if(!empty($_SESSION['user'])): ?>
            <a href="<?= BASE_URL ?>/index.php?action=profile"  class="<?= ($action === 'profile') ? 'active' : '' ?>">Perfil</a>
        <?php endif; ?>
    </nav>

    <div class="nav-right">
        <?php if (!empty($_SESSION['user'])): ?>
        
            Hola, <?= htmlspecialchars($_SESSION['user']['id']) ?>
            |
            <a class="logout-link" href="<?= BASE_URL ?>/index.php?action=logout">Logout</a>
        <?php else: ?>
            <a class="login-link" href="<?= BASE_URL ?>/index.php?action=login">Login</a>
        <?php endif; ?>
    </div>
</header>

<main>
