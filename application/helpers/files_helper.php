<?php

require_once(APPPATH.'exception/UploadException.php');

function uploadFile($fileName=FALSE, $subfolders, $fieldId, $folderName, $allowedTypes, $extraConfig=[]){

    $ci =& get_instance();
    $ci->load->library('upload');

    $config = setUploadOptions(
        $fileName, $subfolders, $folderName, $allowedTypes, $extraConfig
    );

    $ci->upload->initialize($config);
    if($ci->upload->do_upload($fieldId)){
        $file = $ci->upload->data();
        $path = $file['full_path'];
        return $path;
    }
    else{
        // Errors on file upload
        $errors = $ci->upload->display_errors();
        throw new UploadException('Error on file upload.', $errors);
    }
}

function setUploadOptions($fileName=FALSE, $subfolders=[], $folderName, $allowedTypes, $extraConfig=[]){

    // Remember to give the proper permission to the /upload_files folder
    $UPLOAD_FOLDER_PATH = "upload_files/{$folderName}";

    $desiredPath = APPPATH.$UPLOAD_FOLDER_PATH;

    $path = createFolders($desiredPath, $subfolders);

    $config['upload_path'] = $path;
    $config['allowed_types'] = $allowedTypes;
    $config['max_size'] = '5500';
    $config['remove_spaces'] = TRUE;
    if($fileName !== FALSE){
        $config['file_name'] = $fileName;
    }

    return array_merge($config, $extraConfig);
}

function createFolders($desiredPath, $subfolders){

    foreach ($subfolders as $prefix => $suffix) {

        $auxPath = $desiredPath;

        $pathToAdd = "/".$prefix."_".$suffix;

        if(is_dir($auxPath.$pathToAdd)){
            $desiredPath .= $pathToAdd;
            $auxPath = $desiredPath;
        }
        else{
            mkdir($auxPath.$pathToAdd, 0755, TRUE);
            $desiredPath .= $pathToAdd;
        }
    }

    return $desiredPath;
}

function downloadFile($path){

    $ci =& get_instance();
    $ci->load->helper('download');
    if(file_exists($path)){
        force_download($path, NULL);
        $downloaded = TRUE;
    }
    else{
        $downloaded = FALSE;
    }

    return $downloaded;
}