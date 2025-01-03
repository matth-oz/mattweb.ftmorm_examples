<?php
    use \Bitrix\Main\Entity;
    use Bitrix\Main\Loader;
    use Bitrix\Main\Context;

    use Mattweb\Ftmorm;
    // подключение служебной части пролога
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
    
    Loader::Includemodule('mattweb.ftmorm');
    
    $arrRes = [];

    $arTeamComposition = [
        'GOALKEEPER' => 'Вратарь',
        'DEFENDER' => 'Защитник',
        'FORWARD' => 'Нападающий',
        'MIDFIELDER'=> 'Полузащитник',
    ];

    $gamesEntity = Ftmorm\GamesTable::getEntity();

    $query = new Entity\Query($gamesEntity);
    $query->setSelect(['LAST_GAME_DATE', 'FIRST_GAME_DATE']);
    $query->registerRuntimeField(null, new Entity\ExpressionField('LAST_GAME_DATE', 'MAX(%s)', ['GAME_DATE']));
    $query->registerRuntimeField(null, new Entity\ExpressionField('FIRST_GAME_DATE', 'MIN(%s)', ['GAME_DATE']));

    $gamesRes = $query->exec();

    $arGameDates = $gamesRes->fetch();
    
    $arrRes['DATE_MATCH_EARLIEST'] = $arGameDates['FIRST_GAME_DATE']->format('d.m.Y');
    $arrRes['DATE_MATCH_LATEST'] = $arGameDates['LAST_GAME_DATE']->format('d.m.Y');

    $ourTeamName = Ftmorm\GamesTable::OUR_TEAM_NAME;
    $ourTeamCity = Ftmorm\GamesTable::OUR_TEAM_CITY;
    
    $playersEntity = Ftmorm\PlayersTable::getEntity();

    $query = new Entity\Query($playersEntity);
    $query->setSelect(['ID', 'FIRST_NAME', 'LAST_NAME', 'NICKNAME', 'CITIZENSHIP', 'DOB', 'ROLE']);

    $playersRes = $query->exec();

    while($arR = $playersRes->fetch()){
        $arrRes['PLAYERS'][$arR['ID']] = [
            'PLAYER_ID' => $arR['ID'],
            'PLAYER_FN' => $arR['FIRST_NAME'],
            'PLAYER_LN' => $arR['LAST_NAME'],
            'PLAYER_NN' => $arR['NICKNAME'],
            'PLAYER_CITIZEN' => $arR['CITIZENSHIP'],
            'PLAYER_DOB' => $arR['DOB']->format('d.m.Y'),
            'PLAYER_ROLE' => $arTeamComposition[$arR['ROLE']]
        ];
    }
    //dump($arrRes);

?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/test_orm/css/styles.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Список игроков, участвоваших в матчах c <?=$arrRes['DATE_MATCH_EARLIEST']?> по <?=$arrRes['DATE_MATCH_LATEST']?></title>
</head>
<body>
<div class="main-wrap">
        <div class="head">
            <h1>Список игроков, участвоваших в матчах c <?=$arrRes['DATE_MATCH_EARLIEST']?> по <?=$arrRes['DATE_MATCH_LATEST']?></h1>
        </div>
        <div class="main-cont">
            <table class="main-tbl">
                <tr>
                    <th>Имя и фамилия игрока</th>
                    <th>Имя на футболке</th>
                    <th>Дата рождения</th>
                    <th>Гражданство</th>
                    <th>Амплуа</th>
                </tr>
                <?
                foreach($arrRes['PLAYERS'] as $arPlayer):?>
                <tr>
                    <td>
                    <a href="/test_orm/t5/query/detail.php?pl_id=<?=$arPlayer['PLAYER_ID']?>"><?=$arPlayer['PLAYER_FN'].' '.$arPlayer['PLAYER_LN']?></a>
                    </td>
                    <td>
                    <a href="/test_orm/t5/query/detail.php?pl_id=<?=$arPlayer['PLAYER_ID']?>"><?=$arPlayer['PLAYER_NN']?></a>
                    </td>
                    <td>
                    <a href="/test_orm/t5/query/detail.php?pl_id=<?=$arPlayer['PLAYER_ID']?>"><?=$arPlayer['PLAYER_DOB']?></a>
                    </td>
                    <td>
                    <a href="/test_orm/t5/query/detail.php?pl_id=<?=$arPlayer['PLAYER_ID']?>"><?=$arPlayer['PLAYER_CITIZEN']?></a>
                    </td>
                    <td>
                    <a href="/test_orm/t5/query/detail.php?pl_id=<?=$arPlayer['PLAYER_ID']?>"><?=$arPlayer['PLAYER_ROLE']?></a>
                    </td>
                </tr>
                <?endforeach?>
            </table>
        </div>
    </div>
</body>
</html>
<?
    // подключение служебной части эпилога
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>