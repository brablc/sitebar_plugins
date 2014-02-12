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
    'prefix' => 'ldap',

    // We provide information about authorization on following commands
    'authorization' => array
    (
        'LDAP Settings',
        'Sign Up',
        'Reset Password',
    ),

    // We are hooked on following builds
    'build' => array
    (
        'SiteBar Settings',
        'LDAP Settings',
    ),

    // We are hooked on following executions
    'command' => array
    (
        'SiteBar Settings',
        'LDAP Settings',
    ),
);

// We get called when noone else wanted to say meaning on the given command or
// when the command is to be called during build of some other command, we
// can specify whether it is allowed in its context.
function ldapIsAuthorized(&$um, $command)
{
    switch ($command)
    {
        case 'LDAP Settings':
            if ($um->isAdmin() && $um->getParam('config','auth')=='LDAP')
            {
                if (!function_exists('ldap_connect'))
                {
                    // It must have already been sellected, so we reject it
                    $um->setParam('config','auth','');
                    $um->saveConfiguration();
                    $um->error("No LDAP support!");
                    $um->error("SiteBar default athorization reset!");
                    return false;
                }
                return true;
            }
            return false;

    }

    return false;
}

function ldapBuildSiteBarSettings($cmdWin, &$fields)
{
    $fields['LDAP Settings'] = array('type'=>'addbutton');
    $fields['-hiddenOldAuth-'] = array('name'=>'auth_old', 'value'=>$cmdWin->um->getParam('config','auth'));
}

function ldapCommandSiteBarSettings(&$cmdWin)
{
    // Never external
    $cmdWin->um->setParam('user','extern_commander',0);

    if (SB_reqVal('auth')=='LDAP' && SB_reqVal('auth')!=SB_reqVal('auth_old'))
    {
        $cmdWin->forwardCommand('LDAP Settings');
    }
}

function ldapBuildLDAPSettings($cmdWin, &$fields)
{
    $ldap_server = $cmdWin->um->getParamB64('config','ldap_server');
    $ldap_user_filter = $cmdWin->um->getParamB64('config','ldap_user_filter');
    $ldap_user_tree = $cmdWin->um->getParamB64('config','ldap_user_tree');
    $ldap_protocol_version_3 = $cmdWin->um->getParam('config','ldap_protocol_version_3');

    if (!strlen($ldap_server))
    {
        $ldap_server = 'ldap.example.tld';
    }
    if (!strlen($ldap_user_filter))
    {
        $ldap_user_filter = "(&(username=%s)(objectClass=posixAccount))";
    }
    if (!strlen($ldap_user_tree))
    {
        $ldap_user_tree = "ou=People,dc=example,dc=tld";
    }

    $fields['LDAP Server'] = array('name'=>'ldap_server', 'value'=>$ldap_server);
    $fields['LDAP Protocol Version 3'] = array('name'=>'ldap_protocol_version_3', 'type'=>'checkbox',
        'checked'=>$cmdWin->um->getParamCheck('config','ldap_protocol_version_3'));
    $fields['LDAP Mail Filter'] = array('name'=>'ldap_user_filter', 'value'=>$ldap_user_filter);
    $fields['LDAP User Tree'] = array('name'=>'ldap_user_tree', 'value'=>$ldap_user_tree);

    // Never external
    $cmdWin->um->setParam('user','extern_commander',0);
}

function ldapCommandLDAPSettings(&$cmdWin)
{
    $values = array
    (
        'ldap_server',
        'ldap_user_filter',
        'ldap_user_tree',
        'ldap_protocol_version_3',
    );

    foreach ($values as $check)
    {
        $cmdWin->um->setParamB64('config',$check, SB_reqVal($check));
    }

    $cmdWin->um->saveConfiguration();
    $cmdWin->forwardCommand('SiteBar Settings');
}

?>
