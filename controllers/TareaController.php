<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

/* aca no requerimos el router porque sera todo por medio de una API,
el router solo hace render a las vistas, pero siendo una API no 
requerimos vistas.*/
class TareaController {
    public static function index(){

        $proyectoId = $_GET['id'];  // obtenemos la url
        
        if(!$proyectoId) header('Location: /dashboard');
        
        $proyecto = Proyecto::where('url', $proyectoId);
        session_start();
        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) header('Location: /404');

        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);

        // debuguear($tareas);
        echo json_encode(['tareas' => $tareas]);
    }   

    public static function crear(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            session_start();

            $proyectoId = $_POST['proyectoId'];  // recibimos desde Js
            $proyecto = Proyecto::where('url', $proyectoId);  // si existe o no

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Ocurrió un error al agregar la tarea'
                ];
                echo json_encode($respuesta); // lo que estamos retornando
                return;
            }

            // Todo bien, instanciar y crear la tarea:
            $tarea = new Tarea($_POST);
            // reescribimos el valor del proyectoId por el id del proyecto
            $tarea->proyectoId = $proyecto->id; 
            $resultado = $tarea->crear();

            // construir una respuesta de exito
            $respuesta = [
                'tipo' => 'exito',
                'id' => $resultado['id'],
                'mensaje' => 'Tarea creada correctamente',
                'proyectoId' => $proyecto->id
            ];
            echo json_encode($respuesta); // retornando
            // echo json_encode($proyecto); // lo que estamos retornando
        }
    } 

    public static function actualizar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // Validar que el proyecto exista
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);
            session_start();
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Ocurrió un error al agregar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            // Validado.
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->actualizar();

            if($resultado){
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id, // requerimos la tarea id para tener virtual DOM actualizado
                    'proyectoId' => $proyecto->id,
                    'mensaje' => 'Actualizado Correctamente'
                ];
                echo json_encode(['respuesta' => $respuesta]);
            }
        }
    } 

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            session_start();

            $proyectoId = $_POST['proyectoId'];  // recibimos desde Js
            $proyecto = Proyecto::where('url', $proyectoId);  // si existe o no

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Ocurrió un error al agregar la tarea'
                ];
                echo json_encode($respuesta); // lo que estamos retornando
                return;
            }

            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();

            $resultado = [
                'resultado' => $resultado,
                'mensaje' => 'Eliminado Correctamente',
                'tipo' => 'exito'
            ];
            echo json_encode($resultado);
        }
    } 
}