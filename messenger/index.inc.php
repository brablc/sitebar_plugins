<?php
/******************************************************************************
 *  SiteBar 3 - The Bookmark Server for Personal and Team Use.                *
 *  Copyright (C) 2005  Ondrej Brablc <http://brablc.com/mailto?o>            *
 *                                                                            *
 *  This program is free software; you can redistribute it and/or modify      *
 *  it under the terms of the GNU General Public License as published by      *
 *  the Free Software Foundation; either version 2 of the License, or         *
 *  (at your option) any later version.                                       *
 *                                                                            *
 *  This program is distributed in the hope that it will be useful,           *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of            *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             *
 *  GNU General Public License for more details.                              *
 *                                                                            *
 *  You should have received a copy of the GNU General Public License         *
 *  along with this program; if not, write to the Free Software               *
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA *
 ******************************************************************************/

require_once('./inc/errorhandler.inc.php');
require_once('./inc/page.inc.php');
require_once('./inc/usermanager.inc.php');

class Messenger extends SB_ErrorHandler
{
    var $um;
    var $db;
    var $folder = 'inbox';
    var $folders = array
    (
        'inbox'  => 'Inbox',
        'saved'  => 'Saved',
        'outbox' => 'Outbox',
    );
    var $displayedNew = 0;
    var $displayedAll = 0;
    var $ajax = false;

    function Messenger()
    {
        $this->ajax = SB_reqChk('ajax');

        $this->um = SB_UserManager::staticInstance();
        SB_Skin::set($this->um->getParam('user','skin'));

        $this->db =& $this->um->db;

        if (SB_reqChk('folder'))
        {
            $this->folder = SB_reqVal('folder');
        }

        if (SB_reqVal('function')=='toggle')
        {
            if ($this->um->isAnonymous())
            {
                echo "Error: Allow cookies!";
                exit;
            }

            $section = SB_reqVal('section');
            $mid = intval(SB_reqVal('mid'));
            $keep = intval(SB_reqVal('keep'));

            if ($section == 'new')
            {
                $this->db->setUserData( 'messenger', $this->um->uid, $mid, $keep?'new':'read' );
            }
            if ($section == 'read')
            {
                $this->db->setUserData( 'messenger', $this->um->uid, $mid, $keep?'read':'deleted' );
            }

            $this->db->setUserData( 'messenger', $this->um->uid, 'changed', time());
            exit;
        }
    }

    function getPluginUrl($params=array())
    {
        $url = 'plugin.php?name=messenger';
        $paramsStr = '';
        foreach ($params as $label => $value)
        {
            $url .= '&amp;' . $label . '=' . $value;
        }
        return $url;
    }

    function drawHeader()
    {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/REC-html40/loose.dtd">

<html>
<head>
    <title><?php echo SB_T("SiteBar Messenger") ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="StyleSheet"    href="<?php echo SB_Skin::path().'/directory.css'?>" type="text/css" media="all">
    <link rel="StyleSheet"    href="plugins/messenger/index.css" type="text/css" media="all">
    <link rel="Shortcut Icon" href="<?php echo SB_Skin::path().'/root_transparent.png'?>">
    <script type="text/javascript" src="inc/sitebar.js"></script>
    <script type="text/javascript" src="plugins/messenger/index.js"></script>
    <link rel="Author"        href="http://brablc.com/">
</head>
<body class="cmnBaseFont cmnPageBackground ">
    <div class="title cmnTitleColorInverse"><?php echo SB_T('SiteBar Messenger')?></div>
    <div class="folders">
<?php

        foreach ($this->folders as $folder => $label)
        {
            echo '[';
            if ($folder != $this->folder)
            {
                echo '<a style="color: black;" href="' . $this->getPluginUrl(array('folder'=>$folder)) . '">';
            }
            echo SB_T($label);
            if ($folder != $this->folder)
            {
                echo '</a>';
            }
            echo ']';
        }

?>
    </div>
    <form method="post" action="">
    <div class="buttons">
    <input type="button" value="<?php echo SB_T("Toggle Selection"); ?>" onClick="SB_messengerToggle()">
<?php
            if ($this->folder=='inbox')
            {
?>
    <input name='command[mark]' type="submit" value="<?php echo SB_T("Read"); ?>">
    <input name='command[unmark]' type="submit" value="<?php echo SB_T("Unread"); ?>">
    <input name='command[save]' type="submit" value="<?php echo SB_T("Save"); ?>">
<?php
            }

            if ($this->folder=='inbox' || $this->folder=='saved')
            {
?>
    <input name='command[delete]' type="submit" value="<?php echo SB_T("Delete"); ?>">
<?php
            }
            if ($this->folder=='outbox')
            {
?>
    <input name='command[back]' type="submit" value="<?php echo SB_T("Cancel"); ?>">
    <input name='command[expire]' type="submit" value="<?php echo SB_T("Expire"); ?>">
<?php
            }
?>
    </div>
<?php
    }

    function drawBody()
    {
        $where = array('type'=>'messenger');
        $res = null;
        $midRecs = array();

        if ($this->folder == 'outbox')
        {
            # Messages created by current user
            $where['^1']  = "AND dkey like '%_uid' AND dvalue = '".$this->um->uid."'";
            $res = $this->db->select("dkey, 'outbox' folder", 'sitebar_data', $where, 'cast(dkey as unsigned) DESC');
            foreach ($this->db->fetchRecords($res) as $outRec)
            {
                $midRecs[] = $outRec;
            }
            $gids = array_keys($this->um->getModeratedGroups());
            if ($this->um->isAdmin())
            {
                $gids[] = 1;
            }
            # Messages created by moderators
            if (count($gids))
            {
                $where['^1']  = "AND dkey like '%_gid' AND dvalue IN ('".implode("','",$gids)."')";
                $res = $this->db->select("dkey, 'outbox' folder", 'sitebar_data', $where, 'cast(dkey as unsigned) DESC');
                foreach ($this->db->fetchRecords($res) as $outRec)
                {
                    $midRecs[] = $outRec;
                }
            }
            if ($this->um->isAdmin())
            {
                $where['^1']  = "AND dkey like '%_from' AND dvalue = 'admins'";
                $res = $this->db->select("dkey, 'outbox' folder", 'sitebar_data', $where, 'cast(dkey as unsigned) DESC');
                foreach ($this->db->fetchRecords($res) as $outRec)
                {
                    $midRecs[] = $outRec;
                }
            }
        }
        else
        {
            $where['^1']  = 'AND';
            $where['uid'] = $this->um->uid;
            $where['^2'] = 'AND dvalue LIKE \'' . $this->db->escapeString($this->folder) . '%\'';
            $res = $this->db->select('dkey, dvalue folder', 'sitebar_user_data', $where, 'cast(dkey as unsigned) DESC');
            $midRecs = $this->db->fetchRecords($res);
        }

        $command = SB_reqVal('command');
        $webMids = SB_reqVal('mid');

        $seenMid = array();

        foreach ($midRecs as $rec)
        {
            $mid = intval($rec['dkey']);

            if (isset($seenMid[$mid]))
            {
                continue;
            }

            $seenMid[$mid] = 1;

            $folder = $rec['folder'];

            if (isset($webMids[$mid]))
            {
                if ($this->folder=='outbox')
                {
                    // Let user get number of new messages once again
                    $this->db->update('sitebar_user_data',array('dvalue'=>-1),"type='messenger' AND dkey = 'new'");

                    if (isset($command['back']))
                    {
                        $this->db->delete('sitebar_data',"type='messenger' AND dkey like '".$mid."_%'");
                        $this->db->delete('sitebar_user_data',"type='messenger' AND dkey = '$mid'");
                        continue;
                    }
                    if (isset($command['expire']))
                    {
                        $this->db->update(
                            'sitebar_user_data',
                            array('dvalue'=>'expired'),
                            "type='messenger' AND dkey = '$mid' AND dvalue IN ('inbox_new','inbox_newseen')");
                    }
                }
                else
                {
                    if (isset($command['mark']))
                    {
                        $folder = 'inbox_read';
                        $this->db->setUserData('messenger',$this->um->uid,$mid,$folder);
                        if ($this->ajax)
                        {
                           echo $mid.';read';
                        }
                    }
                    if (isset($command['unmark']))
                    {
                        $folder = 'inbox_newseen';
                        $this->db->setUserData('messenger',$this->um->uid,$mid,$folder);
                        if ($this->ajax)
                        {
                           echo $mid.';new';
                        }
                    }
                    if (isset($command['save']))
                    {
                        $this->db->setUserData('messenger',$this->um->uid,$mid,'saved');
                        continue;
                    }
                    if (isset($command['delete']))
                    {
                        $this->db->setUserData('messenger',$this->um->uid,$mid,'deleted');
                        continue;
                    }
                }

            }

            $fromuser = $this->um->getUser(intval($this->db->getData('messenger', $mid.'_uid')));

            $as  = $this->db->getData('messenger', $mid.'_from');
            $from = '';
            $to = $this->db->getData('messenger', $mid.'_to');

            $date = date("r", $this->db->getData('messenger', $mid.'_datetime'));
            $expires = $this->db->getData('messenger', $mid.'_expires');


            if ($as == 'admins')
            {
                $from = SB_T('Administrators').' (';
            }
            if ($as == 'moderator')
            {
                $gid = intval($this->db->getData('messenger', $mid.'_gid'));

                if (!$this->um->isModerator($gid))
                {
                    continue;
                }

                $group = $this->um->getGroup($gid);
                $from = SB_T('Moderator of %s Group', $group['name']).' (';
            }

            $from .= $fromuser['fullname'];

            if ($as != 'user')
            {
                $from .= ')';
            }

            $message = $this->db->getData('messenger', $mid.'_message');

            if ($this->db->getData('messenger', $mid.'_format') == 'plain')
            {
                $message = "<pre>" . $message . "</pre>";
            }
            else
            {
                $message = stripslashes($message);
            }

            $highlight = '';

            if (strstr($folder,'inbox_new'))
            {
                $this->displayedNew++;
                $highlight = ' highlight';

                if ($folder=='inbox_new')
                {
                    $this->db->setUserData('messenger',$this->um->uid,$mid,'inbox_newseen');
                }
            }

            $isnew = (strpos($folder,'inbox_new')!==false);
            $img = SB_Page::relBaseUrl().sprintf('plugins/messenger/msg_%s.gif',($isnew?'new':'read'));
            $checkbox = "<input class='checkBox' type='checkbox' name='mid[$mid]'>";

            if (!$this->ajax)
            {

?>
<div id='message<?php echo $mid ?>' class='message'>
<table class='cmnMenu'>
<tr>
    <td class='icon'><img id="img<?php echo $mid ?>" src="<?php echo $img ?>" <?php if ($this->folder=='inbox'):?>onclick='SB_messengerToggleItem(this,<?php echo $mid ?>)'<?php endif;?>></td>
    <td class='cmnMenuItem<?php echo $highlight ?>'><?php echo SB_T('From') ?></td>
    <td><?php echo $from ?></td>
</tr>
<?php
                if ($this->folder=='outbox' && $to)
                {
?>
<tr>
    <td class='check'><?php echo $checkbox; $checkbox = '&nbsp;'; ?></td>
    <td class='cmnMenuItem<?php echo $highlight ?>'><?php echo SB_T('To') ?></td>
    <td><?php echo $to ?></td>
</tr>
<?php
                }
?>
<tr>
    <td class='check'><?php echo $checkbox ?></td>
    <td class='cmnMenuItem<?php echo $highlight ?>'><?php echo SB_T('Date') ?></td>
    <td><?php echo $date ?></td>
</tr>
<tr>
    <td class='status'>&nbsp;</td>
    <td class='cmnMenuItem<?php echo $highlight ?>'><?php echo SB_T('Subject') ?></td>
    <td>
<?php
        echo stripslashes($this->db->getData('messenger', $mid.'_subject'));
?>
    </td>
</tr>
<?php
                if ($this->folder=='outbox')
                {
?>
<tr>
    <td class='check'>&nbsp;</td>
    <td class='cmnMenuItem'><?php echo SB_T('Expiration') ?></td>
    <td><?php echo $expires ?></td>
</tr>
<?php
                    $states = array
                    (
                        'inbox_new'     => 'Unread',
                        'inbox_newseen' => 'Seen',
                        'inbox_read'    => 'Read',
                        'saved'         => 'Saved',
                        'deleted'       => 'Deleted',
                        'expired'       => 'Expired',
                    );

                    $breakdown = '';
                    foreach ($states as $status => $label)
                    {
                        $res = $this->db->select('count(*) count', 'sitebar_user_data', "type='messenger' AND dkey='$mid' AND dvalue='$status'");
                        $countRec = $this->db->fetchRecord($res);
                        if ($countRec['count']>0)
                        {
                            if (SB_reqVal('drill')==$status && is_string($webMids) && $mid==$webMids)
                            {
                                if ($breakdown != '')
                                {
                                    $breakdown .= '<br>';
                                }

                                $breakdown .= SB_T($label) . ": ";
                                $res = $this->db->select('uid', 'sitebar_user_data', "type='messenger' AND dkey='$mid' AND dvalue='$status'");
                                foreach ($this->db->fetchRecords($res) as $uidRec)
                                {
                                    $user = $this->um->getUser($uidRec['uid']);
                                    $breakdown .= '<a href="command.php?command=Modify%20User&amp;uid='.$uidRec['uid'].'">'.$user['username'].'</a> ';
                                }
                                $breakdown .= "<br>";
                            }
                            else
                            {
                                $breakdown .= '<a class="states" href="'.$this->getPluginUrl(array('folder'=>$this->folder,'mid'=>$mid,'drill'=>$status)).'">' . SB_T($label) . '</a> [' . $countRec['count']. '] ';
                            }
                        }
                    }
?>
<tr>
    <td class='status'>&nbsp;</td>
    <td class='cmnMenuItem'><?php echo SB_T("Status") ?></td>
    <td><?php echo $breakdown ?></td>
</tr>
<?php
                }
?>
<tr>
    <td class='message' colspan='3'><?php echo $message ?></td>
</tr>
</table>
</div>
<?php
            }
        }

        if ($this->folder=='inbox' && is_array($command))
        {
            $this->db->lock($tables=array('sitebar_data'=>'WRITE','sitebar_user_data'=>'WRITE'));
            $this->db->setUserData('messenger', $this->um->uid, 'new', $this->displayedNew);
            $this->db->unlock();
        }
    }


    function drawFoot()
    {
        $baseurl = SB_Page::absBaseUrl();
?>
    </form>
    <div class="footer cmnTitleColorInverse">
    <?php echo SB_T("Messages from SiteBar installation at"); ?>
    <a class="url" href="<?php echo $baseurl?>"><?php echo $baseurl?></a>
    </div>
</body>
</html>
<?php
    }

    function run()
    {
        if (!$this->ajax)
        {
            $this->drawHeader();
        }
        if (!$this->um->isAnonymous())
        {
            $this->drawBody();
        }
        if (!$this->ajax)
        {
            $this->drawFoot();
        }
        // If we have no errors or ignore them
        if (!$this->hasErrors())
        {
            $this->writeErrors(false);
        }
    }

}

$messenger = new Messenger();
$messenger->run();
