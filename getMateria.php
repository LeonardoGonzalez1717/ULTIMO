<?php
	require_once 'model/conexion.php';
	session_start();
	$id_ano = $_POST['id_ano'];
	$periodo = $_SESSION['periodos']['periodo'];
	
	// $id_estado = "hola";
	$queryM = "SELECT p.id, m.materia FROM pensum p inner join materia m on m.id = p.id_materia WHERE p.id_ano = '$id_ano' and p.cursando = '$periodo'";
	$resultadoM = mysqli_query($db, $queryM);
	
	$html= "<option value='0'>Seleccionar una materia</option>";
	
	if (mysqli_num_rows($resultadoM) == 0) {
		$html .= "<option value='0'>No hay pensum creado de esta materia</option>";
		
		
	}else{
		while($rowM = $resultadoM->fetch_assoc())
		{
			$html.= "<option value='".$rowM['id']."'>".$rowM['materia']."</option>";
		}

	}
	echo $html;
?>