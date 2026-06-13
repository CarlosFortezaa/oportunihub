<?php include APP_ROOT . "/views/templates/header.php"; ?>

<?php if(empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') { ?>
    <h2>¿Quieres recibir notificaciones cuando se publique una nueva oportunidad?</h2>
<?php } else { ?>
    <h2>Lista de Correos Suscritos</h2>
<?php } ?>

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
<?php if(empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') { ?>
    <p>Suscríbete aquí.</p>
<?php } else { ?>
    <p>Aquí puedes manejar los correos suscritos al boletín de oportunidades.</p>
<?php } ?>

<?php
// asegurarse que el email sera un array
$emails  = $emails  ?? [];
?>

<!-- form para anadir emails -->
<form method="post" action="<?= BASE_URL ?>/index.php?action=subscribe_email">
    <label>
        Email:
        <input type="email" name="email" required>
    </label>
    <button type="submit">Añadir / Suscribir</button>
</form>

<hr>

<!-- muestar los emails  por cada email lo presenten los emails suscritos y opcion de borrar -->
<?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'){ ?>


    <h3>Correos suscritos:</h3>

    <?php if (!empty($emails)){ ?>
        <ul>
            <?php foreach ($emails as $email){ ?>
                
                <li>
                    <?= htmlspecialchars($email) ?>
                    <form method="post"
                          action="index.php?action=unsubscribe_email"
                          style="display:inline;">
                        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                        <button type="submit" class="submit">Eliminar</button>
                    </form>
                </li>
            <?php } ?>
        </ul>
    <?php } else { ?>
        <p>No hay correos suscritos.</p>
        <?php } ?>
    <?php } ?>