<?php
    Migration::alterField( 
        'roundcreatures', 
        'desire', 
        'desire', 
        'ENUM("NONE","NORTH","EAST","SOUTH","WEST") COLLATE utf8_unicode_ci NOT NULL' 
    );
    Migration::alterField( 'games', 'created', 'created', 'DATETIME NOT NULL' );
?>
