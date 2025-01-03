<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Тестирование фильтра orm");
?><?$APPLICATION->IncludeComponent(
	"ftmorm:matches", 
	".default", 
	array(
		"DETAIL_PAGE_PATH" => "",
		"ELEMENT_ID_REQ_VAR" => "GM_ID",
		"EL_PAGE_COUNT" => "10",
		"LIST_PAGE_PATH" => "",
		"SEF_FOLDER" => "/test_orm/extended/t5_complex_component/",
		"SEF_MODE" => "Y",
		"SORT_COOKIE_LD" => "5",
		"USE_FILTER" => "Y",
		"FILTER_NAME" => "arrElementsFilter",
		"COMPONENT_TEMPLATE" => ".default",
		"ORM_CLASS_S_FIELDS" => array(
			0 => "CITY",
			1 => "GAME_DATE",
			2 => "OWN",
		),
		"FILTER_ACTION_URL" => "",
		"FILTER_DISPLAY_MODE" => "horizontal",
		"USE_TEXTFIELDS_TOOLTIPS" => "Y",
		"MIN_QUERY_LENGTH" => "3",
		"SHOW_BOOL_ONLY_TRUE" => "N",
		"ORM_CLASS_S_FIELDS_TYPES" => "{\"CITY\":\"IS\",\"GAME_DATE\":\"ISR\",\"OWN\":\"NMB\"}",
		"USE_ORM_ENTITY_ALIAS" => "Y",
		"SEF_URL_TEMPLATES" => array(
			"matches" => "",
			"detail" => "#GM_ID#/",
		)
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>