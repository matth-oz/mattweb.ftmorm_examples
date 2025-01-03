<?php 
    use Bitrix\Main\ORM;
    use \Bitrix\Main\Entity;
    use Bitrix\Main\Loader;
    use Mattweb\Ftmorm;

    // подключение служебной части пролога 
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 
    Loader::Includemodule('mattweb.ftmorm');
?>
<?
$arrRes = [];

$ourTeamName = Ftmorm\GamesTable::OUR_TEAM_NAME;
$ourTeamCity = Ftmorm\GamesTable::OUR_TEAM_CITY;

// подключаем класс для вывода SQL запроса
// \Bitrix\Main\Application::getConnection()->startTracker();

$LineUpsRes = Ftmorm\LineupsTable::getList([
    'select' => ['GAME_ID', 'GOAL_SUMM', 'GM_'=>'GAME', 'GM_OPPONENT_NAME'=>'GAME.TEAM.NAME'],
    'order' => ['GAME_ID' => 'desc'],
    'group' => ['GAME_ID'],
    'runtime' => [new Entity\ExpressionField('GOAL_SUMM', 'SUM(%s)', ['GOALS'])],
]);

// выводим на экран SQL запрос
// echo '<pre>', $LineUpsRes->getTrackerQuery()->getSql(), '</pre>';

while($arR = $LineUpsRes->fetch()){
    $arrRes['MATCHES'][$arR['GM_ID']] = [
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

}

$arMatchesFK = array_key_first($arrRes['MATCHES']);
$arMatchesLK = array_key_last($arrRes['MATCHES']);

$arrRes['DATE_MATCH_LATEST'] = $arrRes['MATCHES'][$arMatchesFK]['GAME_DATE'];
$arrRes['DATE_MATCH_EARLIEST'] = $arrRes['MATCHES'][$arMatchesLK]['GAME_DATE'];

/*echo '<pre>';
print_r($arrRes);
echo '</pre>';*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/test_orm/css/styles.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                $i = 1;
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
                    <td><?=$i?></td>
                    <td><?=$arMatch['GAME_DATE']?></td>
                    <td><?=$arMatch['CITY']?></td>
                    <td><?=$arMatch['TM_NAME']?></td>
                    <td><?=$arMatch['GAME_SCORE']?></td>
                    <td <?if($arMatch['AUTO_GOALS'] > 0):?>class="ag-alert"<?endif?>><?=$arMatch['AUTO_GOALS']?></td>
                </tr>
                <?
                $i++;
                endforeach?>

            </table>
        </div>
    </div>
</body>
</html>
<? 
    // подключение служебной части эпилога 
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php"); 
?>