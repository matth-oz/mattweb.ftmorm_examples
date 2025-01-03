<?php 
    define('STOP_STATISTICS', true);
    define('PUBLIC_AJAX_MODE', true);
    define('NOT_CHECK_PERMISSIONS', true);
    define('EL_PAGE_COUNT', 10);
    
    use \Bitrix\Main\UI;
    use \Bitrix\Main\Entity;
    use \Bitrix\Main\Loader;
    use \Bitrix\Main\Context;
    use \Bitrix\Main\Web\Cookie;
    use \Bitrix\Main\Web\Json;

    use Mattweb\Ftmorm;
    
    // подключение служебной части пролога
    require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

    if(file_exists(__DIR__."/functions.php")){
        require(__DIR__."/functions.php");
    }  

    Loader::IncludeModule('mattweb.ftmorm');

    $server = Context::getCurrent()->getServer();
    $request = Context::getCurrent()->getRequest();

    $reqValues = $request->getQueryList();
    $ar = [$request->isAjaxRequest()];
    /*dumpJSON($ar, false);
    dumpJSON($reqValues, false);
    dumpJSON($_COOKIE, false);*/

    // расчитываем правильные номера в таблице на всех страницах кроме первой
    $pager = $reqValues['pager'];
    $pageNum = 0;
    if(!empty($pager) && $pager != 'page-all'){
        $arPager = explode('-', $pager);
        $pageNum = intval($arPager[1]) - 1;
    }

    $arrRes = $arFilter = $arResult = [];

    $ourTeamName = Ftmorm\GamesTable::OUR_TEAM_NAME;
    $ourTeamCity = Ftmorm\GamesTable::OUR_TEAM_CITY;

    
    // сортировка
    $sortDataCookie = $request->getCookie('sortData');
    $sortOrdCookie = $request->getCookie('sortOrd');
    
    //dumpJSON([$sortDataCookie, $sortOrdCookie]);
    
    /*if($request->isAjaxRequest()){
        dumpJSON(['cookie', $sortDataCookie, $sortOrdCookie]);
    }*/
    

   
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
    
    //dumpJSON($reqValues);
    if(isset($reqValues['filter'])){
        if($reqValues['match_city'] != 'all'){
            $arFilter['GM_CITY'] = $reqValues['match_city'];
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

    // создаем объект пагинации
    $nav = new UI\PageNavigation("pager");
    $nav->allowAllRecords(true)
            ->setPageSize(EL_PAGE_COUNT)
            ->initFromUri();

    $offset = $nav->getOffset();

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
      
    ob_start();
    $APPLICATION->IncludeComponent(
        "bitrix:main.pagenavigation",
        ".default",
        Array(
        "NAV_OBJECT" => $nav,
            "SEF_MODE" => "N"
        ),
        false
    );
    
    $arResult['NAV_STRING'] = ob_get_clean();

    $resHTML = '';
    foreach($arrRes['MATCHES'] as $arMatch){
        if($arMatch['GAME_RESULT'] == 'D'){
            $rowCSSClass = 'd-row';
        }
        elseif($arMatch['GAME_RESULT'] == 'W'){
            $rowCSSClass = 'w-row';
        }else{
            $rowCSSClass = 'l-row';
        }

        $resHTML .= '<tr class="'.$rowCSSClass.'">';
        $resHTML .= '<td>';
        $resHTML .= '<a href="/test_orm/extended/t4/getlist/detail.php?gm_id='.$arMatch['ID'].'">'.$arMatch['ORD_NUMBER'].'</a>';
        $resHTML .= '</td><td>';
        $resHTML .= '<a href="/test_orm/extended/t4/getlist/detail.php?gm_id='.$arMatch['ID'].'">'.$arMatch['GAME_DATE'].'</a>';
        $resHTML .= '</td><td>';
        $resHTML .= '<a href="/test_orm/extended/t4/getlist/detail.php?gm_id='.$arMatch['ID'].'">'.$arMatch['CITY'].'</a>';
        $resHTML .= '</td><td>';
        $resHTML .= '<a href="/test_orm/extended/t4/getlist/detail.php?gm_id='.$arMatch['ID'].'">'.$arMatch['TM_NAME'].'</a>';
        $resHTML .= '</td><td>';
        $resHTML .= '<a href="/test_orm/extended/t4/getlist/detail.php?gm_id='.$arMatch['ID'].'">'.$arMatch['GAME_SCORE'].'</a>';
        $resHTML .= '</td>';
        $resHTML .= '<td ';
        if($arMatch['AUTO_GOALS'] > 0) $resHTML .= 'class="ag-alert">';
        $resHTML .= '<a href="/test_orm/extended/t4/getlist/detail.php?gm_id='.$arMatch['ID'].'">'.$arMatch['AUTO_GOALS'].'</a>';
        $resHTML .= '</td></tr>';
    }

    $arResult['MATCHES_HTML'] = $resHTML;

    header('Content-Type: application/json; charset='.LANG_CHARSET);
    echo Json::encode($arResult);
    die();
?>