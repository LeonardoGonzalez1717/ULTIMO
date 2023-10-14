<?php 
    require 'model/conexion.php';
    require_once 'fpdf/fpdf.php';
    session_start();
    if (!isset($_SESSION['usuario_admin']) && !isset($_SESSION['usuario_lector'])) {
        $_SESSION['alertas'] = 'Por favor introducir un usuario';
        echo '<script>';
            echo 'window.location="login_form.php"';
             echo '</script>';
    }
if (isset($_GET['ano'])) {
    $id_ano = $_GET['ano'];
}else {
    echo '<script>';
    echo 'window.location="index.php"';
     echo '</script>';
     exit;
}
class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
     $periodo = $_SESSION['periodos']['periodo'];
    $this->Image('img/candelaria.png', 10, 10, 45);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Movernos a la derecha
    $this->Cell(80);
    // Título
    $this->Cell(60,10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", 'Nomina estudiantil '),1,1,'C');
    // Salto de línea
    $this->Ln(20);
    $this->Cell(80,10, 'Periodo', 'LTRB',0,'C', 0);
    $this->Cell(80,10, $periodo, 'LTRB',1,'C', 0);
    if (isset($_GET['ano'])) {
        
        $id_ano = $_GET['ano'];
        $this->Cell(180,10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", 'Estudiantes de '.' '. $id_ano), '0',1,'C', 0);
    }else{
        
        $this->Cell(180,10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", 'Todos los estudiantes'), '0',1,'C', 0);
    }
    
   


}
function Footer() {
    // Posiciona el pie de página a 1.5 cm del borde inferior
    $this->SetY(-15);

    // Selecciona la fuente Arial itálica 8
    $this->SetFont('Arial', 'I', 8);

    
    $this->Cell(0, 5, 'NOTA: los espacios en blanco son solo complementar la cantidad de notas de las otras materias', 0, 1, 'C');
    $this->Cell(0, 5, 'U.E.P APEP LA CANDELARIA, Turmero 2115, Aragua', 0, 0, 'C');
}

}
$pdf = new PDF();
$pdf ->AddPage();
$pdf ->SetFont('Arial','',12);   

    
    $pdf->Cell(50,10, 'Nombre', 1,0,'C', 0);
    $pdf->Cell(50,10, 'Apellido', 1,0,'C', 0);
    $pdf->Cell(30,10, 'Cedula', 1,0,'C', 0);
    if (isset($_GET['general'])) {
        $pdf->Cell(30,10, 'Año', 1,0,'C', 0);
    }

    $pdf->Cell(35,10,  iconv("UTF-8", "ISO-8859-1//TRANSLIT", 'Sección/Mención'), 1,1,'C', 0);

    $id_periodo = $_SESSION['periodos']['id'];
    if (isset($_GET['ano'])) {
        
        $sql = "select a.nombre,a.apellido,a.cedula, an.ano, s.seccion from cursando c inner join ano an on an.id = c.id_ano inner join seccion s on s.id = an.id_seccion inner join alumno a on a.id = c.id_alumno where ano = '$id_ano' and id_periodo = $id_periodo";
        $query = mysqli_query($db, $sql);
    }elseif ($_GET['general']) {
        $sql = "select a.nombre,a.apellido,a.cedula, an.ano, s.seccion from cursando c inner join ano an on an.id = c.id_ano inner join seccion s on s.id = an.id_seccion inner join alumno a on a.id = c.id_alumno where id_periodo = $id_periodo order by an.ano, an.id";
        $query = mysqli_query($db, $sql);
    }

        while($querys = mysqli_fetch_assoc($query)){
            $cedula = number_format($querys['cedula'], 0, '.', '.' );

                $pdf->Cell(50,10,iconv("UTF-8", "ISO-8859-1//TRANSLIT", $querys['nombre']),1,0,'C',0);
                $pdf->Cell(50,10,iconv("UTF-8", "ISO-8859-1//TRANSLIT", $querys['apellido']),1,0,'C',0);
                $pdf->Cell(30,10, $cedula,1,0,'C',0);
                if (isset($_GET['general'])) {
                    $pdf->Cell(30,10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $querys['ano']), 1,0,'C', 0);
                }
                $pdf->Cell(35,10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $querys['seccion']),1,1,'C',0);
        }
        $pdf->Output();