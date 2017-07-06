<?php

//Clase para armar el objeto de las credenciales de acceso.
if (isset($_GET["cuit"]))
    $cuit1 = $_GET["cuit"];
else
    $cuit1 = "";
foreach ($_GET as $param => $valor) {
    if ($param != "cuit")
        die("Acceso Incorrecto");
}
class Credentials{
    public $username ='';
    public $password= '';

    //Constructor de la clase Credentials

    public function Credentials($username, $password){
        $this->username = $username;
        $this->password = $password;
    }
}
include 'configuracion.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Acceso Incorrecto");
} 
if ($cuit1 != "") {
     $sql = "SELECT * FROM declarantes";
    $result = $conn->query($sql);
    $cont = 0;
    while($row = $result->fetch_assoc())
    {
        if ($row["cuit"] == $cuit1) {
            $cont++;
        }
    }
    if ($cont == 0) {
        die("Acceso Incorrecto");
    }
}


$sql = "CREATE TABLE IF NOT EXISTS declarantes (
cuit VARCHAR(15) PRIMARY KEY NOT NULL, 
nombre VARCHAR(100) NOT NULL,
ministerio VARCHAR(100),
cargo VARCHAR(100),
ultima DATE,
uuid_last INT(11)
);";

$sql3 = "CREATE TABLE IF NOT EXISTS ministerios (
ministerio VARCHAR(100) PRIMARY KEY NOT NULL
);";

$sql4 = "CREATE TABLE IF NOT EXISTS cargos (
cargo VARCHAR(100) PRIMARY KEY NOT NULL
);";

$sql2 = "CREATE TABLE IF NOT EXISTS declaraciones (
    uuid INT(11) UNSIGNED NOT NULL PRIMARY KEY,
    cuit VARCHAR(15) NOT NULL, 
    nombre VARCHAR(100) NOT NULL,
    ministerio VARCHAR(100),
    cargo VARCHAR(100),
    fecha DATE,
    tipo VARCHAR(30),
    revision INT(2)
);";

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
$rslt = $service->getList($cuit1,0,1000);
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
        $sql = "INSERT INTO declarantes (cuit,nombre,ministerio,cargo,ultima,uuid_last) VALUES ('{$cuit}','{$nombre}','{$ministerio}','{$cargo}', '{$fecha}', '{$uuid}');";
        if(!$conn->query($sql))
        die("Acceso Incorrecto");
    }
    else 
    {
        $declarante = $result->fetch_assoc();
        if (strtotime($fecha) > strtotime($declarante["ultima"]))
        {
            $sql = "UPDATE declarantes SET nombre='{$nombre}',ministerio='{$ministerio}',cargo='{$cargo}',ultima='{$fecha}',uuid_last='{$uuid}' WHERE cuit='{$cuit}';";
            if(!$conn->query($sql))
               die("Acceso Incorrecto");
        }
    }
    $sql = "INSERT IGNORE INTO ministerios (ministerio) VALUES ('{$ministerio}');";
    if(!$conn->query($sql))
        die("Acceso Incorrecto");
    $sql ="INSERT IGNORE INTO cargos (cargo) VALUES ('{$cargo}');";
    if(!$conn->query($sql))
       die("Acceso Incorrecto");
    $sql = "INSERT IGNORE INTO declaraciones (uuid,cuit,nombre,ministerio,cargo,fecha,tipo,revision) VALUES ({$uuid},'{$cuit}','{$nombre}','{$ministerio}','{$cargo}','{$fecha}','{$tipo}',{$revision});";
    if(!$conn->query($sql))
        die("Acceso Incorrecto");
}
if ($cuit1 == "")
    echo "Actualizadas todas las tablas";
else
    echo "Actualizadas tablas para ".$cuit1;
?>