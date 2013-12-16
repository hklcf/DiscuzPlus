<?php

/*
	Version: 1.0.0(BUG Fixed)
	Author: HKLCF (admin@hklcf.com)
	Copyright: HKLCF (www.hklcf.com)
	Last Modified: 2004/09/22
*/

require './include/common.php';
$maxavatarsize = "1024000";

if($action == 'uploadavatar'){
        $url= 'uploadavatar.php';
        $url2='memcp.php';
        $image_size = @getimagesize($upavatars); 
        if(($upavatars_type=='image/gif')||($upavatars_type=='image/pjpeg')){
                if($upavatars_size>1024000){
                        showmessage('profile_uploadavatar_filetoobig',$url);
                }elseif($image_size[0] > $maxavatarsize || $image_size[1] > $maxavatarsize) {
                        showmessage('profile_uploadavatar_toobig',$url);
                }else{
                        $avatarsdir = $discuz_root.'./customavatars/';
                        if(is_dir($avatarsdir))
                        {
                                $query = $db->query("SELECT uid FROM $table_members WHERE username='$discuz_user'");
                                $uid = $db->result($query, 0);
                                $avatar = $avatarsdir.$uid.'.gif';
                                copy($upavatars,$avatar);
                                $db->query("UPDATE $table_members SET avatar='$avatar' WHERE username='$discuz_user'");
                                showmessage('profile_avatar_upload_succeed',$url2);
                        }else{
                                showmessage('profile_customavatardir_nonexistence');
                        }
                }
        }else{
                showmessage('profile_uploadavatar_wrongfiletype',$url);
        }
}

if(!$discuz_user||!$discuz_pw)
{
        showmessage('not_loggedin');
}else{
        if($allowavatar == 1 || $allowavatar == 0)
        {
                showmessage('profile_uploadavatar_nopremission');
        } elseif($allowavatar == 2){
                $query = $db->query("SELECT avatar FROM $table_members WHERE username='$discuz_user'");
                $avatar = $db->result($query, 0);
                include template('memcp_uploadavatar');
        }
}

?>