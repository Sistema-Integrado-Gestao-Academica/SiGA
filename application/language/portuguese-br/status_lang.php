<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once(MODULESPATH."/secretary/constants/OfferConstants.php");
require_once(MODULESPATH."/student/constants/StatusConstants.php");

$lang[OfferConstants::PROPOSED_OFFER] = 'proposta';
$lang[OfferConstants::PLANNED_OFFER] = 'planejada';
$lang[OfferConstants::APPROVED_OFFER] = 'aprovada';

$lang[StatusConstants::DELAYED_QUALIFY] = "Qualificação atrasada";