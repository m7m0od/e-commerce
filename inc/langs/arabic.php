<?php

function lang($phrase)
{
        static $lang =['HOME_ADMIN'=>'الصفحة الرئيسية',
                       'CATEGORIES'=>'الاقسام',
                       'ITEMS'=>'العناصر',
                       'MEMBERS'=>'الاعضاء',
                       'STATISTICS'=>'الاحصائيات',
                       'LOGS'=>'التسجيل',
                       'PROFILE'=>'تعديل الملف',
                       'ADMIN_SETTINGS'=>'الاعدادات',
                       'ADMIN_OUT'=>'تسجيل خروج'
                     ];
    return $lang[$phrase];
}





?>