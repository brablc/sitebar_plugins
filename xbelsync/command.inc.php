<?php
/******************************************************************************
 *  SiteBar 3 - The Bookmark Server for Personal and Team Use.                *
 *  Copyright (C) 2005-2008  Ondrej Brablc <http://brablc.com/mailto?o>       *
 *                                                                            *
 *  This program is free software: you can redistribute it and/or modify      *
 *  it under the terms of the GNU Affero General Public License as published  *
 *  by the Free Software Foundation, either version 3 of the License, or      *
 *  (at your option) any later version.                                       *
 *                                                                            *
 *  This program is distributed in the hope that it will be useful,           *
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of            *
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             *
 *  GNU Affero General Public License for more details.                       *
 *                                                                            *
 *  You should have received a copy of the GNU Affero General Public License  *
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.     *
 ******************************************************************************/

$plugin = array
(
    'prefix' => 'xbelsync',

    // We provide information about authorization on following commands
    'authorization' => array
    (
        'XBELSync Settings',
    ),

    // We are hooked on following builds
    'build' => array
    (
        'User Settings',
        'XBELSync Settings',
    ),

    // We are hooked on following executions
    'command' => array
    (
        'XBELSync Settings',
    ),
);

// We get called when noone else wanted to say meaning on the given command or
// when the command is to be called during build of some other command, we
// can specify whether it is allowed in its context.
function xbelsyncIsAuthorized(&$um, $command)
{
    switch ($command)
    {
        case 'XBELSync Settings':
            return !$um->isAnonymous();
    }

    return false;
}

function xbelsyncBuildUserSettings(&$cmdWin, &$fields)
{
    $fields['XBELSync Settings'] = array('type'=>'addbutton');
}

function xbelsyncBuildXBELSyncSettings(&$cmdWin, &$fields)
{
    $url = SB_Page::absBaseUrl() . 'plugin.php?name=xbelsync';
    $target = SB_Page::target();

    $cmdWin->um->setParam('user','default_folder', $cmdWin->um->getParam('user','xbelsync_nid'));

    $fields['-raw-xbelsync-4-'] = SB_P('xbelsync::command::warning', array
    (
        'http://sitebar.org/gs_export_ff.php', $target,
        'index.php?hits=0&w=xbel&mode=download', $target,
    ));

    $fields['Synchronize Folder'] = array('type'=>'callback','function'=>'_buildAddBookmark','show_label'=>true);
    $fields['Merge on Upload'] = array
    (
        'name'=>'xbelsync_merge',
        'type'=>'checkbox',
        'checked'=>null,
        'title'=>SB_P('xbelsync::command::tooltip_xbelsync_merge'),
    );
    $fields['Keep Custom Order'] = array
    (
        'name'=>'xbelsync_keep_custom_order',
        'type'=>'checkbox',
        'checked'=>null,
        'title'=>SB_P('xbelsync::command::tooltip_xbelsync_keep_custom_order'),
    );

    if (!$cmdWin->um->getParam('user','xbelsync_merge'))
    {
        unset($fields['Merge on Upload']['checked']);
    }

    if ($cmdWin->um->getParam('user','xbelsync_keep_custom_order')=="N")
    {
        unset($fields['Keep Custom Order']['checked']);
    }

    $fields['-raw-xbelsync-3-'] = SB_P('xbelsync::command::hint_merge');
    $fields['-raw-xbelsync-1-'] = SB_P('xbelsync::command::extension',
        array('https://addons.mozilla.org/firefox/addon/8426'));

    $parts = parse_url(SB_Page::absBaseUrl());

    $fields['Protocol '] = array('name'=>'scheme', 'value'=>strtoupper($parts['scheme']), 'disabled'=>null);
    $fields['Host '] =  array('name'=>'host', 'value'=>$parts['host'], 'disabled'=>null);
    $fields['User '] =  array('name'=>'user', 'value'=>$cmdWin->um->username, 'disabled'=>null);
    $fields['Password '] =  array('name'=>'pass', 'value'=>'********', 'disabled'=>null);
    $fields['Path '] =  array('name'=>'path', 'value'=>SB_safeVal($parts,'path').'plugin.php?name=xbelsync', 'disabled'=>null);

    $fields['-raw-xbelsync-2-'] = SB_P('xbelsync::command::test', array($url, $target));

    // Never external
    $cmdWin->um->setParam('user','extern_commander',0);
}

function xbelsyncCommandXBELSyncSettings(&$cmdWin)
{
    $cmdWin->um->setParam('user','xbelsync_merge', SB_reqVal('xbelsync_merge'));
    $cmdWin->um->setParam('user','xbelsync_keep_custom_order', SB_reqVal('xbelsync_keep_custom_order')?"Y":"N");
    $cmdWin->um->setParam('user','xbelsync_nid', SB_reqVal('nid_acl'));
    $cmdWin->um->saveUserParams();

    // Never external
    $cmdWin->um->setParam('user','extern_commander',0);
    $cmdWin->forwardCommand('User Settings');
}

?>
