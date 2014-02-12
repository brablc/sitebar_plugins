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

require_once('./inc/errorhandler.inc.php');
require_once('./inc/page.inc.php');
require_once('./inc/usermanager.inc.php');
require_once('./inc/tree.inc.php');

$um =& SB_UserManager::staticInstance();
$tree =& SB_Tree::staticInstance();

if ($um->isAnonymous())
{
    $email = '';
    $pass = '';

    if (!isset($_SERVER['PHP_AUTH_USER']))
    {
        header("HTTP/1.1 401 Unauthorized");
        header("WWW-Authenticate: Basic realm=\"SiteBar\"");

        if (!isset($_SERVER['PHP_AUTH_USER']))
        {
            echo "Password protected page!";
        }
        exit;
    }
    else
    {
        $email = $_SERVER['PHP_AUTH_USER'];
        $pass = $_SERVER['PHP_AUTH_PW'];

        if ($pass=='')
        {
            header("HTTP/1.1 401 Unauthorized");
            echo "Missing password!";
            unset($_SERVER['PHP_AUTH_USER']);
            unset($_SERVER['PHP_AUTH_PW']);
            exit;
        }

        if (!$um->login($email, $pass))
        {
            header("HTTP/1.1 401 Unauthorized");
            echo "Wrong username or password!";
            unset($_SERVER['PHP_AUTH_USER']);
            unset($_SERVER['PHP_AUTH_PW']);
            exit;
        }
    }
}

$root = SB_reqChk('root')?SB_reqVal('root'):$um->getParam('user','xbelsync_nid');

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    SB_setVal('w', SB_reqVal('get',false,'xbel_mozilla'));
    SB_setVal('exr', 1);
    if (!SB_reqChk('hits'))
    {
        SB_setVal('hits', 0);
    }
    SB_setVal('root', $root);
    require('./index.php'); // from root
}
else
{
    header('Content-type: text/plain');

    if ($root=='')
    {
        echo SB_P('xbelsync::command::setup_problem');
        exit;
    }

    if (!$um->isAuthorized('Import Bookmarks', false, null, $root, null))
    {
        echo $root;
        echo SB_P('xbelsync::command::access_denied');
        exit;
    }

    require_once('./inc/loader.inc.php');

    $bm = new SB_Loader();
    $type = SB_reqVal('loader');

    $bm->loadFile('php://input', SB_reqVal('put', false,'xbel'));

    // If not loaded message will be recorded and we go out
    if ($bm->success)
    {
        // This here ensures, that after the first upload it is possible to go back
        if (!$um->getParam('user','xbelsync_merge')
        &&   $um->isAuthorized('Delete Folder', false, null, $root, null))
        {
            // Cleanup
            $tree->purgeNode($root);

            // For potential recovery
            $tree->removeNode($root, true);
        }

        $tree->syncMode = true;
        $tree->syncColumns = array('url','name','comment');

        $keepCustomOrder = ($um->getParam('user','xbelsync_keep_custom_order')!="N");

        if ($keepCustomOrder)
        {
            $bm->root->sort_mode = 'custom';
        }
        $tree->importTree(
            $root,
            $bm->root,
            false,
            ($keepCustomOrder?'linkCallBackCounter':'linkCallBackRemover'),
            ($keepCustomOrder?'nodeCallBackCounter':null));
    }

    if ($um->hasErrors())
    {
        foreach ($um->getErrors() as $err)
        {
            if ($err[0] == E_ERROR)
            {
                echo $err[1];
            }
        }
    }
}

$customOrder = 0;

function removeHitCounter(&$link)
{
    if (strstr($link->url,'go.php?'))
    {
        $link->url = preg_replace('/^.*\/go\.php\?\w*id=\d+&url=/', '', $link->url);
    }
}

function linkCallBackRemover($link)
{
    removeHitCounter($link);
    return $link;
}

function linkCallBackCounter($link)
{
    global $customOrder;
    $customOrder++;
    removeHitCounter($link);
    $link->order = $customOrder;
    return $link;
}

function nodeCallBackCounter($node)
{
    global $customOrder;
    $customOrder++;
    $node->order = $customOrder;
    $node->sort_mode = 'custom';
    return $node;
}


?>
