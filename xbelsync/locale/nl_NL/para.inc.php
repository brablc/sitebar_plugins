<?php

$para['xbelsync::command::tooltip_xbelsync_merge'] = <<<_P
Indien niet aangevinkt, zal de doelmap volledig worden verwijderd voorafgaand aan de upload.
_P;

$para['xbelsync::command::tooltip_xbelsync_keep_custom_order'] = <<<_P
Indien aangevinkt, zal de volgorde van favorieten worden aangehouden zoals in Firefox. 
_P;

$para['xbelsync::command::warning'] = <<<_P
<p style='color:yellow;background:red;font-weight:bold'>
Maak een reservekopie van zowel Firefox als SiteBar favorieten (gebruik de links hieronder)
alvorens met synchronisatie te gaan experimenteren!
</p>
<p>
- Zie ook de <a href="%s" %s>Handleiding Favorieten exporteren uit Firefox</a><br>
- <a href="%s" %s>SiteBar favorieten ophalen</a>
</p>
_P;

$para['xbelsync::command::hint_merge'] = <<<_P
<p>
Het is aan te bevelen om Samenvoegen uit te zetten in de SiteBar en
de Bookmark Synchronizer. Na een verandering in de favorieten van SiteBar
moet deze daarna in Firefox worden gedownload. Na een wijziging in de
favorieten van Firefox moeten die worden ge-upload naar SiteBar.
_P;

$para['xbelsync::command::extension'] = <<<_P
<p>
Bewaar en installeer de <a target="_content" href="%s">Bookmarks Synchronizer</a>
extensie voor Firefox. Open menu "Favorieten->Synchroniseer Favorieten" en vul de
volgende velden in:</p>
_P;

$para['xbelsync::command::test'] = <<<_P
<p>
Test de instellingen door <a href="%s" %s>hier</a> te klikken. Een document in XBEL formaat
moet nu opgehaald worden. De Bookmark Synchronizer extensie toont geen enkele zinvolle
foutmelding wanneer het ophalen mislukt - het zal alleen vertellen dat het document
niet in het XBEL formaat is.
_P;

$para['xbelsync::command::setup_problem'] = <<<_P
Configuratieprobleem!

Gaat u alstublieft naar de SiteBar web interface en
kies een map voor de synchronisatie via het commando
"Gebruikers instellingen -> XBELSync instellingen"!
_P;

$para['xbelsync::command::access_denied'] = <<<_P
Toegang geweigerd!

U mist de rechten om favorieten in deze map te importeren!
_P;

?>
