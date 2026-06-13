<?php include APP_ROOT . "/views/templates/header.php"; ?>

<h2>Login</h2>

<?php if (!empty($_SESSION['success'])) { ?>
    <div class="success">
        <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php } else if (!empty($errores)) { ?>
        <div class="errors">
            <ul>
            <?php foreach ($errores as $e): ?>
                    <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
            </ul>
        </div>
<?php } ?>
<div class="login_form_div">
    <!--post method  envia  la data a index-->
    <form class="login_form" method="post" action="<?= BASE_URL ?>/index.php?action=login">

        <label>User ID:</label>
        <input type="text" name="user_id" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button class="submit" type="submit">Login</button>
    </form>
</div>
<!-- muestra ver la lista de oportunidades-->
<a href="<?= BASE_URL ?>/index.php?action=opportunities_list">Ver Oportunidades</a>

<?php include APP_ROOT . "/views/templates/footer.php"; ?>