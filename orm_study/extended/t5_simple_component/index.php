<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Тестирование фильтра orm");
?><br>
 <?$APPLICATION->IncludeComponent(
	"ftmorm:ftmorm.filter", 
	".default", 
	array(
		"BOOL_FIELD_TRUE_VALUE" => "",
		"FILTER_ACTION_URL" => "/test_orm/extended/t5_simple_component/",
		"ORM_CLASS_NAME" => "PlayersTable",
		"ORM_CLASS_S_FIELDS" => array(
			0 => "NICKNAME",
			1 => "CITIZENSHIP",
			2 => "DOB",
			3 => "ROLE",
		),
		"COMPONENT_TEMPLATE" => ".default",
		"ORM_CLASS_S_FIELDS_TYPES" => "{\"NICKNAME\":\"IS\",\"CITIZENSHIP\":\"IT\",\"DOB\":\"ISR\",\"ROLE\":\"IS\"}",
		"SHOW_BOOL_ONLY_TRUE" => "N",
		"FILTER_DISPLAY_MODE" => "vertical",
		"USE_TEXTFIELDS_TOOLTIPS" => "Y",
		"MIN_QUERY_LENGTH" => "3",
		"USE_ORM_ENTITY_ALIAS" => "N"
	),
	false
);?><br>
 <br>
 <br>

<?
$arrElementsFilter = (isset($_GET['filter'])) ? $USER->GetParam('ftmormfilter') : "";

$pagerBaseLink = (isset($_GET['filter'])) ? $APPLICATION->GetCurPage()."?filter=y" : "";

if(!isset($_GET['filter'])){
	$USER->SetParam('ftmormfilter', '');
}

$APPLICATION->IncludeComponent(
	"ftmorm:players.list", 
	".default", 
	array(
		"DETAIL_PAGE_PATH" => "/test_orm/extended/t5_simple_component/detail.php?pl_id=#PL_ID#",
		"EL_PAGE_COUNT" => "10",
		"FILTER_NAME" => "arrElementsFilter",
		"COMPONENT_TEMPLATE" => ".default",
		"PAGENAV_TEMPLATE" => "orm_filter"
	),
	false
);?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>