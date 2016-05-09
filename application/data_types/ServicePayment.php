<?php

class ServicePayment{

	const FILE_NAME = "proposta.xls";
	const COMMITMENT_TERM = "Declaro-me de acordo com o valor total da proposta e forma de pagamento, nos termos que diciplinam as normas internas vigente na FUB.";

	private $sheet;

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
	private $email;
	private $address;
	private $projectDenomination;
	private $bank;
	private $agency;
	private $accountNumber;

	// Propose data
	private $totalValue;
	private $period;
	private $endPeriod;
	private $weekHours;
	private $weeks;
	private $totalHours;
	private $serviceDescription;

	private $installment1;
	private $installment2;
	private $installment3;
	private $installment4;
	private $installment5;

	public function __construct($userType, $legalSupport, $resourseSource, $costCenter, $dotationNote, $name,
		$id, $pisPasep, $cpf, $enrollmentNumber, $arrivalInBrazil, $phone, $email, $address, $projectDenomination, $bank,
		$agency, $accountNumber, $totalValue, $period, $endPeriod, $weekHours, $weeks, $totalHours, $serviceDescription,
		$installment1, $installment2, $installment3, $installment4, $installment5){

		$this->userType = $userType;
		$this->legalSupport = $legalSupport;

		$this->resourseSource = $resourseSource;
		$this->costCenter = $costCenter;
		$this->dotationNote = (string) $dotationNote;

		$this->name = $name;
		$this->id = $id;
		$this->pisPasep = $pisPasep;
		$this->cpf = (string) $cpf;
		$this->enrollmentNumber = $enrollmentNumber;
		$this->arrivalInBrazil = $arrivalInBrazil;
		$this->phone = $phone;
		$this->email = $email;
		$this->address = $address;
		$this->projectDenomination = $projectDenomination;
		$this->bank = $bank;
		$this->agency = (string) $agency;
		$this->accountNumber = (string) $accountNumber;

		$this->totalValue = $totalValue;
		$this->period = $period;
		$this->endPeriod = $endPeriod;
		$this->weekHours = $weekHours;
		$this->weeks = $weeks;
		$this->totalHours = $totalHours;
		$this->serviceDescription = $serviceDescription;

		$this->installment1 = $installment1;
		$this->installment2 = $installment2;
		$this->installment3 = $installment3;
		$this->installment4 = $installment4;
		$this->installment5 = $installment5;
	}

	private function generateSheet(){

		$ci =& get_instance();

		$ci->load->library("ExcelSheet", '', 'sheet');

		$ci->sheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

		// Creating the columns
		$ci->sheet->setActiveSheetIndex(0);

		$activeSheet = $ci->sheet->getActiveSheet();

		$activeSheet->setTitle('Proposta');

		$activeSheet->getDefaultStyle()->getFont()->setName('Times New Roman');

		$styleArray = array(
			'borders' => array(
				'allborders' => array(
					'style' => PHPExcel_Style_Border::BORDER_THIN,
				),
			)
		);

		$activeSheet->getStyle('A1:G47')->applyFromArray($styleArray);

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
		$activeSheet->getCell('E14')->setValueExplicit($this->dotationNote(), PHPExcel_Cell_DataType::TYPE_STRING);
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
		$activeSheet->getCell('B19')->setValueExplicit($this->pisPasep(), PHPExcel_Cell_DataType::TYPE_STRING);
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
		$activeSheet->getCell('D19')->setValueExplicit($this->cpf(), PHPExcel_Cell_DataType::TYPE_STRING);
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

		// User phone
		$activeSheet->getStyle('A20')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('A20')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A20', "TELEFONE:");

		$activeSheet->getStyle('B20')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('B20')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('B20', $this->phone());
		$activeSheet->mergeCells('B20:C20');

		// User bank
		$activeSheet->getStyle('D20')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('D20')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('D20', "BANCO:");

		$activeSheet->getStyle('E20')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('E20')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('E20', $this->bank());
		$activeSheet->mergeCells('E20:G20');

		// User email
		$activeSheet->getStyle('A21')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('A21')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A21', "EMAIL:");

		$activeSheet->getStyle('B21')->getFont()->setBold(false)->setSize(10);
		$activeSheet->getStyle('B21')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('B21', $this->email());
		$activeSheet->mergeCells('B21:C21');

		// User agency
		$activeSheet->getStyle('D21')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('D21')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('D21', "AGÊNCIA:");

		$activeSheet->getStyle('E21')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('E21')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->getCell('E21')->setValueExplicit($this->agency(), PHPExcel_Cell_DataType::TYPE_STRING);
		$activeSheet->mergeCells('E21:G21');

		// User address
		$activeSheet->getStyle('A22')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('A22')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A22', "ENDEREÇO:");

		$activeSheet->getStyle('B22')->getFont()->setBold(false)->setSize(10);
		$activeSheet->getStyle('B22')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('B22', $this->address());
		$activeSheet->mergeCells('B22:C22');

		// User account number
		$activeSheet->getStyle('D22')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('D22')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('D22', "N. DA CONTA:");

		$activeSheet->getStyle('E22')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('E22')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->getCell('E22')->setValueExplicit($this->accountNumber(), PHPExcel_Cell_DataType::TYPE_STRING);
		$activeSheet->mergeCells('E22:G22');

		// Project denomination
		$activeSheet->getStyle('A23')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('A23')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A23', "Denominaçãodo Projeto:");

		$activeSheet->getStyle('B23')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('B23')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('B23', $this->projectDenomination());
		$activeSheet->mergeCells('B23:G23');

	// Propose data

		$activeSheet->getStyle('A24')->getFont()->setBold(true)->setSize(12);
		$activeSheet->getStyle('A24')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A24', "5 - DADOS DA PROPOSTA:");
		$activeSheet->mergeCells('A24:G24');

		// Total value
		$activeSheet->getStyle('A25')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('A25')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A25', "VALOR TOTAL:");

		$activeSheet->getStyle('A26')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('A26')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A26', "R$".$this->totalValue());

		// Period
		$activeSheet->getStyle('B25')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('B25')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('B25', "PERÍODO:");
		$activeSheet->mergeCells('B25:C25');

		$activeSheet->getStyle('B26')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('B26')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('B26', $this->period()." - ".$this->endPeriod());
		$activeSheet->mergeCells('B26:C26');

		// Week hours
		$activeSheet->getStyle('D25')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('D25')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('D25', "H. SEMANAIS:");

		$activeSheet->getStyle('D26')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('D26')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('D26', $this->weekHours());

		// Weeks
		$activeSheet->getStyle('E25')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('E25')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('E25', "SEMANAS:");
		$activeSheet->mergeCells('E25:F25');

		$activeSheet->getStyle('E26')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('E26')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('E26', $this->weeks());
		$activeSheet->mergeCells('E26:F26');

		// Total hours
		$activeSheet->getStyle('G25')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('G25')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('G25', "TOTAL HORAS:");

		$activeSheet->getStyle('G26')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('G26')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('G26', $this->totalHours());

		// Installment
		$activeSheet->getStyle('A27')->getFont()->setBold(true)->setSize(9);
		$activeSheet->getStyle('A27')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A27', "PARCELAMENTO");
		$activeSheet->mergeCells('A27:G27');

			// Installment number
			$activeSheet->getStyle('A28')->getFont()->setBold(false)->setSize(9);
			$activeSheet->getStyle('A28')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('A28', "N. DA PARCELA");

			// Date
			$activeSheet->getStyle('B28')->getFont()->setBold(false)->setSize(9);
			$activeSheet->getStyle('B28')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('B28', "DATA");

			// Value
			$activeSheet->getStyle('C28')->getFont()->setBold(false)->setSize(9);
			$activeSheet->getStyle('C28')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('C28', "VALOR");
			$activeSheet->mergeCells('C28:D28');

			// Worked hours
			$activeSheet->getStyle('E28')->getFont()->setBold(false)->setSize(9);
			$activeSheet->getStyle('E28')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('E28', "HORAS TRABALHADAS");
			$activeSheet->mergeCells('E28:G28');

			// Installment number

			$activeSheet->getStyle('A29')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('A29')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('A29', "1");


			$activeSheet->getStyle('A30')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('A30')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('A30', "2");

			$activeSheet->getStyle('A31')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('A31')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('A31', "3");

			$activeSheet->getStyle('A32')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('A32')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('A32', "4");

			$activeSheet->getStyle('A33')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('A33')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('A33', "5");

			// Instalment 1
			$activeSheet->getStyle('B29')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('B29')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('B29', $this->installment1()['date']);

			$activeSheet->getStyle('C29')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('C29')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('C29', $this->installment1()['value']);
			$activeSheet->mergeCells('C29:D29');

			$activeSheet->getStyle('E29')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('E29')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('E29', $this->installment1()['hour']);
			$activeSheet->mergeCells('E29:G29');

			// Installment 2

			$activeSheet->getStyle('B30')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('B30')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('B30', $this->installment2()['date']);

			$activeSheet->getStyle('C30')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('C30')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('C30', $this->installment2()['value']);
			$activeSheet->mergeCells('C30:D30');

			$activeSheet->getStyle('E30')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('E30')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('E30', $this->installment2()['hour']);
			$activeSheet->mergeCells('E30:G30');

			// Installment 3

			$activeSheet->getStyle('B31')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('B31')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('B31', $this->installment3()['date']);

			$activeSheet->getStyle('C31')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('C31')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('C31', $this->installment3()['value']);
			$activeSheet->mergeCells('C31:D31');

			$activeSheet->getStyle('E31')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('E31')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('E31', $this->installment3()['hour']);
			$activeSheet->mergeCells('E31:G31');

			// Installment 4

			$activeSheet->getStyle('B32')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('B32')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('B32', $this->installment4()['date']);

			$activeSheet->getStyle('C32')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('C32')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('C32', $this->installment4()['value']);
			$activeSheet->mergeCells('C32:D32');

			$activeSheet->getStyle('E32')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('E32')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('E32', $this->installment4()['hour']);
			$activeSheet->mergeCells('E32:G32');

			// Installment 5

			$activeSheet->getStyle('B33')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('B33')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('B33', $this->installment5()['date']);

			$activeSheet->getStyle('C33')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('C33')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('C33', $this->installment5()['value']);
			$activeSheet->mergeCells('C33:D33');

			$activeSheet->getStyle('E33')->getFont()->setBold(false)->setSize(10);
			$activeSheet->getStyle('E33')->getAlignment()
						->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$activeSheet->setCellValue('E33', $this->installment5()['hour']);
			$activeSheet->mergeCells('E33:G33');

		// Service description
		$activeSheet->getStyle('A34')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('A34')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A34', "DESCRIÇÃO DETALHADA DOS SERVIÇOS - (Anexar folha complementar, se necessário):");
		$activeSheet->mergeCells('A34:G34');

		$activeSheet->getStyle('A35')->getFont()->setBold(false)->setSize(10);
		$activeSheet->getStyle('A35')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A35', $this->serviceDescription());
		$activeSheet->mergeCells('A35:G38');

// COMMITMENT TERM

		$activeSheet->getStyle('A39')->getFont()->setBold(true)->setSize(12);
		$activeSheet->getStyle('A39')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A39', "6 - TERMO DE COMPROMISSO (Prestador de Serviço):");
		$activeSheet->mergeCells('A39:G39');

		$activeSheet->getStyle('A40')->getFont()->setBold(false)->setSize(10);
		$activeSheet->getStyle('A40')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->getStyle('A40')->getAlignment()->setWrapText(true);
		$activeSheet->setCellValue('A40', self::COMMITMENT_TERM);
		$activeSheet->mergeCells('A40:G41');

		$activeSheet->getStyle('A42')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('A42')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A42', "DATA: ");
		$activeSheet->mergeCells('A42:B42');

		$activeSheet->getStyle('C42')->getFont()->setBold(false)->setSize(12);
		$activeSheet->getStyle('C42')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('C42', "ASSINATURA: ");
		$activeSheet->mergeCells('C42:G42');

// APROVEMENT

		$activeSheet->getStyle('A43')->getFont()->setBold(true)->setSize(12);
		$activeSheet->getStyle('A43')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A43', "7 - APROVAÇÃO (Gestor e Titular da Unidade):");
		$activeSheet->mergeCells('A43:G43');

		$activeSheet->getStyle('A44')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('A44')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A44', "GESTOR DO PROJETO:");
		$activeSheet->mergeCells('A44:C44');

		$activeSheet->getStyle('D44')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('D44')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('D44', "TITULAR DA UNIDADE:");
		$activeSheet->mergeCells('D44:G44');

		$activeSheet->getStyle('A45')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('A45')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('A45', "DATA:");
		$activeSheet->mergeCells('A45:C45');

		$activeSheet->getStyle('D45')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('D45')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$activeSheet->setCellValue('D45', "DATA:");
		$activeSheet->mergeCells('D45:G45');

		$activeSheet->getStyle('A46')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('A46')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$activeSheet->setCellValue('A46', "ASSINATURA/CARIMBO:");
		$activeSheet->mergeCells('A46:C47');

		$activeSheet->getStyle('D46')->getFont()->setBold(false)->setSize(9);
		$activeSheet->getStyle('D46')->getAlignment()
					->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT)
					->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);
		$activeSheet->setCellValue('D46', "ASSINATURA/CARIMBO:");
		$activeSheet->mergeCells('D46:G47');

		$this->sheet = $ci->sheet;
	}

	public function downloadSheet(){

		$this->generateSheet();

		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.self::FILE_NAME.'"');

		$objWriter = PHPExcel_IOFactory::createWriter($this->sheet(), 'Excel5');

		ob_end_clean();
		$objWriter->save('php://output');
	}

/* Getters */

	private function sheet(){
		return $this->sheet;
	}

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

	public function email(){
		return $this->email;
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

	public function endPeriod(){
		return $this->endPeriod;
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

	public function installment1(){
		return $this->installment1;
	}

	public function installment2(){
		return $this->installment2;
	}

	public function installment3(){
		return $this->installment3;
	}

	public function installment4(){
		return $this->installment4;
	}

	public function installment5(){
		return $this->installment5;
	}
/**/
}