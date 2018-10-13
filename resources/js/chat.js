function getURLParams()
{
    var url = document.location.href.split("?");
    
    if(url.length > 1)
    {
        // Params found un URL !
        var get = new Object;
        var params = url[1].split("&");

        for(var i in params)
        {
            var tmp = params[i].split("=");
            get[tmp[0]] = unescape(tmp[1].replace("+", " "));
        }
        
        return get;
    }
    
   
    return false;
}

function isset(data)
{
    if(typeof(data) == "undefined")
        return false;
    
    return true;
}

$_GET = getURLParams();

 var objDiv = $('div.chat_area');
 if ((typeof (objDiv) != "undefined" || objDiv !== null || objDiv.length > 0 || objDiv[0] !== null || typeof (objDiv[0]) != "undefined") && isset($_GET.id_target))
    objDiv.scrollTop(objDiv[0].scrollHeight);

$('#send-chat').click(function(e){
    e.preventDefault(); // on empêche le bouton d'envoyer le formulaire

    var message = encodeURIComponent( $('#message').val() );
    var id_user_author = encodeURIComponent( $('#id_user_author').val() );
    var id_user_target = encodeURIComponent( $('#id_user_target').val() );
    var photo_profile = $('#photo_profile').attr('src');

    var d = new Date();
    var hour = d.getHours();
    var minute = d.getMinutes();
    var last_id = "";


    if(id_user_author != "" && message != "" && id_user_target != ""){ // on vérifie que les variables ne sont pas vides

        $.ajax({

            url : "app/model/API/chat.php", // on donne l'URL du fichier de traitement

            type : "POST", // la requête est de type POST

            data : "id_user_author=" + id_user_author + "&id_user_target=" + id_user_target + "&message=" + message, // et on envoie nos données
            success : function(e){
                //alert(e);
                last_id = e;
               // alert(last_id);
               $('#messages').append('<li class="left clearfix admin_chat">'
                                + '<span class="chat-img1 pull-right"><img src="' + photo_profile + '"'
                                + 'alt="User Avatar" class="img-circle"></span>'
                                + '<div class="chat-body1 clearfix">'
                                + '<p id="' + last_id + '">' +  decodeURIComponent(message) + '</p>'
                                + '<div class="chat_time pull-left"> Aujourd\'hui à ' + hour.toString() + ' : ' + minute.toString() + '</div>'
                                + '</div></li>'); // on ajoute le message dans la zone prévue
                var objDiv = $('div.chat_area');
                objDiv.scrollTop(objDiv[0].scrollHeight);
            } 
        });
    }

});

function charger(){


    setTimeout( function(){


        var premierID = $('#messages p:last').attr('id'); // on récupère l'id le plus récent
        var id_user_author = encodeURIComponent( $('#my_user_id').val() );
        var id_user_target = encodeURIComponent( $('#id_user_target').val() );

        //alert("test" + id_user_author);

        $.ajax({
            url : "app/model/API/chat.php?id_user_author=" + id_user_author + "&id_user_target=" + id_user_target + "&last_message=" + premierID, // on passe l'id le plus récent au fichier de chargement
            type : "GET",
            success : function(html){
                if (html != '')
                {
                    $('#messages').append(html);
                    var objDiv = $('div.chat_area');
                    objDiv.scrollTop(objDiv[0].scrollHeight);
                }
            }

        });

        $.ajax({
            url : "app/model/API/chat.php?action=show_friends&id_user_author=" + id_user_author,
            type : "GET",
            success : function(html){
                $('#friend_list').replaceWith('<ul class="list-unstyled" id ="friend_list">' + html + '</ul>');
            }

        });

        charger();
    }, 1500);


}

charger();
