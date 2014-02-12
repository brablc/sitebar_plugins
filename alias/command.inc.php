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
    /**
    * Each plugin must have a unique name, this unique name is used to prefix
    * functions to avoid redefinition of functions (not allowed in PHP).
    * In this document we refer to the prefix as ${prefix}.
    */
    'prefix' => 'alias',

    /**
    * A plugin can change authorization to various commands, in the following
    * array you specify for what commands this plugin wants to change or
    * newly specify authorization.
    */
    'authorization' => array
    (
        'Alias',
        'Folder Alias',
        'Alias Settings',
    ),

    /**
    * A plugin may change context menu. Set the value to true in order to
    * make the framework to execute function ${prefix}Context. This single
    * function can modify any of the three context menus.
    */
    'context' => true,

    /**
    * A plugin can change they layout/content of existing commands or add
    * new commands - refered to as ${command} (command without spaces).
    * The framework will call function from this module with name composed
    * as ${prefix}Build${command}(&$cmdWin, &$fields).
    *
    * The reference to $fields is what is the function supposed to change.
    * The $cmdWin is a reference to the object of the Command Window.
    * You use this to access its existing objects like:
    *
    * $cmdWin->um   : User Manager object from inc/usermanager.inc.php
    * $cmdWin->tree : Tree object from inc/tree.inc.php
    *
    * If you need access to the database object from inc/database.inc.php,
    * you may get reference to the singleton object by calling
    * $db =& SB_Database::staticInstance(); or you may take existing
    * reference in both the User Manager and Tree object: $cmdWin->um->db.
    */
    'build' => array
    (
        // New commands
        'Alias',
        'Folder Alias',
        'Alias Settings',

        // Existing command - we modify them
        'Properties',
        'Folder Properties',
        'SiteBar Settings',
    ),

    /**
    * A plugin should specify what should be executed for new commands.
    * It may specify, what should be executed additionally (after) to the
    * functionality of the existing commands.
    */
    'command' => array
    (
        // New commands
        'Alias',
        'Folder Alias',
        'Alias Settings',
    ),
);

/**
* We get called when noone else wanted to say meaning on the given command or
* when the command is to be called during build of some other command, we
* can specify whether it is allowed in its context.
*/
function aliasIsAuthorized(&$um, $command, $ignoreAnonymous, $gid, &$node, &$acl, &$link)
{
    switch ($command)
    {
        case 'Alias':
            // We give the "Alias" command the same rights as "Properties" has.
            return $um->isAuthorized('Properties');
        case 'Folder Alias':
            // The same above.
            return $um->isAuthorized('Folder Properties');

        case 'Alias Settings':
            return $um->isAdmin();
    }

    return false;
}

/**
* Please go to inc/writers/sitebar.inc.php function run and check what
* does the menu arrays look like. In the following function you are
* going to modify this array (you can add, remove or modify items).
*
* The things behind colon have something to do with rights. Ask
* me if you need more details. I will write more doc about it then.
*/
function aliasContext(&$nodeMenu, &$linkMenu, &$userMenu)
{
    $tmp = array();

    foreach ($linkMenu as $item)
    {
        if (SB_safeVal($item,'name')=='Properties')
        {
            // We add new item before the command specified above
            $tmp[] = array('name'=>'Alias','acl'=>'*u');
        }
        $tmp[] = $item;
    }

    $linkMenu = $tmp;

    $tmp = array();

    foreach ($nodeMenu as $item)
    {
        if (SB_safeVal($item,'name')=='Folder Properties')
        {
            // We add new item before the command specified above
            $tmp[] = array('name'=>'Folder Alias','acl'=>'*u');
        }
        $tmp[] = $item;
    }

    $nodeMenu = $tmp;
}

/**
* Additionally to the context menu we put a pbutton on link properites
* page. Mainly only to demonstrate that we can do so.
*/
function aliasBuildProperties(&$cmdWin, &$fields)
{
    $fields['Alias'] = array('type'=>'addbutton');
}

/**
* Additionally to the context menu we put a pbutton on folder properites
* page. Mainly only to demonstrate that we can do so.
*/
function aliasBuildFolderProperties(&$cmdWin, &$fields)
{
    $fields['Folder Alias'] = array('type'=>'addbutton');
}

/**
* We show how to build a simple form. If you want to build your own
* form, it is best to check command.php and select a command that is
* closest to what you want and to copy the functionality.
*
* It is highly recommended to use SiteBar's framework functions for
* accessing of links or folders. The management of rights is complex and
* is optimized using caching of certain information.
* Bypassing the functions can lead to security problems.
*/
function aliasBuildAlias(&$cmdWin, &$fields)
{
    // Get the link object.
    $link = $cmdWin->tree->getLink(SB_reqVal('lid_acl'));

    /**
    * Store the ID for the command execution. If you want the authorization
    * to be properly checked, then you must use "lid_acl" here.
    * This will check, whether the user has right to access the link.
    */
    $fields['-hidden1-'] = array('name'=>'lid_acl','value'=>$link->id);

    // Let user specify the value
    $fields['Alias'] = array('name'=>'alias');
}

function aliasBuildFolderAlias(&$cmdWin, &$fields)
{
    // Get the node object
    $node = $cmdWin->tree->getNode(SB_reqVal('nid_acl'));

    /**
    * Store the ID for the command execution. If you want the authorization
    * to be properly checked, then you must use "nid_acl" here.
    * This will check, whether the user has right to access the folder.
    */
    $fields['-hidden1-'] = array('name'=>'nid_acl','value'=>$node->id);

    // Let user specify the value
    $fields['Folder Alias'] = array('name'=>'alias');
}

/**
* We want to customize alias plugin from "SiteBar Settings".
*/
function aliasBuildSiteBarSettings($cmdWin, &$fields)
{
    $fields['Alias Settings'] = array('type'=>'addbutton');
}

/**
*
*/
function aliasBuildAliasSettings($cmdWin, &$fields)
{
    $rewrite_path = $cmdWin->um->getParamB64('config','alias_rewrite_path', 'alias/.htrewritemap');
    $link_url = $cmdWin->um->getParamB64('config','alias_link_url', 'go.php?id=%d');
    $node_url = $cmdWin->um->getParamB64('config','alias_node_url', 'index.php?w=dir&root=%d');

    $fields['RewriteMap Txt File Path'] = array('name'=>'alias_rewrite_path', 'value'=>$rewrite_path);
    $fields['Rewrite Link URL Format'] = array('name'=>'alias_link_url', 'value'=>$link_url);
    $fields['Rewrite Folder URL Format'] = array('name'=>'alias_node_url', 'value'=>$node_url);
}

/**
* And here we execute the commands when the user pressed "Submit" button.
*/
function aliasCommandAlias(&$cmdWin)
{
    // Get the link object
    $link = $cmdWin->tree->getLink(SB_reqVal('lid_acl'));

    $cmdWin->um->warn('Your alias was "%s" you can do whatever you like with it!', SB_reqVal('alias'));
}

function aliasCommandFolderAlias(&$cmdWin)
{
    // Get the node object
    $node = $cmdWin->tree->getLink(SB_reqVal('nid_acl'));

    $cmdWin->um->warn('Your alias was "%s" you can do whatever you like with it!', SB_reqVal('alias'));
}

function aliasCommandAliasSettings(&$cmdWin)
{
    $values = array
    (
        'alias_rewrite_path',
        'alias_link_url',
        'alias_node_url',
    );

    foreach ($values as $check)
    {
        $cmdWin->um->setParamB64('config',$check, SB_reqVal($check));
    }

    $cmdWin->um->saveConfiguration();
    $cmdWin->forwardCommand('SiteBar Settings');
}

?>
