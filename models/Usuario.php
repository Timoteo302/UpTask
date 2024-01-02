<?php
 
namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_actual;
    public $password_nuevo;
    public $token;
    public $confirmado;

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password_nuevo'] ?? '';        $this->password2 = $args['password2'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    // Validar el login de usuarios
    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'Email no válido';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El Password no puede ir vacío';
        }
        
        return self::$alertas;
    }

    // Validacion para cuentas nuevas
    public function validarNuevaCuenta() {
        if(!$this->nombre){
            self::$alertas['error'][] = 'El Nombre del Usuario es Obligatorio';
        }

        if(!$this->email){
            self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
        }

        /*3 validaciones para password
        1- Haya un password
        2- Minimo de 6 caractetes
        3- Ambos campos iguales  */
        //1
        if(!$this->password){
            self::$alertas['error'][] = 'El Password no puede ir vacío';
        }
        //2
        if(strlen($this->password) < 6 ){
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteces';
        }
        //3
        if($this->password !== $this->password2){
            self::$alertas['error'][] = 'Los password son diferentes';
        }

        return self::$alertas;
    }


    public function validarPassword(){

        if(!$this->password){
            self::$alertas['error'][] = 'El Password no puede ir vacío';
        }

        if(strlen($this->password) < 6 ){
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteces';
        }

        return self::$alertas;
    }

    // Validar email
    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'Email no válido';
        }
        return self::$alertas;

        /* Hacer una segunda validacion para ver si en realidad es un email
        porque va haber personas que van a querer poner solo una letra o la 
        arroba y listo, y si tenemos 1000 usuarios va buscar, algo que no va 
        encontrar, entonces hacemos otra validacion.
        
        Usamos filter_var()
        1- lo que queremos validad.
        2- Filtro que queremos utilizar
        "FILTER_VALIDATE_EMAIL", verifica la estructura adecuada de un email.
        true si no es valido (por eso lo negamos)
        false si es valido*/

    }

    public function validarPerfil(){
        if(!$this->nombre){
            self::$alertas['error'][] = "El Nombre es obligatorio";
        }
        if(!$this->email){
            self::$alertas['error'][] = "El Email es obligatorio";
        }
        return self::$alertas;
    }

    // Hashea el password
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    // Generar un Token
    public function crearToken() : void {
        $this->token = uniqid();
        //tambien podemos usar md5( uniqid())
        //genera valores menos repetitivos
    }

    public function nuevoPassword() : array {
        if(!$this->password_actual){
            self::$alertas['error'][] = 'La Contraseña actual no puede ir vacía';
        }
        if(!$this->password_nuevo){
            self::$alertas['error'][] = 'La Contraseña nueva no puede ir vacía';
        }

        if(strlen($this->password_nuevo) < 6){
            self::$alertas['error'][] = 'La Contraseña debe contener al menos 6 carácteres';
        }
        return self::$alertas;
    }

    // Comprobar la contraseña
    public function comprobarPassword() : bool{
        return password_verify($this->password_actual, $this->password);
        /*Se le pasa la contraseña que queremos comprobar y la que esta 
        hasheada, retornara true si son iguales; false si son diferentes. */
    }

}