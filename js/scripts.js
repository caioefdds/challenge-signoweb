function AdicionarCampo(data) {
    event.preventDefault();
    
    var num = parseInt(data.dataset.num);
    var new_num = num+1;

    var tag_1 = document.createElement('b');
    var tag_2 = document.createElement('br');
    var tag_3 = document.createElement('input');
    var tag_4 = document.createElement('br');
    var text = document.createTextNode("OPÇÃO "+new_num+":");
    tag_1.innerText = "OPÇÃO "+new_num+":";
    tag_3.setAttribute('type', 'text');
    tag_3.setAttribute('maxlength', '50');
    tag_3.setAttribute('class', 'input-enquete');
    tag_3.setAttribute('name', 'opcao_'+new_num);
    tag_3.setAttribute('placeholder', 'Digite a opção');

    var final = document.createElement('div');
    final.appendChild(tag_1);
    final.appendChild(tag_2);
    final.appendChild(tag_3);
    final.appendChild(tag_4);

    document.getElementById("botao_plus").setAttribute('data-num' , new_num);
    document.getElementById("num_opcoes").setAttribute('value' , new_num);

    document.getElementById("botao_plus").before(final);
}

function processaVoto(id) {
    event.preventDefault();
    var c = confirm("Você tem certeza?");

    if(c == true) {
        var guardarLocal = guardaVoto(id);

        if(guardarLocal == true) {
                var voto = parseInt(document.getElementById("votes_"+id).innerText);

                var req = new XMLHttpRequest();
                req.open('POST', '../classes/class.request.php', true);
                req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                req.onreadystatechange = tratarEventosConexao;
                req.send('func=addvoto&id='+id+'&votes='+voto);

        } else if(guardarLocal == false) {
            alert("Você já votou!");
        }
    }
}

function tratarEventosConexao(e) {
    if(e.target.readyState == 4) {
        if(e.target.status == 200) {
            var res = JSON.parse(e.target.responseText);
            document.getElementById("votes_"+res.id).innerHTML = res.votes;
        } else if (e.target.status == 404) {
            console.log("Arquivo não encontrado");
        }
    }
}

function guardaVoto(id) {

    var id_enquete = document.getElementById("id_enquete").value;
    var id_user = document.getElementById("id_user").value;
    var voto = verificaVoto(id_enquete, id_user);

    if(voto == true) {
        // localStorage.setItem(id_enquete, id);
        localStorage.setItem(id_enquete+"_"+id_user, id);
        return true;
    } else {
        return false;
    }
}

function verificaVoto(id_enquete, id_user) {

    //var local = localStorage.getItem(id_enquete);
    var local = localStorage.getItem(id_enquete+"_"+id_user);

    if(local != undefined) {
        return false;
    } else {
        return true;
    }
}

function DeletePoll(id) {

    event.preventDefault();
    var req = new XMLHttpRequest();
    req.open('POST', '../classes/class.request.php', true);
    req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    req.onreadystatechange = DeletePollEvent;
    req.send('func=removePoll&id='+id);
}

function DeletePollEvent(e) {
    if(e.target.readyState == 4) {
        if(e.target.status == 200) {
            window.location.href = "my_poll.php";
        } else if (e.target.status == 404) {
            console.log("Arquivo não encontrado");
        }
    }
}

function atualizaVotos() {

    window.setTimeout(id => {
        var id = document.getElementById("id_enquete").value;

        var req = new XMLHttpRequest();
        req.open('POST', '../classes/class.request.php', true);
        req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        req.onreadystatechange = function (e) {
            if(e.target.readyState == 4) {
                if(e.target.status == 200) {
                    var res = JSON.parse(e.target.responseText);
                    var cont = res.options.contador;
                    var resp = res.options;
                    for(var a=0; a<cont; a++) {
                        document.getElementById("votes_"+resp[a].id).innerText = resp[a].votes;
                        console.log(resp[a]);
                    }
                } else if (e.target.status == 404) {
                    console.log("Arquivo não encontrado");
                }
            }
        };
        req.send('func=atualizaVotos&id='+id);

        atualizaVotos(id);
    }, 3000);
}