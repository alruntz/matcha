<br />
<br />
<br />
<div class="main_section">
   <div class="container">
      <div class="chat_container" id="chat_sidebar">
         <div class="col-sm-3 chat_sidebar">
         <div class="row">
            <div id="custom-search-input">
               <div class="input-group col-md-12">
                  <input type="text" class="  search-query form-control" placeholder="Conversation" />
                  <button class="btn btn-danger" type="button">
                  <span class=" glyphicon glyphicon-search"></span>
                  </button>
               </div>
            </div>
            <div class="member_list">
               <ul class="list-unstyled" id ="friend_list">
                <?php show_friends($db, get_my_user_id($db)); ?>
                <!--
                  <li class="left clearfix">
                     <span class="chat-img pull-left">
                     <img src="https://lh6.googleusercontent.com/-y-MY2satK-E/AAAAAAAAAAI/AAAAAAAAAJU/ER_hFddBheQ/photo.jpg" alt="User Avatar" class="img-circle">
                     </span>
                     <div class="chat-body clearfix">
                        <div class="header_sec">
                           <strong class="primary-font">Jack Sparrow</strong> <strong class="pull-right">
                           09:45AM</strong>
                        </div>
                        <div class="contact_sec">
                           <strong class="primary-font">(123) 123-456</strong> <span class="badge pull-right">3</span>
                        </div>
                     </div>
                  </li>
                -->
               
               </ul>
            </div></div>
         </div>
         <!--chat_sidebar-->

         <?php 
         $tabFriends = explode(';', $my_user_infos->friends);
         if (isset($_GET['id_target']) && in_array($_GET['id_target'], $tabFriends)) {?>
         
         <div class="col-sm-9 message_section">
         <div class="row">
         
         <div class="chat_area" id="chat_area">
         <ul class="list-unstyled" id="messages">
            <?php
                show_messages($db, $_GET['id_target'], get_my_user_id($db));
            ?>
<!--
            <li class="left clearfix">
                <span class="chat-img1 pull-left">
                    <img src="https://lh6.googleusercontent.com/-y-MY2satK-E/AAAAAAAAAAI/AAAAAAAAAJU/ER_hFddBheQ/photo.jpg" alt="User Avatar" class="img-circle">
                </span>
                <div class="chat-body1 clearfix">
                  <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia.</p>
                  <div class="chat_time pull-right">09:40PM</div>
                </div>
            </li>

            <li class="left clearfix admin_chat">
                     <span class="chat-img1 pull-right">
                     <img src="https://lh6.googleusercontent.com/-y-MY2satK-E/AAAAAAAAAAI/AAAAAAAAAJU/ER_hFddBheQ/photo.jpg" alt="User Avatar" class="img-circle">
                     </span>
                     <div class="chat-body1 clearfix">
                        <p>Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia.</p>
                        <div class="chat_time pull-left">09:40PM</div>
                     </div>
                  </li>
-->
         
         </ul>
         </div><!--chat_area-->
          <div class="message_write">
            <form action="#" method="post" id="send-message-chat">
              <div class="clearfix"></div>
              <div class="chat_bottom">
                <input type="hidden" name="id_user_author" id="id_user_author" value=<?php echo '"' . get_my_user_id($db) . '"'; ?>>
                <input type="hidden" name="id_user_target" id="id_user_target" value=<?php echo '"' . $_GET['id_target'] . '"'; ?>>
                <input type="submit" value="Send" class="pull-right btn btn-success" id="send-chat">
              </div>
            </form>
             <textarea class="form-control" name="message" id="message" placeholder="type a message" form="send-message-chat"></textarea>
          </div>
         </div>
         </div> 
         <!--message_section-->
         <?php } ?>
      </div>
   </div>
</div>