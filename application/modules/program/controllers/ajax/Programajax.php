<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class ProgramAjax extends MX_Controller {

    public function addFieldFile(){
        $this->load->library('upload');
        $program = $this->input->post("program_id");

        $config = $this->setUploadOptions($process->getName(), $course["id_program"], $course["id_course"], $processId);

        $this->upload->initialize($config);
        $status = "";
        if($this->upload->do_upload("notice_file")){

            $noticeFile = $this->upload->data();
            $noticePath = $noticeFile['full_path'];

            $wasUpdated = $this->updateNoticeFile($processId, $noticePath);

            if($wasUpdated){
                $status = self::NOTICE_FILE_SUCCESS;
            }
            else{
                $status = self::NOTICE_FILE_ERROR_ON_UPDATE;
            }
        }
        else{
            // Errors on file upload
            $errors = $this->upload->display_errors();
            $status = $errors."<br>".self::NOTICE_FILE_ERROR_ON_UPLOAD.".";
        }

        return $status;
    }

    private function setUploadOptions($fileName, $programId){
        
        // Remember to give the proper permission to the /upload_files folder
        define("PORTAL_UPLOAD_FOLDER_PATH", "upload_files/portal");

        $desiredPath = APPPATH.PORTAL_UPLOAD_FOLDER_PATH;

        $ids = array(
            "p" => $programId,
        );

        $path = $this->createFolders($desiredPath, $ids);

        $config['upload_path'] = $path;
        $config['file_name'] = $fileName;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = '5500';
        $config['remove_spaces'] = TRUE;

        return $config;
    }
}