<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("ORM список");
?><?$APPLICATION->IncludeComponent(
	"ftmorm:elements.list", 
	"teams", 
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "N",
		"DEBUG_MODE" => "N",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENTS_COUNT" => "20",
		"FILE_404" => "",
		"FILTER_NAME" => "",
		"ORM_CLASS_NAME" => "TeamsTable",
		"ORM_CLASS_R_FIELDS" => array(
		),
		"ORM_CLASS_S_FIELDS" => array(
			0 => "NAME",
			1 => "FOUND_YEAR",
		),
		"PAGER_SHOW_ALL" => "Y",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "orm_filter",
		"PAGER_TITLE" => "Элементы",
		"PAGE_HEADER" => "Список команд-соперников [teams]",
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "Y",
		"SHOW_ELEMENT_ID" => "Y",
		"SORT_BY" => "0",
		"SORT_ORDER" => "DESC",
		"COMPONENT_TEMPLATE" => "teams"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>