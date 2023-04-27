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

//Si queremos anadir un diarios
if($tarea == 'anadir'){

    //Obtenemos sus parametros
    $usuario = $_POST["usuario"];
    $fecha = date('Y-m-d H:i:s');
    $titulo = $_POST["titulo"];
    $cuerpo = $_POST["cuerpo"];
    $foto = $_POST["foto"];


    //Realizamos la consulta
    $resultado = mysqli_query($con, "INSERT INTO diarios (usuario, fecha, titulo, cuerpo, foto) VALUES ('$usuario','$fecha', '$titulo', '$cuerpo', '$foto')");

    
    if ($resultado) {
        echo "El diario se ha guardado correctamente";
    } else { 
        echo 'Error de consulta';
    }


//Si queremos listar todos los diarios de un usuario
} elseif($tarea == 'listar') {

    //Obtenemos el nombre de usuario
    $usuario = $_POST['usuario'];
    
    //Consultamos sus diarios
    $consulta = "SELECT * FROM diarios WHERE usuario = '$usuario'";
    
    $resultado = mysqli_query($con, $consulta);


    //Si tiene algun diario
    if (mysqli_num_rows($resultado) > 0) {
   
        $datos = array();
        $cont = 0;
            
        //Guardamos en un array los datos de todos los diarios
        while ($fila = mysqli_fetch_row($resultado)){
       
            $foto_base64 = base64_encode($fila[4]);
        
            $datos[$cont] = array(
                "usuario" => $fila[0],
                "fecha" => $fila[1],
                "titulo" => $fila[2],
                "cuerpo" => $fila[3],
                "foto" => $fila[4]
        );
        $cont++;
        }
        //Convertimos el array en un json
        $json = json_encode($datos);
        //Devolvemos el json
        echo $json;

    } else {
        echo "No se encontraron resultados.";
    }
   
//Si queremos guardar los datos de un diario modificado
} elseif($tarea == 'editar') {

    //Obtenemos los datos necesarios para realizar la consulta
    $usuario = $_POST['usuario'];
    $fecha = $_POST['fecha'];
    $titulo = $_POST['titulo'];
    $cuerpo = $_POST['cuerpo'];

    //Realizamos la consulta
    $consulta = "UPDATE diarios SET titulo='$titulo', cuerpo='$cuerpo' WHERE usuario='$usuario' AND fecha='$fecha'";
    $resultado = mysqli_query($con, $consulta);

    if ($resultado) {
        echo "El registro se ha actualizado correctamente.";
    } else {
        echo 'Error de consulta';
    }

}else { //Si queremos eliminar un diario

    //Obtenemos los datos necesarios para realizar la consulta
    $usuario = $_POST['usuario'];
    $fecha = $_POST['fecha'];

    //Realizamos la consulta
    $consulta = "DELETE FROM diarios WHERE usuario='$usuario' AND fecha='$fecha'";
    $resultado = mysqli_query($con, $consulta);

    if ($resultado) {
        echo "El registro se ha eliminado correctamente.";
    } else {
        echo 'Error de consulta';
    }

}

mysqli_close($con);

?>