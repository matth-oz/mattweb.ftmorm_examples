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

    if(!empty($reqValues['gm_id'])){
        $gmID = intval($reqValues['gm_id']);

        $arrRes = $arFilter = [];
        $arrRes['MATCH_GOALS'] = [];

        $ourTeamName = Ftmorm\GamesTable::OUR_TEAM_NAME;
        $ourTeamCity = Ftmorm\GamesTable::OUR_TEAM_CITY;

        $arFilter = ['GAME_ID'=> $gmID];


        $LineUpsEntity = Ftmorm\LineupsTable::getEntity();
        $query = new Entity\Query($LineUpsEntity);
        $query->setSelect(['GAME_ID', 'PL_'=>'PLAYER', 'START', 'TIME_IN', 'GOALS', 'CARDS', 'GM_'=>'GAME', 'GM_OPPONENT_NAME'=>'GAME.TEAM.NAME']);
        $query->setFilter($arFilter);

        $LineUpsRes = $query->exec();
        
        $i=0;
        $opponentGoals = 0;
        while($arR = $LineUpsRes->fetch()){
            if($i == 0){
                $arrRes['MATCH'] = [
                    'ID' => $arR['GAME_ID'],
                    'TM_NAME' => $arR['GM_OPPONENT_NAME'],
                    'CITY' => (!empty($arR['GM_CITY']) ? $arR['GM_CITY'] : $ourTeamCity),
                    'GAME_DATE' => $arR['GM_GAME_DATE']->format("d.m.Y"),
                    'OUR_GOALS' => intval($arR['GM_GOALS']),
                    'OPPONENT_GOALS' => 0,
                    'AUTO_GOALS' => intval($arR['GM_OWN']),
                ];

                if($arrRes['MATCH']['CITY'] == $ourTeamCity){
                    $arrRes['MATCH_TITLE'] = 'Матч '.$ourTeamName.' - '.$arrRes['MATCH']['TM_NAME'];
                }
                else{
                    $arrRes['MATCH_TITLE'] = 'Матч '.$arrRes['MATCH']['TM_NAME'].' - '.$ourTeamName;
                }

            }

            $startType = ($arR['START'] == 'B') ? 'BASE' : 'RESERVE';

            $arrRes['MATCH_PLAYERS'][$startType][$arR['PL_ID']] = [
                'PLAYER_ID' => $arR['PL_ID'],
                'PLAYER_FN' => $arR['PL_FIRST_NAME'],
                'PLAYER_LN' => $arR['PL_LAST_NAME'],
                'PLAYER_NN' => $arR['PL_NICKNAME'],
                'PLAYER_CITIZEN' => $arR['PL_CITIZENSHIP'],
                'PLAYER_DOB' => $arR['PL_DOB']->format('d.m.Y'),
                'START' => $arR['START'],
                'TIME_IN' => $arR['TIME_IN'],
                'PLAYER_ROLE' => $arR['PL_ROLE'],
            ];
            
            
            if($arR['GOALS'] > 0){
                $opponentGoals += $arR['GOALS'];
                if(array_key_exists($arR['PL_ID'], $arrRes['MATCH_GOALS'])){
                    $arrRes['MATCH_GOALS'][$arR['PL_ID']] = $arrRes['MATCH_GOALS'][$arR['PL_ID']] + $arR['GOALS'];
                }
                else{
                    $arrRes['MATCH_GOALS'][$arR['PL_ID']] = $arR['GOALS'];
                }
            }

            if(!empty($arR['CARDS'])){
                $arrRes['MATCH_CARDS'][$arR['PL_ID']][] = $arR['CARDS'];
            }
            
            /*echo '<pre>';
            print_r($arR);
            echo '</pre>';*/

            $i++;
        }

        $arrRes['MATCH']['OPPONENT_GOALS'] = $opponentGoals + $arrRes['MATCH']['AUTO_GOALS'];

        if($arrRes['MATCH']['CITY'] == $ourTeamCity){
            $arrRes['MATCH_SCORE'] = $arrRes['MATCH']['OUR_GOALS'].' : '.$arrRes['MATCH']['OPPONENT_GOALS'];
        }
        else{
            $arrRes['MATCH_SCORE'] = $arrRes['MATCH']['OPPONENT_GOALS'].' : '.$arrRes['MATCH']['OUR_GOALS'];
        }

        if($arrRes['MATCH']['OUR_GOALS'] > $arrRes['MATCH']['OPPONENT_GOALS']){
            $arrRes['MATCH_RESULT_TXT'] = 'Наша команда выигрыла';
            $arrRes['MATCH_RESULT_CODE'] = 'W';
        }
        elseif($arrRes['MATCH']['OUR_GOALS'] < $arrRes['MATCH']['OPPONENT_GOALS']){
            $arrRes['MATCH_RESULT_TXT'] = 'Наша команда проиграла';
            $arrRes['MATCH_RESULT_CODE'] = 'W';
        }
        else{
            $arrRes['MATCH_RESULT_TXT'] = 'Матч закончился ничьей';
            $arrRes['MATCH_RESULT_CODE'] = 'D';
        }
    }
    else{
        ShowError('Скипту не передан обязательный параметр');
        die();
    }

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
    <title><?=$arrRes['MATCH_TITLE']?></title>
</head>
<body>
    <div class="main-wrap">
        <div class="head">
            <h1><?=$arrRes['MATCH_TITLE']?></h1>
        </div>
        <div class="main-cont">
            <div class="cont-detail">
                <div class="summary">
                    <p>Город: <span><?=$arrRes['MATCH']['CITY']?></span></p>
                    <p>Матч прошел: <span><?=$arrRes['MATCH']['GAME_DATE']?></span></p>
                    <p>Счет: <span class="score"><?=$arrRes['MATCH_SCORE']?></span></p>
                    <p>Автоголы: <span><?=$arrRes['MATCH']['AUTO_GOALS']?></span></p>
                    <p class="res-<?=strtolower($arrRes['MATCH_RESULT_CODE'])?>"><?=$arrRes['MATCH_RESULT_TXT']?></p>
                </div>
            
                <div class="players-list">
                    <h3>Игроки команды <?=$arrRes['MATCH']['TM_NAME']?>:</h3>
                    <table class="main-tbl">
                        <tr>
                            <th>Имя и фамилия игрока</th>
                            <th>Имя на футболке</th>
                            <th>Амплуа</th>
                            <th>Минут на поле</th>
                            <th>Голы</th>
                            <th>Взыскания</th>
                        </tr>
                        <tr>
                            <td colspan="6" class="hdr-row">Основной состав</td>
                        </tr>
                        <?foreach($arrRes['MATCH_PLAYERS']['BASE'] as $plId => $arrPlayer):?>
                            <tr>
                                <td><?=$arrPlayer['PLAYER_FN'].' '.$arrPlayer['PLAYER_LN']?></td>
                                <td><?=$arrPlayer['PLAYER_NN']?></td>
                                <td><?=$arTeamComposition[$arrPlayer['PLAYER_ROLE']]?></td>
                                <td><?=$arrPlayer['TIME_IN']?></td>
                                <td>
                                    <?if(isset($arrRes['MATCH_GOALS'][$plId])):?>
                                        <?=$arrRes['MATCH_GOALS'][$plId]?>
                                    <?else:?>0<?endif?>
                                </td>
                                <td>
                                <?if(isset($arrRes['MATCH_CARDS'][$plId])):?>
                                        <?foreach($arrRes['MATCH_CARDS'][$plId] as $card):?>
                                            <?if($card == 'Y'):?>
                                                <span class="card yell"></span>
                                            <?endif?>
                                            <?if($card == 'Y2'):?>
                                                <span class="card yell"></span>
                                                <span class="card yell"></span>
                                            <?endif?>
                                            <?if($card == 'R'):?>
                                                <span class="card red"></span>
                                            <?endif?>
                                        <?endforeach?>
                                    <?else:?>–<?endif?>
                                </td>
                            </tr>
                        <?endforeach?>
                        <tr>
                            <td colspan="6" class="hdr-row">Запасные игроки</td>
                        </tr>
                        <?foreach($arrRes['MATCH_PLAYERS']['RESERVE'] as $plId => $arrPlayer):?>
                            <tr>
                                <td><?=$arrPlayer['PLAYER_FN'].' '.$arrPlayer['PLAYER_LN']?></td>
                                <td><?=$arrPlayer['PLAYER_NN']?></td>
                                <td><?=$arTeamComposition[$arrPlayer['PLAYER_ROLE']]?></td>
                                <td><?if(!empty($arrPlayer['TIME_IN'])):?><?=$arrPlayer['TIME_IN']?><?else:?>–<?endif?></td>
                                <td>
                                    <?if(isset($arrRes['MATCH_GOALS'][$plId])):?>
                                        <?=$arrRes['MATCH_GOALS'][$plId]?>
                                    <?else:?>0<?endif?>
                                </td>
                                <td>
                                <?if(isset($arrRes['MATCH_CARDS'][$plId])):?>
                                        <?foreach($arrRes['MATCH_CARDS'][$plId] as $card):?>
                                            <?if($card == 'Y'):?>
                                                <span class="card yell"></span>
                                            <?endif?>
                                            <?if($card == 'Y2'):?>
                                                <span class="card yell"></span>
                                                <span class="card yell"></span>
                                            <?endif?>
                                            <?if($card == 'R'):?>
                                                <span class="card red"></span>
                                            <?endif?>
                                        <?endforeach?>
                                    <?else:?>–<?endif?>
                                </td>
                            </tr>
                        <?endforeach?>
                    </table>
                </div>
            </div>
        </div>
        <div class="foot">
            <p><a href="/test_orm/basic/t4/query/">← К списку</a></p>
        </div>
    </div>

</body>
</html>
<?
    // подключение служебной части эпилога
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>