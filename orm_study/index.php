<?// подключение служебной части пролога 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/orm_study/css/styles.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Изучаем ORM</title>
</head>
<body>
<div class="main-wrap">
    <div class="head">
        <h1>Изучаем ORM</h1>
    </div>
    <div class="main-cont">
        <div class="main-cont-section">
            <h3>Простые способы использования ORM (basic)</h3>
            <ul class="items-list">
                <li>
                    <a href="/orm_study/basic/t1/getlist/">Список матчей за весь период (getlist) [tb1.1]</a>
                    <div class="item-desc">
                        Список матчей за весь период. Используем метод <code>getList</code> нашего класса <code>LineupsTable</code>, унаследованного от DataManager [<a href="https://bxapi.ru/?module_id=main&class=DataManager">bxapi</a> | <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/datamanager/index.php">dev.1c-bitrix</a>].
                        Считаем кол-во голов для каждого матча с помощью <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/expressionfield/__construct.php">Entity\ExpressionField</a>, а также данные о матчах из связанной модели <code>GameTable</code>. Связь 1:N.
                    </div>
                </li>
                <li>
                    <a href="/orm_study/basic/t1/query/">Список матчей за весь период (query) [tb1.2]</a>
                    <div class="item-desc">
                        Список матчей за весь период. Используем объект класса <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/query/index.php">Entity\Query</a>. При создании передаем в него сущность нашего класса <code>LineupsTable</code>, унаследованного от <code>DataManager</code> [<a href="https://bxapi.ru/?module_id=main&class=DataManager">bxapi</a> | <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/datamanager/index.php">dev.1c-bitrix</a>].
                        Считаем кол-во голов для каждого матча, добавляя <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/expressionfield/__construct.php">Entity\ExpressionField</a> с помощью <a href="https://bxapi.ru/src/?module_id=main&name=Query::registerRuntimeField">registerRuntimeField</a>, а также данные о матчах из связанной модели <code>GameTable</code>. Связь 1:N.
                    </div>
                </li>
                <li>
                    <a href="/orm_study/basic/t2/getlist/">Список матчей за весь период с постраничной навигацией (getlist) [tb2.1]</a>
                    <div class="item-desc">
                        Список матчей за весь период. Используем метод <code>getList</code> нашего класса <code>LineupsTable</code>, унаследованного от <code>DataManager</code> [<a href="https://bxapi.ru/?module_id=main&class=DataManager">bxapi</a> | <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/datamanager/index.php">dev.1c-bitrix</a>].
                        Считаем кол-во голов для каждого матча с помощью <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/expressionfield/__construct.php">Entity\ExpressionField</a>, а также данные о матчах из связанной модели <code>GameTable</code>. Связь 1:N.<br />
                        Постраничная навигация сделана как описано в <a href="https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=2741&LESSON_PATH=3913.5062.5748.2741">документации</a> с использованием компонента <code>bitrix:main.pagenavigation</code>.
                    </div>
                </li>
                <li>
                    <a href="/orm_study/basic/t2/query/">Список матчей за весь период с постраничной навигацией (query) [tb2.2]</a>
                    <div class="item-desc">
                        Список матчей за весь период. Используем объект класса <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/query/index.php">Entity\Query</a>. При создании передаем в него сущность нашего класса <code>LineupsTable</code>, унаследованного от <code>DataManager</code> [<a href="https://bxapi.ru/?module_id=main&class=DataManager">bxapi</a> | <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/datamanager/index.php">dev.1c-bitrix</a>].
                        Считаем кол-во голов для каждого матча, добавляя <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/expressionfield/__construct.php">Entity\ExpressionField</a> с помощью <a href="https://bxapi.ru/src/?module_id=main&name=Query::registerRuntimeField">registerRuntimeField</a>, а также данные о матчах из связанной модели <code>GameTable</code>. Связь 1:N.<br />
                        Постраничная навигация сделана как описано в <a href="https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=2741&LESSON_PATH=3913.5062.5748.2741">документации</a> с использованием компонента <code>bitrix:main.pagenavigation</code>.
                    </div>
                </li>
                <li id="getlist_list">
                    <a href="/orm_study/basic/t3/getlist/">Список матчей за весь период с постраничной навигацией, фильтром и сортировкой (getlist) [tb3.1]</a>
                    <div class="item-desc">
                        Список матчей за весь период. Используем метод <code>getList</code> нашего класса <code>LineupsTable</code>, унаследованного от <code>DataManager</code> [<a href="https://bxapi.ru/?module_id=main&class=DataManager">bxapi</a> | <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/datamanager/index.php">dev.1c-bitrix</a>].
                        Считаем кол-во голов для каждого матча с помощью <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/expressionfield/__construct.php">Entity\ExpressionField</a>, а также данные о матчах из связанной модели <code>GameTable</code>. Связь 1:N.<br />
                        Постраничная навигация сделана как описано в <a href="https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=2741&LESSON_PATH=3913.5062.5748.2741">документации</a> с использованием компонента <code>bitrix:main.pagenavigation</code>.<br />
                        Фильтр и сортировка учитывается в ссылках постраничной навигации. Это реализовано с помощью регулярных выражений. Значения полей для сортировки хранятся в cookie.
                    </div>
                </li>
                <li id="query_list">
                    <a href="/orm_study/basic/t3/query/">Список матчей за весь период с постраничной навигацией, фильтром и сортировкой (query)  [tb3.2]</a>
                    <div class="item-desc">
                        Список матчей за весь период. Используем объект класса <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/query/index.php">Entity\Query</a>. При создании передаем в него сущность нашего класса <code>LineupsTable</code>, унаследованного от <code>DataManager</code> [<a href="https://bxapi.ru/?module_id=main&class=DataManager">bxapi</a> | <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/datamanager/index.php">dev.1c-bitrix</a>].
                        Считаем кол-во голов для каждого матча, добавляя <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/expressionfield/__construct.php">Entity\ExpressionField</a> с помощью <a href="https://bxapi.ru/src/?module_id=main&name=Query::registerRuntimeField">registerRuntimeField</a>, а также данные о матчах из связанной модели <code>GameTable</code>. Связь 1:N.<br />
                        Постраничная навигация сделана как описано в <a href="https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=2741&LESSON_PATH=3913.5062.5748.2741">документации</a> с использованием компонента <code>bitrix:main.pagenavigation</code>.<br />
                        Фильтр и сортировка учитывается в ссылках постраничной навигации. Это реализовано с помощью регулярных выражений. Значения полей для сортировки хранятся в cookie.
                    </div>
                </li>
                <li>
                    <a class="faded" style="display: inline;" href="/orm_study/basic/t4/getlist/">Список матчей за весь период с постраничной навигацией, фильтром и сортировкой (getlist) [t4.1]</a> <a style="display: inline;" href="#getlist_list">↑↑↑ (по сути копия примера 3.1)</a>
                    <a href="/orm_study/basic/t4/getlist/detail.php?gm_id=1">Детальная страница матча (getlist). Пример - матч с id=1  [tb4.1.1]</a>
                    <div class="item-desc">
                        Страница с детальной информацией о матче и списком игроков команды-соперника, участвовавших в нем. Идентификатор матча передается скрипту в get-параметре <code>gm_id</code>.
                    </div>
                </li>
                <li>
                    <a class="faded" style="display: inline;" href="/orm_study/basic/t4/query/">Список матчей за весь период с постраничной навигацией, фильтром и сортировкой (query) [t4.2]</a> <a style="display: inline;" href="#query_list">↑↑↑ (по сути копия примера 3.2)</a>
                    <a href="/orm_study/basic/t4/query/detail.php?gm_id=1">Детальная страница матча (query). Пример - матч с id=1 [tb4.2.1]</a>
                    <div class="item-desc">
                        Страница с детальной информацией о матче и списком игроков команды-соперника, участвовавших в нем. Идентификатор матча передается скрипту в get-параметре <code>gm_id</code>.
                    </div>
                </li>
                <li>
                    <a href="/orm_study/basic/t5/getlist/">Список игроков (getlist) [tb5.1]</a>
                    <a href="/orm_study/basic/t5/getlist/detail.php?pl_id=11">Детальная страница игрока (getlist). Пример - игрок с id=11  [tb5.1.1]</a>
                    <div class="item-desc">
                        Список игроков. Используем метод (getList) нашего класса <code>PlayersTable</code>, унаследованного от <code>DataManager</code> [<a href="https://bxapi.ru/?module_id=main&class=DataManager">bxapi</a> | <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/datamanager/index.php">dev.1c-bitrix</a>].
                        <br /><br /><hr /><br />
                        Страница с детальной информацией об игроке со списком матчей, в которых он участвовал. Идентификатор игрока передается скрипту в get-параметре <code>pl_id</code>.
                    </div>
                </li>
                <li>
                    <a href="/orm_study/basic/t5/query/">Список игроков (query) [tb5.2]</a>
                    <a href="/orm_study/basic/t5/query/detail.php?pl_id=11">Детальная страница игрока (query). Пример - игрок с id=11 [tb5.2.1]</a>
                    <div class="item-desc">
                        Список игроков. Используем метод (query) нашего класса <code>PlayersTable</code>, унаследованного от <code>DataManager</code> [<a href="https://bxapi.ru/?module_id=main&class=DataManager">bxapi</a> | <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/datamanager/index.php">dev.1c-bitrix</a>].
                        <br /><br /><hr /><br />
                        Страница с детальной информацией об игроке со списком матчей, в которых он участвовал. Идентификатор игрока передается скрипту в get-параметре <code>pl_id</code>.
                    </div>
                </li>
            </ul>
        </div>            
    </div>
</div>
</body>
</html>
<?// подключение служебной части эпилога 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>