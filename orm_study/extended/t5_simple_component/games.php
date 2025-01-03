<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("ORM список");
?><?$APPLICATION->IncludeComponent(
	"ftmorm:elements.list", 
	"games", 
	array(
		"COMPONENT_TEMPLATE" => "games",
		"ORM_CLASS_NAME" => "GamesTable",
		"ORM_CLASS_S_FIELDS" => array(
			0 => "CITY",
			1 => "GOALS",
			2 => "GAME_DATE",
			3 => "OWN",
		),
		"ORM_CLASS_R_FIELDS" => array(
			0 => "TEAM",
		),
		"SHOW_ELEMENT_ID" => "Y",
		"ELEMENTS_COUNT" => "20",
		"FILTER_NAME" => "",
		"SORT_BY" => "0",
		"SORT_ORDER" => "DESC",
		"DETAIL_URL" => "index_detail.php?el_id=#ELEMENT_ID#",
		"PAGER_TEMPLATE" => "orm_filter",
		"PAGER_SHOW_ALL" => "Y",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "Элементы",
		"PAGER_SHOW_ALWAYS" => "N",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "36000000",
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "Y",
		"FILE_404" => "",
		"DEBUG_MODE" => "N",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"PAGE_HEADER" => "Список матчей команды [games]"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>