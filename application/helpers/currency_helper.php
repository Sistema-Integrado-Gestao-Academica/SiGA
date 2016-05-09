<?php 
function currencyBR($number) {
	return "R$ " . number_format($number, 2, ",", ".");
}
