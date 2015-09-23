<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arComponentParameters = array(
   "GROUPS" => array(
      "SETTINGS" => array(
         "NAME" => "Настройки"
      ),
   ),
   "PARAMETERS" => array(
		"MIN_COUNT_UG" => array(
			"PARENT" => "SETTINGS", 
			"NAME" => GetMessage('MIN_COUNT_OF_ORDERS'),
			"TYPE" => "STRING",
			"DEFAULT" => "1",
			"COLS" => "4"
			),
		"TIME_INTERVAL_UG" => array(
			"PARENT" => "SETTINGS", 
			"NAME" => GetMessage('REPORT_GENERATION_TIME'),
			"TYPE" => "STRING",
			"DEFAULT" => "7",
			"COLS" => "4"
			),
		"IS_AGENT" => array(
			"PARENT" => "SETTINGS",
			"NAME" => GetMessage('COMP_FROM_AGENT'),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => false,
			"COLS" => "4"
			),
   )
);
?>