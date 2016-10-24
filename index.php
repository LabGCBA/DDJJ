<?php

//Clase para armar el objeto de las credenciales de acceso.

class Credentials{
    public $username ='';
    public $password= '';

    //Constructor de la clase Credentials

    public function Credentials($username, $password){
        $this->username = $username;
        $this->password = $password;
    }
}

$declaracionJurada = array(
    "Datos Personales" => array(
        "Información Personal"=> array(),
        "Información Laboral" => array(),
        "Información Académica" => array(),
    ),
    "Datos Laborales" => array(
        "Antecedentes Laborales" => array(),
        "Actividades Actuales" => array(
            "Trabajo" => array(),
        ),

    ),
    "Datos Familiares" => array(),
    "Bienes" => array(),
);

class Propiedad{
    public $nombre = "";
    public $value = "";
    public $isPublic = false;

    public function Propiedad($nombre, $value, $isPublic){
        $this->nombre = $nombre;
        $this->value = $value;
        $this->isPublic = $isPublic;
    }
}

$url = "http://10.79.0.72";
$url = "http://euf.hml.gcba.gob.ar";
$location = "/dynform-web/transaccionService";

$service = new SoapClient(null, array("location" => $url."/ddjj/services/historico/list.php", "uri" => $url));
$service = new SoapClient($url.$location."?wsdl");

$service->__setSoapHeaders(new SoapHeader($url,'authenticate', new Credentials('20279391137', 'testmarino')));

//$rslt = $service->getList();
$params = array("arg0" => 562790);
$rslt = $service->buscarTransaccionPorUUID($params);

$data = json_encode((array)($rslt->return), JSON_UNESCAPED_UNICODE);
$data = json_decode($data);
$data = $data->valorFormComps;
//var_dump($data);
$i=0;
while ($data[$i]->orden <= 144) {
    $j = $data[$i]->orden;
    $property = "";
    $subpropery = "";
    $subsubproperty = "";
    if ($j <= 8) {
        $property = "Datos Personales";
        $subpropery = "Información Personal";
    }
    else if ($j==16){
        $property = "Datos Personales";
        $subpropery = "Información Académica";
    }
    else if ($j <= 22){
        $property = "Datos Personales";
        $subpropery = "Información Laboral";
    }
    else if ($j <= 30){
        $property = "Datos Laborales";
        $subpropery = "Antecedentes Laborales";
        $subsubproperty = 0;
        if ($data[$i-1]->orden >= $j){
            $subsubproperty++;
        }
    }
    prop = new Propiedad($data[$i]->etiqueta, property_exists($data[$i], "valorStr") ? $data[$i]->valorStr : (property_exists($data[$i], "valorLong") ? $data[$i]->valorLong : $data[$i]->valorDate), true);
    array_push($declaracionJurada[$property][$subpropery], array());
    $i++;
    
}

$data = json_encode((array)($declaracionJurada), JSON_UNESCAPED_UNICODE);
echo $data;
?>