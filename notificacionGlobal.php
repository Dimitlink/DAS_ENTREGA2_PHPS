<?php
//Obtenemos las credenciales de la bd
$DB_SERVER = 'localhost';
$DB_DATABASE = 'Xjwojciechowska0_';
$DB_USER = 'Xjwojciechowska0';
$DB_PASS = 'aQWGmrrWt';

//Nos conectamos a la bd
$conn = mysqli_connect($DB_SERVER, $DB_USER, $DB_PASS, $DB_DATABASE);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Obtenemos todos los tokens almacenados
$resultado = mysqli_query($conn, "SELECT token FROM tokens");

//Definimos el mensaje
$title = 'Notificación externa';
$body = 'Notificación realizada de manera externa';
$notification = array('title' => $title , 'body' => $body, 'sound' => 'default', 'badge' => '1');
$arrayToSend = array('notification' => $notification,'priority'=>'high');

//Definimos las cabeceras
$url = "https://fcm.googleapis.com/fcm/send";
$serverKey = 'AAAAa9QYogA:APA91bG5-B5U53sSHw5A4IY1DcjUXVr3gv4_LzO-r0BYTFrLNUpJ8nRG7wWm-bpLCD1P2r68MXVmNppQbmbU_MsKpRtY2nCVwU99PTl9dYjUqk80kIwmIam8pi77Fe3tkpp-nxv7v1Fl';
$headers = array();
$headers[] = 'Content-Type: application/json';
$headers[] = 'Authorization: key='. $serverKey;

//Para cada token
while ($row = mysqli_fetch_assoc($resultado)) {
    $token = $row['token'];
    $arrayToSend['to'] = $token;

    //Enviamos la notificacion desde Firebase
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayToSend));
    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
    //Send the request
    $response = curl_exec($ch);
    //Close request
    if ($response === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    //Cerramos la conexion a Firebase
    curl_close($ch);
}
//Cerramos la conexion a la bd
mysqli_close($conn);
?>
