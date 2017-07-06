<?php

//Clase para armar el objeto de las credenciales de acceso.


$uuid = isset($_GET["uuid"]) ? $_GET["uuid"] : die("UUID no especificado");
$url = "http://euf.gcba.gob.ar";
$location = "/dynform-web/transaccionService";


$service = new SoapClient($url.$location."?wsdl");

foreach ($_GET as $param => $valor) {
    if ($param != "uuid")
        die("Acceso Incorrecto");
}

include 'configuracion.php';
$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT * FROM declaraciones";
$result = $conn->query($sql);
$declaraciones = array();
$cont = 0;
while($row = $result->fetch_assoc())
{
    if ($row["uuid"] == $uuid) {
        $cont++;
    }
}
if ($cont == 0) {
    die("Acceso Incorrecto");
}


$params = array("arg0" => $uuid);
$rslt = $service->buscarTransaccionPorUUID($params);

$data = json_encode((array)($rslt->return), JSON_UNESCAPED_UNICODE);
$data = json_decode($data);
$data = $data->valorFormComps;
$result = "";
foreach($data as $campo)
{
    $campo->etiqueta = str_replace(",", "-", $campo->etiqueta);
    $result .= $campo->etiqueta.",";
}
$result = substr($result, 0, -1);
$result .= "\r\n";
foreach($data as $campo)
{
    $result .= property_exists($campo, "valorStr") ? $campo->valorStr."," : (property_exists($campo, "valorLong") ? $campo->valorLong."," : $campo->valorDate.",");
}
$result = substr($result, 0, -1);
$file = "declaracion{$uuid}.csv";
$myfile = fopen($file, "w") or die("No se pudo abrir");
fwrite($myfile, $result);
fclose($myfile);
header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file));
readfile($file);
exit;
?>