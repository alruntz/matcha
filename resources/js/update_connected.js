function update_connected(){
    setTimeout( function(){
        //alert("test1");
        var user_id = encodeURIComponent( $('#my_user_id').val() );
        $.get("app/model/API/update_connected.php?user_id=" + user_id, function(data){
            //alert(data);
        });
        update_connected();
    }, 1500);
}

update_connected();