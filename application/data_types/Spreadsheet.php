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

		$activeSheet->getColumnDimension('A')->setWidth(18);
		$activeSheet->getColumnDimension('B')->setWidth(10);
		$activeSheet->getColumnDimension('C')->setWidth(12);
		$activeSheet->getColumnDimension('D')->setWidth(15);
		$activeSheet->getColumnDimension('E')->setWidth(2);
		$activeSheet->getColumnDimension('F')->setWidth(14);
		$activeSheet->getColumnDimension('G')->setWidth(20);

		$activeSheet->setCellValue('A1', 'Logo UnB');
		$activeSheet->mergeCells('A1:B3');

	// Header
		$activeSheet->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);;
		$activeSheet->getStyle('C1')->getFont()->setBold(true)->setName('Arial')->setSize(14);
		$activeSheet->setCellValue('C1', "Fundação Universidade de Brasília - UnB");
		$activeSheet->mergeCells('C1:G2');

		$activeSheet->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		$activeSheet->getStyle('C3')->getFont()->setBold(true)->setName('Arial')->setSize(10);
		$activeSheet->setCellValue('C3', "CGC - 00.038.174/0001-43");
		$activeSheet->mergeCells('C3:G3');

		$activeSheet->getStyle('A4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$activeSheet->getStyle('A4')->getFont()->setSize(12)->setName('Arial');
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


	// Finantial source identification

		$activeSheet->getStyle('A9')->getFont()->setBold(true)->setSize(12);
		$activeSheet->setCellValue('A9', '3 - IDENTIFICAÇÃO DA FONTE FINANCIADORA');
		$activeSheet->mergeCells('A9:G9');

		// Resource source
		$activeSheet->getStyle('A10')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('A10')->getAlignment()
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A10', 'FONTE DE RECURSO:');
		$activeSheet->mergeCells('A10:A11');

		$activeSheet->getStyle('B10')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('B10')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('B10', $this->resourseSource());
		$activeSheet->mergeCells('B10:G11');

		// Cost center
		$activeSheet->getStyle('A12')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('A12')->getAlignment()
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A12', 'CENTRO DE CUSTO:');
		$activeSheet->mergeCells('A12:A13');

		$activeSheet->getStyle('B12')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('B12')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('B12', $this->costCenter());
		$activeSheet->mergeCells('B12:G13');

		// Dotation note
		$activeSheet->getStyle('A14')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('A14')->getAlignment()
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A14', 'NOTA DE DOTAÇÃO - (INFORMAR O NÚMERO OU ANEXAR CÓPIA):');
		$activeSheet->mergeCells('A14:D14');

		$activeSheet->getStyle('E14')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('E14')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('E14', $this->dotationNote());
		$activeSheet->mergeCells('E14:G14');

	// User identification

		$activeSheet->getStyle('A15')->getFont()->setBold(true)->setSize(12);
		$activeSheet->getStyle('A15')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A15', "4 - IDENTIFICAÇÃO DO USUÁRIO:");
		$activeSheet->mergeCells('A15:G15');

		// User name
		$activeSheet->getStyle('A16')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('A16')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A16', "NOME COMPLETO:");
		$activeSheet->mergeCells('A16:A17');

		$activeSheet->getStyle('B16')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('B16')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('B16', $this->name());
		$activeSheet->mergeCells('B16:G17');

		// User id
		$activeSheet->getStyle('A18')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('A18')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A18', "CART. IDENT.");

		$activeSheet->getStyle('A19')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('A19')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A19', $this->id());

		// User PIS PASEP
		$activeSheet->getStyle('B18')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('B18')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('B18', "INSCRIÇÃO: PIS e/ou INSS");
		$activeSheet->mergeCells('B18:C18');

		$activeSheet->getStyle('B19')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('B19')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('B19', $this->pisPasep());
		$activeSheet->mergeCells('B19:C19');
		
		// User CPF
		$activeSheet->getStyle('D18')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('D18')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('D18', "CPF");
		$activeSheet->mergeCells('D18:E18');

		$activeSheet->getStyle('D19')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('D19')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('D19', $this->cpf());
		$activeSheet->mergeCells('D19:E19');
		
		// User enrollment
		$activeSheet->getStyle('F18')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('F18')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('F18', "MATRÍCULA");

		$activeSheet->getStyle('F19')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('F19')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('F19', $this->enrollmentNumber());

		// User arrival in Brazil
		$activeSheet->getStyle('G18')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('G18')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('G18', "CHEGADA AO BRASIL");

		$activeSheet->getStyle('G19')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('G19')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('G19', $this->arrivalInBrazil());

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