<?php

function lang($phrase)
{
    static $lang =['HOME_ADMIN'=>'Home',
                   'CATEGORIES'=>'Categories',
                   'ITEMS'=>'Items',
                   'MEMBERS'=>'Members',
                   'STATISTICS'=>'Statistics',
                   'COMMENTS'=>'Comments',
                   'LOGS'=>'Logs',
                   'PROFILE'=>'Edit Profile',
                   'ADMIN_SETTINGS'=>'Settings',
                   'ADMIN_OUT'=>'LogOut'
                 ];
    return $lang[$phrase];
}





?>