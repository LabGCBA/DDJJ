<?php
include 'configuracion.php';
$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT * FROM cargos;";
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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