<?php include APP_ROOT . '/views/templates/header.php'; ?>

<?php if (!empty($errores)){ ?>
    <div class="errors">
        <ul>
            <?php foreach ($errores as $e): ?>
                <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php } ?>

<form action="index.php?action=create_opportunity" method="post" enctype="multipart/form-data"> <!-- enctype es para poder subir archivos -->
    <legend>Crea tu Oportunidad</legend>

    <label for="title">Titulo: </label>
    <input type="text" id="title" name="title" value="<?= $_POST['title'] ?? ''; ?>">

    <label for="description">Descripcion: (Usar doble enter para parrafos y '*' para listas)</label>
    <textarea name="description" id="description"><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
</textarea>

    <label for="sponsor">Patrocinador: </label>
    <input type="text" id="sponsor" name="sponsor" value="<?= $_POST['sponsor'] ?? ''; ?>">

    <label for="url">URL (opcional): </label>
    <input type="url" id="url" name="url"  value="<?= $_POST['url'] ?? ''; ?>">

    <label for="attachment">Archivo Adjunto (opcional): </label>
    <input type="file" id="attachment" name="attachment">

    <label for="date_posted">Dia de publicacion: </label>
    <input type="date" id="date_posted" name="date_posted" value="<?= $_POST['date_posted'] ?? date('Y-m-d'); ?>" readonly> <!-- read only para que no puedan cambiar el dia de publicacion -->

    <label for="deadline">Fecha limite (opcional): </label>
    <input type="date" id="deadline" name="deadline"  value="<?= $_POST['deadline'] ?? ''; ?>">

    <label for="posted_by">Publicado por: </label>
    <input type="text" id="posted_by" name="posted_by" value="<?= $_SESSION['user']['id'] ?>" readonly> <!-- por la misma razon de date posted -->

    <label for="type">Tipo: </label>
    <select name="type" id="type">
        <option value="Empleo">Empleo</option>
        <option value="Internado">Internado</option>
        <option value="Beca-de-Investigación">Beca de Investigación</option>
        <option value="Beca-Académica">Beca Académica</option>
        <option value="Investigación">Investigación</option>
        <option value="Voluntariado">Voluntariado</option>
        <option value="Otro" selected>Otro</option>
    </select>

    <input class="submit" type="submit" value="Crear Oportunidad">
</form>