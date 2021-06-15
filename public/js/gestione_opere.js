let checkHandle=null;

function showArtworkModal(){
    document.getElementById("artworkModal").classList.remove('hidden');
}

function hideArtworkModal(){
    document.getElementById("artworkModal").classList.add('hidden');
}

function clearFields(){
    clearField(document.querySelector("input[name='nomeOpera']"));
    clearField(document.querySelector("input[name='autoreOpera']"));
    clearField(document.querySelector("input[name='annoInizioCreazioneOpera']"));
    clearField(document.querySelector("input[name='annoUltimaturaOpera']"));

    clearUpload();
}

function deleteArtwork(event){
    const rowToDelete=event.currentTarget.parentNode.parentNode;
    fetch(GESTIONE_OPERE_DELETE_ROUTE+'/'+rowToDelete.getAttribute('data-id')).then(function(response){
        if(response.ok){
            response.text().then(function(text){
                const err=document.querySelector("#errors p");
                err.textContent='';
                try{
                    rowToDelete.remove();
                }catch(e){
                    err.textContent=e; 
                    err.append(document.createElement("br"));
                    err.append(text);
                    document.querySelector("#errors>div").classList.remove('hidden');
                }
            });
        }
    });
}

function addArtwork(imageUrl, idOpera){
    const table=document.querySelector('table');
    const row=document.createElement("tr");
    const rowNome=document.createElement("td");
    const rowAutore=document.createElement("td");
    const rowAnnoInizioCreazione=document.createElement("td");
    const rowAnnoUltimatura=document.createElement("td");
    const rowImmagine=document.createElement("td");
    const rowIconaCancellaOpera=document.createElement("td");
    const rowImmagineImg=document.createElement("img");
    const rowIconaCancellaOperaImg=document.createElement("img");

    const annoInizioCreazioneOpera=document.querySelector("input[name='annoInizioCreazioneOpera']").value;
    const annoUltimaturaOpera=document.querySelector("input[name='annoUltimaturaOpera']").value;

    rowNome.textContent=document.querySelector("input[name='nomeOpera']").value;
    rowAutore.textContent=document.querySelector("input[name='autoreOpera']").value;
    rowAnnoInizioCreazione.textContent=annoInizioCreazioneOpera.length===0 ? "N.D." :  annoInizioCreazioneOpera+" "+(document.getElementById("annoInizioCreazioneOperaAC").checked ? "a.C." : "d.C.");
    rowAnnoUltimatura.textContent=annoUltimaturaOpera.length===0 ? "N.D." :  annoUltimaturaOpera+" "+(document.getElementById("annoUltimaturaOperaAC").checked ? "a.C." : "d.C.");
    rowImmagineImg.classList.add('iconaOpera');
    rowImmagineImg.src=imageUrl;
    rowIconaCancellaOperaImg.classList.add('iconaCancellaOpera');
    rowIconaCancellaOperaImg.src='images/assets/cancella.png';

    row.setAttribute("data-id",idOpera);
    table.appendChild(row);
    row.appendChild(rowNome);
    row.appendChild(rowAutore);
    row.appendChild(rowAnnoInizioCreazione);
    row.appendChild(rowAnnoUltimatura);
    row.appendChild(rowImmagine);
    row.appendChild(rowIconaCancellaOpera);
    rowImmagine.appendChild(rowImmagineImg);
    rowIconaCancellaOpera.appendChild(rowIconaCancellaOperaImg);

    rowIconaCancellaOperaImg.addEventListener('click', deleteArtwork);
}

document.querySelector("#content>div .button").addEventListener('click', showArtworkModal);

function checkPage(event,saveCheck){
    if(checkHandle)
        clearTimeout(checkHandle);

    let isOk=true;
    isOk&=checkEmpty(document.getElementById('nomeOpera'));
    isOk&=checkEmpty(document.getElementById('autoreOpera'));

    const annoInizioCreazioneOperaAC=document.getElementById('annoInizioCreazioneOperaAC');
    const annoUltimaturaOperaAC=document.getElementById('annoUltimaturaOperaAC');

    checkUpload(saveCheck);
    
    let data = new FormData();
    data.append('nomeOpera', document.querySelector("input[name='nomeOpera']").value);
    data.append('autoreOpera', document.querySelector("input[name='autoreOpera']").value);
    data.append('annoInizioCreazioneOpera',document.getElementById('annoInizioCreazioneOpera').value);
    data.append('annoInizioCreazioneOperaSegno',annoInizioCreazioneOperaAC.checked ? -1 : 1);
    data.append('annoUltimaturaOpera',document.getElementById('annoUltimaturaOpera').value);
    data.append('annoUltimaturaOperaSegno',annoUltimaturaOperaAC.checked ? -1 : 1);

    if(saveCheck && isOk && upload_original.value.length>0)
        data.append('immagineOpera', upload_original.files[0]);

    data.append('type', saveCheck ? 'save' : 'check');

    checkHandle=setTimeout(function(){
        checkHandle=null;    
        fetch(GESTIONE_OPERE_CHECK_ROUTE, {method: 'post', body:data, headers: {'X-CSRF-TOKEN': CSFR_TOKEN}}).then(function(response){
            if(response.ok){
                response.text().then(function(text){
                    const err=document.querySelector("#errors p");
                    err.textContent='';
                    try{
                        const object=JSON.parse(text);
                        const errorsList=Array.isArray(object) ? object : [];
                        
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

                        if(isOk){
                            if(saveCheck && typeof object.filePath!=="undefined"){
                                isOk=false;
                                addArtwork(object.filePath, object.idOpera);
                                clearFields();
                                hideArtworkModal();
                            }else
                                isOk&=checkUpload();
                        }

                        document.getElementById('saveArtwork').disabled = !isOk;

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

document.getElementById('nomeOpera').addEventListener('input', checkPage);
document.getElementById('autoreOpera').addEventListener('input', checkPage);
document.getElementById('annoInizioCreazioneOpera').addEventListener('input', checkPage);
document.getElementById('annoUltimaturaOpera').addEventListener('input', checkPage);
document.getElementById('annoInizioCreazioneOperaAC').addEventListener('change', checkPage);
document.getElementById('annoInizioCreazioneOperaDC').addEventListener('change', checkPage);
document.getElementById('annoUltimaturaOperaAC').addEventListener('change', checkPage);
document.getElementById('annoUltimaturaOperaDC').addEventListener('change', checkPage);

document.getElementById('upload').addEventListener('click', clickSelectFile);
document.getElementById('upload_original').addEventListener('change', checkPage);

document.getElementById('saveArtwork').addEventListener('click', function(event){
    checkPage(event,true);
});

for(const element of document.querySelectorAll("table .iconaCancellaOpera"))
    element.addEventListener('click',deleteArtwork);

modalInit();
