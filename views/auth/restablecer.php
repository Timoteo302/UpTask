
<div class="contenedor restablecer">
    <?php include_once __DIR__ . '/../template/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Coloca tu nuevo password</p>

        <?php include_once __DIR__ . '/../template/alertas.php'; ?>

        <?php if($mostrar){ ?>

            <form method="POST" class="formulario">

                <div class="campo">
                    <label for="password">Contraseña</label>
                    <input 
                        type="password"
                        id="password"
                        placeholder="Tu Contraseña"
                        name="password"
                    >
                </div>

                <input type="submit" value="Guardar Contraseña" class="boton">
            </form>

        <?php } ?>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
            <a href="/olvide">¿Olvidaste tu Contraseña?</a>
        </div>
    </div> <!-- .contenedor-sm -->
</div>