function onSuccessWeather(text, divId){
    try{
        const dataWeather=JSON.parse(text);
        const description=document.createElement("h2");
        description.textContent="Meteo : " + dataWeather.data[0].weather.description;
        const divWeather=document.querySelector("#" + divId);
        if(!divWeather) //Per evitare che, rimuovendo il preferito mentre si attende la risposta del servizio, si abbia un errore
            return;
        divWeather.appendChild(description);
        const img=document.createElement("img");
        img.src='https://www.weatherbit.io/static/img/icons/' + dataWeather.data[0].weather.icon + '.png';
        divWeather.appendChild(img);
    }catch(e){
        console.error("onSuccessWeather",e,text);
    }
}

function onErrorWeather(error, divId) {
    const description=document.createElement("h2");
    description.textContent="Errore meteo: " + error;
    const divWeather=document.querySelector("#" + divId);
    divWeather.appendChild(description);
}

function showWeather(id, divId){
    fetch(API_WEATHER_ROUTE+'/'+id).then(function(response){
        if(response.ok){
            response.text().then(function(text){
                if(text.length>0)
                    onSuccessWeather(text,divId);
            });
        }else{
            response.text().then(function(text){
                console.error(text);
            });
            return null;
        }
    },function(error){    
        onErrorWeather(error, divId);
    });
}
