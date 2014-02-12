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

$plugin = array
(
    'prefix' => 'messenger',

    'context' => true,
    'message' => true,

    // We provide information about authorization on following commands
    'authorization' => array
    (
        'Send Message to User',
        'Send Message to All',
        'Send Message to Members',
        'Send Message to Moderators',
        'SiteBar Messenger',
    ),

    // We are hooked on following builds
    'build' => array
    (
        'Maintain Users',
        'Maintain Groups',

        'Send Message to User',
        'Send Message to All',
        'Send Message to Members',
        'Send Message to Moderators',
    ),

    // We want to execute before the command on which we are hooked
    'command' => array
    (
        'Send Message to User',
        'Send Message to All',
        'Send Message to Members',
        'Send Message to Moderators',
    ),

);

function messengerIsAuthorized(&$um, $command)
{
    if (preg_match('/^Send Message to (.*)/', $command, $reg))
    {
        $verifiedOld = $um->verified;
        if (!$verifiedOld)
        {
            $um->verified = true;
        }
        $res = $um->isAuthorized('Send Email to '.$reg[1]);
        $um->verified = $verifiedOld;
        return $res;
    }

    switch ($command)
    {
        case 'SiteBar Messenger': return !$um->isAnonymous();
    }
}

function messengerContext(&$nodeMenu, &$linkMenu, &$userMenu)
{
    $newUserMenu = array();
    foreach ($userMenu as $command)
    {
        if (SB_safeVal($command,'name')=='User Settings')
        {
            $newUserMenu[] = array('name'=>'SiteBar Messenger','href'=>'plugin.php?name=messenger');
        }
        $newUserMenu[] = $command;
    }

    $userMenu = $newUserMenu;
}

function messenger_MapRead($id)
{
    return 1;
}

function messengerGetNewCount($uid)
{
    $db =& SB_Database::staticInstance();
    $count = intval($db->getUserData( 'messenger', $uid, 'new' ));
    if ($count == -1)
    {
        $res = $db->select( 'count(*) new', 'sitebar_user_data', array('uid'=>$uid, '^1'=>"AND dvalue LIKE 'inbox_new%'"));
        $rec = $db->fetchRecord($res);
        $count = intval($rec['new']);
        $db->setUserData( 'messenger', $uid, 'new', $count );
    }

    return $count;
}

function messengerBuildMaintainUsers(&$cmdWin, &$fields)
{
    $newFields = array();

    if (!is_array($fields))
    {
        return $fields;
    }

    foreach ($fields as $key => $value)
    {
        $newFields[$key] = $value;

        if (preg_match('/^Send Email to (.*)/', $key, $reg))
        {
            $newFields['Send Message to '.$reg[1]] = $value;
        }
    }

    $fields = $newFields;
}

function messenger_BuildFormatting($select=null)
{
    $um =& SB_UserManager::staticInstance();

    $formats = array();

    if ($um->isAdmin())
    {
        $formats['html'] = 'HTML';
    }

    $formats['plain'] = 'Plain Text';

    foreach ( $formats as $format => $label)
    {
        echo '<option '. ($select==$format?'selected':'') .
             ' value="' . $format . '">' . $label . "</option>\n";
    }
}

function messenger_BuildFrom($select=null)
{
    $um =& SB_UserManager::staticInstance();

    $froms = array();

    if (SB_reqChk('command_gid') && $um->isModerator(SB_reqVal('command_gid')))
    {
        $froms['moderator'] = 'Group Moderator';
    }

    if ($um->isAdmin())
    {
        $froms['admins'] = 'Administrators';
    }

    $froms['user'] = 'Current User';

    foreach ( $froms as $from => $label)
    {
        echo '<option '. ($select==$from?'selected':'') .
             ' value="' . $from . '">' . $label . "</option>\n";
    }
}

function messengerBuildMaintainGroups(&$cmdWin, &$fields)
{
    messengerBuildMaintainUsers($cmdWin, $fields);
}

function messengerCommonBuild(&$cmdWin, &$fields, $to)
{
    $fields['--hidden1-'] = array('name'=>'uid', 'value'=>SB_reqVal('uid'));
    $fields['--hidden2-'] = array('name'=>'command_gid', 'value'=>SB_reqVal('command_gid'));

    $fields['From'] =  array('name'=>'from','type'=>'selectextern','_options'=>'messenger_BuildFrom');
    $fields['To (Just Label)'] =  array('name'=>'to','value'=>$to);
    $fields['Subject'] = array('name'=>'subject');
    $fields['Message'] = array('name'=>'message', 'type'=>'textarea', 'rows'=>5);

    if ($cmdWin->um->isAdmin())
    {
        $fields['--raw1-'] = '<p><a href="http://www.fckeditor.net/demo/default.html">FCKeditor - WYSIWYG</a></p>';
    }

    $fields['Formatting'] = array('name'=>'format','type'=>'selectextern','_options'=>'messenger_BuildFormatting');


    $fields['Respect Allow Info Mail'] = array
    (
        'name'=>'respect',
        'type'=>'checkbox',
        'checked'=>1,
        'title'=>SB_P('command::tooltip_respect')
    );

    $fields['Expiration'] = array
    (
        'name'=>'expires',
        'value'=>date('Y-m-d', mktime(0,0,0,date('m')+1,date('d'),date('Y')) )
    );
}

function messengerBuildSendMessagetoUser(&$cmdWin, &$fields)
{
    $fromuser = $cmdWin->um->getUser(intval(SB_reqVal('uid')));
    messengerCommonBuild($cmdWin, $fields, $fromuser['fullname']);
}

function messengerBuildSendMessagetoAll(&$cmdWin, &$fields)
{
    messengerCommonBuild($cmdWin, $fields, SB_T('All Users'));
    $groups = $cmdWin->um->getGroups();
    $fields['Excludes Members of Groups'] = array('name'=>'gids[]','type'=>'select','_options'=>'_buildGroupMultipleList','size'=>count($groups),'multiple'=>null);
}

function messengerBuildSendMessagetoMembers(&$cmdWin, &$fields)
{
    $group = $cmdWin->um->getGroup(SB_reqVal('command_gid', true));
    messengerCommonBuild($cmdWin, $fields, SB_T('Members of %s Group', $group['name']));
}

function messengerBuildSendMessagetoModerators(&$cmdWin, &$fields)
{
    $group = $cmdWin->um->getGroup(SB_reqVal('command_gid', true));
    messengerCommonBuild($cmdWin, $fields, SB_T('Moderators of %s Group', $group['name']));
}

function messengerCommonCommand(&$cmdWin, &$to)
{
    if ($cmdWin->hasErrors())
    {
        return;
    }

    $db =& SB_Database::staticInstance();

    $db->lock($tables=array('sitebar_data'=>'WRITE','sitebar_user_data'=>'WRITE'));
    $mid = intval($db->getData( 'messenger', 'id'))+1;
    $db->setData( 'messenger', 'id', $mid);

    $db->setData('messenger', $mid.'_to',        SB_reqVal('to'));
    $db->setData('messenger', $mid.'_from',      SB_reqVal('from'));
    $db->setData('messenger', $mid.'_uid',       $cmdWin->um->uid);
    $db->setData('messenger', $mid.'_gid',       SB_reqVal('command_gid'));
    $db->setData('messenger', $mid.'_subject',   SB_reqVal('subject'));
    $db->setData('messenger', $mid.'_message',   SB_reqVal('message'));
    $db->setData('messenger', $mid.'_format',    $cmdWin->um->isAdmin()?SB_reqVal('format'):'plain');
    $db->setData('messenger', $mid.'_expires',   SB_reqVal('expires'));
    $db->setData('messenger', $mid.'_datetime',      time());

    $counter = 0;

    if (!ini_get('safe_mode'))
    {
        // We need more time if our database is slow
        set_time_limit(intval(count($to)/20)+10);
    }

    foreach ($to as $uid => $user)
    {
        if ($uid == SB_ANONYM)
        {
            continue;
        }

        $userparams = $user['params'];
        $cmdWin->um->explodeParams($userparams, 'tmp');

        if (SB_reqChk('respect') && !$cmdWin->um->getParam('tmp','allow_info_mails'))
        {
            continue;
        }

        SB_SetLanguage($cmdWin->um->getParam('tmp','lang'));

        $counter++;
        $new = intval($db->getUserData( 'messenger', $uid, 'new' ));
        $db->setUserData( 'messenger', $uid, 'new', $new+1 );
        $db->setUserData( 'messenger', $uid, $mid, 'inbox_new' );
    }

    $db->setData('messenger', $mid.'_counter', $counter);
    $db->unlock();

    $cmdWin->warn('Sent to %s recipients.', $counter);

    SB_SetLanguage($cmdWin->um->getParam('user','lang'));
}

function messengerCommandSendMessagetoUser(&$cmdWin)
{
    $uid = SB_reqVal('uid', true);
    $to = array($uid => $cmdWin->um->getUser($uid));
    messengerCommonCommand($cmdWin, $to);
}

function messengerCommandSendMessagetoAll(&$cmdWin)
{
    $to = $cmdWin->um->getUsers();
    if (SB_reqChk('gids'))
    {
        foreach (SB_reqVal('gids') as $gid)
        {
            foreach ($cmdWin->um->getMembers($gid) as $uid => $rec)
            {
                if (isset($to[$uid]))
                {
                    unset($to[$uid]);
                }
            }
        }
    }
    messengerCommonCommand($cmdWin, $to);
}

function messengerCommandSendMessagetoMembers(&$cmdWin)
{
    $to = $cmdWin->um->getMembers(SB_reqVal('command_gid', true));
    messengerCommonCommand($cmdWin, $to);
}

function messengerCommandSendMessagetoModerators(&$cmdWin)
{
    $to = $cmdWin->um->getMembers(SB_reqVal('command_gid', true), true);
    messengerCommonCommand($cmdWin, $to);
}

?>
