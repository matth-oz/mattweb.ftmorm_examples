<?php 
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?> 
<?
$APPLICATION->IncludeComponent(
	"ftmorm:player.detail", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"ELEMENT_ID_REQ_VAR" => "pl_id",
		"LIST_PAGE_PATH" => "/test_orm/extended/t5_simple_component/"
	),
	false
);?>
<?     
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
?>