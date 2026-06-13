<?php include APP_ROOT . '/views/templates/header.php'; ?>

<h2>Editar Usuario</h2>

<?php if (!empty($errores)){ ?>
    <div class="errors">
        <ul>
            <?php foreach ($errores as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php } ?>
<!--- Formulario para editar post envia los datos a update user  -->
<form action="index.php?action=update_user" method="post">
    
   
     <!-- Campo visible PERO bloqueado -->
       <label>User ID:</label>
        <input type="text" name="user_id"
           value="<?= htmlspecialchars($user->getUserId()) ?>"
           readonly>

   
    

    <label>Email:</label>
    <input type="email" name="email"
           value="<?= htmlspecialchars($user->getEmail()) ?>">

    <label>Rol:</label>
    <select name="role" required>
        <option value="admin" <?= ($user->getRole() === 'admin') ? 'selected' : '' ?>>Admin</option>
        <option value="contributor" <?= ($user->getRole() === 'contributor') ? 'selected' : '' ?>>Contributor</option>
    </select>

    <label>Nueva contraseña (opcional):</label>
    <input type="password" name="password">
    <small>Dejar vacío para mantener la contraseña actual.</small>

    <br>
    <button type="submit" class="submit">Guardar Cambios</button>
</form>

<br>
<a href="index.php?action=manage_users">Volver a la lista de usuarios</a>

<?php include APP_ROOT . '/views/templates/footer.php'; ?>
