<?php

//Clase para armar el objeto de las credenciales de acceso.

$declaracionJurada = array(
    "Datos Personales" => array(
        "Información Personal"=> array(),
        "Información Laboral" => array(),
        "Información Académica" => array(),
    ),
    "Datos Laborales" => array(
        "Antecedentes Laborales" => array(),
        "Actividades Actuales" => array(),

    ),
    "Datos Familiares" => array(),
    "Bienes" => array(
        "Bienes Muebles" => array(),
        "Bienes Muebles No Registrables" => array(),
        "Bienes Inmuebles" => array(),
        "Títulos" => array(),
        "Sociedades" => array(),
        "Depósitos" => array(),
        "Otros Trabajos" => array(),
        "Actividades" => array(),
        "Venta de inmuebles" => array(),
        "Deudas" => array(),
        "Acreencias" => array(),
    ),
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

$uuid = $_GET["uuid"];
$url = "http://euf.hml.gcba.gob.ar";
$location = "/dynform-web/transaccionService";


$service = new SoapClient($url.$location."?wsdl");




$params = array("arg0" => $uuid);
$rslt = $service->buscarTransaccionPorUUID($params);

$data = json_encode((array)($rslt->return), JSON_UNESCAPED_UNICODE);
$data = json_decode($data);
$data = $data->valorFormComps;
//var_dump($data);
$i=0;
$subsubproperty = 0;
$contador = 0;
while ($i < count($data) && $data[$i]->orden <= 150) 
{
    $valid = true;
    $j = $data[$i]->orden;
    $property = "";
    $subproperty = "";
    if ($j <= 8) 
    {
        $property = "Datos Personales";
        $subproperty = "Información Personal";
    }
    else if ($j==16)
    {
        $property = "Datos Personales";
        $subproperty = "Información Académica";
    }
    else if ($j <= 22)
    {
        $property = "Datos Personales";
        $subproperty = "Información Laboral";
    }
    else if ($j == 23)
    {
        $contador =  $data[$i]->valorLong+1;
        for ($k = 0; $k < $contador; $k++)
        {
            $declaracionJurada["Datos Laborales"]["Antecedentes Laborales"][strval($k)] = array();
        }
        $valid = false;
    }
    else if ($j <= 30)
    {
        $property = "Datos Laborales";
        $subproperty = "Antecedentes Laborales";
        if ($data[$i-1]->orden >= $j)
        {
            $subsubproperty++;
        }
    }
    else if($j == 31)
    {
        $contador =  $data[$i]->valorLong+1;
        $subsubproperty = 0;
        for ($k = 0; $k < $contador; $k++)
        {
            $declaracionJurada["Datos Laborales"]["Actividades Actuales"][strval($k)] = array();
        }
        $valid = false;
    }
    else if ($j <= 40)
    {
        $property = "Datos Laborales";
        $subproperty = "Actividades Actuales";
        if ($data[$i-1]->orden >= $j)
        {
            $subsubproperty++;
        }
    }
    else if($j == 41)
    {
        $subsubproperty = 0;
        $contador =  $data[$i]->valorLong+1;
        for ($k = 0; $k < $contador; $k++)
        {
            $declaracionJurada["Datos Familiares"][strval($k)] = array();
        }
        $valid = false;
    }
    else if ($j <= 44)
    {
        $property = "Datos Familiares";
        $subproperty = 0;
        if ($data[$i-1]->orden >= $j)
        {
            $subproperty++;
        }
    }
    else if($j == 45)
    {
        $contador =  $data[$i]->valorLong+1;
        for ($k = 0; $k < $contador; $k++)
        {
            $declaracionJurada["Bienes"]["Bienes Muebles"][strval($k)] = array();
        }
        $valid = false;
    }
    else if ($j <= 55)
    {
        $property = "Bienes";
        $subproperty = "Bienes Muebles";
        if ($data[$i-1]->orden >= $j)
        {
            $subsubproperty++;
        }
    }
    else if($j == 56)
    {
        $subsubproperty = 0;
        $contador =  $data[$i]->valorLong+1;
        for ($k = 0; $k < $contador; $k++)
        {
            $declaracionJurada["Bienes"]["Bienes Muebles No Registrables"][strval($k)] = array();
        }
        $valid = false;
    }
    else if ($j <= 66)
    {
        $property = "Bienes";
        $subproperty = "Bienes Muebles No Registrables";
        if ($data[$i-1]->orden >= $j)
        {
            $subsubproperty++;
        }
    }
    else if($j == 67)
    {
        $subsubproperty = 0;
        $contador =  $data[$i]->valorLong+1;
        for ($k = 0; $k < $contador; $k++)
        {
            $declaracionJurada["Bienes"]["Bienes Inmuebles"][strval($k)] = array();
        }
        $valid = false;
    }
    else if ($j <= 82)
    {
        $property = "Bienes";
        $subproperty = "Bienes Inmuebles";
        if ($data[$i-1]->orden >= $j)
        {
            $subsubproperty++;
        }
    }
    else if($j == 83)
    {
        $subsubproperty = 0;
        $contador =  $data[$i]->valorLong+1;
        for ($k = 0; $k < $contador; $k++)
        {
            $declaracionJurada["Bienes"]["Títulos"][strval($k)] = array();
        }
        $valid = false;
    }
     else if ($j <= 93)
    {
        $property = "Bienes";
        $subproperty = "Títulos";
        if ($data[$i-1]->orden >= $j)
        {
            $subsubproperty++;
        }
    }
    else if($j == 94)
    {
        $subsubproperty = 0;
        $contador =  $data[$i]->valorLong+1;
        for ($k = 0; $k < $contador; $k++)
        {
            $declaracionJurada["Bienes"]["Sociedades"][strval($k)] = array();
        }
        $valid = false;
    }
    else if ($j <= 103)
    {
        $property = "Bienes";
        $subproperty = "Sociedades";
        if ($data[$i-1]->orden >= $j)
        {
            $subsubproperty++;
        }
    }
    else if($j == 104)
    {
        $subsubproperty = 0;
        $contador =  $data[$i]->valorLong+1;
        for ($k = 0; $k < $contador; $k++)
        {
            $declaracionJurada["Bienes"]["Depósitos"][strval($k)] = array();
        }
        $valid = false;
    }
    else if ($j <= 110)
    {
        $property = "Bienes";
        $subproperty = "Depósitos";
        if ($data[$i-1]->orden >= $j)
        {
            $subsubproperty++;
        }
    }
    else if($j == 111)
    {
        $subsubproperty = 0;
        $contador =  $data[$i]->valorLong+1;
        for ($k = 0; $k < $contador; $k++)
        {
            $declaracionJurada["Bienes"]["Otros Trabajos"][strval($k)] = array();
        }
        $valid = false;
    }
    else if ($j <= 120)
    {
        $property = "Bienes";
        $subproperty = "Otros Trabajos";
        if ($data[$i-1]->orden >= $j)
        {
            $subsubproperty++;
        }
    }
    else if($j == 121)
    {
        $subsubproperty = 0;
        $contador =  $data[$i]->valorLong+1;
        for ($k = 0; $k < $contador; $k++)
        {
            $declaracionJurada["Bienes"]["Actividades"][strval($k)] = array();
        }
        $valid = false;
    }
    else if ($j <= 126)
    {
        $property = "Bienes";
        $subproperty = "Actividades";
        if ($data[$i-1]->orden >= $j)
        {
            $subsubproperty++;
        }
    }
    else if($j == 127)
    {
        $subsubproperty = 0;
        $contador =  $data[$i]->valorLong+1;
        for ($k = 0; $k < $contador; $k++)
        {
            $declaracionJurada["Bienes"]["Venta de inmuebles"][strval($k)] = array();
        }
        $valid = false;
    }
    else if ($j <= 138)
    {
        $property = "Bienes";
        $subproperty = "Venta de inmuebles";
        if ($data[$i-1]->orden >= $j)
        {
            $subsubproperty++;
        }
    }
    else if($j == 139)
    {
        $subsubproperty = 0;
        $contador =  $data[$i]->valorLong+1;
        for ($k = 0; $k < $contador; $k++)
        {
            $declaracionJurada["Bienes"]["Deudas"][strval($k)] = array();
        }
        $valid = false;
    }
    else if ($j <= 143)
    {
        $property = "Bienes";
        $subproperty = "Deudas";
        if ($data[$i-1]->orden >= $j)
        {
            $subsubproperty++;
        }
    }
    else if($j == 144)
    {
        $subsubproperty = 0;
        $contador =  $data[$i]->valorLong+1;
        for ($k = 0; $k < $contador; $k++)
        {
            $declaracionJurada["Bienes"]["Acreencias"][strval($k)] = array();
        }
        $valid = false;
    }
    else if ($j <= 150)
    {
        $property = "Bienes";
        $subproperty = "Acreencias";
        if ($data[$i-1]->orden >= $j)
        {
            $subsubproperty++;
        }
    }
    else
    {
        $valid = false;
    }
    if ($valid)
    {
        $prop = new Propiedad($data[$i]->etiqueta, property_exists($data[$i], "valorStr") ? $data[$i]->valorStr : (property_exists($data[$i], "valorLong") ? $data[$i]->valorLong : $data[$i]->valorDate), true);
        if ($contador!=0)
            array_push($declaracionJurada[$property][$subproperty][strval($subsubproperty)], $prop);
        else 
            array_push($declaracionJurada[$property][$subproperty], $prop);
    }
    $i++;
    
}

$data = json_encode((array)($declaracionJurada), JSON_UNESCAPED_UNICODE);
echo $data;
?>