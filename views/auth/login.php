
<div class="contenedor login">
    <?php include_once __DIR__ . '/../template/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>

        <?php include_once __DIR__ . '/../template/alertas.php'; ?>

        <form action="/" method="POST" class="formulario">
            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email"
                    id="email"
                    placeholder="Tu Email"
                    name="email"
                >
            </div>

            <div class="campo">
                <label for="password">Contraseña</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Tu Contraseña"
                    name="password"
                >
            </div>

            <input type="submit" value="Iniciar Sesión" class="boton">
        </form>

        <div class="acciones">
            <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
            <a href="/olvide">¿Olvidaste tu Contraseña?</a>
        </div>
    </div> <!-- .contenedor-sm -->
</div>