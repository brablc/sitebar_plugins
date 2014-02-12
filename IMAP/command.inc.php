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
    'prefix' => 'imap',

    // We provide information about authorization on following commands
    'authorization' => array
    (
        'IMAP Settings',
        'Sign Up',
        'Reset Password',
    ),

    // We are hooked on following builds
    'build' => array
    (
        'SiteBar Settings',
        'IMAP Settings',
    ),

    // We are hooked on following executions
    'command' => array
    (
        'SiteBar Settings',
        'IMAP Settings',
    ),
);

// We get called when noone else wanted to say meaning on the given command or
// when the command is to be called during build of some other command, we
// can specify whether it is allowed in its context.
function imapIsAuthorized(&$um, $command)
{
    switch ($command)
    {
        case 'IMAP Settings':
            if ($um->isAdmin() && $um->getParam('config','auth')=='IMAP')
            {
                if (!function_exists('imap_open'))
                {
                    // It must have already been sellected, so we reject it
                    $um->setParam('config','auth','');
                    $um->saveConfiguration();
                    $um->error("No IMAP support!");
                    $um->error("SiteBar default athorization reset!");
                    return false;
                }
                return true;
            }
            return false;
    }

    return false;
}

function imapBuildSiteBarSettings($cmdWin, &$fields)
{
    $fields['IMAP Settings'] = array('type'=>'addbutton');
    $fields['-hiddenOldAuth-'] = array('name'=>'auth_old', 'value'=>$cmdWin->um->getParam('config','auth'));
}

function imapCommandSiteBarSettings(&$cmdWin)
{
    // Never external
    $cmdWin->um->setParam('user','extern_commander',0);

    if (SB_reqVal('auth')=='IMAP' && SB_reqVal('auth')!=SB_reqVal('auth_old'))
    {
        $cmdWin->forwardCommand('IMAP Settings');
    }
}

function imapBuildIMAPSettings($cmdWin, &$fields)
{
    $imap_server = $cmdWin->um->getParam('config','imap_server');
    $imap_port = $cmdWin->um->getParam('config','imap_port');
    $imap_ssl = $cmdWin->um->getParam('config','imap_ssl');
    $imap_default_domain = $cmdWin->um->getParam('config','imap_default_domain');

    if (!strlen($imap_port))
    {
        $imap_port = 143;
    }

    $fields['IMAP Server'] = array('name'=>'imap_server', 'value'=>$imap_server);
    $fields['IMAP Port'] = array('name'=>'imap_port', 'value'=>$imap_port);
    $fields['IMAP Use SSL'] = array('name'=>'imap_ssl', 'type'=>'checkbox');
    $fields['IMAP Default Domain'] = array('name'=>'imap_default_domain', 'value'=>$imap_default_domain);

    if ($imap_ssl)
    {
        $fields['IMAP Use SSL']['checked'] = null;
    }

    // Never external
    $cmdWin->um->setParam('user','extern_commander',0);
}

function imapCommandIMAPSettings(&$cmdWin)
{
    $values = array
    (
        'imap_server',
        'imap_port',
        'imap_ssl',
        'imap_default_domain',
    );

    foreach ($values as $check)
    {
        $value = SB_reqVal($check);

        if ($check == 'imap_default_domain')
        {
            if (strlen($value)>0 && substr($value,0,1) == '@')
            {
                $value = substr($value,1);
            }
        }

        $cmdWin->um->setParam('config',$check, $value);
    }

    $cmdWin->um->saveConfiguration();
    $cmdWin->forwardCommand('SiteBar Settings');
}

?>
