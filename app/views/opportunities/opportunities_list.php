<?php 
    include APP_ROOT . "/views/templates/header.php"; 
    require_once APP_ROOT . '/util/tags.php';
?>

<h2>Lista de Oportunidades</h2>

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

<!-- SEARCH BAR -->
<!-- Formulario que envia el termino de busqueda preservando el filtro de vencidas si esta activo -->
<form method="get" action="<?= BASE_URL ?>/index.php" class="search-bar">
    <!-- Parametros ocultos para mantener el controlador y la accion -->
    <input type="hidden" name="c" value="opportunity">
    <input type="hidden" name="a" value="index">

    <!-- Si el filtro de vencidas esta activo, lo preservamos al buscar -->
    <?php if(!empty($showExpired)){ ?>
        <input type="hidden" name="expired" value="1">
    <?php } ?>

    <!-- Campo de texto para ingresar el termino de busqueda -->
    <!-- El valor se mantiene despues de buscar usando htmlspecialchars() para seguridad -->
    <input
        type="text"
        name="search"
        placeholder="Buscar oportunidades..."
        value="<?= htmlspecialchars($search ?? '') ?>"
    >

    <button class="submit" type="submit">Buscar</button>
</form>

<!-- BARRA DE FILTROS -->
<!-- Formulario para aplicar filtros adicionales -->
<form method="get" action="<?= BASE_URL ?>/index.php" class="filters-bar">

    <!-- Si hay un termino de busqueda activo, lo preservamos al cambiar filtros -->
    <?php if(!empty($search)){ ?>
        <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
    <?php } ?>

    <div class="filter-group">
        <label>
            <!-- Checkbox para filtrar solo oportunidades vencidas -->
            <!-- onchange envia el formulario automaticamente al cambiar el estado -->
            <input
                type="checkbox"
                name="expired"
                value="1"
                <?php if(!empty($showExpired)){ echo "checked"; } ?>
                onchange="this.form.submit()"
            >
            Mostrar solo vencidas
        </label>
    </div>

    <div class="filter-actions">
        <!-- Si hay filtros activos (busqueda o vencidas) mostramos opcion para limpiarlos -->
        <?php if(!empty($search) || !empty($showExpired)){ ?>
            <a href="<?= BASE_URL ?>/index.php?c=opportunity&a=index">Limpiar filtros</a>
        <?php } ?>
    </div>
</form>


<!-- INDICADORES DE FILTROS ACTIVOS -->
<!-- Mostramos los filtros aplicados actualmente -->
<?php if(!empty($search) || !empty($showExpired)){ ?>
    <div class="active-filters">
        <strong>Filtros activos:</strong>
        <?php if(!empty($search)){ ?>
            Búsqueda: "<?= htmlspecialchars($search) ?>"
        <?php } ?>
        <?php if(!empty($showExpired)){ ?>
            <?php if(!empty($search)){ echo " | "; } ?>
            Mostrando solo vencidas
        <?php } ?>
    </div>
<?php } ?>
<br>

<!-- SORT BAR -->
<form method="get" action="<?= BASE_URL ?>/index.php" class="sort-bar">

    <!-- Preservar búsqueda -->
    <?php if (!empty($search)) { ?>
        <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
    <?php } ?>

    <!-- Preservar filtro de vencidas -->
    <?php if (!empty($showExpired)) { ?>
        <input type="hidden" name="expired" value="1">
    <?php } ?>

    <input type="hidden" name="c" value="opportunity">
    <input type="hidden" name="a" value="index">

    <label for="sort">Ordenar por:</label>
    <select name="sort" onchange="this.form.submit()">
        <option value="">-- Seleccionar --</option>
        <option value="desc" <?= ($sort ?? '') === 'desc' ? 'selected' : '' ?>>Fecha límite (DESC)</option>
        <option value="asc" <?= ($sort ?? '') === 'asc' ? 'selected' : '' ?>>Fecha límite (ASC)</option>
    </select>

</form>

<?php if (!empty($sort)) { ?>
    <div class="active-filters">
        <strong>Orden aplicado:</strong>
        <?= htmlspecialchars($sort) ?>
    </div>
<?php } ?>

<br>


<!-- TABLA DE RESULTADOS -->
<!-- Si hay oportunidades para mostrar, las desplegamos en una tabla -->
<?php if (!empty($opportunities)){ ?>
    <table>
        <tr>
            <th>Título</th>
            <th>Descripción</th>
            <th>Patrocinador</th>
            <th>Enlace</th>
            <th>Archivo adjunto</th>
            <th>Fecha de Publicación</th>
            <th>Fecha Límite</th>
            <th>Tipo</th>
            <th>Publicado por</th>
            <!-- Columna de acciones visible solo para usuarios autenticados -->
            <?php if(!empty($_SESSION['user'])){ ?>
                <th>Acciones</th>
            <?php } ?>
        </tr>

        <?php foreach ($opportunities as $opp){
            // Obteniendo la fecha limite de la oportunidad
            $deadline = $opp->getDeadline();
            // Determinando si la oportunidad esta vencida
            $isExpired = $opp->isExpired();
        ?>
            <!-- Aplicando clase CSS especial si la oportunidad esta vencida -->
            <tr class="<?php if($isExpired){ echo "expired-row"; } ?>">
                <td>
                    <?= htmlspecialchars($opp->getTitle()) ?>
                    <!-- Mostrando badge de "VENCIDA" si corresponde -->
                    <?php if($isExpired){ ?>
                        <span class="expired-badge">VENCIDA</span>
                    <?php } ?>
                </td>

                <!-- Procesando descripcion con add_tags() para formateo especial -->
                <td><?= add_tags($opp->getDescription()) ?></td>
                <td><?= htmlspecialchars($opp->getSponsor()) ?></td>
                <td>
                    <!-- Si hay URL, mostramos enlace, de lo contrario mensaje informativo -->
                    <?php if(!empty($opp->getUrl())){ ?>
                        <a href="<?= htmlspecialchars($opp->getUrl()) ?>" target="_blank">Ver</a>
                    <?php } else { ?>
                        <p style="color:#999;">Sin URL adjuntado</p>
                    <?php } ?>
                </td>
                <td>
                    <!-- Si hay archivo adjunto, mostramos enlace de descarga -->
                    <?php if(!empty($opp->getAttachmentPath())) { ?>
                       <a href="<?= htmlspecialchars($opp->getAttachmentPath()) ?>" target="_blank" download>Descargar</a>
                    <?php } else {?>
                        <p span style="color:#999;">Sin file adjuntado</p>
                    <?php }?>
                </td>
                <td><?= htmlspecialchars($opp->getDatePosted()) ?></td>
                <td>
                    <!-- Mostrando fecha limite si existe, de lo contrario mensaje alternativo -->
                    <?php if(!empty($deadline)){ ?>
                        <?= htmlspecialchars($deadline) ?>
                    <?php } else { ?>
                        <span style="color:#999;">Sin fecha límite</span>
                    <?php } ?>
                </td>

                <td>
                    <?php $t = $opp->getType(); ?>
                    <span class="type-badge type-<?= strtolower($t) ?>">
                        <?= htmlspecialchars($t) ?>
                    </span>
                </td>

                <td>
                    <?= $opp->getPostedBy(); ?>
                </td>

                <!-- Mostrando acciones solo si el usuario es el creador o es admin -->
                <?php if(!empty($_SESSION['user']) &&
                         (($_SESSION['user']['id'] === $opp->getPostedBy()) || $_SESSION['user']['role'] === 'admin')){ ?>
                    <td>
                        <a href="<?= BASE_URL ?>/index.php?action=edit_opportunity&opp_id=<?= $opp->getOppId() ?>">Editar</a> |
                        <a href="<?= BASE_URL ?>/index.php?action=delete_opportunity&opp_id=<?= $opp->getOppId() ?>"
                           onclick="return confirm('¿Seguro que deseas eliminar esta oportunidad?')">Eliminar</a>
                    </td>
                <!-- Si el usuario esta autenticado pero no tiene permisos, mostramos guion -->
                <?php } elseif(!empty($_SESSION['user'])){ ?>
                    <td>-</td>
                <?php } ?>
            </tr>
        <?php }?>
    </table>

<!-- Si no hay oportunidades, mostramos mensaje informativo -->
<?php } else{ ?>
    <div class="no-results">
        <p>No se encontraron oportunidades.</p>
    </div>
<?php } ?>

<!-- Boton para crear nueva oportunidad, visible solo para usuarios autenticados -->
<br>
<?php if(!empty($_SESSION['user'])){ ?>
    <p>
        <a class="submit" href="<?= BASE_URL ?>/index.php?action=create_opportunity">Crear Oportunidad</a>
    </p>
<?php } ?>

<?php include APP_ROOT . "/views/templates/footer.php"; ?>