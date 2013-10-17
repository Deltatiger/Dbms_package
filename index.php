<?php
    //This is the main file.
    include 'includes/config.php';
    
    $sql = "SELECT `c_name`, `c_id` FROM `{$db->name()}`.`dbms_category`";
    $query = $db->query($sql);
    $catOptions = '<ul id="catOptions">';
    while($row = $db->result($query))   {
        $catOptions .= '<li> <a href="#" id="catName" data-catId="'.$row->c_id.'">'.$row->c_name.'</a></li>';
    }
    $catOptions .= '<ul>';
    $template->setTemplateVar('catagories', $catOptions);
    
    $template->setPage('index');
    $template->setPageTitle('myKart - Home Page');
    
    $template->loadPage();
?>

