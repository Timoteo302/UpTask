<?php

namespace Controllers;

use MVC\Router;
use Model\Proyecto;
use Model\Usuario;


class DashboardController {

    public static function index(Router $router){
        // Vuelvo a iniciar la session para que no se me pierda la info de la variable $_SESSION
        session_start(); // variables que dura la session 24minutos
        isAuth(); // protegiendo la ruta
        $id = $_SESSION['id'];

        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->render('dashboard/index', [
            'titulo' => "Proyectos",
            'proyectos' => $proyectos
        ]);
    }

    public static function crear_proyecto(Router $router){
        session_start();
        isAuth();
        $alertas = [];


        if( $_SERVER['REQUEST_METHOD'] === 'POST' ){
            $proyecto = new Proyecto($_POST);
            // debuguear($proyecto);

            // Validación
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)){
                // Generar una URL unica
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                // Almacenar el creado del proyecto
                $proyecto->propietarioId = $_SESSION['id'];

                // Guardar el proyecto
                $proyecto->crear();

                // Redireccionar 
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }

        $router->render('dashboard/crear-proyecto', [
            'titulo' => "Crear Proyecto",
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router){
        session_start();
        isAuth();  // proteger las rutas
        $alertas = [];

        $token = $_GET['id'];
        if(!$token) header('Location: /dashboard');

        // Revisar que la persona que visita el proyecto, es quien lo creo
        $proyecto = Proyecto::where('url', $token);

        if($proyecto->propietarioId !== $_SESSION['id']){
            header('Location: /dashboard');
        }

        $router->render('dashboard/proyecto',[
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function perfil(Router $router){
        session_start();
        isAuth();
        $alertas = [];

        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $usuario->sincronizar($_POST);

            $alertas = $usuario->validarPerfil();

            if(empty($alertas)){

                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario && $existeUsuario->id != $usuario->id){
                    // Mensaje de Error
                    Usuario::setAlerta('error', 'Email no válido, ya pertenece a otra cuenta');
                    $alertas = $usuario->getAlertas();
                } else{
                    // Actualizar el usuario
                    $usuario->actualizar();

                    Usuario::setAlerta('exito', 'Guardado Correctamente');
                    $alertas = $usuario->getAlertas();

                    // Asignar el nombre nuevo a la barra
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }

        $router->render('dashboard/perfil', [
            'titulo' => "Perfil",
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function restablecer_contraseña(Router $router){
        session_start();
        isAuth();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = Usuario::find($_SESSION['id']);

            // Sincronizar con los datos del usuario
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevoPassword();

            if(empty($alertas)){
                $resultado = $usuario->comprobarPassword();
                
                if($resultado){
                    // Asignar la nueva contraseña
                    $usuario->password = $usuario->password_nuevo;

                    // Eliminar propiedades no necesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    // Hashear la nueva contraseña
                    $usuario->hashPassword();

                    $resultado = $usuario->actualizar();

                    if($resultado){
                        Usuario::setAlerta('exito', 'Contraseña Guardada Correctamente');
                        $alertas = Usuario::getAlertas();
                    }
                } else{
                    Usuario::setAlerta('error', 'Contraseña Incorrecta');
                    $alertas = Usuario::getAlertas();
                }
            }
        }

        $router->render('dashboard/restablecer_contraseña', [
            'titulo' => 'Restablecer Contraseña',
            'alertas' => $alertas
        ]);
    }

    public static function eliminar_proyecto(){

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            if($_POST['confirm']){
                $proyectoId = $_POST['proyectoId'];  // recibimos desde Js
                $proyecto = Proyecto::where('url', $proyectoId);
                $proyecto->eliminar();
            }
        }
    }
}

