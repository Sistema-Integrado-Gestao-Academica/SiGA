<?php

function uploadFile($fileName, $ids, $fieldId, $folderName, $allowedTypes){

    $ci =& get_instance();
    $ci->load->library('upload');

    $config = setUploadOptions($fileName, $ids, $folderName, $allowedTypes);

    $ci->upload->initialize($config);
    $status = "";
    if($ci->upload->do_upload($fieldId)){
        $file = $ci->upload->data();
        $path = $file['full_path'];
    }
    else{
        // Errors on file upload
        $errors = $ci->upload->display_errors();
        $path = FALSE;
    }

    return $path;
}

function setUploadOptions($fileName, $ids, $folderName, $allowedTypes){

    // Remember to give the proper permission to the /upload_files folder
    define("UPLOAD_FOLDER_PATH", "upload_files/{$folderName}");

    $desiredPath = APPPATH.UPLOAD_FOLDER_PATH;

    $path = createFolders($desiredPath, $ids);

    $config['upload_path'] = $path;
    $config['file_name'] = $fileName;
    $config['allowed_types'] = $allowedTypes;
    $config['max_size'] = '5500';
    $config['remove_spaces'] = TRUE;

    return $config;
}

function createFolders($desiredPath, $ids){

    foreach ($ids as $folderType => $id) {

        $auxPath = $desiredPath;

        $pathToAdd = "/".$folderType."_".$id;

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
