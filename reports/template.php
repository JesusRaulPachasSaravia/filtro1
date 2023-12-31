<?php

// Librerias obtenidas COMPOSER
require '../../vendor/autoload.php';

// Namespaces (espacios de nombres/contenedor de clase)
use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

// Como estructurar el reporte?
// PDF = multiples paginas / cada seccion se construya de forma independiente
// Construccion reporte PDF
try{

  // Contenido (HTML) que renderizaremos como PDF
  $content = "";
  
  // Iniciamos la creacion del binario
  ob_start();

  // Estilos
  include('../estilos.html');

  // Invocar todas las secciones (archivo.php)
  // include './seccion1.php';


  // Cierre en el proceso de creacion de binarios
  $content .= ob_get_clean();

  // Configuracion del archivo PDF
  // P = portrait (vertical) / L = landscape(horizontal)
  $html2pdf = new Html2Pdf('P', 'A4', 'es', true, 'UTF-8', array(15,5,15,5));
  $html2pdf->writeHTML($content);
  $html2pdf->output('reporte.pdf');


} catch (Html2PdfException $error){
  $formatter = new ExceptionFormatter($error);
  echo $formatter->getHtmlMessage();
}
?>