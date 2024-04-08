<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;


class LoginController{

    public static function login(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD']=== 'POST'){
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();


            if(empty($alertas)){
                //comprobar 
                $usuario = Usuario::where('email', $auth->email);

                if($usuario){
                    //verificar password
                    if($usuario->comprobrarPasswordAndVerificado($auth->password)){
                        // Autenticar el usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " ". $nombre->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        //redireccionamiento
                        if($usuario->admin === '1'){
                            $_SESSION['admin'] = $usuario->admin ?? null;

                            header('Location: /admin');
                        }
                        else{
                            header('Location: /cita');
                        }
                    }
                }
                else{
                    Usuario::setAlerta('error','Usuario no encontrado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    }

    public static function logout(){
        session_start();
        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router){
        $alertas = [];
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado === '1'){
                    //generar un token

                    $usuario->crearToken();
                    $usuario->guardar();
                    

                    //enviar el email

                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();


                    //Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email');
                    
                }
                else{
                    Usuario::setAlerta('error','El usuario no existe o no esta confrimado');
                    
                }
            }
            else{

            }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide-password',[
            'alertas'=>$alertas
        ]);

    }
    public static function recuperar(Router $router){
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);
        //buscar usuario por su token
        $usuario = Usuario::where('token', $token);
        // debuguear($usuario);
        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
            $error = true;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            // lEER EL NUEVO PASSWORD Y GUARDARLO

            $password = New Usuario($_POST);
            $alertas = $password->validarPassword();
            
            $alertas = Usuario::getAlertas();

            if(empty($alertas)){
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();

                if($resultado){
                    Header('Location:  /');
                }
            }

        }



        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password',[
            'alertas' => $alertas,
            'error'=> $error
        ]);
    }
    public static function crear(Router $router){
        
        $usuario = new Usuario;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD']=== 'POST'){

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();
            // debuguear($usuario);

            //revisar que alerta este vacio

            if(empty($alertas)){
                //veritifcar que el usuario no este registrado

                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }
                else{
                    //hashear password

                    $usuario->hashPassword();

                    //generar un token único

                    $usuario->crearToken();


                    $email = new Email($usuario->email,$usuario->nombre,$usuario->token);

                    $email->enviarConfirmacion();

                    //crear el usuario 

                    $resultado = $usuario->guardar();
                    if($resultado){
                        header('Location: /mensaje');
                    }

                    // debuguear($usuario);

                }
            }
        }


        $router->render('auth/crear-cuenta',[
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router){
        $alertas = [];

        $token = s($_GET['token']);


        $usuario = Usuario::where('token',$token);

        if(empty($usuario)){
            //Mostrar Mensaje de error
            Usuario::setAlerta('error','Token no valido');
        }
        else{
            //modificar usuario Confirmado
            $usuario->confirmado = '1';
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito','Cuenta Comprobada Correctamente');

        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta',[
            'alertas' => $alertas
        ]);
    }
}