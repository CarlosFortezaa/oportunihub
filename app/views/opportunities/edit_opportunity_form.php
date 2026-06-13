<?php include APP_ROOT . '/views/templates/header.php'; ?>

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

<form action="index.php?action=edit_opportunity&opp_id=<?= $opportunity->getOppId(); ?>" method="post" enctype="multipart/form-data"> <!-- enctype es para poder subir archivos -->
    <legend>Edita tu Oportunidad</legend>

    <input type="hidden" name="opp_id" value="<?= $opportunity->getOppId() ?>">

    <label for="title">Titulo: </label>
    <input type="text" id="title" name="title" 
    value="<?= htmlspecialchars($opportunity->getTitle())?>"  >

    <label for="description">Descripcion: (Usar doble enter para parrafos y '*' para listas)</label>
    <textarea name="description" id="description" ><?= htmlspecialchars($opportunity->getDescription() )?></textarea>

    <label for="sponsor">Patrocinador: </label>
    <input type="text" id="sponsor" name="sponsor" 
    value="<?= htmlspecialchars($opportunity->getSponsor()) ?>"  >

    <label for="url">URL (opcional): </label>
    <input type="url" id="url" name="url"
    value="<?= htmlspecialchars($opportunity->getUrl()) ?>">

    <label for="attachment">Archivo Adjunto (opcional): </label>
    <?php if (!empty($opportunity->getAttachmentPath())){ ?>
    <p>Archivo actual: 
        <a href="<?= htmlspecialchars($opportunity->getAttachmentPath()) ?>" target="_blank" download>
            Descargar
        </a>
    </p>
    <p>
        <a href="index.php?action=delete_file_from_opportunity&opp_id=<?= $opportunity->getOppId() ?>">Eliminar archivo adjunto</a>
    </p>
    
    <?php } ?>
    <input type="file" id="attachment" name="attachment">

    <label for="date_posted">Dia de publicacion: </label>
    <input type="date" id="date_posted" name="date_posted" value="<?= $_POST['date_posted'] ?? date('Y-m-d'); ?>" readonly> <!-- read only para que no puedan cambiar el dia de publicacion -->

    <label for="deadline">Fecha limite (opcional): </label>
    <input type="date" id="deadline" name="deadline"
    value="<?= htmlspecialchars($opportunity->getDeadline()) ?>">

    <label for="posted_by">Publicado por: </label>
    <input type="text" id="posted_by" name="posted_by" value="<?= $_SESSION['user']['id'] ?>" readonly> <!-- por la misma razon de date posted -->

    <label for="type">Tipo: </label>
    <select name="type" id="type" value="">
        <?php $currentType = $opportunity->getType(); ?>
        <option value="Empleo" <?= $currentType === 'Empleo' ? 'selected' : ''; ?>>Empleo</option>
        <option value="Internado" <?= $currentType === "Internado" ? 'selected' : '' ?>>Internado</option>
        <option value="Beca-de-Investigación" <?= $currentType === "Beca-de-Investigación" ? 'selected' : '' ?>>Beca de Investigación</option>
        <option value="Beca-Académica" <?= $currentType === "Beca-Académica" ? 'selected' : '' ?>>Beca Académica</option>
        <option value="Investigación" <?= $currentType === "Investigación" ? 'selected' : '' ?>>Investigación</option>
        <option value="Voluntariado" <?= $currentType === "Voluntariado" ? 'selected' : '' ?>>Voluntariado</option>
        <option value="Otro" <?= $currentType === "Otro" ? 'selected' : '' ?>>Otro</option>
    </select>

    <input class="submit" type="submit" value="Editar Oportunidad">
</form>