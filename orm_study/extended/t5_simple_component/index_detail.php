<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("ORM детальная");
?><?$APPLICATION->IncludeComponent(
	"ftmorm:element.detail", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"ORM_CLASS_NAME" => "GamesTable",
		"ORM_CLASS_S_FIELDS" => array(
			0 => "TEAM_ID",
			1 => "CITY",
			2 => "GOALS",
		),
		"ORM_CLASS_R_FIELDS" => array(
			0 => "TEAM",
		),
		"LIST_URL" => "",
		"DETAIL_URL" => "",
		"SET_PAGE_TITLE" => "N",
		"SET_STATUS_404" => "Y",
		"SHOW_404" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"MESSAGE_404" => "",
		"FILE_404" => "",
		"DEBUG_MODE" => "Y"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>