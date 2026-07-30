<?php

// This should be modifed as your own use warrants.

require_once('simplepie.inc');
//Added by Kimi in C1.2.2 (code by Mark and visiblesoul)
$filetoget = preg_replace('/[^\w\.\-\/]+/', '', $_GET['i']);
SimplePie_Misc::display_cached_file($filetoget, '../../cache', 'spi');  
?>
