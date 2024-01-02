<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {


    public static function login(Router $router) {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);

            $alertas = $usuario->validarLogin();

            if(empty($alertas)){
                // Verificar que el usuario exista
                $usuario = Usuario::where('email', $usuario->email);

                if(!$usuario || !$usuario->confirmado){
                    Usuario::setAlerta('error', 'El Usuario No Existe o No Esta Confirmado');
                }else{
                    // El usuario existe:
                    // Comprobar la contraseña con "password_verify
                    if( password_verify($_POST['password'], $usuario->password) ){
                        
                        // Iniciar la sesion
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionar
                        header('Location: /dashboard');
                        // debuguear($_SESSION);
                    }else{
                        Usuario::setAlerta('error', 'Contraseña Incorrecta');
                    }
                }

            }
        }
        $alertas = Usuario::getAlertas();
        // Render a la vista
        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout() {
        session_start();  // toma toda la informacion que este en el servidor
        $_SESSION = []; //limpiamos el arreglo
        header('Location: /');  // slo llevamos a la pagina principal
    }

    //crear
    public static function crear(Router $router) {

        // creamos la variable antes para no tener undefined variable
        $alertas=[];

        $usuario = new Usuario;
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar($_POST);
            // debuguear($usuario);
            $alertas = $usuario->validarNuevaCuenta();
            // debuguear($alertas);

            if(empty($alertas)){
                // Comprobar que usuario no este registrado previamente
                $existeUsuario = Usuario::where('email', $usuario->email);
                // debuguear($existeUsuario);

                if($existeUsuario) {
                    //eso lo va guardar un momento en memoria pero lo tengo que sacar de ahis
                    Usuario::setAlerta('error', 'El usuario ya está registrado'); 
                    $alertas = Usuario::getAlertas();
                    //entonces obtengo de nuevo la variable de alertas y obtenemos las alertas para que se quede guardada en la var
                } else{
                    // debuguear($usuario);
                    // Hashear el password
                    $usuario->hashPassword();

                    // Eliminar password2 porque no lo requerimos, era solo para validar que el usuario recuerde la contraseña
                    // Eliminar un elemento de un objeto
                    unset($usuario->password2);

                    // Generar el Token
                    $usuario->crearToken();
                    // debuguear($usuario);
                    // Crear un nuevo usuario
                    $resultado = $usuario->crear();  //crear, porque id es null

                    // Enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    // debuguear($email);
                    $email->enviarConfirmacion();
                    // debuguear($email);
                    if( $resultado){
                        header('Location: /mensaje');
                        // instalar php mailer, 
                        // 1 - "composer require phpmailer/phpmailer"
                        // 2 - "composer update"
                        // 3 - en composer.json, en el psr-4 registamos una carpeta mas
                        // "Classes\\" : "./classes" en el psr-4
                    }
                }
            } 
        }
        // Render a la vista
        $router->render('auth/crear', [
            'titulo' => 'Crea tu cuenta en UpTask',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }


    public static function olvide(Router $router) {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)){
                // Buscar el usuario
                $usuario = Usuario::where('email', $usuario->email);
                // debuguear($usuario);
                if($usuario && $usuario->confirmado){
                    // Generar un nuevo token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    // Actualizar el usuario
                    $usuario->actualizar();

                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Imprimir la alerta
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');                    
                } else{
                    Usuario::setAlerta('error', 'El Usuario no existe o no está confirmado');
                }
            }

        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide', [
            'titulo' => 'Olvide mi Password',
            'alertas' => $alertas
        ]);
    }

    public static function restablecer(Router $router) {
        $alertas = [];

        $token = s($_GET['token']);
        $mostrar = true;

        if(!$token) header('Location: /');

        // Identificar el usuario con este token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token No Válido');
            $mostrar = false;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            // Añadir la nueva contraseña
            $usuario->sincronizar($_POST);

            // Validar la contraseña
            $alertas = $usuario->validarPassword();

            if(empty($alertas)){
                // Hashear contraseña
                $usuario->hashPassword();

                // Eliminar el token
                $usuario->token = null;

                // Guardar el usuario en la BD
                $resultado = $usuario->actualizar();

                // Redireccionar
                if($resultado){
                    header('Location: /');
                }
                // debuguear($usuario);
            }
        }
        $alertas = Usuario::getAlertas();
        //Muestra la vista
        $router->render('auth/restablecer', [
            'titulo' => 'Restablecer Contraseña',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje(Router $router) {
        //Muestra la vista
        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente'
        ]);
    }

    public static function confirmar(Router $router) {
        $alertas = [];
        
        $token = s($_GET['token']);

        if(!$token){
            header('Location: /');
        }

        // Encontrar al usuario con el token
        $usuario = Usuario::where('token', $token);
        // En caso de que no haya un usuario, asi no aparezca error
        if(empty($usuario)){
            // No se encontro un usuario con ese token 
            Usuario::setAlerta('error', 'Token No Válido');
        } else{
            // Confirmar la cuenta
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);

            // Guardar en la base de datos
            // debuguear($usuario);
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }

        $alertas = Usuario::getAlertas();

        //Muestra la vista
        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta Uptask',
            'alertas' => $alertas
        ]);

    }
}