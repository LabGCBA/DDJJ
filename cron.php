<?php

//Clase para armar el objeto de las credenciales de acceso.

$cuit = $_GET["cuit"];

class Credentials{
    public $username ='';
    public $password= '';

    //Constructor de la clase Credentials

    public function Credentials($username, $password){
        $this->username = $username;
        $this->password = $password;
    }
}
$servername = "localhost";
$username = "hernanpc";
$password = "6268";
$dbname = "ddjj";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "CREATE TABLE IF NOT EXISTS declarantes (
cuit VARCHAR(11) PRIMARY KEY NOT NULL, 
nombre VARCHAR(30) NOT NULL,
ministerio VARCHAR(50),
cargo VARCHAR(50),
ultima DATE
);";

$sql3 = "CREATE TABLE IF NOT EXISTS ministerios (
ministerio VARCHAR(30) PRIMARY KEY NOT NULL
);";

$sql4 = "CREATE TABLE IF NOT EXISTS cargos (
cargo VARCHAR(30) PRIMARY KEY NOT NULL
);";

$sql2 = "CREATE TABLE IF NOT EXISTS declaraciones (uuid INT(11) UNSIGNED NOT NULL PRIMARY KEY,cuit VARCHAR(11) NOT NULL, nombre VARCHAR(30) NOT NULL,ministerio VARCHAR(50),cargo VARCHAR(50),fecha VARCHAR(30),tipo VARCHAR(30),revision INT(2));";

if(!$conn->query($sql2))
    echo "Error creando tabla: ".$conn->error ."\n";
if(!$conn->query($sql))
    echo "Error creando tabla: ".$conn->error ."\n";
if(!$conn->query($sql3))
    echo "Error creando tabla: ".$conn->error ."\n";
if(!$conn->query($sql4))
    echo "Error creando tabla: ".$conn->error ."\n";

$url = "http://10.79.0.72";
$service = new SoapClient(null, array("location" => $url."/ddjj/services/historico/list.php", "uri" => $url));
$service->__setSoapHeaders(new SoapHeader($url,'authenticate', new Credentials('20279391137', 'testmarino')));
$rslt = $service->getList($cuit,0,200);
$data = json_decode($rslt)->lista;



foreach($data as &$declaracion)
{
    $cuit = $declaracion->login;
    $uuid = $declaracion->SADE_trans_UUID;
    $nombre = $declaracion->userName;
    $ministerio = $declaracion->jurisdiction_value;
    $cargo = $declaracion->job_value;
    $fecha = date("Y-m-d", strtotime($declaracion->created_at));
    $tipo = $declaracion->affidavitTypeValue;
    $revision = $declaracion->revision_value;
    $sql = "SELECT * FROM declarantes WHERE cuit='{$cuit}'";
    $result = $conn->query($sql);
    if ($result->num_rows <= 0)
    {
        $sql = "INSERT INTO declarantes (cuit,nombre,ministerio,cargo,ultima) VALUES ('{$cuit}','{$nombre}','{$ministerio}','{$cargo}', '{$fecha}');";
        if(!$conn->query($sql))
        echo "Error cargando valor a tabla: " .$conn->error ."\n";
    }
    else 
    {
        $declarante = $result->fetch_assoc();
        echo $declarante["ultima"]."-".$fecha."\n";
        if (strtotime($fecha) > strtotime($declarante["ultima"]))
        {
            echo "Atroden";
            $sql = "UPDATE declarantes SET nombre='{$nombre}',ministerio='{$ministerio}',cargo='{$cargo}',ultima='{$fecha}' WHERE cuit='{$cuit}';";
            if(!$conn->query($sql))
                echo "Error cargando valor a tabla: " .$conn->error ."\n";
        }
    }
    $sql = "INSERT IGNORE INTO ministerios (ministerio) VALUES ('{$ministerio}');";
    if(!$conn->query($sql))
        echo "Error cargando valor a tabla: " .$conn->error ."\n";
    $sql ="INSERT IGNORE INTO cargos (cargo) VALUES ('{$cargo}');";
    if(!$conn->query($sql))
        echo "Error cargando valor a tabla: " .$conn->error ."\n";
    $sql = "INSERT IGNORE INTO declaraciones (uuid,cuit,nombre,ministerio,cargo,fecha,tipo,revision) VALUES ({$uuid},'{$cuit}','{$nombre}','{$ministerio}','{$cargo}','{$fecha}','{$tipo}',{$revision});";
    if(!$conn->query($sql))
        echo "Error cargando valor a tabla: " .$conn->error ."\n";
}


?>