<?php
include 'configuracion.php';
$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT * FROM ministerios;";
if ($conn->connect_error) {
    die("Acceso Incorrecto");
}
if (count($_GET)!= 0){
    die("Acceso Incorrecto");
}
$result = $conn->query($sql);
$ministerios = array();
while($row = $result->fetch_assoc())
{
    array_push($ministerios, $row["ministerio"]);
}
$data = json_encode((array)($ministerios), JSON_UNESCAPED_UNICODE);
echo $data;
?>