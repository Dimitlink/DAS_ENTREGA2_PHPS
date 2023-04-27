<?php
//obtenemos el token del dispositivo y la tearea que se pide realizar
$token = $_POST['token'];
$tarea = $_POST['tarea'];


//Si es una notificicacion de la actividad EscribirDiario
if($tarea == 'escritura'){
    $title = "¡Felicidades por guardar un diario!";
    $body = "¡Sigue así!";
} else { //Si es una notificacion de la actividad Registro
    $title = "Gracias por registrarte en CloudPages";
    $body = "Nos alegra tener nuevos usuarios como tú.";

}

//Definimos el mensaje
$notification = array('title' => $title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
$arrayToSend = array('to' => $token, 'notification' => $notification,'priority'=>'high');
$url = "https://fcm.googleapis.com/fcm/send";

//Definimos las cabeceras para la peticion a Firebase
$serverKey = 'AAAAa9QYogA:APA91bG5-B5U53sSHw5A4IY1DcjUXVr3gv4_LzO-r0BYTFrLNUpJ8nRG7wWm-bpLCD1P2r68MXVmNppQbmbU_MsKpRtY2nCVwU99PTl9dYjUqk80kIwmIam8pi77Fe3tkpp-nxv7v1Fl';
$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization: key='. $serverKey;
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayToSend));
curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);

//Enviamos la peticion
$response = curl_exec($ch);

if ($response === FALSE) {
    die('FCM Send Error: ' . curl_error($ch));
}

//Cerramos la conexion a Firebase
curl_close($ch);


//Definimos las credenciales para acceder a la bd
$DB_SERVER = 'localhost';
$DB_DATABASE = 'Xjwojciechowska0_';
$DB_USER = 'Xjwojciechowska0';
$DB_PASS = 'aQWGmrrWt';

//Realizamos la conexion a la bd
$conn = mysqli_connect($DB_SERVER, $DB_USER, $DB_PASS, $DB_DATABASE);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$token = $_POST['token']; 

//Comprobamos si el token existe
$comprobacion = mysqli_query($conn, "SELECT token FROM tokens WHERE token='$token'");

//Si no existe lo anadimos a la tabla de tokens
if (mysqli_num_rows($comprobacion) == 0) {
    $resultado = mysqli_query($conn, "INSERT INTO tokens (token) VALUES ('$token')");
    
} 
//cerramos la conexion
mysqli_close($conn);
?>