<?php
/*
   $_usuario="sitio2k_dinamica";
   $_password="Dinamica001";
   mysql_connect("localhost",$_usuario,$_password);
   mysql_select_db(sitio2k_dinamica);  
*/


$_usuario="root";
$_password="";
mysql_connect('localhost',$_usuario,$_password);
mysql_select_db('new_spazios');


define('PATH_RAIZ_FOTOS', 'files/');
define('IVA', '21');
define('INTERES_DIA_MORA', '0');
define('CANT_DIGITOS', '2');// cantidad de decimales a imprimir

?>