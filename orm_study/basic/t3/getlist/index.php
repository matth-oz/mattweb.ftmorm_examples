<?php
    use \Bitrix\Main\UI;
    use \Bitrix\Main\Entity;
    use Bitrix\Main\Loader;
    use Bitrix\Main\Context;
    use Bitrix\Main\Web\Cookie;

    use Mattweb\Ftmorm;

    define('EL_PAGE_COUNT', 10);
    define('SORT_COOKIE_LT', time() + 86400*30);
    
    // подключение служебной части пролога
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

    function clearSortParamUrl($curPage){
        $resUrl = '';
        $queryString = $_SERVER['QUERY_STRING'];        
        $pattern = '/&?sort=clear&?(pager=page\-[0-9]{1,})?/';

        preg_match($pattern, $queryString, $matches, PREG_OFFSET_CAPTURE);

        if(!empty($matches[0][0])){
            $replaceStr = '';
            $replacePattern = '/'.$matches[0][0].'/';

            $queryString = preg_replace($replacePattern, $replaceStr, $queryString);

            if(strlen($queryString) > 0){
                $resUrl .= $curPage.'?'.$queryString;
            }
            else{
                $resUrl .= $curPage;
            }

        }

        return $resUrl;
    }

    /*
    array('game_date' => 'asc');
    array('game_date' => 'desc');
    array('gm_city' => 'asc');
    array('gm_city' => 'desc');
    array('tm_name' => 'asc');
    array('tm_name' => 'desc');
    */
    function buildSortParamUrl($curPage, $sort_params = array()){
        $resUrl = '';
        $queryString = $_SERVER['QUERY_STRING'];        
        $pattern = '/sort=(game_date|gm_city|tm_name)&ord=(asc|desc)/';
        
        if(!empty($sort_params)){
            $sort = key($sort_params);
            $ord = $sort_params[$sort];

            if(strlen($queryString) == 0){
                $resUrl .= $curPage.'?sort='.$sort.'&ord='.$ord;
            }
            else{                
                preg_match($pattern, $queryString, $matches, PREG_OFFSET_CAPTURE);
            
                if(!empty($matches[0][0])){
                    $replaceStr = 'sort='.$sort.'&ord='.$ord;
                    $replacePattern = '/'.$matches[0][0].'/';
                
                    if($matches[0][0] != $replaceStr){
                        $queryString = preg_replace($replacePattern, $replaceStr, $queryString);
                    }
                    $resUrl .= $curPage.'?'.$queryString;
                }
                else{
                    $resUrl .= $curPage.'?'.$queryString.'&sort='.$sort.'&ord='.$ord;
                }
            }
        }
        else{
            if(strlen($queryString) == 0){
                $resUrl .= $curPage.'?sort=clear';
            }
            else{
                preg_match($pattern, $queryString, $matches, PREG_OFFSET_CAPTURE);
                if(!empty($matches[0][0])){
                    $replaceStr = 'sort=clear';
                    $replacePattern = '/'.$matches[0][0].'/';
                    
                    $queryString = preg_replace($replacePattern, $replaceStr, $queryString);                   
                    $resUrl .= $curPage.'?'.$queryString;
                }
            }           
        }

        return $resUrl;
    }

    global $APPLICATION;
    $curPage = $APPLICATION->getCurPage();
    Loader::Includemodule('mattweb.ftmorm');

    $server = Context::getCurrent()->getServer();
    $request = Context::getCurrent()->getRequest();
    $reqValues = $request->getQueryList();

    /*echo '<br /><br />';
    echo $APPLICATION->GetCurPage();
    echo '<br />';
    echo $server->getRequestUri();
    echo '<br />';
    echo $server->getPhpSelf();
    echo '<br />';

    echo '<pre>';
    print_r($reqValues);
    var_dump(isset($reqValues['sort']));
    var_dump(!empty($reqValues['sort']));
    echo '</pre>';*/

    // расчитываем правильные номера в таблице на всех страницах кроме первой
    $pager = $reqValues['pager'];
    $pageNum = 0;
    if(!empty($pager) && $pager != 'page-all'){
        $arPager = explode('-', $pager);
        $pageNum = intval($arPager[1]) - 1;
    }

    // сортировка
    $sortDataCookie = $request->getCookie('sortData');
    $sortOrdCookie = $request->getCookie('sortOrd');
    
    $response = Context::getCurrent()->getResponse();

    // удаляем сортировку из COOKIE
    if($reqValues['sort'] == 'clear' 
        && !empty($sortDataCookie) && !empty($sortOrdCookie)){
        $response->addCookie(
            new Cookie('sortData', $sortDataCookie, time()-3600)
        );

        $response->addCookie(
            new Cookie('sortOrd', $sortOrdCookie, time()-3600)
        );

        $locHref = clearSortParamUrl($curPage);

        $response->addHeader('Location', $locHref);
        $response->flush();
    }

    // сохраняем сортировку в COOKIE
    // обязательно перезагружаем страницу
    if((!empty($reqValues['sort'] && !empty($reqValues['ord']))
     && ($sortDataCookie != $reqValues['sort'] || $sortOrdCookie != $reqValues['ord']))){

        $response->addCookie(
            new Cookie('sortData', $reqValues['sort'], SORT_COOKIE_LT)
        );

        $response->addCookie(
            new Cookie('sortOrd', $reqValues['ord'], SORT_COOKIE_LT)
        );

        $response->addHeader('Location', $server->getRequestUri());
        $response->flush();
    }

    /*echo '$sortDataCookie: ';
    var_dump($sortDataCookie);
    echo '<br />';
    echo '$sortOrdCookie: ';
    var_dump($sortOrdCookie);
    echo '<br />';*/
    
    if(!is_null($sortDataCookie) && !is_null($sortOrdCookie)){
        switch($sortDataCookie){
            case 'game_date':
                $filterKey = 'GM_GAME_DATE';
                break;
            case 'gm_city':
                $filterKey = 'GM_CITY';
                break;
            case 'tm_name':
                $filterKey = 'GM_OPPONENT_NAME';
                break;
        }

        $arOrder[$filterKey] = $sortOrdCookie;
    }
    else{
        $arOrder = ['GAME_ID' => 'desc'];
    }

    /*echo '<pre>';
    print_r($arOrder);
    echo '</pre>';*/

   
?>
<?
$arrRes = $arFilter = $arFilterData = [];

$ourTeamName = Ftmorm\GamesTable::OUR_TEAM_NAME;
$ourTeamCity = Ftmorm\GamesTable::OUR_TEAM_CITY;

// формируем фильтр для выборки на основе параметров get-запрооса
if(isset($reqValues['filter'])){
    if($reqValues['match_city'] != 'all'){
        $arFilter['GM_CITY'] = ($reqValues['match_city'] == $ourTeamCity) ? '' : $reqValues['match_city'];
    }

    if(!empty($reqValues['game_dstart'])){
        $stts = strtotime($reqValues['game_dstart']);
        $dtstts = date('d.m.Y H:i:s', $stts);
        
        $arFilter['>=GM_GAME_DATE'] = new \Bitrix\Main\Type\DateTime($dtstts);
    }

    if(!empty($reqValues['game_dfinish'])){
        $fnts = strtotime($reqValues['game_dfinish']);
        $dtfnts = date('d.m.Y H:i:s', $fnts);

        $arFilter['<=GM_GAME_DATE'] = new \Bitrix\Main\Type\DateTime($dtfnts);
    }

    $ts = strtotime($reqValues['game_dfinish']);
    $dtfts = date('d.m.Y H:i:s', $ts);

    $dtftsObj = new \Bitrix\Main\Type\DateTime($dtfts);
}

    /*echo '<pre>';
    print_r($arFilter);
    echo '</pre>';*/


// формируем данные для заполнения полей фильтра в форме
// вычисляем правильные даты первого и последнего матча для заголовка при пагинации
$arFilterData['GM_CITY']['team_city'] = $ourTeamCity;

$gamesRes = Ftmorm\GamesTable::getList([
    'select' => ['CITY', 'GAME_DATE'],
]);

$arGameDates = [];

$k=0;
while($arGmData = $gamesRes->fetch()){
    if(!empty($arGmData['CITY']) && !in_array($arGmData['CITY'], $arFilterData['GM_CITY'])){
        $nk = 'city_'.$k;
        $arFilterData['GM_CITY'][$nk] = $arGmData['CITY'];
    }

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

/*echo '<pre>';
print_r($arFilterData);
echo '</pre>';*/

// создаем объект пагинации
$nav = new UI\PageNavigation("pager");
$nav->allowAllRecords(true)
		->setPageSize(EL_PAGE_COUNT)
		->initFromUri();

$offset = $nav->getOffset();

// подключаем класс для вывода SQL запроса
// \Bitrix\Main\Application::getConnection()->startTracker();

/*echo $nav->getOffset();
echo $nav->getLimit();*/

$LineUpsRes = Ftmorm\LineupsTable::getList([
    'select' => ['GAME_ID', 'GOAL_SUMM', 'GM_'=>'GAME', 'GM_OPPONENT_NAME'=>'GAME.TEAM.NAME'],
    'order' => $arOrder,
    'group' => ['GAME_ID'],
    'filter' => $arFilter,
    'runtime' => [new Entity\ExpressionField('GOAL_SUMM', 'SUM(%s)', ['GOALS'])],
    'offset' => $nav->getOffset(),
    'limit' => $nav->getLimit(),
    'count_total' => true,
]);

$nav->setRecordCount($LineUpsRes->getCount());

// выводим на экран SQL запрос
// echo '<pre>', $LineUpsRes->getTrackerQuery()->getSql(), '</pre>';

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
            <h1>
            Матчи команды <?=$ourTeamName?> c <?=$arrRes['DATE_MATCH_EARLIEST']?> по <?=$arrRes['DATE_MATCH_LATEST']?>
            </h1>
        </div>
        <div class="main-cont">

            <?if(!empty($arFilterData)):?>
            <div class="filter-wrap">
                <form class="filter-form" method="get" action="<?=$server->getRequestUri()?>">
                    <div class="filter-field-wrap">
                        <label for="match_city">City: </label>
                        <select name="match_city" id="match_city">
                            <option value="all">Все города</option>
                            <?foreach($arFilterData['GM_CITY'] as $city):?>
                                <option value="<?=$city?>" <?if($reqValues['match_city'] !== 'all' && $city == $reqValues['match_city']):?>selected="selected"<?endif?>><?=$city?></option>
                            <?endforeach?>
                        </select>
                    </div>
                    <div class="filter-field-wrap">
                        <label for="game_dstart">Start date: </label>
                        <input 
                        type="date" 
                        id="game_dstart" 
                        name="game_dstart" 
                        value="<?if(isset($reqValues['game_dstart'])):?><?=$reqValues['game_dstart']?><?endif?>" 
                        min="" 
                        max="" />
                    </div>
                    <div class="filter-field-wrap">
                        <label for="game_dfinish">Finish date: </label>
                        <input 
                        type="date" 
                        id="game_dfinish" 
                        name="game_dfinish" 
                        value="<?if(isset($reqValues['game_dfinish'])):?><?=$reqValues['game_dfinish']?><?endif?>" 
                        min="" 
                        max="" />
                    </div>
                    <div class="filter-field-wrap">
                        <input type="submit" name="filter" value="Filter"/>
                    </div>
                </form>
            </div>
            <?endif?>
            <table class="main-tbl">
                <tr>
                    <th>Номер</th>
                    <th>Дата проведения
                        <?
                        $addClass = '';
                        if($sortDataCookie == 'game_date' && $sortOrdCookie == 'desc'){
                            $addClass = ' active';
                            $sortParams = [];
                        }
                        else{
                            $sortParams = ['game_date'=>'desc'];                            
                        }?>                        
                        <a href="<?=buildSortParamUrl($curPage, $sortParams)?>" class="sort-btn<?=$addClass?>">▼</a>
                        <?
                        $addClass = '';
                        if($sortDataCookie == 'game_date' && $sortOrdCookie == 'asc'){
                            $addClass = ' active';
                            $sortParams = [];
                        }
                        else{
                            $sortParams = ['game_date'=>'asc'];
                        }?>                        
                        <a href="<?=buildSortParamUrl($curPage, $sortParams)?>" class="sort-btn<?=$addClass?>">▲</a>
                    </th>
                    <th>Город
                        <?
                        $addClass = '';
                        if($sortDataCookie == 'gm_city' && $sortOrdCookie == 'desc'){
                            $addClass = ' active';
                            $sortParams = [];
                        }
                        else{
                            $sortParams = ['gm_city'=>'desc'];
                        }?>                        
                        <a href="<?=buildSortParamUrl($curPage, $sortParams)?>" class="sort-btn<?=$addClass?>">▼</a>
                        <?
                        $addClass = '';
                        if($sortDataCookie == 'gm_city' && $sortOrdCookie == 'asc'){
                            $addClass = ' active';
                            $sortParams = [];
                        }
                        else{
                            $sortParams = ['gm_city'=>'asc'];
                        }?>                        
                        <a href="<?=buildSortParamUrl($curPage, $sortParams)?>" class="sort-btn<?=$addClass?>">▲</a>
                    </th>
                    <th>Команда-соперник
                        <?
                        $addClass = '';
                        if($sortDataCookie == 'tm_name' && $sortOrdCookie == 'desc'){
                            $addClass = ' active';
                            $sortParams = [];
                        }
                        else{
                            $sortParams = ['tm_name'=>'desc'];
                        }?>                        
                        <a href="<?=buildSortParamUrl($curPage, $sortParams)?>" class="sort-btn<?=$addClass?>">▼</a>
                        <?
                        $addClass = '';
                        if($sortDataCookie == 'tm_name' && $sortOrdCookie == 'asc'){
                            $addClass = ' active';
                            $sortParams = [];
                        }
                        else{
                            $sortParams = ['tm_name'=>'asc'];
                        }?>                        
                        <a href="<?=buildSortParamUrl($curPage, $sortParams)?>" class="sort-btn<?=$addClass?>">▲</a>
                    </th>
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
                    <td>
                    <a href="/test_orm/basic/t4/getlist/detail.php?gm_id=<?=$arMatch['ID']?>"><?=$arMatch['ORD_NUMBER']?></a>
                    </td>
                    <td>
                    <a href="/test_orm/basic/t4/getlist/detail.php?gm_id=<?=$arMatch['ID']?>"><?=$arMatch['GAME_DATE']?></a>
                    </td>
                    <td>
                    <a href="/test_orm/basic/t4/getlist/detail.php?gm_id=<?=$arMatch['ID']?>"><?=$arMatch['CITY']?></a>
                    </td>
                    <td>
                    <a href="/test_orm/basic/t4/getlist/detail.php?gm_id=<?=$arMatch['ID']?>"><?=$arMatch['TM_NAME']?></a>
                    </td>
                    <td>
                    <a href="/test_orm/basic/t4/getlist/detail.php?gm_id=<?=$arMatch['ID']?>"><?=$arMatch['GAME_SCORE']?></a>
                    </td>
                    <td <?if($arMatch['AUTO_GOALS'] > 0):?>class="ag-alert"<?endif?>>
                    <a href="/test_orm/basic/t4/getlist/detail.php?gm_id=<?=$arMatch['ID']?>"><?=$arMatch['AUTO_GOALS']?></a>
                    </td>
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
<?    // подключение служебной части эпилога
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>