<?php include APP_ROOT . '/views/templates/header.php'; ?>

<h2>Administrar Usuarios</h2>

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
<br>
<table>
    <tr>
        <th>User ID</th>
        <th>Email</th>
        <th>Rol</th>
        <th>Acciones</th>
    </tr>
<!-- el ciclo for itera los usuarios para ir imprimiendo en una tabla -->
    <?php foreach ($users as $u): ?>
        <tr>
            <!--- muestra el ID, email, rol y opciones para  cada usuario -->
            <td><?= htmlspecialchars($u->getUserId()) ?></td>
            <td><?= htmlspecialchars($u->getEmail()) ?></td>
            <td><?= htmlspecialchars($u->getRole()) ?></td>
            <td>
                <a href="<?= BASE_URL ?>/index.php?action=edit_user_form&id=<?= $u->getUserId() ?>">Editar</a> |
                <a href="<?= BASE_URL ?>/index.php?action=delete_user&id=<?= $u->getUserId() ?>"
                   onclick="return confirm('¿Seguro de eliminar este usuario?');">
                   Eliminar
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include APP_ROOT . '/views/templates/footer.php'; ?>
