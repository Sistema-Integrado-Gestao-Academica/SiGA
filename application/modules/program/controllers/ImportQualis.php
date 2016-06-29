<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(MODULESPATH."auth/constants/PermissionConstants.php");

class ImportQualis extends MX_Controller {

    const QUALIS_UPLOAD_PATH = "upload_files/qualis";
    const QUALIS_FILENAME = "periodic_qualis";
    const QUALIS_FILE_TYPES = "csv";
    const QUALIS_FILE_SIZE = "10000";// in KBs

    public function __construct(){
        parent::__construct();
        $this->load->model("program/importQualis_model", "qualis_model");
    }

    public function index($notSavedPeriodics = array()){

        $data = array(
            'maxSize' => self::QUALIS_FILE_SIZE,
            'notSavedPeriodics' => $notSavedPeriodics
        );

        loadTemplateSafelyByPermission(
            PermissionConstants::IMPORT_QUALIS_PERMISSION,
            "program/qualis/import_qualis",
            $data
        );
    }

    private function createUploadPath(){
        $path = APPPATH.self::QUALIS_UPLOAD_PATH;
        if(!is_dir($path)){
            // Create the upload path if it not exists, recursively with permission 755
            mkdir($path, 0755, TRUE);
        }
        return $path;
    }

    public function upload(){

        $this->load->library('upload');

        $path = $this->createUploadPath();

        $config['upload_path'] = $path;
        $config['file_name'] = self::QUALIS_FILENAME;
        $config['allowed_types'] = self::QUALIS_FILE_TYPES;
        $config['max_size'] = self::QUALIS_FILE_SIZE;
        $config['remove_spaces'] = TRUE;
        $config['overwrite'] = TRUE;

        $this->upload->initialize($config);

        if($this->upload->do_upload("qualis_file")){

            $file = $this->upload->data();
            $filePath = $file['full_path'];

            $notSavedPeriodics = $this->readQualisFile($filePath);

            $this->index($notSavedPeriodics);

        }else{
            // Errors on file upload
            $errors = $this->upload->display_errors();

            $errors = str_replace("<p>", "<br>", $errors);
            $errors = str_replace("</p>", "", $errors);

            $status = "danger";
            $message = $errors."<br>Tente novamente.";
            $this->session->set_flashdata($status, $message);
            redirect("import_qualis");
        }
    }

    private function readQualisFile($file){

        $periodics = array_map('str_getcsv', file($file));

        define("ISSN_INDEX", 0);
        define("PERIODIC_INDEX", 1);
        define("AREA_INDEX", 2);
        define("QUALIS_INDEX", 3);

        $notSavedPeriodics = array();
        $is_first = TRUE;
        foreach($periodics as $periodic){

            // The first line of CSV is the columns titles. Don't need saving
            if(!$is_first){
                $issn = $periodic[ISSN_INDEX];
                $issnAlreadyExists = $this->qualis_model->issnExists($issn);

                $periodicToSave = array(
                    ImportQualis_model::ISSN_COLUMN => $issn,
                    ImportQualis_model::PERIODIC_COLUMN => $periodic[PERIODIC_INDEX],
                    ImportQualis_model::AREA_COLUMN => $periodic[AREA_INDEX],
                    ImportQualis_model::QUALIS_COLUMN => $periodic[QUALIS_INDEX]
                );

                // Only save the periodic if the ISSN is OK
                $issnPattern = "/([\d]{4})-([\d]{3})([xX\d]{1})/";
                if(preg_match($issnPattern, $issn) && !$issnAlreadyExists){
                    $this->qualis_model->save($periodicToSave);
                }else{
                    $notSavedPeriodics[] = $periodicToSave;
                }
            }

            $is_first = FALSE;
        }

        return $notSavedPeriodics;
    }
}