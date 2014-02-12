<?php

$para['xbelsync::command::tooltip_xbelsync_merge'] = <<<_P
Si cette option n'est pas sélectionnée, le dossier destination sera totalement vidé avant le téléchargement.
_P;

$para['xbelsync::command::tooltip_xbelsync_keep_custom_order'] = <<<_P
Permet de conserver l'ordre des favoris tel qu'il est dans Firefox.
_P;

$para['xbelsync::command::warning'] = <<<_P
<p style='color:yellow;background:red;font-weight:bold'>
N'oubliez pas de faire une sauvegarde de vos favoris de Firefox ainsi que de Sitebar (utilisez les liens ci-dessous) avant de tester la synchronisation!
</p>
<p>
- Voir le <a href="%s" %s>Guide d'exportation pour Firefox</a><br>
- <a href="%s" %s>Télécharger les favorus SiteBar</a>
</p>
_P;

$para['xbelsync::command::hint_merge'] = <<<_P
<p>
Il est recommandé de désactiver la Fusion dans SiteBar ainsi que dans le Bookmark Synchronizer.
Lorsque vous faites des modifications dans SiteBar, téléchargez vos changements dans Firefox. Lorsque vous faites des modifications dans Firefox, envoyez vos changements vers SiteBar.
_P;

$para['xbelsync::command::extension'] = <<<_P
<p>
Téléchargez et installez l'extension <a href='%s'>Bookmarks Synchronizer</a>
pour Firefox. Ouvrez le menu "Marque-pages->Synchronisation" et indiquez les valeurs suivantes:</p>
_P;

$para['xbelsync::command::test'] = <<<_P
<p>
Testez les paramètres en cliquant <a href="%s" %s>ici</a>. Un document au format XBEL devrait être téléchargé.
L'extension de synchronisation des favoris n'affichera pas de messages d'erreur valides si ce téléchargement échoue - elle indiquera
uniquement que le document n'est pas au format XBEL.
_P;

$para['xbelsync::command::setup_problem'] = <<<_P
Problème de configuration!

Veuillez ouvrir l'interface web de SiteBar et sélectionner le dossier
pour la synchronisation en utilisant la commande "Paramètres utilisateurs -> Paramètres XBELSync"!
_P;

$para['xbelsync::command::access_denied'] = <<<_P
Accès refusé!

Vous n'avez pas les droits d'import de favoris vers ce dossier!
_P;

?>
