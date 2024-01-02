
<?php include_once __DIR__ . '/header-dashboard.php'; ?>

    <div class="contenedor-sm">
        <a href="/perfil" class="enlace">Volver a Perfil</a>
        
        <?php @include_once __DIR__ . '/../template/alertas.php'; ?>

        <form class="formulario" method="POST" action="/restablecer-contraseña">
            <div class="campo">
                <label for="password_actual">Contraseña Actual</label>
                <input type="password"
                    name="password_actual"
                    placeholder="Tu Contraseña Actual">
            </div>
            <div class="campo">
                <label for="password_nuevo">Contraseña Nueva</label>
                <input type="password"
                    name="password_nuevo"
                    placeholder="Tu Contraseña Nueva">
            </div>

            <input type="submit" value="Guardar Cambios">
            
        </form>
    </div>

<?php include_once __DIR__ . '/footer-dashboard.php'; ?>
