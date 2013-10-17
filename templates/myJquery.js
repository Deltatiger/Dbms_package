/*
 * This is all the jquery that is used in the entire website.
 */

$(document).ready(function(){
    $('#aItemCat').on({
        'change' : function()    {
            // This function is used to load the value of the SubCategory
            var categoryId = $('#aItemCat').val();
            $.post('ajax/main.php', {catId : categoryId}, function (result) {
                //This loads the text into the subcategory
                
                $('#aItemSubCat').html(result);
            });
        }
    });
    $('#aItemName').bind("input", function()   {
            // This function is used to check if the item with the same name exists.
            var itemName = $('#aItemName').val();
            $.post('ajax/main.php', {iName : itemName}, function(result)    {
                // This loads the result into the message box.
                if (result != '')   {
                    // This means that the output is an error. We diable the submit button.
                    $('#aItemSubmit').attr('disabled', 'disabled');
                } else {
                    if ( itemName.trim().length == 0) { // Make sure no submit on null values.
                        $('#aItemSubmit').attr('disabled', 'disabled');
                    } else {
                        $('#aItemSubmit').removeAttr('disabled');
                    }
                }
                $('#aItemNameExists').html(result);
            });
        }
    );
        
    $('.catName').on({
        'click' : function()    {
            //Change the category to sub category
            var catId = $(this).data('catid');
            var catName = $(this).html();
            $('#leftIndexHeading').html(catName);
            $.post('ajax/main.php', {catIdShow : catId}, function(result)   {
                if ( result != '')  {
                    $('#mIndexLeftContent').html(result);
                }
            });
        }
    });
    
    $('#showCat').live('click', function()    {
        //Change subcategory back to category
        $.post('ajax/main.php', {showCat : ''}, function(result)    {
           if (result != '')    {
               $('#mIndexLeftContent').html(result);
               $('#leftIndexHeading').html('Categories');
           }
        });
    });
    
    $('.subCatName').on({
        'click' : function()    {
            //Used to load the items of the paticular category into the rightPane.
            var subCatId = $(this).data('subcatid');
            $.post('ajax/main.php', {subCatId : subCatId}, function(result) {
                if (result != '')   {
                    $('#mIndexRightContnet').html(result);
                }
            });
        }
    });
});