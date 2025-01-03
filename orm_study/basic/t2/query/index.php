<?php
    use \Bitrix\Main\UI;
    use \Bitrix\Main\Entity;
    use Bitrix\Main\Loader;
    use Bitrix\Main\Context;

    use Mattweb\Ftmorm;

    define('EL_PAGE_COUNT', 10);

    // подключение служебной части пролога
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
    Loader::Includemodule('mattweb.ftmorm');

    $request = Context::getCurrent()->getRequest();
    $reqValues = $request->getQueryList();

    // расчитываем правильные номера в таблице на всех страницах кроме первой
    $pager = $reqValues['pager'];
    $pageNum = 0;
    if(!empty($pager) && $pager != 'page-all'){
        $arPager = explode('-', $pager);
        $pageNum = intval($arPager[1]) - 1;
    }
?>
<?
global $APPLICATION;
$arrRes = $arGameDates = [];

$ourTeamName = Ftmorm\GamesTable::OUR_TEAM_NAME;
$ourTeamCity = Ftmorm\GamesTable::OUR_TEAM_CITY;

// вычисляем правильные даты первого и последнего матча для заголовка при пагинации
$gamesRes = Ftmorm\GamesTable::getList([
    'select' => ['CITY', 'GAME_DATE'],
]);

$k=0;
while($arGmData = $gamesRes->fetch()){
    $arGameDates[$k] = $arGmData['GAME_DATE']->format("d.m.Y");
    $k++;
}

usort($arGameDates, function($a, $b) {
    return strtotime($a) - strtotime($b);
});

$arMatchesFK = array_key_first($arGameDates);
$arMatchesLK = array_key_last($arGameDates);

$arrRes['DATE_MATCH_EARLIEST'] = $arGameDates[$arMatchesFK];
$arrRes['DATE_MATCH_LATEST'] = $arGameDates[$arMatchesLK];


// создаем объект пагинации
$nav = new UI\PageNavigation("pager");
$nav->allowAllRecords(true)
		->setPageSize(EL_PAGE_COUNT)
		->initFromUri();

$offset = $nav->getOffset();

$LineUpsEntity = Ftmorm\LineupsTable::getEntity();

$query = new Entity\Query($LineUpsEntity);
$query->setSelect(['GAME_ID', 'GOAL_SUMM', 'GM_'=>'GAME', 'GM_OPPONENT_NAME'=>'GAME.TEAM.NAME']);
$query->setOrder(['GAME_ID' => 'desc']);
$query->setGroup('GAME_ID');
$query->setOffset($nav->getOffset());
$query->setLimit($nav->getLimit());
$query->countTotal(true);
$query->registerRuntimeField(null, new Entity\ExpressionField('GOAL_SUMM', 'SUM(%s)', ['GOALS']));

/*$sql = $query->getQuery();
echo $sql;*/

$LineUpsRes = $query->exec();

$nav->setRecordCount($LineUpsRes->getCount());

/*  echo '<pre>';
    print_r($arrRes);
    echo '</pre>';
*/
$i = ($pageNum > 0) ? (EL_PAGE_COUNT * $pageNum + 1) : 1;
while($arR = $LineUpsRes->fetch()){
    $arrRes['MATCHES'][$arR['GM_ID']] = [
        'ORD_NUMBER' => $i,
        'ID' => $arR['GM_ID'],
        'TM_NAME' => $arR['GM_OPPONENT_NAME'],
        'CITY' => (!empty($arR['GM_CITY']) ? $arR['GM_CITY'] : $ourTeamCity),
        'GAME_DATE' => $arR['GM_GAME_DATE']->format("d.m.Y"),
        'OUR_GOALS' => intval($arR['GM_GOALS']),
        'OPPONENT_GOALS' => (intval($arR['GOAL_SUMM']) + intval($arR['GM_OWN'])),
        'AUTO_GOALS' => intval($arR['GM_OWN']),
    ];

    $opponentGoals = intval($arR['GOAL_SUMM']) + intval($arR['GM_OWN']);
    $ourCommandGoals = intval($arR['GM_GOALS']);

    if($opponentGoals != $ourCommandGoals){
        $gmRes = ($opponentGoals > $ourCommandGoals) ? 'L' : 'W';
    }
    else{
        $gmRes = 'D'; // Draw - ничья
    }

    $arrRes['MATCHES'][$arR['GM_ID']]['GAME_RESULT'] = $gmRes;
    
    $score = '';
    if(!empty($arR['GM_CITY']))
        $score .= $opponentGoals.' : '.$ourCommandGoals;
    else
        $score .= $ourCommandGoals.' : '.$opponentGoals;
    
    $arrRes['MATCHES'][$arR['GM_ID']]['GAME_SCORE'] = $score;
    $i++;
}

$arrRes['NAV_OBJECT'] = $nav;
?>
<?
/*echo '<pre>';
print_r($arrRes);
echo '</pre>';*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/test_orm/css/styles.css" />
    <title>Матчи команды <?=$ourTeamName?></title>
</head>
<body>
    <div class="main-wrap">
        <div class="head">
            <h1>Матчи команды <?=$ourTeamName?> c <?=$arrRes['DATE_MATCH_EARLIEST']?> по <?=$arrRes['DATE_MATCH_LATEST']?></h1>
        </div>
        <div class="main-cont">
            <table class="main-tbl">
                <tr>
                    <th>Номер</th>
                    <th>Дата проведения</th>
                    <th>Город</th>
                    <th>Команда-соперник</th>
                    <th>Счет</th>
                    <th>Автоголы</th>
                </tr>
                <?
                foreach($arrRes['MATCHES'] as $arMatch):?>
                <?if($arMatch['GAME_RESULT'] == 'D'){
                    $rowCSSClass = 'd-row';
                }
                elseif($arMatch['GAME_RESULT'] == 'W'){
                    $rowCSSClass = 'w-row';
                }else{
                    $rowCSSClass = 'l-row';
                }?>
                <tr class="<?=$rowCSSClass?>">
                    <td><?=$arMatch['ORD_NUMBER']?></td>
                    <td><?=$arMatch['GAME_DATE']?></td>
                    <td><?=$arMatch['CITY']?></td>
                    <td><?=$arMatch['TM_NAME']?></td>
                    <td><?=$arMatch['GAME_SCORE']?></td>
                    <td <?if($arMatch['AUTO_GOALS'] > 0):?>class="ag-alert"<?endif?>><?=$arMatch['AUTO_GOALS']?></td>
                </tr>
                <?endforeach?>
            </table>
            <div class="nav-wrap">
                <?$APPLICATION->IncludeComponent(
                    "bitrix:main.pagenavigation",
                    ".default",
                    Array(
                    "NAV_OBJECT" => $arrRes['NAV_OBJECT'],
                        "SEF_MODE" => "N"
                    ),
                    false
                );?>
            </div>
        </div>
    </div>
</body>
</html>

<?
    // подключение служебной части эпилога
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>