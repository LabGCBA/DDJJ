<?php
include 'configuracion.php';
$conn = new mysqli($servername, $username, $password, $dbname);
$cuit = isset($_GET["cuit"]) ? $_GET["cuit"] : "";
foreach ($_GET as $param => $valor) {
    if ($param != "cuit")
        die("Acceso Incorrecto");
}
if ($cuit == "")
    $sql = "SELECT * FROM declaraciones";
else
    $sql = "SELECT * FROM declaraciones WHERE cuit='{$cuit}';";
if ($conn->connect_error) {
    die("Acceso Incorrecto");
} 
$result = $conn->query($sql);
$declaraciones = array();
while($row = $result->fetch_assoc())
{
    array_push($declaraciones, $row);
}
$data = json_encode((array)($declaraciones), JSON_UNESCAPED_UNICODE);
echo $data;
?>