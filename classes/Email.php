<?php

namespace Classes;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Email{
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;    
        $this->nombre = $nombre;    
        $this->token = $token;    
    }

    public function enviarConfirmacion() {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['USER'];
            $mail->Password = $_ENV['PASSWORD'];
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('wiernatimoteoweb@gmail.com', 'Uptask');
            $mail->addAddress($this->email);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Confirma tu Cuenta';

            // importante en el <a> caundo tengamos el dominio hay que colocarle el dominio
            $contenido = '<html>';
            $contenido .= "<p><strong>Hola " . $this->nombre . "</strong>, Has Creado tu cuenta en Uptask, solo debes confirmarla en el siguiente enlace:</p>";
            $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
            $contenido .= "<p>Si tu no creaste esta cuenta, puedes ignonar este mensaje.</p>";
            $contenido .= '</html>';

            $mail->Body = $contenido;

            $mail->send();

        } catch (Exception $e) {
            echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
        }

    }


    public function enviarInstrucciones() {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $_ENV['USER'];
            $mail->Password = $_ENV['PASSWORD']; 
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('wiernatimoteoweb@gmail.com', 'Uptask');
            $mail->addAddress($this->email);

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Restablece tu Contraseña';

            // importante en el <a> caundo tengamos el dominio hay que colocarle el dominio
            $contenido = '<html>';
            $contenido .= "<p><strong>Hola " . $this->nombre . "</strong>, Parece que has olvidado tu contraseña, sigue el siguiente enlace para establecer una nueva:</p>";
            $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/restablecer?token=" . $this->token . "'>Restablecer Contraseña</a></p>";
            $contenido .= "<p>Si tu no creaste esta cuenta, puedes ignonar este mensaje.</p>";
            $contenido .= '</html>';

            $mail->Body = $contenido;

            $mail->send();

        } catch (Exception $e) {
            echo 'Error al enviar el correo: ' . $mail->ErrorInfo;
        }

    }

}