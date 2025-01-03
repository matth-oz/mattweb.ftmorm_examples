<?php
// подключение служебной части пролога
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
use \Bitrix\Main\Loader;
use \Bitrix\Main\Entity;
use Mattweb\Ftmorm;

if(!Loader::IncludeModule('mattweb.ftmorm')){
    ShowError('Модуль mattweb.ftmorm не подключен');
}


$className = trim("Mattweb\\Ftmorm\\LineupsTable");

/*Получение полей модели*/
$pt = $className::getMap();

foreach($pt as $elPt){

	 //$fArr = $elPt->toArray();
	 //$ipr = $elPt->isPrimary();

    $parentClass = get_class($elPt);
    if(str_contains($parentClass, 'IntegerField')){
        $ipr = $elPt->isPrimary();
        dump($ipr);
    }
    dump($parentClass);
    $fn = $elPt->getName(); // имя поля    
    dump($fn);
    $fd = $elPt->getParameter('description');
    dump($fd);
}

$curEntity = $className::getEntity();
$query = new Entity\Query($curEntity);

$query->setSelect(['GAME_ID', 'START', 'TIME_IN', 'GOALS', 'CARDS']);
$query->setOrder(['GAME_ID' => 'DESC']);
$query->countTotal(true);
$elementsRes = $query->exec();
$countRes = $elementsRes->getCount();
echo $countRes;
while($arR = $elementsRes->fetch()){
    dump($arR);
}


/*$servObj = new ServiceActions('GamesTable');

$obRelationFields = $servObj->getRelationFields('full');

echo '<pre>';
var_export($obRelationFields);
echo '</pre>';

echo '<hr />';
echo '<br />';*/

/*
$arTeamOrmData = $obRelationFields['TEAM'];
$className = $arTeamOrmData["REFERENCE_NAME"].'Table';

$tt = $className::getMap();

foreach($tt as $elTt){
    $elName = $elTt->getName();
    $elTitle = $elTt->getTitle();

    $parentClass = get_class($elTt);
    
    var_export($elName);
    echo ' - ';
    var_export($elTitle);
    echo '<br />';
    var_export($parentClass);
    echo '<br /><br />';    
}

/*$teamsEntity = $className::getEntity();
$query = new Entity\Query($teamsEntity);*/


/*$obRes = Ftmorm\GamesTable::getList([
    //'select' => ['CITY', 'GAME_DATE', 'TEAM__'=>'TEAM.*'],
    'select' => ['CITY', 'GAME_DATE', 'GAME__'=>'GAMES.*'],
    'filter' => ['ID'=> 11]
]); 

while($arRes = $obRes->fetch()){

    echo '<pre>';
    var_export($arRes);
    echo '</pre>';
}*/


/*
// https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=3030&LESSON_PATH=3913.3516.5748.3030
$obRes = Ftmorm\TeamsTable::query()
    ->setSelect(['NAME'])
	//->where("ID", 1)
    ->whereLike('NAME', '%city%')
	->exec();

while($arRes = $obRes->fetch()){

    echo '<pre>';
    var_export($arRes);
    echo '</pre>';
}*/


/*
// https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=5753&LESSON_PATH=3913.3516.5748.5063.5753

$obRes = Ftmorm\TeamsTable::getList([
    'select' => ['NAME'],
    'filter' => ['%=NAME'=> '%city%']
]); 

while($arRes = $obRes->fetch()){

    echo '<pre>';
    var_export($arRes);
    echo '</pre>';
}
*/

/*
https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=5751&LESSON_PATH=3913.3516.5748.5063.5751

$teamsEntity = Ftmorm\TeamsTable::getEntity();
$query = new Entity\Query($teamsEntity);

$query->setSelect(['NAME']);
$query->setFilter([
    ['%=NAME'=> '%city%']
]);
$curEntityRes = $query->exec();

while($arCurRes = $curEntityRes->fetch()){

    echo '<pre>';
    var_export($arCurRes);
    echo '</pre>';
}*/
?>

<?
// подключение служебной части эпилога
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>