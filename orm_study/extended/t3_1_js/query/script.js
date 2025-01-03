window.addEventListener('load', function(){

    console.log('test test test!');

    const filterForm = document.querySelector(window.MatchList.params.formSelector);
    const matchCity = document.getElementById(window.MatchList.params.citySelSelector);
    const gameDStart = document.getElementById(window.MatchList.params.gameDstartSelector);
    const gameDFinish = document.getElementById(window.MatchList.params.gameDfinishSelector);


    const sortBtns = document.querySelectorAll(window.MatchList.params.sortBtnSelector);

    const trWrap = document.querySelector(window.MatchList.params.dataTbodySelector);
    const navWrap = document.querySelector(window.MatchList.params.navWrapSelector); 
    
    /*Filter by city select handler*/
    matchCity.addEventListener('change', (e)=>{
        console.log('matchCity changed');
        let resObj = window.MatchList.applyFilter(filterForm);

        resObj.then(
            response => {                    
                trWrap.innerHTML = '';
                trWrap.innerHTML = response.MATCHES_HTML;
                navWrap.innerHTML = response.NAV_STRING;
            },
            error =>{
                console.log(error);
            }
        );

        e.preventDefault();
    });
    
    /*Filter by date. Start date in period*/
    gameDStart.addEventListener('blur', (e)=>{
        console.log('gameDStart blured');
        let resObj = window.MatchList.applyFilter(filterForm);
        resObj.then(
            response => {                    
                trWrap.innerHTML = '';
                trWrap.innerHTML = response.MATCHES_HTML;
                navWrap.innerHTML = response.NAV_STRING;
            },
            error =>{
                console.log(error);
            }
        );
        e.preventDefault();
    });
    
    /*Filter by date. Finish date in period*/
    gameDFinish.addEventListener('blur', (e)=>{
        console.log('gameDFinish blured');
        let resObj = window.MatchList.applyFilter(filterForm);
        resObj.then(
            response => {                    
                trWrap.innerHTML = '';
                trWrap.innerHTML = response.MATCHES_HTML;
                navWrap.innerHTML = response.NAV_STRING;
            },
            error =>{
                console.log(error);
            }
        );
        e.preventDefault();
    });

    /*Sort buttons handler*/
    sortBtns.forEach((sortBtn)=>{
        sortBtn.addEventListener('click', (e)=>{
            
            let sortParams = {};
            sortParams.sortCookieParam = 'sortData';
            sortParams.ordCookieParam = 'sortOrd';

            if(!sortBtn.classList.contains('active')){
                sortParams.sort = sortBtn.dataset.sort;
                sortParams.ord = sortBtn.dataset.order;
              
                sortBtns.forEach((sb)=>{
                    if(sb.classList.contains('active')){
                        sb.classList.remove('active');
                    }
                });

                sortBtn.classList.add('active');
                //?sort=game_date&ord=asc           
            }
            else{
                sortParams.sort = 'clear'; 
                sortBtns.forEach((sb)=>{
                    if(sb.classList.contains('active')){
                        sb.classList.remove('active');
                    }
                });
            } 
            
            let resObj = window.MatchList.applySort(sortParams);
            resObj.then(
                response => {                    
                    trWrap.innerHTML = '';
                    trWrap.innerHTML = response.MATCHES_HTML;
                    navWrap.innerHTML = response.NAV_STRING;
                },
                error =>{
                    console.log(error);
                }
            );          

            e.preventDefault();
        });

    });

});