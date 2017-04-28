<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(MODULESPATH."/program/constants/SelectionProcessConstants.php");

$lang['homologation']  = "Homologação";
$lang['pre_project'] = "Avaliação de Pré-Projeto";
$lang['written_test'] = "Prova escrita";
$lang['oral_test']  = "Prova oral";

$lang['Homologação']  = "homologation";
$lang['Avaliação de Pré-Projeto'] = "pre_project";
$lang['Prova escrita'] = "written_test";
$lang['Prova oral']  = "oral_test";

$lang['male']  = "Masculino";
$lang['female']  = "Feminino";

$lang[SelectionProcessConstants::DRAFT] = "<p class='label label-warning'> Rascunho </p>";
$lang[SelectionProcessConstants::DISCLOSED] = "<p class='label label-success'>Divulgado</p>";
$lang[SelectionProcessConstants::OPEN_FOR_SUBSCRIPTIONS] = "<p class='label label-info'>Inscrições abertas</p>";
$lang[SelectionProcessConstants::IN_HOMOLOGATION_PHASE] = "<p class='label label-success'>Em fase de Homologação</p>";
$lang[SelectionProcessConstants::IN_PRE_PROJECT_PHASE] = "<p class='label label-success'>Em fase de Avaliação de Pré-Projeto</p>";
$lang[SelectionProcessConstants::IN_WRITTEN_TEST_PHASE] = "<p class='label label-success'>Em fase de Prova escrita</p>";
$lang[SelectionProcessConstants::IN_ORAL_TEST_PHASE] = "<p class='label label-success'>Em fase de Prova Oral</p>";
$lang[SelectionProcessConstants::FINISHED] = "<p class='label label-danger'>Encerrado</p>";
$lang[SelectionProcessConstants::INCOMPLETE_CONFIG] = "<p class='label label-danger'>Configuração incompleta</p>";
$lang[SelectionProcessConstants::WAITING_NEXT_PHASE] = "<p class='label label-warning'> Aguardando avanço para a próxima fase </p>";