<?php 
    // подключение служебной части пролога 
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
?> 
<?$APPLICATION->IncludeComponent(
	"ftmorm:matches.list",
	"",
	Array(
		"DETAIL_PAGE_PATH" => "/test_orm/extended/t3_simple_component/detail.php?gm_id=#GM_ID#",
		"EL_PAGE_COUNT" => "10",
		"SORT_COOKIE_LD" => "5"
	)
);?>
<? 
    // подключение служебной части эпилога 
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php"); 
?>