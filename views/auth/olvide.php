
<div class="contenedor olvide">
    <?php include_once __DIR__ . '/../template/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recupera tu Acceso Uptask</p>

        <?php include_once __DIR__ . '/../template/alertas.php'; ?>


        <form action="/olvide" method="POST" class="formulario">

            <div class="campo">
                <label for="email">Email</label>
                <input 
                    type="email"
                    id="email"
                    placeholder="Tu Email"
                    name="email"
                >
            </div>

            <input type="submit" value="Enviar Instrucciones" class="boton">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Inciar Sesión</a>
            <a href="/crear">¿Aún no tienes una cuenta? Obtener una</a>
        </div>
    </div> <!-- .contenedor-sm -->
</div>