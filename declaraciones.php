<?php
include 'configuracion.php';
$conn = new mysqli($servername, $username, $password, $dbname);
$cuit = isset($_GET["cuit"]) ? $_GET["cuit"] : "";
if ($cuit == "")
    $sql = "SELECT * FROM declaraciones";
else
    $sql = "SELECT * FROM declaraciones WHERE cuit='{$cuit}';";
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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