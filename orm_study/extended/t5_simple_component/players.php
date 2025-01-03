<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("ORM список");
?><?$APPLICATION->IncludeComponent(
	"ftmorm:elements.list", 
	"players", 
	array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "N",
		"DEBUG_MODE" => "Y",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENTS_COUNT" => "20",
		"FILE_404" => "",
		"FILTER_NAME" => "",
		"ORM_CLASS_NAME" => "PlayersTable",
		"ORM_CLASS_R_FIELDS" => array(
		),
		"ORM_CLASS_S_FIELDS" => array(
			0 => "FIRST_NAME",
			1 => "LAST_NAME",
			2 => "NICKNAME",
			3 => "CITIZENSHIP",
			4 => "DOB",
			5 => "ROLE",
		),
		"PAGER_SHOW_ALL" => "Y",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "orm_filter",
		"PAGER_TITLE" => "Элементы",
		"PAGE_HEADER" => "Список игроков команд-соперников [players]",
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "Y",
		"SHOW_ELEMENT_ID" => "Y",
		"SORT_BY" => "0",
		"SORT_ORDER" => "DESC",
		"COMPONENT_TEMPLATE" => "players"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>