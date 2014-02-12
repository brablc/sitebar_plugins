<?php
/******************************************************************************
 *  SiteBar 3 - The Bookmark Server for Personal and Team Use.                *
 *  Copyright (C) 2004-2005  Ondrej Brablc <http://brablc.com/mailto?o>       *
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
    'prefix' => 'mailgate',

    // We provide information about authorization on following commands
    'authorization' => array
    (
        'Mail Gate',
        'Mail Gate Settings',
    ),

    // We are hooked on following builds
    'build' => array
    (
        'User Settings',
        'Mail Gate',
        'Mail Gate Settings',
        'SiteBar Settings',
    ),

    // We are hooked on following executions
    'command' => array
    (
        'Mail Gate',
        'Mail Gate Settings',
        'SiteBar Settings',
    ),
);

// We get called when noone else wanted to say meaning on the given command or
// when the command is to be called during build of some other command, we
// can specify whether it is allowed in its context.
function mailgateIsAuthorized(&$um, $command)
{
    switch ($command)
    {
        case 'Mail Gate Settings':
            return !$um->isAnonymous()
                && !$um->demo && $um->verified
                && $um->getParam('config','use_mail_features');

        case 'Mail Gate':
            $user = $um->getUser(SB_reqVal('uid'));
            $um->explodeParams($user['params'], 'tmp');
            if (isset($user['verified'])
            && $user['verified']
            && $um->getParam('tmp', 'allow_mailgate_use')
            && $um->getParam('config','use_mail_features'))
            {
                return true;
            }
            // In case of failure not external
            $um->setParam('user','extern_commander',0);
            return false;
    }

    return false;
}

function mailgateBuildUserSettings(&$cmdWin, &$fields)
{
    $fields['Mail Gate Settings'] = array('type'=>'addbutton');
}

function mailgateBuildMailGateSettings(&$cmdWin, &$fields)
{
    $fields['Enable Mail Gate'] = array('name'=>'allow_mailgate_use',
        'type'=>'checkbox', 'value'=>1,
        'checked'=>$cmdWin->um->getParamCheck('user','allow_mailgate_use'));

    $fields['Subject Prefix'] = array('name'=>'mailgate_subject',
        'value'=>$cmdWin->um->getParam('user','mailgate_subject'));

    $fields['Mail Gate Keyword'] = array('name'=>'mailgate_keyword',
        'value'=>$cmdWin->um->getParam('user','mailgate_keyword'));

    $fields['Go to URL after Execution'] = array('name'=>'mailgate_redirect',
        'value'=>urldecode($cmdWin->um->getParam('user','mailgate_redirect')));

    $fields['Show IP and Domain'] = array('name'=>'mailgate_show_ip',
        'type'=>'checkbox', 'value'=>1,
        'checked'=>$cmdWin->um->getParamCheck('user','mailgate_show_ip'));

    $url = SB_Page::absBaseUrl() . 'command.php?command=Mail Gate&uid=' . $cmdWin->um->uid;
    $showurl = str_replace(' ', '%20', $url);
    $fields['-raw1-'] = SB_P('command::mailgate_url', array($url, $showurl));

    // Never external
    $cmdWin->um->setParam('user','extern_commander',0);
}

function mailgateCommandMailGateSettings(&$cmdWin)
{
    $values = array
    (
        'allow_mailgate_use',
        'mailgate_keyword',
        'mailgate_subject',
        'mailgate_show_ip',
    );

    foreach ($values as $check)
    {
        $cmdWin->um->setParam('user',$check, SB_reqVal($check));
    }

    $cmdWin->um->setParam('user','mailgate_redirect', urlencode(SB_reqVal('mailgate_redirect')));
    $cmdWin->um->saveUserParams();

    // Never external
    $cmdWin->um->setParam('user','extern_commander',0);
    $cmdWin->forwardCommand('User Settings');
}

function mailgateBuildSiteBarSettings(&$cmdWin, &$fields)
{
    $fields['Allow CC in Mail Gate'] = array('name'=>'allow_mailgate_cc',
        'type'=>'checkbox', 'value'=>1,
        'checked'=>$cmdWin->um->getParamCheck('config','allow_mailgate_cc'));
}

function mailgateCommandSiteBarSettings(&$cmdWin)
{
    $values = array
    (
        'allow_mailgate_cc',
    );

    foreach ($values as $check)
    {
        $cmdWin->um->setParam('config',$check, SB_reqVal($check));
    }

    $cmdWin->um->saveConfiguration();
}

function mailgateBuildMailGate(&$cmdWin, &$fields)
{
    // Never external
    $cmdWin->um->setParam('user','extern_commander',0);

    if (!SB_reqChk('uid'))
    {
        $cmdWin->error('No user was selected!');
        return null;
    }

    $user = $cmdWin->um->getUser(SB_reqVal('uid'));

    $fields['To'] = array('name'=>'realname', 'value'=>$user['name'], 'disabled' => null);
    $fields['From Name'] = array('name'=>'from_name');
    $fields['From Email'] = array('name'=>'from_email');

    if ($cmdWin->um->getParam('config','allow_mailgate_cc'))
    {
        $fields['Send CC to Sender'] = array('name'=>'cc_email', 'type'=>'checkbox');
    }

    $fields['Subject'] = array('name'=>'subject');
    $fields['Message'] = array('name'=>'message', 'type'=>'textarea', 'rows'=>5);

    $cmdWin->um->explodeParams($user['params'], 'tmp');

    if ($cmdWin->um->getParam('tmp','mailgate_keyword')!='')
    {
        $fields['Keyword'] = array('name'=>'keyword');
    }

    $fields['-hidden1-'] = array('name'=>'from_ip', 'value'=>$_SERVER['REMOTE_ADDR'], 'disabled' => null);
    $fields['-hidden2-'] = array('name'=>'uid', 'value'=>SB_reqVal('uid'));
    $fields['-hidden3-'] = array('name'=>'referer', 'value'=>
        isset($_GET['forward'])
        ?$_GET['forward']
        :isset($_SERVER['HTTP_REFERER'])
         ?$_SERVER['HTTP_REFERER']
         :'');

    if ($cmdWin->um->getParam('tmp','mailgate_show_ip'))
    {
        $fields['-raw1-'] = SB_T('Your domain %s and IP address %s were recorded!',
                array(gethostbyaddr($_SERVER['REMOTE_ADDR']), $_SERVER['REMOTE_ADDR']));
    }
}

function mailgateCommandMailGate(&$cmdWin)
{
    // Never external
    $cmdWin->um->setParam('user','extern_commander',0);

    $user = $cmdWin->um->getUser(SB_reqVal('uid', true));

    $cmdWin->checkMandatoryFields(array('from_name', 'from_email', 'subject', 'from_ip'));

    if (!preg_match('/^\S+@\S+\.\w+$/i', SB_reqVal('from_email')))
    {
        $cmdWin->error('Field %s does not look like a valid email.', array(SB_T('From')));
    }

    $cmdWin->um->explodeParams($user['params'], 'tmp');

    $keyword = $cmdWin->um->getParam('tmp','mailgate_keyword');

    if ($keyword!='' && SB_reqVal('keyword')!=$keyword)
    {
        $cmdWin->error('Keyword does not match!');
    }

    if ($cmdWin->hasErrors())
    {
        $cmdWin->goBack();
        return;
    }

    SB_SetLanguage($cmdWin->um->getParam('tmp','lang'));
    $message  = stripslashes(SB_reqVal('message'));
    $subject  = $cmdWin->um->getParam('tmp','mailgate_subject') . SB_reqVal('subject');

    $ret = $cmdWin->um->sendMail($user, $subject, $message,
        SB_reqVal('from_name'), SB_reqVal('from_email'),
        SB_reqVal('cc_email') && $cmdWin->um->getParam('config','allow_mailgate_cc'));

    if ($ret)
    {
        $url = SB_reqVal('referer');

        if (!$url)
        {
            $url = $cmdWin->um->getParam('tmp','mailgate_redirect');
        }

        if ($url)
        {
            header('Location: ' . urldecode($url));
            exit;
        }
        $cmdWin->warn('Email sent successfully!');
    }
    else
    {
        $cmdWin->warn('Cannot send email!');
    }
}

?>
