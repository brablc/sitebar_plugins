<?php

$para['xbelsync::command::tooltip_xbelsync_merge'] = <<<_P
Pokud není zatrženo, cílová složka bude úplně vymazána před provedení uploadu.
_P;

$para['xbelsync::command::tooltip_xbelsync_keep_custom_order'] = <<<_P
Pokud je zatrženo, bude pořadí bookmarků odpovídat pořadí ve Firefoxu.
_P;

$para['xbelsync::command::warning'] = <<<_P
<p style='color:yellow;background:red;font-weight:bold'> Proveďte zálohu Firefox i SiteBar záložek před tím, než začnete experimentovat se synchronizací! Použijte níže uvedené odkazy.</p> <p> - Viz <a href="%s" %s>Export Guide for Firefox</a><br> - <a href="%s" %s>Stáhnout SiteBar odkazy</a> </p>
_P;

$para['xbelsync::command::hint_merge'] = <<<_P
Doporučujeme vypnout slučování v SiteBaru i v Bookmark Synchronizer.
Při změne záložek v SiteBaru proveďte download v Bookmark Synchronizeru.
Při změne záložek ve Firefoxu proveďte upload v Bookmark Synchronizeru.
_P;

$para['xbelsync::command::extension'] = <<<_P
Stáhněte a nainstalujte <a target="_content" href="%s">Bookmarks Synchronizer</a> rozšíření pro Firefox. Otevřete menu "Bookmarks->Synchronize Bookmarks" a vyplňte následující hodnoty.
_P;

$para['xbelsync::command::test'] = <<<_P
Otestujte nastavení kliknutím <a href="%s" %s>zde</a>. Musí být stažen dokument v XBEL formátu. Bookmark Synchronizer bohužel nezobrazuje žádné smysluplné chybové hlášky při problémech se stahováním - pouze oznámí, že dokument není v XBEL formátu.
_P;

$para['xbelsync::command::setup_problem'] = <<<_P
Problém nastavení!

Prosím otevřete webové rozhraní SiteBaru a vyberte složku pro synchronizaci pomocí menu "Nastavení -> XBELSync Nastavení".
_P;

$para['xbelsync::command::access_denied'] = <<<_P
Pristup odepren!

Nemate pravo importovat odkazy do teto slozky!
_P;

?>
