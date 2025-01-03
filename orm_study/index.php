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
            <div class="main-cont-section">
                <h3>Продвинутые способы использования ORM (extended)</h3>
                <ul class="items-list">
                    <li>
                        <a href="/orm_study/extended/t3_simple_component/">Список матчей за весь период с постраничной навигацией, фильтром и сортировкой (компонент ftmorm:matches.list) [te1.1]</a> 
                        <div class="item-desc">
                            На основе скрипта из пункта tb3.2 был создан простой компонент для вывода списка матчей с постраничной навигацией, фильтром и сортировкой. 
                        </div>
                    </li>
                    <li>
                        <a href="/orm_study/extended/t3_simple_component/detail.php?gm_id=38">Детальная страница матча (компонент ftmorm:match.detail). Пример - матч с id=1 [te2.1]</a> 
                        <div class="item-desc">
                            На основе скрипта из пункта tb4.1.1 был создан компонент для вывода детальной информации о конкретном матче.
                        </div>
                    </li>
                    <li>
                        <a href="/orm_study/extended/t3_1_js/getlist/">Список матчей за весь период с постраничной навигацией, фильтром и сортировкой + AJAX (getlist) [te3.1]</a> 
                        <div class="item-desc">
                            На основе примера t3.1 были созданы скрипты для получения данных о матчах, их фильтрации и сортировки с помощью AJAX. При реализации использовался JS Fetch(). Постраничная навигация также работает.<br />
                            <u>Условия следующие:</u><br />
                            <b>Фильтр</b><br />
                            • При активизации фильтра, если постраничная навигация активна, то она сбрасывается<br />
                            <b>Сортировка</b><br />
                            • При активизации сортировки, если постраничная навигация активна, она остается<br />
                            <b>Пагинация</b><br />
                            • Если при фильтрации количество элементов больше, чем на 1 страницу, постраничная навигация работает стандартным способом<br />
                            • Если при сортировке количество элементов больше, чем на 1 страницу, постраничная навигация работает стандартным способом<br />
                            Так как постраничная навигация работает на стандартном компоненте Битрикса, для корректной работы пришлось кастомизировать его шаблон и добавить JS-код.<br />
                            Весь JS код этого примера находится в 3 файлах: init.js (папка js шаблона сайта), script.js (папка шаблона компонента постраничной навигации) и script.js (папка где размещается сам скрипт).
                            Все свойства и функции, необхоимые для работы находятся в объекте <code>window.MatchList</code>.                            
                        </div>
                    </li>
                    <li>
                        <a href="/orm_study/extended/t3_1_js/query/">Список матчей за весь период с постраничной навигацией, фильтром и сортировкой + AJAX (query) [te4.1]</a> 
                        <div class="item-desc">
                            В этом примере сделано все аналогично примеру te3.1 за исключением серверного скрипта, обрабатывающего AJAX-запросы. В нем используется объект класса <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/query/index.php">Entity\Query</a>. 
                            При создании передаем в него сущность нашего класса <code>LineupsTable</code>, унаследованного от <code>DataManager</code> [<a href="https://bxapi.ru/?module_id=main&class=DataManager">bxapi</a> | <a href="https://dev.1c-bitrix.ru/api_d7/bitrix/main/entity/datamanager/index.php">dev.1c-bitrix</a>].
                        </div>
                    </li>
                    <li>
                        <a href="/orm_study/extended/t5_simple_component/">Список игроков (компонент ftmorm:players.list) [te6.1]<sup>*</sup></a>
                        <div class="item-desc">                            
                            На основе примера tb5.2 был создан компонент для показа списка игроков с постраничной навигацией.<br />
                            <u>Используются следующие параметры компонента:</u><br />
                            • Шаблон пути к детальной странице <br />
                            • Количество элементов на странице <br />
                            • Имя переменной, содержащей массив для фильтрации - указатель на массив, который формирует компонент <code>ftmorm:ftmorm.filter</code>, применяемый для фильтрации элементов списка. Значение по умолчанию - <code>arrElementsFilter</code><br />
                        
                            Постраничная навигация сделана как описано в документации с использованием компонента <code>bitrix:main.pagenavigation</code>. Название шаблона постраничной навигации передается в виде строки в одном из параметров компонента.<br />
                            <span style="color: #f00; font-size: .8em;"><sup>*</sup> - для корректной работы для страницы должен быть выбран шаблон сайта <b>clear</b></span>
                        </div>
                    </li>
                    
                    <li>
                        «Универсальный» компонент для вывода списков ftmorm:elements.list
                        <div class="item-desc">
                            Компонент <code>ftmorm:elements.list</code> задумывался как <b>«универсальный»</b> в рамках модуля. 
                            После выбора сущности из списка и указания ее полей для вывода в параметрах компонента, полученные данные должны выводится на страницу.<br />
                            Например, как <a href="/orm_study/extended/t5_complex_component/">здесь</a><br />
                            Но в процессе разработки выяснилось, что добиться желаемых результатов получается не всегда. 
                            Например, если для вывода данных потребуется «связка» 2 или более сущностей и/или вычисление значений полей, то возникают проблемы, которые состоят в том, что невозможно передать через параметры компонента <u>логику его работы</u>.
                            • <a href="/orm_study/extended/t5_simple_component/games.php">Список матчей команды [games] (компонент ftmorm:elements.list) [te6.1.3]<sup>*</sup></a><br />
                            • <a href="/orm_study/extended/t5_simple_component/lineups.php">Список матчей команды [lineups] (компонент ftmorm:elements.list) [te6.1.4]<sup>*</sup></a>
                            <br />
                            Если для вывода списка достаточно одной таблицы ([players], [teams]), то данных из нее выводятся корректно.<br />
                            • <a href="/orm_study/extended/t5_simple_component/players.php">Список игроков [players] (компонент ftmorm:elements.list) [te6.1.2]</a><br />
                            • <a href="/orm_study/extended/t5_simple_component/teams.php">Список команд-соперников [teams] (компонент ftmorm:elements.list) [te6.1.5]</a>   
                            <br /><br />
                            <sup>*</sup> - помечены ссылки на страницы, где данные из сущностей выводятся «универсальным» компонентом некорректно, потому что корректного отображения требуется определенная логика (объединение сущностей и вычисляемые поля), передать которую в компонент не представляется возможным. 
                        </div>
                    </li>
                    

                    <li>
                        <a href="/orm_study/extended/t5_simple_component/">Фильтр по элементам на примере списка игроков (компонент ftmorm:ftmorm.filter) [te6.2]</a>
                        <div class="item-desc">
                            Для фильтрации элементов сущностей был создан компонент <b>Фильтр по элементам</b>.<br />
                            Старался сделать его более универсальным, для этого в параметрах формируется список всех классов модуля. 
                            После выбора класса подгружаются его поля. При выборе полей нужно заполнить CUSTOM-параметр, определяющий внешний вид HTML-поля в шаблоне формы фильтра. <br />
                            Фильтр размещается в коде страницы выше компонента в котором должна срабатывать фильтрация (например, Список игроков) и формирует массив, который затем используется расположенным ниже компонентом для создания фильтра в запросе.  
                        </div>
                    </li>
                    <li>
                        <a href="/orm_study/extended/t5_simple_component/detail.php?pl_id=11">Детальная страница игрока (компонент ftmorm:player.detail). Ссылка на пример - игрок с <code>id=11</code> [tb5.2.1]<sup>*</sup></a>
                        <div class="item-desc">                           
                            На основе примера tb5.2.1 был создан компонент <b>Детальная страница игрока</b>. <br />
                            <u>Компонент выводит:</u><br />
                            • Детальную информацию об игроке<br />
                            • Список матчей в которых он участвовал<br />
                            • Список поощрений и взысканий<br />
                            Для возврата к списку предусмотрена ссылка.<br />
                            <u>В качестве параметров передаются:</u><br />
                            • url страницы со списком элементов<br />
                            • имя get-параметра, в котором передается идентификатор записи<br />
                            <span style="color: #f00; font-size: .8em;"><sup>*</sup> - для корректной работы для страницы должен быть выбран шаблон сайта <b>clear</b></span>
                        </div>
                    </li>
                    <li>
                        <a href="/orm_study/extended/t5_complex_component/">Матчи (комплексный компонент ftmorm:matches) [te8.1]</a>
                        <div class="item-desc">
                            Из простых компонентов, созданных ранее, собрал комплексный компонент <b>Матчи</b> (ftmorm:matches).<br /> 
                            <u>Он включает в себя:</u><br />
                            • Компонент «Фильтр по элементам (ftmorm:ftmorm.filter)»<br />
                            <span style="color: #f00; font-size: .8em;">Есть ограничения: из-за того, что данные в таблице, которую выводит компонент «Список матчей…» получены из разных моделей, фильтровать получится не по всем колонкам, которые есть в таблице.</span><br />  
                            • Компонент «Список матчей за весь период» (ftmorm:matches.list)<br />
                            • Компонент «Детально о матче» (ftmorm:match.detail)<br />
                        </div>
                    </li>
                </ul>
            </div>
            <div class="main-cont-section">
                <h3>Добавление и редактирование в административной части</h3>
                <ul class="items-list">
                    <li>Список команд (/admin/ftmorm_teams_list.php)</li>
                    <li>Добавление и редактирование команды (/admin/ftmorm_team_edit.php)</li>
                    <li>Список игроков (/admin/ftmorm_players_list.php)</li>
                    <li>Добавление и редактирование игрока (/admin/ftmorm_player_edit.php)</li>
                    <li>Список матчей (/admin/ftmorm_matches_list.php)</li>
                    <li>Добавление нового матча, добавление и редактирование игроков, участвующих в матче (/admin/ftmorm_match_edit.php)</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
<?// подключение служебной части эпилога 
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>