<?php
    use Bitrix\Main\Loader;
    use Bitrix\Main\Context;
    use \Bitrix\Main\Entity;

    use Mattweb\Ftmorm;
    // подключение служебной части пролога
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
    Loader::Includemodule('mattweb.ftmorm');

    $arTeamComposition = [
        'GOALKEEPER' => 'Вратарь',
        'DEFENDER' => 'Защитник',
        'FORWARD' => 'Нападающий',
        'MIDFIELDER'=> 'Полузащитник',
    ];

    $request = Context::getCurrent()->getRequest();
    $reqValues = $request->getQueryList();
    
    if(!empty($reqValues['pl_id'])){
        $plID = intval($reqValues['pl_id']);
        $arrRes = [];

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

        $query = new Entity\Query(Ftmorm\PlayersTable::getEntity());
        $query->setSelect(['*']);
        $query->setFilter(['ID' => $plID]);

        $arPlayer = $query->exec()->fetch();

        $arrRes['PLAYER'] = [
            'ID' => $arPlayer['ID'],
            'FN' => $arPlayer['FIRST_NAME'],
            'LN' => $arPlayer['LAST_NAME'],
            'NN' => $arPlayer['NICKNAME'],
            'CITIZEN' => $arPlayer['CITIZENSHIP'],
            'DOB' => $arPlayer['DOB']->format('d.m.Y'),
            'ROLE' => $arTeamComposition[$arPlayer['ROLE']],
        ];

        $query = new Entity\Query(Ftmorm\LineupsTable::getEntity());
        $query->setSelect(['GOAL_SUMM', 'CARDS', 'GM_'=>'GAME', 'TM_NAME'=>'GAME.TEAM.NAME']);
        $query->setGroup('GM_ID');
        $query->setFilter(['PLAYER_ID'=>$plID]);
        $query->registerRuntimeField(null, new Entity\ExpressionField('GOAL_SUMM', 'SUM(%s)', ['GOALS']));

        $gamesRes = $query->exec();

        $arrRes['PLAYER_TOTAL_GOALS'] = 0;
        $arrRes['PLAYER_TOTAL_CARDS'] = 0;

        while($arGameRes = $gamesRes->fetch()){
            $arrRes['PLAYER_GAMES'][$arGameRes['GM_ID']] = [
                'GM_ID' => $arGameRes['GM_ID'],
                'GM_TEAM' => $arGameRes['TM_NAME'],
                'GM_CITY' => (!empty($arR['GM_CITY']) ? $arR['GM_CITY'] : $ourTeamCity),
                'GM_DATE' => $arGameRes['GM_GAME_DATE']->format('d.m.Y'),
                'GM_PLAYER_GOALS' => intval($arGameRes['GOAL_SUMM']),
                'GM_PLAYER_CARDS' => $arGameRes['CARDS'],
            ];

            if(intval($arGameRes['GOAL_SUMM']) > 0){
                $arrRes['PLAYER_GOALS_INFO'][$arGameRes['GM_ID']] = intval($arGameRes['GOAL_SUMM']);
            }

            $arrRes['PLAYER_TOTAL_GOALS'] += intval($arGameRes['GOAL_SUMM']);

            if(!empty($arGameRes['CARDS'])){
                $gmCardQuant = ($arGameRes['CARDS'] == 'Y2') ? 2 : 1;
                $arrRes['PLAYER_TOTAL_CARDS'] += $gmCardQuant;
                $arrRes['PLAYER_CARDS_INFO'][$arGameRes['GM_ID']] = $arGameRes['CARDS'];
            }

            $arrRes['PAGE_TITLE'] = 'Матчи игрока '.$arrRes['PLAYER']['FN'].' '.$arrRes['PLAYER']['LN']
        .' с '.$arrRes['DATE_MATCH_EARLIEST'].' по '.$arrRes['DATE_MATCH_LATEST'];
            
            //dump($arGameRes);
        }

        //dump($arrRes);
    }
    else{
        ShowError('Скипту не передан обязательный параметр');
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/test_orm/css/styles.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$arrRes['PAGE_TITLE']?></title>
</head>
    <body>
        <div class="main-wrap">
            <div class="head">
                <h1><?=$arrRes['PAGE_TITLE']?></h1>
            </div>
            <div class="main-cont">
                <div class="cont-detail">
                    <div class="summary">
                        <p>Имя и фамилия игрока:
                            &nbsp;<span><?=$arrRes['PLAYER']['FN'].' '.$arrRes['PLAYER']['LN'];?></span></p>
                        <p>Дата рождения: <span><?=$arrRes['PLAYER']['DOB']?></span></p>
                        <p>Имя на футболке: <span><?=$arrRes['PLAYER']['NN']?></span></p>
                        <p>Гражданство: <span><?=$arrRes['PLAYER']['CITIZEN']?></span></p>
                        <p>Амплуа: <span><?=$arrRes['PLAYER']['ROLE']?></span></p>
                    </div>
                    <div class="players-list">
                        <h3>Матчи, в которых участвовал игрок <?=$arrRes['PLAYER']['FN'].' '.$arrRes['PLAYER']['LN'];?></h3>
                        <table class="main-tbl">
                            <tr>
                                <th>Дата матча</th>
                                <th>Команда</th>
                                <th>Город</th>
                                <th>Голы игрока</th>
                                <th>Взыскания</th>
                            </tr>
                            <?foreach($arrRes['PLAYER_GAMES'] as $gmId => $arrGame):?>
                            <tr>
                                <td><?=$arrGame['GM_DATE']?></td>
                                <td><?=$arrGame['GM_TEAM']?></td>
                                <td><?=$arrGame['GM_CITY']?></td>
                                <td><?=$arrGame['GM_PLAYER_GOALS']?></td>
                                <td>
                                    <?if($arrGame['GM_PLAYER_CARDS'] == 'Y'):?>
                                        <span class="card yell"></span>
                                    <?endif?>
                                    <?if($arrGame['GM_PLAYER_CARDS'] == 'Y2'):?>
                                        <span class="card yell"></span>
                                        <span class="card yell"></span>
                                    <?endif?>
                                    <?if($arrGame['GM_PLAYER_CARDS'] == 'YR'):?>
                                        <span class="card yell"></span>
                                        <span class="card red"></span>
                                    <?endif?>
                                    <?if($arrGame['GM_PLAYER_CARDS'] == 'R'):?>
                                        <span class="card red"></span>
                                    <?endif?>
                                </td>
                            </tr>
                            <?endforeach?>
                        </table>
                    </div>
                    <div class="result-details">
                        <div class="result-detail">
                            <h3>Голы игрока</h3>
                            <table class="main-tbl">
                                <tr>
                                    <th>Дата матча</th>
                                    <th>Команда</th>
                                    <th>Голы игрока</th>
                                </tr>
                            <?foreach($arrRes['PLAYER_GOALS_INFO'] as $key=>$goalVal):?>
                                <tr>
                                    <td>
                                        <?=$arrRes['PLAYER_GAMES'][$key]['GM_DATE']?>
                                    </td>
                                    <td>
                                        <?=$arrRes['PLAYER_GAMES'][$key]['GM_TEAM']?>
                                    </td>
                                    <td><?=$goalVal?></td>
                                </tr>
                            <?endforeach?>
                                <tr>
                                    <td class="ttl-info" colspan="2">Всего голов в играх:</td>
                                    <td><span class="ttl-qnt"><?=$arrRes['PLAYER_TOTAL_GOALS']?></span></td>
                                </tr>
                            </table>
                        </div>
                        <div class="result-detail">
                            <h3>Взыскания игрока</h3>
                            <table class="main-tbl">
                                <tr>
                                    <th>Дата матча</th>
                                    <th>Взыскание</th>
                                </tr>
                                <?foreach($arrRes['PLAYER_CARDS_INFO'] as $key=>$cardVal):?>
                                <tr>
                                    <td>
                                    <?=$arrRes['PLAYER_GAMES'][$key]['GM_DATE']?>
                                    </td>
                                    <td>
                                        <?if($cardVal == 'Y'):?>
                                            <span class="card yell"></span>
                                        <?endif?>
                                        <?if($cardVal == 'Y2'):?>
                                            <span class="card yell"></span>
                                            <span class="card yell"></span>
                                        <?endif?>
                                        <?if($cardVal == 'YR'):?>
                                            <span class="card yell"></span>
                                            <span class="card red"></span>
                                        <?endif?>
                                        <?if($cardVal == 'R'):?>
                                            <span class="card red"></span>
                                        <?endif?>
                                    </td>
                                </tr>
                                <?endforeach?>
                                <tr>
                                    <td class="ttl-info">Всего взысканий в играх:</td>
                                    <td><span class="ttl-qnt"><?=$arrRes['PLAYER_TOTAL_CARDS']?></span></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="foot">
                <p><a href="/test_orm/basic/t5/query/">← К списку</a></p>
            </div>
        </div>
    </body>
</html>
<?
    // подключение служебной части эпилога
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>