<?php 
use \Bitrix\Main\Entity;
use Bitrix\Main\Loader;
use Bitrix\Main\Context;

use Mattweb\Ftmorm;
// подключение служебной части пролога 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 

Loader::includeModule('mattweb.ftmorm');

$arGameDates = [];

$arSelect = ['GM_ID'=>'ID', 'GM_CITY'=>'CITY', 'GM_GAME_DATE'=>'GAME_DATE'];

$modelName = 'Mattweb\Ftmorm\GamesTable';
$curEntity = $modelName::getEntity();
$query = new Entity\Query($curEntity);

$fieldVal = 'Exeter';

$filterCond = '%=GM_CITY';
$filterVal = '%'.$fieldVal.'%';

$query->setSelect($arSelect);
$query->setFilter([
    [$filterCond => $filterVal]
]);
$curEntityRes = $query->exec();

while($arCurRes = $curEntityRes->fetch()){

    $arGameDates[$arCurRes['GM_ID']] = [
        'CITY' => $arCurRes['GM_CITY'],
        'GAME_DATE' =>  $arCurRes['GM_GAME_DATE']->format("d.m.Y")
    ];    
}


/*$gamesRes = Ftmorm\GamesTable::getList([
    'select' => ['GM_ID'=>'ID', 'GM_CITY'=>'CITY', 'GM_GAME_DATE'=>'GAME_DATE'],
]);

$arGameDates = [];

while($arGmData = $gamesRes->fetch()){
    $arGameDates[$arGmData['GM_ID']] = [
        'CITY' => $arGmData['GM_CITY'],
        'GAME_DATE' =>  $arGmData['GM_GAME_DATE']->format("d.m.Y")
    ];
}*/

dump($arGameDates);


?> 
<? 
    // подключение служебной части эпилога 
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php"); 
?>