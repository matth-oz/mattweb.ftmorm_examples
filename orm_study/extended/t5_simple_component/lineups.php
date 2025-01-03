<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("ORM список");
?><?$APPLICATION->IncludeComponent(
	"ftmorm:elements.list", 
	"lineups", 
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
		"ORM_CLASS_NAME" => "LineupsTable",
		"ORM_CLASS_R_FIELDS" => array(
			0 => "GAME",
			1 => "PLAYER",
		),
		"ORM_CLASS_S_FIELDS" => array(
			0 => "START",
			1 => "TIME_IN",
			2 => "GOALS",
			3 => "CARDS",
		),
		"PAGER_SHOW_ALL" => "Y",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "orm_filter",
		"PAGER_TITLE" => "Элементы",
		"PAGE_HEADER" => "Список матчей команды [lineups]",
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "Y",
		"SHOW_ELEMENT_ID" => "Y",
		"SORT_BY" => "PLAYER_ID",
		"SORT_ORDER" => "DESC",
		"COMPONENT_TEMPLATE" => "lineups"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>