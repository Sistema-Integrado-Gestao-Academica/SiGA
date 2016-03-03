<?php

class GroupException extends Exception{

    public function __construct($message, $exception_code = 0){
        parent::__construct($message, $exception_code);
    }

    public static function handle($exception){

        $message = "Contate o administrador do sistema. Erro ao ler grupos do banco de dados. Dados inconsistentes.<br>Erro encontrado: '<b>";
        $message .= $exception->getMessage()."</b>'";
        show_error($message, 500, 'Ocorreu um erro');
    }
}