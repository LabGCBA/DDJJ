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
$url = "http://10.79.0.72";
$service = new SoapClient(null, array("location" => $url."/ddjj/services/historico/list.php", "uri" => $url));
$service->__setSoapHeaders(new SoapHeader($url,'authenticate', new Credentials('20279391137', 'testmarino')));
$rslt = $service->getList($cuit,0,200);
echo $rslt;
?>