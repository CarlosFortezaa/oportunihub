<?php include APP_ROOT . "/views/templates/header.php"; ?>

<h2>Mi Perfil</h2>

<!--si hay exito  envia mensaje--->
<?php if(!empty($_SESSION['success'])){ ?>
    <div class="success">
        <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php } else  if (!empty($errores)){ ?>
    <div class="errors">
        <ul>
            <?php foreach ($errores as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php } ?>
<!---Muestra form para editar y envia cambios a la funcion update profile-->
<form method="post" action="<?= BASE_URL ?>/index.php?action=update_profile">

    <label>User ID (no editable):</label>
    <input type="text" value="<?= htmlspecialchars($user->getUserId()) ?>" disabled>

    <br><br>

    <label>Email:</label>
    <input type="email" name="email" 
           value="<?= htmlspecialchars($user->getEmail()) ?>" required>

    <br><br>

    <label>Nueva Contraseña (opcional):</label>
    <input type="password" name="password">
    <small>Dejar en blanco para mantener la misma contraseña</small>

    <br><br>

    <button class="submit" type="submit">Actualizar Perfil</button>
</form>

<?php include APP_ROOT . "/views/templates/footer.php"; ?>
