function load_notif_chat() {

	var id_user = encodeURIComponent( $('#my_user_id').val() );

        $.ajax({
            url : "app/model/API/menu.php?action=show_notif_chat&id_user=" + id_user,
            type : "GET",
            success : function(html){
                 $('#notifs_chat').html(html);
            }

        });
}

function load_nb_notif_chat()
{
	setTimeout( function(){
	var id_user = encodeURIComponent( $('#my_user_id').val() );

        $.ajax({
            url : "app/model/API/menu.php?action=nb_notif_chat&id_user=" + id_user,
            type : "GET",
            success : function(html){
                 $('#text_notif_chat').text('Notif. chat (' + html + ')');
            }

        });

        //load_notif_chat();
        load_nb_notif_chat();
    }, 1500);
}

function load_notif_view() {

	var id_user = encodeURIComponent( $('#my_user_id').val() );

        $.ajax({
            url : "app/model/API/menu.php?action=show_notif_view&id_user=" + id_user,
            type : "GET",
            success : function(html){
                 $('#notifs_view').html(html);
            }

        });
}

function load_nb_notif_view()
{
	setTimeout( function(){
	var id_user = encodeURIComponent( $('#my_user_id').val() );

        $.ajax({
            url : "app/model/API/menu.php?action=nb_notif_view&id_user=" + id_user,
            type : "GET",
            success : function(html){
                 $('#text_notif_view').text('Notif. vues (' + html + ')');
            }

        });

        //load_notif_chat();
        load_nb_notif_view();
    }, 1500);
}

function load_notif_friendsreq() {

	var id_user = encodeURIComponent( $('#my_user_id').val() );

        $.ajax({
            url : "app/model/API/menu.php?action=show_notif_friendsreq&id_user=" + id_user,
            type : "GET",
            success : function(html){
                 $('#notifs_friendsreq').html(html);
            }

        });
}

function load_nb_notif_friendsreq()
{
	setTimeout( function(){
	var id_user = encodeURIComponent( $('#my_user_id').val() );

        $.ajax({
            url : "app/model/API/menu.php?action=nb_notif_friendsreq&id_user=" + id_user,
            type : "GET",
            success : function(html){
                 $('#text_notif_friendsreq').text('Likes (' + html + ')');
            }

        });
        load_nb_notif_friendsreq();
    }, 1500);
}

function remove_notif(str, id)
{
	if (str == "chat")
	{
		$.ajax({
  		      url : "app/model/API/chat.php?action=view_message&id=" + id,
  		      type : "GET",
  		      success : function(){
                 load_notif_chat();
            }
     	});
	}
	else if (str == "view")
	{
		$.ajax({
  		      url : "app/model/API/menu.php?action=view_view&id=" + id,
  		      type : "GET",
  		      success : function(){
                 load_notif_view();
            }
     	});
	}
	else if (str == "friendsreq_remove")
	{
		$.ajax({
  		      url : "app/model/API/menu.php?action=view_like&id=" + id,
  		      type : "GET",
  		      success : function(test){
                 load_notif_friendsreq();
            }
     	});
	}
	else if (str == "friendsreq_add")
	{
		$.ajax({
  		      url : "app/model/API/add_friend.php?id_friend=" + id,
  		      type : "GET",
  		      success : function(){
                 load_notif_friendsreq();
            }
     	});
	}
}


$( document ).ready(function() {

	$( "#toggle_notif_chat" ).click(function() {
 		load_notif_chat();
	});
	$( "#toggle_notif_view" ).click(function() {
 		load_notif_view();
	});
	$( "#toggle_notif_friendsreq" ).click(function() {
 		load_notif_friendsreq();
	});
});

$('#notifs_chat').on('click', function (e) {
	e.stopPropagation();
});
$('#notifs_view').on('click', function (e) {
	e.stopPropagation();
});
$('#notifs_friendsreq').on('click', function (e) {
	e.stopPropagation();
});

load_nb_notif_chat();
load_nb_notif_view();
load_nb_notif_friendsreq();