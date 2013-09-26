<?php

/**
 * This class is mainly used for loading the required pages to the site.
 * This avoids unwanted trouble with the mannual inclusion of files.
 *
 * @author DeltaTiger
 */
class template {
    //This holds the name of the page we want to load.
    private $pageName;
    //This is the constructor of template class. Nothing much to do.
    function __construct() {
        $pageName = '';
    }
    //This function is used to set the page name.
    public function setPageName($page)  {
        if (trim($page) != '')  {
            $this->pageName = $page;
            return true;
        }
        return false;
    }
    //This function is used to load the whole page where required.
    public function loadPage()  {
        //We have to insert the following pages.
        /*
         * 1. Header
         * 2. Navigation Bar
         * 3. Main Body
         * 4. Footer
         */
        include_once 'templates/header.html';
        include_once 'templates/navbar.html';
        include_once 'templates/'.$this->pageName.'.html';
        include_once 'templates/footer.html';
    }
}

?>
