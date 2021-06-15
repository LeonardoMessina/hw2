function addBackup(idBackup, dataBackup){
    const tbody=document.querySelector("#content table>tbody");
    const row=document.createElement("tr");
	const rowData=document.createElement("td");

	row.setAttribute("data-id",idBackup);
	rowData.textContent=dataBackup;

	tbody.insertBefore(row,tbody.firstElementChild);
    row.appendChild(rowData);
}

function doBackup(){
    fetch(BACKUP_OPERE_ROUTE).then(function(response){
        if(response.ok){
            response.text().then(function(text){
                try{
					const object=JSON.parse(text);
					if(object.isError)
						throw object.message;

					message.classList.remove('error');
					message.textContent=object.message;
					
					addBackup(object.id, object.date);
				}catch(e){
					message.classList.add('error');
					message.textContent=e;
                }
				message.classList.remove('hidden');
			});
        }else{
            response.text().then(function(text){console.error(text);});
		}
    },function(error){    
    });
}

document.getElementById('backupButton').addEventListener('click',doBackup);