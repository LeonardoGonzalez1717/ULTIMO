<?php 
require 'model/conexion.php';
require_once 'fpdf/fpdf.php';
if (!isset($_SESSION['usuario_admin']) && !isset($_SESSION['usuario_lector'])) {
    $_SESSION['alertas'] = 'Por favor introducir un usuario';
    echo '<script>';
        echo 'window.location="login_form.php"';
         echo '</script>';
}
session_start();
$periodo = $_SESSION['periodos']['periodo'];

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
        require 'model/conexion.php';
        $alumno_ = $_GET['alumno'];
        if (isset($_GET['lapso'])) {
            
            $lapso = $_GET['lapso'];
        }

       
        $sql_a = "select a.ano, s.seccion from cursando c inner join ano a on a.id = c.id_ano inner join seccion s on s.id = a.id_seccion where id_alumno = $alumno_";
        $query = mysqli_query($db, $sql_a);
        $querys = mysqli_fetch_assoc($query);


        $sql_name = "select * from alumno where id = $alumno_";
        $query_name = mysqli_query($db, $sql_name);
        $querys_name = mysqli_fetch_assoc($query_name);
    $periodo = $_SESSION['periodos']['periodo'];
    $this->Image('img/candelaria.png', 10, 10, 40);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Movernos a la derecha
    $this->Cell(80);
    // Título
    $this->Cell(80,10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", 'Boletín estudiantil '),1,0,'C');
    // Salto de línea
    $this->Ln(20);

    $this->Cell(80,10,iconv("UTF-8", "ISO-8859-1//TRANSLIT", 'Año escolar'), 'LTRB',0,'C', 0);
    $this->Cell(80,10,iconv("UTF-8", "ISO-8859-1//TRANSLIT", $querys['ano'].''.$querys['seccion']), 'LTRB',1,'C', 0);
    $this->Cell(80,10, 'Nombre', 'LTRB',0,'C', 0);
    $this->Cell(80,10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $querys_name['nombre'].' '.$querys_name['apellido']), 'LTRB',1,'C', 0);
    $this->Cell(80,10, 'Periodo', 'LTRB',0,'C', 0);
    $this->Cell(80,10, $periodo, 'LTRB',1,'C', 0);
    $this->Cell(80,10, 'Lapso', 'LTRB',0,'C', 0);
    if (isset($_GET['lapso'])) {
        $this->Cell(80,10, 'Lapso '.$lapso, 'LTRB',1,'C', 0);
    }else{
        $this->Cell(80,10, 'Todos ', 'LTRB',1,'C', 0);
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
$pdf->SetFont('Arial','',16);
if (isset($_GET['lapso']) && isset($_GET['alumno'])) {
    
    $pdf->Cell(70,20, 'Materia', 0,0,'C', 0);
    $pdf->Cell(75,20, 'Notas', 0,0,'C', 0);
    $pdf->Cell(30,20, 'Promedio', 0,1,'C', 0);
}elseif(isset($_GET['alumno'])){
    $pdf->Cell(70,20, 'Materia', 0,0,'C', 0);
    $pdf->Cell(75,20, 'Notas finales', 0,0,'C', 0);
    $pdf->Cell(30,20, 'Promedio', 0,1,'C', 0);
}

$id_ano = $_GET['ano'];
if (isset($_GET['lapso']) && isset($_GET['alumno']) ) {

    $id_alumno = $_GET['alumno'];
    $lapso = $_GET['lapso'];

    $sql_materias = "select count(id_materia) as materia from pensum where id_ano = $id_ano and cursando = '$periodo'";
    $query_materia = mysqli_query($db, $sql_materias);
    $querys_materia = mysqli_fetch_assoc($query_materia);
    $count_materia = $querys_materia['materia'];

    $sql_count = "select distinct id_pensum as pensum from notas where id_alumno = $id_alumno and lapso = $lapso";
    $query_count = mysqli_query($db,$sql_count);
    $querys_count = mysqli_fetch_assoc($query_count);
    $count_query = mysqli_num_rows($query_count);
    if ($count_materia != $count_query) {
        $_SESSION['alerta'] = 'Se deben registrar todas las materias para crear el boletin!';
        header('location: notas_general.php?ano='.$id_ano.'&alumno='.$id_alumno);
    }else{

        
        
        //sacar el count con la nota maxima
        $sql_max = "SELECT  COUNT(nota) as nota from notas where id_alumno = $id_alumno and lapso = $lapso and periodo = '$periodo' GROUP by id_pensum, lapso order by nota DESC LIMIT 1";
    $query_max = mysqli_query($db, $sql_max);
    $querys_max = mysqli_fetch_assoc($query_max);
    
    $sqlM = "select DISTINCT m.materia, n.id_pensum, AVG(n.nota) as notas from notas n inner join pensum p on n.id_pensum = p.id inner join materia m on m.id = p.id_materia where n.id_alumno = $id_alumno and n.lapso = $lapso and periodo = '$periodo' GROUP by n.lapso, n.id_pensum;";
    $resultadoM = mysqli_query($db, $sqlM);
    

  
    while ($resultado = mysqli_fetch_assoc($resultadoM)) {
        $nota_count = array(); 
    $promedio = number_format($resultado['notas'], 2);
    $pdf->Cell(75,10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $resultado['materia']), 1,0,'C', 0);
    $id_pensum = $resultado['id_pensum'];
    
    
    

    //notas una por una
    $sql = "select nota from notas where id_alumno = $id_alumno and id_pensum = $id_pensum and lapso = $lapso and periodo = '$periodo'";
    $notas = mysqli_query($db, $sql);
    while($nota = mysqli_fetch_assoc($notas)){
        $nota_count[] = $nota['nota'];
    }
    //array_pad =  se utiliza para rellenar un array con un valor específico hasta alcanzar una longitud deseada. el primer valor son todas las notas, el segundo el valor a donde quiero llegar(4) y el tercero con que lo voy a rellenar
    $nota_count = array_pad($nota_count, $querys_max['nota'], '');
    
    foreach ($nota_count as $nota_vacia) {
        $pdf->Cell(20, 10, $nota_vacia, 1, 0, 'C', 0);
    }
    
    $pdf ->Cell(30,10, $promedio,1,1,'R',0);
}
}

}elseif (isset($_GET['alumno'])) {
    $id_alumno = $_GET['alumno'];


    $sql_materia = "SELECT DISTINCT id_pensum FROM notas WHERE id_alumno = $id_alumno and periodo = '$periodo' GROUP by lapso, id_pensum";
    $query_materia = mysqli_query($db, $sql_materia);
    
    
    while($querys_materia = mysqli_fetch_assoc($query_materia)){

        $id_pensum = $querys_materia['id_pensum'];

        $sql_lapso_prueba = "select DISTINCT lapso from notas where id_pensum = $id_pensum and id_alumno = $id_alumno";
        $query_lapso = mysqli_query($db, $sql_lapso_prueba);
        $count = mysqli_num_rows($query_lapso);
        if ($count != 3) {
            $_SESSION['alerta'] = 'Por favor registrar las notas en los 3 lapsos de todas las materias';
            header('location: notas_general.php?ano='.$id_ano.'&alumno='.$id_alumno);
        }
    }

    $sql_materias = "select count(id_materia) as materia from pensum where id_ano = $id_ano and cursando = '$periodo'";
    $query_materia = mysqli_query($db, $sql_materias);
    $querys_materia = mysqli_fetch_assoc($query_materia);
    $count_materia = $querys_materia['materia'];

    $sql_count = "select distinct id_pensum as pensum from notas where id_alumno = $id_alumno ";
    $query_count = mysqli_query($db,$sql_count);
    $querys_count = mysqli_fetch_assoc($query_count);
    $count_query = mysqli_num_rows($query_count);

    if ($count_materia != $count_query) {
        $_SESSION['alerta'] = 'Se deben registrar todas las materias para crear el boletin!';
        header('location: notas_general.php?ano='.$id_ano.'&alumno='.$id_alumno);
    }else{

        
        
    $sqlM = "SELECT DISTINCT materia, id_pensum FROM notas n INNER JOIN pensum p on p.id = n.id_pensum INNER JOIN materia m on m.id = p.id_materia WHERE id_alumno = $id_alumno and periodo = '$periodo'";
    $resultadoM = mysqli_query($db, $sqlM);
    while ($resultado = mysqli_fetch_assoc($resultadoM)) {
        $promedios_total = array();
        $id_pensum = $resultado['id_pensum'];

        $pdf->Cell(75,10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $resultado['materia']), 1,0,'C', 0);

        $nota_final = "select avg(n.nota) as nota_final,m.materia from notas n inner join pensum p on p.id = n.id_pensum inner join materia m on p.id_materia = m.id where n.id_alumno = $id_alumno and n.id_pensum = $id_pensum and periodo = '$periodo' group by n.lapso, n.id_pensum";
        $nota = mysqli_query($db, $nota_final);
        if (!empty($nota)) {
            while ($notas = mysqli_fetch_assoc($nota)){
                $notas_limitadas = number_format($notas['nota_final'], 2);
                $pdf->Cell(25,10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $notas_limitadas), 1,0,'C', 0);
           }
        }
        $sql = "SELECT AVG(nota) AS promedio FROM notas where id_alumno = $id_alumno and id_pensum = $id_pensum GROUP BY id_pensum, lapso;"; 
        $promedios = mysqli_query($db, $sql);
        if (!empty($promedios)) {
            while($promedio = mysqli_fetch_assoc($promedios)){
                $promedios_total[] = $promedio['promedio'];
            $promedios_final = array_sum($promedios_total)/count($promedios_total);
        } 
        
    }
    $promedios_final = number_format($promedios_final, 2);
    $pdf->Cell(40,10, iconv("UTF-8", "ISO-8859-1//TRANSLIT", $promedios_final), 1,1,'R', 0);
    }
    
}
}


    $pdf->Output();

?>