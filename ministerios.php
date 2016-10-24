<?php
$servername = "localhost";
$username = "hernanpc";
$password = "6268";
$dbname = "ddjj";
$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT * FROM ministerios;";
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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