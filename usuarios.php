<?php
//Definimos las credenciales para acceder a la bd
$DB_SERVER = 'localhost';
$DB_DATABASE = 'Xjwojciechowska0_';
$DB_USER = 'Xjwojciechowska0';
$DB_PASS = 'aQWGmrrWt';

//Nos conectamos a la bd
$con = mysqli_connect($DB_SERVER, $DB_USER, $DB_PASS, $DB_DATABASE);

if (mysqli_connect_errno()) {
    echo 'Error de conexión: ' . mysqli_connect_error();
    exit();
}


$tarea = $_POST['tarea'];

if($tarea == 'login'){ //Si queremos comprobar las credenciales de un usuario 

    //Obtenemos las credenciales
    $usuario = $_POST["usuario"];
    $contra = $_POST["password"];

    //Realizamos la consulta para averiguar si existe dicho usuario
    $resultado = mysqli_query($con, "SELECT password FROM users WHERE usuario = '$usuario'");

    $fila = mysqli_fetch_row($resultado);

    //Comprobamos la contrasena
    if (password_verify($contra, $fila[0])) {
   
        $resultado = 'Bienvenido a Cloud Pages';
    } else {
    
        $resultado = 'Usuario o contraseña incorrectos';
    }

    //Devolvemos el resultado
    echo $resultado;

} else { //Si queremos registrar un usuario nuevo

    //Obtenemos las credenciales
    $usuario = $_POST["usuario"];
    $contra = $_POST["password"];

    //Hasheamos la contrasena
    $hash = password_hash($contra, PASSWORD_DEFAULT);

    //Comprobamos si ya existe un usuario con este nombre
    $comprobacion = mysqli_query($con, "SELECT usuario FROM users WHERE usuario='$usuario'");

    //Si existe
    if (mysqli_num_rows($comprobacion) > 0) {

        echo "El usuario ya existe, prueba de nuevo";
    } else { //Si no existe 
        
        //Realizamos la conulta para almacenarlo
        $resultado = mysqli_query($con, "INSERT INTO users (usuario, password) VALUES ('$usuario','$hash')");

        if ($resultado) {
            echo "El usuario se ha registrado correctamente";
        } else {
            echo 'Error al realizar la consulta';
        }
    }
}
//Cerramos la conexion
mysqli_close($con);

?>