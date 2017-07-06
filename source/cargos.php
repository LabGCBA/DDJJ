<?php
include 'configuracion.php';
$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT * FROM cargos;";
if ($conn->connect_error) {
    die("Acceso Incorrecto");
}
if (count($_GET)!= 0){
    die("Acceso Incorrecto");
}
$result = $conn->query($sql);
$cargos = array();
while($row = $result->fetch_assoc())
{
    array_push($cargos, $row["cargo"]);
}
$data = json_encode((array)($cargos), JSON_UNESCAPED_UNICODE);
echo $data;
?>