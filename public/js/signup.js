let checkHandle=null;

function checkPage1(){
    if(checkHandle)
        clearTimeout(checkHandle);

    let isOk=true;
    document.getElementById('nextButton').disabled = true;
    isOk&=checkEmpty(document.querySelector("input[name='username']"));
    isOk&=checkEmpty(document.querySelector("input[name='password']"));
    isOk&=checkEmpty(document.querySelector("input[name='email']"));

    let data = new FormData();
    data.append('step','0');
    data.append('username', document.querySelector("input[name='username']").value);
    data.append('password', document.querySelector("input[name='password']").value);
    data.append('confirm_password', document.querySelector("input[name='confirm_password']").value);
    data.append('email', document.querySelector("input[name='email']").value);

    checkHandle=setTimeout(function(){
        checkHandle=null;
        fetch(SIGNUP_CHECK_1_ROUTE, {method: 'post', body:data, headers: {'X-CSRF-TOKEN': CSFR_TOKEN}}).then(function(response){
            if(response.ok){
                response.text().then(function(text){
                    const err=document.querySelector("#errors p");
                    err.textContent='';
                    try{
                        const errorsList=JSON.parse(text);
                        if(!errorsList.length)
                            document.querySelector("#errors>div").classList.add('hidden');
                        else{
                            isOk=false;
                            document.querySelector("#errors>div").classList.remove('hidden');
                        }

                        for(const errorText of errorsList){
                            if(err.textContent.length>0)
                                err.append(document.createElement("br"));
                            err.append(errorText);
                        }
                        document.getElementById('nextButton').disabled = !isOk;
                    }catch(e){
                        err.textContent=e; 
                        err.append(document.createElement("br"));
                        err.append(text);
                        document.querySelector("#errors>div").classList.remove('hidden');
                    }
                });
            }else{
                response.text().then(function(text){console.error(text);});
            }
        });
    },500);
}

function checkPage2(event){
    if(checkHandle)
        clearTimeout(checkHandle);
        
    let isOk=true;
    document.getElementById('nextButton').disabled = true;
    isOk&=checkEmpty(document.querySelector("input[name='telefono1']"));
    isOk&=checkEmpty(document.querySelector("input[name='nomeMuseo']"));
    isOk&=checkEmpty(document.querySelector("input[name='dataApertura']"));

    isOk&=checkEmpty(document.querySelector("select[name='tipoMuseo']"));
    isOk&=checkEmpty(document.querySelector("select[name='provinciaMuseo']"));
    isOk&=checkEmpty(document.querySelector("select[name='cittaMuseo']"));
    isOk&=checkEmpty(document.querySelector("select[name='museoPubblicoPrivato']"));

    checkUpload();

    let data = new FormData();
    data.append('step','1');
    data.append('telefono1', document.querySelector("input[name='telefono1']").value);
    data.append('telefono2', document.querySelector("input[name='telefono2']").value);
    data.append('nomeMuseo', document.querySelector("input[name='nomeMuseo']").value);
    data.append('dataApertura', document.querySelector("input[name='dataApertura']").value);
    
    data.append('tipoMuseo', document.querySelector("select[name='tipoMuseo']").value);
    data.append('provinciaMuseo', document.querySelector("select[name='provinciaMuseo']").value);
    data.append('cittaMuseo', document.querySelector("select[name='cittaMuseo']").value);
    data.append('museoPubblicoPrivato', document.querySelector("select[name='museoPubblicoPrivato']").value);

    checkHandle=setTimeout(function(){
        checkHandle=null;

        fetch(SIGNUP_CHECK_2_ROUTE, {method: 'post', body:data, headers: {'X-CSRF-TOKEN': CSFR_TOKEN}}).then(function(response){
            if(response.ok){
                response.text().then(function(text){
                    const err=document.querySelector("#errors p");
                    err.textContent='';
                    try{
                        const errorsList=JSON.parse(text);
                        if(errorsList.length===0)
                            document.querySelector("#errors>div").classList.add('hidden');
                        else{
                            isOk=false;
                            document.querySelector("#errors>div").classList.remove('hidden');
                        }

                        for(const errorText of errorsList){
                            if(err.textContent.length>0)
                                err.append(document.createElement("br"));
                            err.append(errorText);
                        }

                        if(isOk)
                            isOk&=checkUpload();

                        document.getElementById('nextButton').disabled = !isOk;
                    }catch(e){
                        err.textContent=e; 
                        err.append(document.createElement("br"));
                        err.append(text);
                        document.querySelector("#errors>div").classList.remove('hidden');
                    }
                });
            }else{
                response.text().then(function(text){console.error(text);});
            }
        });
    },500);
}

function checkProvinciaMuseo(event){
    const input = event.currentTarget;
    fetch(SIGNUP_CITIES_LIST_ROUTE+'/'+input.value).then(function(response){
        if(response.ok){
            response.text().then(function(text){
                document.getElementById('cittaMuseo').innerHTML=text;
            });
        }else{
            console.error(response.statusText);
        }
    },function(error){    
        console.error(response.statusText);
    });

    checkPage2(event);
}

document.getElementById('username').addEventListener('input', checkPage1);
document.getElementById('confirm_password').addEventListener('input', checkPage1);
document.getElementById('password').addEventListener('input', checkPage1);
document.getElementById('email').addEventListener('input', checkPage1);
document.getElementById('telefono1').addEventListener('input', checkPage2);
document.getElementById('telefono2').addEventListener('input', checkPage2);
document.getElementById('nomeMuseo').addEventListener('input', checkPage2);
document.getElementById('dataApertura').addEventListener('input', checkPage2);

document.getElementById('tipoMuseo').addEventListener('change', checkPage2);
document.getElementById('provinciaMuseo').addEventListener('change', checkProvinciaMuseo);
document.getElementById('cittaMuseo').addEventListener('change', checkPage2);
document.getElementById('museoPubblicoPrivato').addEventListener('change', checkPage2);

document.getElementById('upload').addEventListener('click', clickSelectFile);
document.getElementById('upload_original').addEventListener('change', checkPage2);