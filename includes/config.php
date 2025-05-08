<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'l1j_remastered');

// Website Configuration
define('SITE_TITLE', 'L1J Remastered Database');
define('SITE_DESCRIPTION', 'A database browser for the L1J Remastered game data with admin CRUD functionality');

// Color Scheme (as specified in the requirements)
define('COLOR_TEXT', '#ffffff');
define('COLOR_BACKGROUND', '#030303');
define('COLOR_PRIMARY', '#080808');
define('COLOR_SECONDARY', '#0a0a0a');
define('COLOR_ACCENT', '#e07c4f');

// Categories
define('CATEGORIES', [
    'weapons' => 'Weapons',
    'armor' => 'Armor',
    'items' => 'Items',
    'monsters' => 'Monsters',
    'maps' => 'Maps',
    'dolls' => 'Dolls',
    'npcs' => 'NPCs',
    'skills' => 'Skills',
    'polymorph' => 'Polymorph'
]);