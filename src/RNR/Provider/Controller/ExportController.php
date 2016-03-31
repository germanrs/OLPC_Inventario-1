<?php

namespace RNR\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Silex\ControllerCollection;
use Symfony\Component\Validator\Constraints as Assert;
require_once '/../../Classes/PHPExcel.php';
require_once '/../../Classes/PHPExcel/IOFactory.php';
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
			$fullname = $_GET["Departamento"] . " " . $_GET["Ciudad"] . " " . $_GET["Escuela"];

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
		header('Content-Disposition: attachment;filename="list.xls"');
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