<?php
include 'configuracion.php';
$conn = new mysqli($servername, $username, $password, $dbname);
$ministerio = isset($_GET["ministerio"]) ? $_GET["ministerio"] : "";
$cargo = isset($_GET["cargo"]) ? $_GET["cargo"] : "";
$busqueda = isset($_GET["busqueda"]) ? $_GET["busqueda"] : "";
if ($busqueda == "")
{
    if ($ministerio == "")
    {
        if ($cargo == "")
        {
            $sql = "SELECT * FROM declarantes;";
        }
        else
        {
            $sql = "SELECT * FROM declarantes WHERE upper(cargo) LIKE upper('%{$cargo}%');";
        }
    }
    else
    {
        if ($cargo == "")
        {
            $sql = "SELECT * FROM declarantes WHERE upper(ministerio) LIKE upper('%{$ministerio}%');";
        }
        else
        {
            $sql = "SELECT * FROM declarantes WHERE upper(cargo) LIKE upper('%{$cargo}%') AND upper(ministerio) LIKE upper('%{$ministerio}%');";
        }
    }
}
else
{
    if ($ministerio == "")
    {
        if ($cargo == "")
        {
            $sql = "SELECT * FROM declarantes WHERE upper(nombre) LIKE upper('%{$busqueda}%') OR upper(ministerio) LIKE upper('%{$busqueda}%') OR upper(cargo) LIKE upper('%{$busqueda}%');";
        }
        else
        {
            $sql = "SELECT * FROM declarantes WHERE upper(cargo) LIKE upper('%{$cargo}%') AND (upper(nombre) LIKE upper('%{$busqueda}%') OR upper(ministerio) LIKE upper('%{$busqueda}%') OR upper(cargo) LIKE upper('%{$busqueda}%'));";
        }
    }
    else
    {
        if ($cargo == "")
        {
            $sql = "SELECT * FROM declarantes WHERE upper(ministerio) LIKE upper('%{$ministerio}%') AND (upper(nombre) LIKE upper('%{$busqueda}%') OR upper(ministerio) LIKE upper('%{$busqueda}%') OR upper(cargo) LIKE upper('%{$busqueda}%'));";
        }
        else
        {
            $sql = "SELECT * FROM declarantes WHERE upper(cargo) LIKE upper('%{$cargo}%') AND upper(ministerio) LIKE upper('%{$ministerio}%') AND (upper(nombre) LIKE upper('%{$busqueda}%') OR upper(ministerio) LIKE upper('%{$busqueda}%') OR upper(cargo) LIKE upper('%{$busqueda}%'));";
        }
    }
}

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$result = $conn->query($sql);
$resultado = array();
while($row = $result->fetch_assoc())
{
    array_push($resultado, $row);
}
$data = json_encode((array)($resultado), JSON_UNESCAPED_UNICODE);
echo $data;
?>