/*
###APP LOAD###
*/
let appTitle;
let appContainer;
let appNavbar;
let btnDock;

function load()
{
    appTitle = document.getElementById("appTitle");
    btnDock = document.getElementById("appTitle").parentElement.parentElement;
    appContainer = document.getElementById("appContainer");
    appNavbar = document.getElementById("appNavbar");

    var xhr = new XMLHttpRequest();
    xhr.open("GET", "/API/students.php");
    xhr.onload = function()
    {
        appTitle.innerHTML = "Tutti gli studenti";
        btnDock.innerHTML += "<div style=\"float: right\"><button id=\"btnAdd\" class=\"btn btn-success\">Aggiungi</button></div>";
        appContainer.innerHTML = "";

        document.getElementById("btnAdd").addEventListener("click", newStudent);

        var table = document.createElement("table");
        var thead = document.createElement("thead");
        var tr = document.createElement('tr');
    
        table.setAttribute("class", "table");
        table.id = "table";
        thead.className = "thead-dark";
        tr.innerHTML =
            '<th>Nome</th>' +
            '<th>Cognome</th>' +
            '<th>Classe</th>' +
            '<th style="width: 1%;">Rimuovi</th>' +
            '<th style="width: 1%;">Edit</th>' ;
    
        thead.appendChild(tr);
        table.appendChild(thead);
        appContainer.appendChild(table);

        var students = JSON.parse(xhr.response);

        for(var i = 0; i < students.length; i++)
        {
            let id = students[i].id;
            tr = document.createElement('tr');
            let button1 = document.createElement("button");
            let button2 = document.createElement("button");
            let td1 = document.createElement("td");
            let td2 = document.createElement("td");

            button1.className = "btn btn-danger";
            button1.innerHTML = "x";
            button1.addEventListener("click", function()
                                            {
                                                removeStudent(id, button1);
                                            });
            td1.appendChild(button1);

            button2.className = "btn btn-primary";
            button2.innerHTML = "&#x1F589;";
            button2.addEventListener("click", function()
                                            {
                                                editStudent(id, button2);
                                            });
            td2.appendChild(button2);

            tr.innerHTML =
                '<td>' +  students[i].name + '</td>' +
                '<td>' +  students[i].surname + '</td>' +
                '<td>' +  students[i].section + '</td>';
            tr.appendChild(td1);
            tr.appendChild(td2);
            table.appendChild(tr);
        }
    };
    xhr.send();
}

function removeStudent(id, button)
{
    document.getElementById("table").removeChild(button.parentElement.parentElement);
    var xhr = new XMLHttpRequest();
    xhr.open("DELETE", '/API/students.php/' + id , true);
    xhr.onload = function()
    {
        if(xhr.status != 200)
        {
            alert("Errore: il server ha risposto con codice " + xhr.status);
            return;
        }

        
    };
    xhr.onerror = function()
    {
        alert("Errore di rete. Sei ancora online?");
    };
    xhr.send();
}

function editStudent(id, button)
{
    let inpName, inpSurname, slcClass;
    var tr = button.parentElement.parentElement;

    tr.innerHTML = tr.innerHTML; //reconstruct the DOM, removing all event listeners

    var tdName = tr.childNodes[0];
    var tdSurname = tr.childNodes[1];
    var tdClass = tr.childNodes[2];
    var btnRemove = tr.childNodes[3].childNodes[0];
    var btnEdit = tr.childNodes[4].childNodes[0];

    tdName.innerHTML = tdSurname.innerHTML = tdClass.innerHTML = "";


    btnRemove.className = "btn btn-secondary";
    btnRemove.disabled = true;

    btnEdit.className = "btn btn-success";
    btnEdit.innerHTML = "&#x2713;";
    btnEdit.addEventListener("click", function()
                                        {
                                            var nome = inpName.value;
                                            var cognome = inpSurname.value;
                                            var classe = slcClass.value;
                                            var obj = { name: nome, surname: cognome, class: classe };
                                            applyChanges(id, JSON.stringify(obj));
                                        });
    
    inpName = document.createElement("input");
    inpName.className = "form-input";
    tdName.appendChild(inpName);

    inpSurname = document.createElement("input");
    inpSurname.className = "form-input";
    tdSurname.appendChild(inpSurname);

    slcClass = document.createElement("select");
    slcClass.className = "form-input";
    tdClass.appendChild(slcClass);
                                        
    var xhr = new XMLHttpRequest();
    xhr.open("GET", '/API/classes.php' , true);
    xhr.onload = function()
    {
        var data = JSON.parse(xhr.response);
        let option;

        for (var i = 0; i < data.length; i++)
        {
            option = document.createElement('option');
            option.text = data[i].section;
            option.value = data[i].id;
            slcClass.add(option);
        } 
    };
    xhr.onerror = function()
    {
        alert("Errore di rete");
    };
    xhr.send();
}

function applyChanges(id, json)
{
    var xhr = new XMLHttpRequest();
    xhr.open("PATCH", '/API/students.php/' + id , true);
    xhr.onload = function()
    {
        if(xhr.status != 200)
        {
            alert("Errore: il server ha risposto con codice " + xhr.status);
            return;
        }
    };
    xhr.onerror = function()
    {
        alert("Errore di rete. Sei ancora online?");
    };
    xhr.send(json);

    //revert changes to the table here
}

function addCorso()
{
    var classe = document.getElementById("class").value;
    var nome = document.getElementById("name").value;
    var cognome = document.getElementById("surname").value;
    var sidi = document.getElementById("sidi").value;
    var tax = document.getElementById("tax").value;

    var obj = { name: nome, surname: cognome, sidi: sidi, tax: tax, class: classe };
    var myJSON = JSON.stringify(obj);
    var xhr = new XMLHttpRequest();
    xhr.open("POST", '/API/students.php' , true);
    xhr.onload = function()
    {
        if(xhr.status != 200)
        {
            alert("Errore: il server ha risposto con codice " + xhr.status + ", utente già esistente");
            return;
        }
        
    };
    xhr.onerror = function()
    {
        alert("Errore di rete. Sei ancora online?");
    };
    xhr.send(myJSON);


}

function newStudent()
{
    btnDock.removeChild(document.getElementById("btnAdd").parentElement);   //DOM gets reconstructed, reassign appTitle
    appTitle = document.getElementById("appTitle");
    var xhr = new XMLHttpRequest();
    xhr.open("GET", '/API/classes.php' , true);
    xhr.onload = function()
    {
    
        appTitle.innerHTML = "Aggiungi Studente";
        appContainer.innerHTML = ' <div id="form">'+
        '       <label for="class">Classe</label><br>'+
        '       <select id="class" class ="form-control"name="class" required></select>'+
        '       <label for="name">Nome</label><br>'+
        '       <input type="text" id="name" class ="form-control" name="name" placeholder="Nome" required></select>'+
        '       <label for="surname" >Cognome</label><br>'+
            '    <input type="text" id="surname" class="form-control" name="surname" placeholder="Cognome" required>'+
            '       <label for="sidi">sidi</label><br>'+
            '       <input type="text" id="sidi" class ="form-control" name="sidi" placeholder="sidi" required></select>'+
            '       <label for="tax" >tax</label><br>'+
                '    <input type="text" id="tax" class="form-control" name="tax" placeholder="tax" required>'+

        ' <button  class="btn btn-outline-success my-2 my-sm-0" onclick="addCorso()" value="segna">Aggiungi</button>'+
        '</div>';

        var data = JSON.parse(xhr.response);
        let option;

        for (var i = 0; i < data.length; i++)
        {
            option = document.createElement('option');
            option.text = data[i].section;
            option.value = data[i].id;
            document.getElementById("class").add(option);
        } 
    };
    xhr.onerror = function()
    {
        alert("Errore");
    };
    xhr.send();
}

window.addEventListener("load", load);