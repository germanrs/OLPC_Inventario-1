<?php

namespace RNR\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\Validator\Constraints as Assert;
require_once dirname(__FILE__).'/../../Classes/PHPExcel.php';
require_once dirname(__FILE__).'/../../Classes/PHPExcel.php';
require_once dirname(__FILE__).'/../../Classes/tcpdf/examples/config/tcpdf_config_alt.php';
require_once dirname(__FILE__).'/../../Classes/tcpdf/tcpdf.php';

/**
 * Controller for the authors
 * @author Rein Bauwens	<rein.bauwens@student.odisee.be>
 */
class ExportController implements ControllerProviderInterface {

	/**
	 * Returns routes to connect to the given application.
	 * @param Application $app An Application instance
	 * @return ControllerCollection A ControllerCollection instance
	 */
	public function connect(Application $app) {

		//@note $app['controllers_factory'] is a factory that returns a new instance of ControllerCollection when used.
		//@see http://silex.sensiolabs.org/doc/organizing_controllers.html
		$controllers = $app['controllers_factory'];

		// Bind sub-routes
		$controllers
			->get('/', array($this, 'export'))
			->method('GET|POST')
			->bind('Export');

		$controllers
			->get('/excel/', array($this, 'excel'))
			->method('GET|POST')
			->bind('Export.excel');

		$controllers
			->get('/barcodes/', array($this, 'barcodes'))
			->method('GET|POST')
			->bind('Export.barcodes');
		// Return ControllerCollection
		return $controllers;

		
	}

	/**
	 * Export page
	 * @param Application $app An Application instance
	 * @return string A blob of HTML where the user can ask for data from the database in an excel or pdf-file. 
	 */
	public function export(Application $app) {
				
		//check if the user is logged in
		//set acces level and username to default
		$access_level = 0;
		$username = '';

		// check if user is already logged in
		if ($app['session']->get('user') && ($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

			//get the user from de database.
			$user = $app['db.people']->fetchAdminPerson($app['session']->get('user'));

			//set acces level and username
			$username = $user[0]['name'];
			$access_level = $user[0]['access_level'];
		}
		else{

			//redirect to login page if user is not logged id
			return $app->redirect($app['url_generator']->generate('auth.login')); 
		}
		

		//return the rendered twig with parameters
		return $app['twig']->render('Export/index.twig', array(
			'access_level' => $access_level,
			'username' => $username
		));	
	}

	/**
	 * barcodes 
	 * @param Application $app An Application instance
	 * @return pdf file of a place with all users and barcodes.
	 */
	public function barcodes(Application $app) {
				
		$data='';

		// check if user is already logged in
		if ($app['session']->get('user') && ($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

		}
		else{

			//redirect to login page if user is not logged in
			return $app->redirect($app['url_generator']->generate('auth.login')); 
		}
		
		//get all the data from the url and put them in an array
		$obj = array('Ciudad' =>  $_GET["Ciudad"],
					'Escuela' =>  $_GET["Escuela"],
					'Turno' =>  $_GET["Turno"],
					'grado' =>  $_GET["grado"],
					'Seccion' =>  $_GET["Seccion"],
					'Departamento' =>  $_GET["Departamento"]);
		//get the id's of the sended places
		$peoplearray = array();
		$Departamentoid = $app['db.places']->getPlace($obj['Departamento'], 2);
		$cityid = $app['db.places']->getCityByName($obj['Ciudad']);
		$placeid =1;
		$schoolid = $app['db.places']->getitemByNameandAncestorID($obj['Escuela'], $cityid);
		$turnoId = $app['db.places']->getitemByNameandAncestorID($obj['Turno'], $schoolid);
		$gradoid = $app['db.places']->getitemByNameandAncestorID($obj['grado'], $turnoId);
		$Seccionid = $app['db.places']->getitemByNameandAncestorID($obj['Seccion'], $gradoid);	
		$data = array();

		//if Seccionid is not empty, get all the data from a seccion
		if(!empty($Seccionid)){
			array_push($data, array('name' => $obj['Departamento'].' : '. $obj['Ciudad'].' : '. $obj['Escuela'].' : '. $obj['Turno'].' : '. $obj['grado'].' : '. $obj['Seccion'], 'data' => $app['db.laptops']->fetchbarcodeList($obj,$Seccionid)));
		}

		//if gradoid is not empty, get all the data from a grade and the children of it
		else if(!empty($gradoid)){
			$seccions =  $app['db.places']->fetchSeccion($gradoid);
			foreach ($seccions as $seccion) {
				array_push($data, array('name' => $obj['Departamento'].' : '. $obj['Ciudad'].' : '. $obj['Escuela'].' : '. $obj['Turno'].' : '. $obj['grado'].' : '. $seccion['name'], 'data' => $app['db.laptops']->fetchbarcodeList($obj,$seccion['id'])));
			}
		}

		//if turnoId is not empty, get all the data from a turno and the children of it
		else if(!empty($turnoId)){
			$grades =  $app['db.places']->fetchGrade($turnoId);
			foreach ($grades as $grade) {
				$seccions =  $app['db.places']->fetchSeccion($grade['id']);
				foreach ($seccions as $seccion) {
					array_push($data, array('name' => $obj['Departamento'].' : '. $obj['Ciudad'].' : '. $obj['Escuela'].' : '. $obj['Turno'].' : '. $grade['name'].' : '. $seccion['name'], 'data' => $app['db.laptops']->fetchbarcodeList($obj,$seccion['id'])));
				}
			}
		}

		//if schoolid is not empty, get all the data from a school and the children of it
		else if(!empty($schoolid)){
			$teachers = $app['db.laptops']->fetchbarcodeList($obj,$schoolid);
			array_push($data, array('name' => $obj['Departamento'].' : '. $obj['Ciudad'].' : '. $obj['Escuela'], 'data' => $teachers));
			$turnos =  $app['db.places']->fetchTurno($schoolid);
			foreach ($turnos as $turno) {
				$grades =  $app['db.places']->fetchGrade($turno['id']);
				foreach ($grades as $grade) {
					$seccions =  $app['db.places']->fetchSeccion($grade['id']);
					foreach ($seccions as $seccion) {
						array_push($data, array('name' => $obj['Departamento'].' : '. $obj['Ciudad'].' : '. $obj['Escuela'].' : '. $turno['name'].' : '. $grade['name'].' : '. $seccion['name'], 'data' => $app['db.laptops']->fetchbarcodeList($obj,$seccion['id'])));
					}
				}
			}
		}

		//set the name
	    $places='Nicaragua';
	    if($_GET["Departamento"] != ''){
	        $places .= ' : '. $_GET["Departamento"];
	        if($_GET["Ciudad"] != ''){
	            $places .= ' : '.$_GET["Ciudad"];
	            if($_GET["Escuela"] != ''){
	                $places .= ' : '.$_GET["Escuela"];
	                if($_GET["Turno"] != ''){
	                    $places .= ' : '.$_GET["Turno"];
	                    if($_GET["grado"] != ''){
	                        $places .= ' : '.$_GET["grado"];
	                        if($_GET["Seccion"] != ''){
	                            $places .= ' : '.$_GET["Seccion"];
	                        }
	                    }
	                }
	            }
	        }
	    }
		// create new PDF document
		$pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, false, 'ISO-8859-1', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('FZT');
		$pdf->SetTitle('List of barcodes');
		$pdf->SetSubject($places);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, 10, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		    require_once(dirname(__FILE__).'/lang/eng.php');
		    $pdf->setLanguageArray($l);
		}

		// set font
		$pdf->SetFont('helvetica', 'B', 20);

		// add a page
		$pdf->AddPage();

		//write text 
		$pdf->Write(0, 'List of barcodes', '', 0, 'L', true, 0, false, false, 0);

		// set font
		$pdf->SetFont('helvetica', '', 15);

		//write text 
		$pdf->Write(0, $places, '', 0, 'L', true, 0, false, false, 0);

		// set font
		$pdf->SetFont('helvetica', '', 13);

		// for every class, set the correct data in the pdf file
		foreach ($data as $class) {
			$namesPerSchoolClass = '';
			$barcodesoffallchildren = '<table cellspacing="0" cellpadding="1" border="1"  style="text-align: center; page-break-before: always;" ><tr>';
			$pdf->Write(0, $class['name'], '', 0, 'L', true, 0, false, false, 0);
			$tbl ="";
			$teller =1;
			$tellernamen = 1;
			$total = count($class['data']);

			//get the correct full name of the class
			foreach ($class['data'] as $child) {
				$classname = '';
				$place_type_id='';
				switch (true) {
				    case stristr($class['name'],'Primer Grado'):
				        $place_type_id=1;
				        break;
				    case stristr($class['name'],'Segundo Grado'):
				        $place_type_id=2;
				        break;
				    case stristr($class['name'],'Tercer Grado'):
				        $place_type_id=3;
				        break;
				    case stristr($class['name'],'Cuarto Grado'):
				        $place_type_id=4;
				        break;
				    case stristr($class['name'],'Quinto Grado'):
				        $place_type_id=5;
				        break;
				    case stristr($class['name'],'Sexto Grado'):
				        $place_type_id=6;
				        break;
				    case stristr($class['name'],'Septimo grado'):
				        $place_type_id=7;
				        break;
				    case stristr($class['name'],'Octavo grado'):
				        $place_type_id=8;
				        break;
				    case stristr($class['name'],'Noveno grado'):
				        $place_type_id=9;
				        break;
				    case stristr($class['name'],'Preescolar'): 
				        $place_type_id=Pr;
				        break;
				    case stristr($class['name'],'Educacion Especial'):
				        $place_type_id=ES;
				        break;
				    }
				$secciond_id='';

				//get the correct full name of the class
				switch (true) {
				    case stristr($class['name'],'Seccion A'):
				        $secciond_id='A';
				        break;
				    case stristr($class['name'],'Seccion B'):
				        $secciond_id='B';
				        break;
				    case stristr($class['name'],'Seccion C'):
				        $secciond_id='C';
				        break;
				}

				// if the child exists, add it to the list of children, the design of the html depends on wich child in the list he is
				if(!empty($child['fullname'])){
					if($tellernamen % 2 == 0){
						$namesPerSchoolClass .= '<td width="320"  border="1"  style="font-size: x-large;" 	height="35" cellpadding="12">'.((strlen($child['fullname'])>20)?substr($child['fullname'],0,20).'...':$child['fullname']).' '.$place_type_id.$secciond_id.'</td></tr>';
					}
					else{
						$namesPerSchoolClass .= '<tr><td width="320"  border="1" height="35" style="font-size: x-large;" cellpadding="12">'.((strlen($child['fullname'])>20)?substr($child['fullname'],0,20).'...':$child['fullname']).' '.$place_type_id.$secciond_id.'</td>';
					}
					$params = $pdf->serializeTCPDFtagParameters(array($child['barcode'], 'C128A', '', '', 55, 15, 0.4, array('position'=>'C', 'border'=>false, 'padding'=>1, 'fgcolor'=>array(0,0,0), 'bgcolor'=>array(255,255,255), 'text'=>true, 'font'=>'helvetica', 'fontsize'=>5, 'stretchtext'=>4), 'N'));
					$barcodesoffallchildren.= '<td width="214"><div><small>'.((strlen($child['fullname'])>22)?substr($child['fullname'],0,22).'...':$child['fullname'])."<br>". $class['name'].'</small></div><tcpdf method="write1DBarcode" params="'.$params.'" /></td>';
					
					if($teller % 24 == 0 && $total != $teller){
						$barcodesoffallchildren.= '</tr></table><table cellspacing="0" cellpadding="1" border="1"  style="text-align: center; page-break-before: always;" ><tr>';		
					}
					else if($teller == $total){
						$barcodesoffallchildren.= '</tr>';
					}
					else if($teller % 3 == 0){
						$barcodesoffallchildren.= '</tr><tr>';
					}

					$teller++;
					$tellernamen++;
				}	
				
			}

			//if the field $class['data'] is not empty set the proper data, else writh class is empty
			if(!empty($class['data'])){
				//add a proper anding to the barcodes list
				if(substr($namesPerSchoolClass,-4)=='/tr>'){
					$tbl = '<table cellspacing="0" cellpadding="1" style="text-align: center; padding-bottom: 5rem; padding-top: 5rem; font-size: 15rem;" >'
						.$namesPerSchoolClass.
						'</table>';
				}
				else if(substr($namesPerSchoolClass,-4)=='/td>'){
					$tbl = '<table cellspacing="0" cellpadding="1" style="text-align: center; padding-bottom: 5rem; padding-top: 5rem; font-size: 15rem;" >'
						.$namesPerSchoolClass.
						'</tr></table>';
				}
				//set the font
				$pdf->SetFont('helvetica', '', 13);

				//write the html
				$pdf->writeHTML($tbl, true, 0, true, 0);

				//add a proper anding to the barcodes list
				if(substr($barcodesoffallchildren,-4)=='<tr>'){
					$tbl = $barcodesoffallchildren.					    
						'</tr></table>';
				}
				else{
					$tbl = $barcodesoffallchildren.					    
						'</table>';
				}
				$pdf->SetFont('helvetica', '', 13);
				$pdf->writeHTML($tbl, true, 0, true, 0);
			}
			else{
				$pdf->Write(0, 'class is empty', '', 0, 'L', true, 0, false, false, 0);
			}
		}
		
		//Close and output PDF document
		$pdf->Output('barcodes.pdf', 'I');

		//exit;
		return $app['twig']->render('Ajax/Dump.twig');		
	}

	/**
	 * Excel page
	 * @param Application $app An Application instance
	 * @return an excel file with all the data from a school, including all clases with students and laptop ids.
	 */
	public function excel(Application $app) {
		$data='';

		// check if user is already logged in
		if ($app['session']->get('user') && ($app['db.people']->fetchAdminPerson($app['session']->get('user')))) {

		}
		else{

			//redirect to login page if user is not logged in
			return $app->redirect($app['url_generator']->generate('auth.login')); 
		}
		
		//get all the data from the url and put them in an array
		$obj = array('coloms' =>  $_GET["coloms"],
						'OrderByTerm' =>  $_GET["OrderByTerm"],
						'orderList' =>  $_GET["orderList"],
						'GroupByTerm' =>  $_GET["GroupByTerm"],
						'inputfield' =>  $_GET["inputfield"],
						'Ciudad' =>  $_GET["Ciudad"],
						'Escuela' =>  $_GET["Escuela"],
						'Turno' =>  $_GET["Turno"],
						'grado' =>  $_GET["grado"],
						'Seccion' =>  $_GET["Seccion"],
						'Departamento' =>  $_GET["Departamento"]);

		//check the type
		if($_GET['formname']=='laptopsForm'){

			//set the fullname of the school
			$fullname = $_GET["Departamento"] . " : " . $_GET["Ciudad"] . " : " . $_GET["Escuela"];

			//set the date
			$date = date("m/d/Y");

			//load an excel template file
			$objPHPExcel = \PHPExcel_IOFactory::load($app['Inventory.base_path'].'/files/example.xlsx');

			//fill in the default data into the first sheet, name of school and date.
			//this is the default sheet
			$objPHPExcel->setActiveSheetIndex(0)
		            ->setCellValue('B2', $fullname)
		            ->setCellValue('D1', $date);

		    //get the department ID
			$Departamentoid = $app['db.places']->getPlace($obj['Departamento'], 2);

			//get the city ID
			$cityid = $app['db.places']->getCityByName($obj['Ciudad']);

			//get the school ID
			$schoolid = $app['db.places']->getitemByNameandAncestorID($obj['Escuela'], $cityid);

			//fetch all teachers from the school
			$teachers = $app['db.laptops']->fetchListOfPeopleFromPlace($schoolid);

			//set teller to 5, this is the first field in the excel file where names can be entherd
			$teller = 5;

			//fill in the default data into the second sheet, name of school and date. 
			//teachers sheet
			$objPHPExcel->setActiveSheetIndex(1)
		            ->setCellValue('C2', $fullname)
		            ->setCellValue('F1', $date);

		   	//put the names and laptops ID of al the teachers into the sheet
			foreach ($teachers as $teacher) {
				$objPHPExcel->setActiveSheetIndex(1)
		            ->setCellValue('G'.$teller, $teacher['name'].' '.$teacher['lastname'] )
		            ->setCellValue('H'.$teller, $teacher['serial_number']);
		        $teller++;
			}

			//change sheet to page three, this is the default sheet of a class
			$objPHPExcel->setActiveSheetIndex(2)
				            ->setCellValue('C2', $fullname)
				            ->setCellValue('F1', $date);

			//get all the turnos of a school
			$turnos =  $app['db.places']->fetchTurno($schoolid);

			//set the parameter turn to his default value
			$turn = 'M';

			//loop over all turns
			foreach ($turnos as $turno) {

				//set the parameter gradeOFClass to his default value
				$gradeOFClass = 1;

				//get all the grades of a turno
				$grades =  $app['db.places']->fetchGrade($turno['id']);

				//loop over all grades
				foreach ($grades as $grade) {

					//get all the secions of a grade
					$seccions =  $app['db.places']->fetchSeccion($grade['id']);

					//set the parameter seccionOfClass to his default value
					$seccionOfClass = 'A';

					//loop over all seccions
					foreach ($seccions as $seccion) {

						//get all the students from a class
						$children = $app['db.laptops']->fetchListOfPeopleFromPlace($seccion['id']);

						//set teller to 5, this is the first field in the excel file where names can be entherd
						$teller = 5;

						//clone the default sheet
						$clonedSheet = clone $objPHPExcel->setActiveSheetIndex(2);

						//set basic data, like name school, class, grade and section of class
						$clonedSheet
				            ->setCellValue('C2', $fullname)
				            ->setCellValue('F1', $date)
				            ->setCellValue('K1', $gradeOFClass)
				            ->setCellValue('K2', $seccionOfClass);
				        
				        //loop over all the students
						foreach ($children as $child) {

							//set the name of the chield and serial number of his laptop in the excel file
							$clonedSheet
					            ->setCellValue('G'.$teller, $child['name'].' '.$child['lastname'] )
					            ->setCellValue('H'.$teller, $child['serial_number']);

					        $teller++;

					        //if teller = 38, jump to 41, this is because of the design of the excel file
					        if($teller== 38){
					        	$teller = 41;
					        }
						}

						// Rename worksheet
						$clonedSheet->setTitle($turn." ". $gradeOFClass." ".$seccionOfClass);

						//add the worksheet to the excel file
						$objPHPExcel->addSheet($clonedSheet);
						
						//increase seccion of class with 1
						$seccionOfClass++;
						
					}
					//increase grade of class with 1
					$gradeOFClass++;
				}
				//change turn to T (Tarde)
				$turn = "T";
			}
		}

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="list.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		//create and save the file to the user his browser
		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');

		exit;
	}
}

//EOF