<?php 
    // подключение служебной части пролога 
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
?> 
<?$APPLICATION->IncludeComponent(
	"ftmorm:match.detail", 
	".default", 
	array(
		"COMPONENT_TEMPLATE" => ".default",
		"ELEMENT_ID_REQ_VAR" => "gm_id",
		"LIST_PAGE_PATH" => "/test_orm/extended/t3_simple_component/"
	),
	false
);?>
<? 
    // подключение служебной части эпилога 
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php"); 
?>