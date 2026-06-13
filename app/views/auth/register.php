<?php include APP_ROOT . "/views/templates/header.php"; ?>
<h2>Crear cuenta nueva</h2>

<?php if(!empty($_SESSION['success'])){ ?>
    <div class="success">
        <?= htmlspecialchars($_SESSION['success']) ?>
    </div>
<?php } ?>
<?php if (!empty($errores)){ ?>
    <div class="errors">
        <ul>
            <?php foreach ($errores as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php } ?>
<!-- metodo post envia la data a la accion register-->
<form method="post" action="<?= BASE_URL ?>/index.php?action=register">

    <label for="user_id">User ID:</label>
    <input type="text" id="user_id" name="user_id" placeholder="ej: juan23"
    value="<?= htmlspecialchars($_POST['user_id'] ?? '') ?>">

    <br><br>

    <label for="email">Correo electrónico:</label>
    <input type="email" id="email" name="email" placeholder="ej: juan@upr.edu" 
    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" >

    <br><br>

    <label for="password">Contraseña:</label>
    <input type="password" id="password" name="password">

    <br><br>
    
    <label for="role">rol:</label>
    <select name="role" id="role" required>
        <option value="contributor">Contributor</option>
        <option value="admin">Admin</option>
    </select>

    <br><br>
    <!-- boton de submit-->
    <button class="submit" type="submit">Crear cuenta</button>
</form>

<?php include APP_ROOT . "/views/templates/footer.php"; ?>