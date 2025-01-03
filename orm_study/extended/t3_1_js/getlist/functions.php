<?

/**
 * Очищает параметры сортировки
 * 
 * @param string $curPage
 * @return string $resUrl 
 * 
 */
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

/**
 * Создает строку с параметрами для сортировки
 * 
 * @param string $curPage
 * @param array $sort_params
 * 
 * @return string $resUrl
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