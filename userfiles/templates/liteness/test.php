<?php


//echo userfiles_path();echo '<br>'; //D:\xampp\htdocs\microweber\userfiles\
//echo userfiles_url() //http://microweber.app/userfiles/ 

//echo modules_url() //http://microweber.app/userfiles/modules/ 
//echo modules_path();//D:\xampp\htdocs\microweber\userfiles\modules\ 


//http://microweber.app/userfiles/modules/footer/copyright.php
//echo modules_url().'footer/copyright.php';
//

echo MW_MODULES_FOLDER_NAME;echo '<br>';//modules
echo public_path();echo '<br>';//D:\xampp\htdocs\microweber
echo MW_USERFILES_FOLDER_NAME;echo '<br>';//userfiles;
echo TEMPLATE_URL;echo '<br>';//http://microweber.app/userfiles/templates/liteness/
echo TEMPLATE_NAME;echo '<br>';//liteness
//echo THIS_TEMPLATE_DIR; //D:\xampp\htdocs\microweber\userfiles\templates\liteness\microweber.app

/*$SITENAME = $_SERVER["SERVER_NAME"];//microweber.app

if (defined('SITE_NAME') == false) {
    define('SITE_NAME', $SITENAME);
}*/
echo SITE_SERVER_NAME;
?>