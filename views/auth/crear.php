
<div class="contenedor crear">
    <?php include_once __DIR__ . '/../template/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en Uptask</p>

        <?php include_once __DIR__ . '/../template/alertas.php'; ?>

        <form action="/crear" method="POST" class="formulario">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input 
                    type="text"
                    id="nombre"
                    placeholder="Tu Nombre"
                    name="nombre"
                    value="<?php echo $usuario->nombre; ?>"
                >
            </div>

            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email"
                    id="email"
                    placeholder="Tu Email"
                    name="email"
                    value="<?php echo $usuario->email; ?>"
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

            <div class="campo">
                <label for="password2">Repetir Contraseña</label>
                <input 
                    type="password"
                    id="password2"
                    placeholder="Repite tu Contraseña"
                    name="password2"
                >
            </div>

            <input type="submit" value="Crear Cuenta" class="boton">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Inciar Sesión</a>
            <a href="/olvide">¿Olvidaste tu Contraseña?</a>
        </div>
    </div> <!-- .contenedor-sm -->
</div>