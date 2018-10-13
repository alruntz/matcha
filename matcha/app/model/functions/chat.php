<?php

	function show_messages($db, $id_target, $my_id)
	{
		$messages = array();
		$req = $db->prepare('SELECT * FROM chat_message WHERE id_user_target = ? AND id_user_author = ?  OR id_user_target = ? AND id_user_author = ?; ');
		$req->execute(array(htmlspecialchars($my_id), htmlspecialchars($id_target), htmlspecialchars($id_target), htmlspecialchars($my_id)));
		while ($val = $req->fetch(PDO::FETCH_OBJ))
		{
			if ($val->id_user_author != $my_id)
			{
				$req2 = $db->prepare('UPDATE chat_message SET view = 1 WHERE id = ?');
				$req2->execute(array($val->id));
			}
			array_push($messages, $val);
		}
		//$messages = array_reverse($messages);
		for ($i = 0; $i < count($messages); $i++)
		{
			$user = get_user_infos_obj($db, $messages[$i]->id_user_author);
			if ($messages[$i]->id_user_author == $my_id)
			{
				echo '<li class="left clearfix admin_chat">
                  <span class="chat-img1 pull-right">';
            }
            else
            {
            	echo '<li class="left clearfix">
                  <span class="chat-img1 pull-left">';
            }
            echo '<a href="?page=user_infos&user_id=' . $user->id . '"><img src="' . $user->photo_profile . '"" alt="User Avatar" class="img-circle"></a>
                  </span>
                  <div class="chat-body1 clearfix">
                   <p id="' . $messages[$i]->id . '">' . $messages[$i]->message . '</p>
                   <div class="chat_time pull-right">' . $messages[$i]->date_send . '</div>
                 </div>
                 </li>';
		}
	}

	function show_messages_lasts($db, $my_id, $id_user_target, $last_id)
	{
		$messages = array();
		$req = $db->prepare('SELECT * FROM chat_message WHERE id_user_target = ? AND id_user_author = ? AND id > ?; ');
		$req->execute(array(htmlspecialchars($my_id), htmlspecialchars($id_user_target), htmlspecialchars($last_id)));
		while ($val = $req->fetch(PDO::FETCH_OBJ))
		{
			$req2 = $db->prepare('UPDATE chat_message SET view = 1 WHERE id = ?');
			$req2->execute(array($val->id));
			array_push($messages, $val);
		}
		for ($i = 0; $i < count($messages); $i++)
		{
			$user = get_user_infos_obj($db, $messages[$i]->id_user_author);
            	echo '<li class="left clearfix">
                  <span class="chat-img1 pull-left"><img src="' . $user->photo_profile . '" alt="User Avatar" class="img-circle">
                  </span>
                  <div class="chat-body1 clearfix">
                   <p id="' . $messages[$i]->id . '">' . $messages[$i]->message . '</p>
                   <div class="chat_time pull-right">' . $messages[$i]->date_send . '</div>
                 </div>
                 </li>';
		}
	}

	function get_last_message($db, $id_user_author, $id_user_target)
	{
		$req = $db->prepare('SELECT * FROM chat_message WHERE id_user_target = ? AND id_user_author = ?  OR id_user_target = ? AND id_user_author = ? ORDER BY id DESC; ');
		if ($req->execute(array(htmlspecialchars($id_user_author), htmlspecialchars($id_user_target), htmlspecialchars($id_user_target), htmlspecialchars($id_user_author))))
		{
			return ($req->fetch(PDO::FETCH_OBJ));
		}
		return (null);
	}

	function get_messages_no_view($db, $id_user_target)
	{
		$ret = array();
		$req = $db->prepare("SELECT * FROM  chat_message WHERE id_user_target = ? AND view = '0'");
		if ($req->execute(array($id_user_target)))
		{
			while ($val = $req->fetch(PDO::FETCH_OBJ))
				array_push($ret, $val);
			return ($ret);
		}
		return (null);
	}

	function get_nb_messages_new($db, $id_user_author, $id_user_target)
	{
		$req = $db->prepare("SELECT COUNT(*) FROM chat_message WHERE id_user_target = ? AND id_user_author = ? AND view = 0");
		$req->execute(array($id_user_author, $id_user_target));
		return($req->fetch()[0]);
	}

	function cmp_friends_lastmsg($a, $b)
	{
		$last_message_a = get_last_message($db, $id_user, $b->id_user);
		return (strtotime($a) - strtotime($b));
	}

	function show_friends($db, $id_user)
	{
		$friends = get_friends($db, $id_user);
		/*Connectés*/
		for ($i = 0; $i < count($friends); $i++)
		{
			if (is_connected($db, $friends[$i]->id))
			{
				$user_infos = get_user_infos_obj($db, $friends[$i]->id);
				echo '<li onclick="location.href=' . "'index.php?page=chat&id_target=" . $user_infos->id_user ."';" . '"class="left clearfix">	
	                     <span class="chat-img pull-left">
	                     <img src="' . $user_infos->photo_profile . '" alt="User Avatar" class="img-circle">
	                     </span>
	                     <div class="chat-body clearfix">
	                        <div class="header_sec">
	                           <strong class="primary-font"> <i style="color: green; "class="fa fa-globe "></i> ' . $user_infos->first_name . '</strong> <strong class="pull-right">
	                           09:45AM</strong>
	                        </div>
	                        <div class="contact_sec">
	                           <strong class="primary-font">';
	             $last_message = get_last_message($db, $id_user, $user_infos->id_user);
	            if ($last_message != null)
	            	echo  $last_message->message;
	            echo '</strong> <span class="badge pull-right">' . get_nb_messages_new($db, $id_user, $user_infos->id_user) . '</span>
	                        </div>
	                     </div>
	                  </li>';
			}
		}
		/*deconnectés*/
		for ($i = 0; $i < count($friends); $i++)
		{
			if (!is_connected($db, $friends[$i]->id))
			{
				$user_infos = get_user_infos_obj($db, $friends[$i]->id);
				echo '<li onclick="location.href=' . "'index.php?page=chat&id_target=" . $user_infos->id_user ."';" . '"class="left clearfix">	
	                     <span class="chat-img pull-left">
	                     <img src="' . $user_infos->photo_profile . '" alt="User Avatar" class="img-circle">
	                     </span>
	                     <div class="chat-body clearfix">
	                        <div class="header_sec">
	                           <strong class="primary-font"><i style="color: red; "class="fa fa-globe "></i> ' . $user_infos->first_name . '</strong> <strong class="pull-right">
	                           09:45AM</strong>
	                        </div>
	                        <div class="contact_sec">
	                           <strong class="primary-font">';
	             $last_message = get_last_message($db, $id_user, $user_infos->id_user);
	            if ($last_message != null)
	            	echo  $last_message->message;
	            echo '</strong> <span class="badge pull-right">' . get_nb_messages_new($db, $id_user, $user_infos->id_user) . '</span>
	                        </div>
	                     </div>
	                  </li>';
			}
		}
	}

?>