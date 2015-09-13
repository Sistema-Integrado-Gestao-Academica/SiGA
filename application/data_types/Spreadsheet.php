<?php

include(APPPATH."/phpexcel/PHPExcel.php");

class Spreadsheet{

	const FILE_NAME = "proposta.xls";
	
	private $userType;
	private $legalSupport;

	// Finantial source identification
	private $resourseSource;
	private $costCenter;
	private $dotationNote;
	
	// User identification attributes
	private $name;
	private $id;
	private $pisPasep;
	private $cpf;
	private $enrollmentNumber;
	private $arrivalInBrazil;
	private $phone;
	private $address;
	private $projectDenomination;
	private $bank;
	private $agency;
	private $accountNumber;

	// Propose data
	private $totalValue;
	private $period;
	private $weekHours;
	private $weeks;
	private $totalHours;
	private $serviceDescription;


	public function __construct($userType, $legalSupport, $resourseSource, $costCenter, $dotationNote, $name,
		$id, $pisPasep, $cpf, $enrollmentNumber, $arrivalInBrazil, $phone, $address, $projectDenomination, $bank,
		$agency, $accountNumber, $totalValue, $period, $weekHours, $weeks, $totalHours, $serviceDescription){

		$this->userType = $userType;
		$this->legalSupport = $legalSupport;

		$this->resourseSource = $resourseSource;
		$this->costCenter = $costCenter;
		$this->dotationNote = $dotationNote;
		
		$this->name = $name;
		$this->id = $id;
		$this->pisPasep = $pisPasep;
		$this->cpf = $cpf;
		$this->enrollmentNumber = $enrollmentNumber;
		$this->arrivalInBrazil = $arrivalInBrazil;
		$this->phone = $phone;
		$this->address = $address;
		$this->projectDenomination = $projectDenomination;
		$this->bank = $bank;
		$this->agency = $agency;
		$this->accountNumber = $accountNumber;

		$this->totalValue = $totalValue;
		$this->period = $period;
		$this->weekHours = $weekHours;
		$this->weeks = $weeks;
		$this->totalHours = $totalHours;
		$this->serviceDescription = $serviceDescription;
	}

	public function generateSheet(){

		$sheet = new PHPExcel();

		$sheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		// Creating the columns
		$sheet->setActiveSheetIndex(0);

		$activeSheet = $sheet->getActiveSheet();

		$activeSheet->setTitle('Proposta');

		$activeSheet->getDefaultStyle()->getFont()->setName('Times New Roman');

		$styleArray = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			)
		);

		$activeSheet->getStyle('A1:G48')->applyFromArray($styleArray);

		$activeSheet->getColumnDimension('A')->setWidth(12);
		$activeSheet->getColumnDimension('B')->setWidth(11);
		$activeSheet->getColumnDimension('C')->setWidth(8);
		$activeSheet->getColumnDimension('D')->setWidth(13);
		$activeSheet->getColumnDimension('E')->setWidth(2);
		$activeSheet->getColumnDimension('F')->setWidth(8);
		$activeSheet->getColumnDimension('G')->setWidth(9);

		$activeSheet->setCellValue('A1', 'Logo UnB');
		$activeSheet->mergeCells('A1:B3');

		$activeSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);;
		$activeSheet->getStyle('C1')->getFont()->setBold(true);
		$activeSheet->setCellValue('C1', "Fundação Universidade de Brasília - UnB");
		$activeSheet->mergeCells('C1:G2');

		$activeSheet->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$activeSheet->getStyle('C3')->getFont()->setBold(true)->setSize(10);
		$activeSheet->setCellValue('C3', "CGC - 00.038.174/0001-43");
		$activeSheet->mergeCells('C3:G3');

		$activeSheet->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$activeSheet->getStyle('A4')->getFont()->setSize(12);
		$activeSheet->setCellValue('A4', "PROPOSTA SIMPLIFICADA DE PRESTAÇÃO DE SERVIÇOS");
		$activeSheet->mergeCells('A4:G4');

		$activeSheet->getStyle('A4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
					->getStartColor()->setARGB('C0C0C0');

		// User type
		$activeSheet->getStyle('A5')->getFont()->setBold(true)->setSize(12);
		$activeSheet->setCellValue('A5', '1 - Usuário:');

		$activeSheet->getStyle('A6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->getStyle('A6')->getFont()->setSize(12);
		$activeSheet->setCellValue('A6', $this->userType());
		$activeSheet->mergeCells('A6:A8');

		// Legal Support
		$activeSheet->getStyle('B5')->getFont()->setBold(true)->setSize(12);
		$activeSheet->setCellValue('B5', '2 - Amparo legal, discriminar:');
		$activeSheet->mergeCells('B5:G5');

		$activeSheet->getStyle('B6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->getStyle('B6')->getFont()->setSize(12);
		$activeSheet->setCellValue('B6', $this->legalSupport());
		$activeSheet->mergeCells('B6:G8');


		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.self::FILE_NAME.'"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($sheet, 'Excel2007');

		ob_end_clean();
		$objWriter->save('php://output');
	}

/* Getters */

	public function userType(){
		return $this->userType;
	}

	public function legalSupport(){
		return $this->legalSupport;
	}

	public function resourseSource(){
		return $this->resourseSource;
	}

	public function costCenter(){
		return $this->costCenter;
	}

	public function dotationNote(){
		return $this->dotationNote;
	}

	public function name(){
		return $this->name;
	}

	public function id(){
		return $this->id;
	}

	public function pisPasep(){
		return $this->pisPasep;
	}

	public function cpf(){
		return $this->cpf;
	}

	public function enrollmentNumber(){
		return $this->enrollmentNumber;
	}

	public function arrivalInBrazil(){
		return $this->arrivalInBrazil;
	}

	public function phone(){
		return $this->phone;
	}

	public function address(){
		return $this->address;
	}

	public function projectDenomination(){
		return $this->projectDenomination;
	}

	public function bank(){
		return $this->bank;
	}

	public function agency(){
		return $this->agency;
	}

	public function accountNumber(){
		return $this->accountNumber;
	}

	public function totalValue(){
		return $this->totalValue;
	}

	public function period(){
		return $this->period;
	}

	public function weekHours(){
		return $this->weekHours;
	}

	public function weeks(){
		return $this->weeks;
	}

	public function totalHours(){
		return $this->totalHours;
	}

	public function serviceDescription(){
		return $this->serviceDescription;
	}
/**/
}