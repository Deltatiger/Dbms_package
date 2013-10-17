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
        
    $('.catName').live('click' , function()    {
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
    );
    
    $('#showCat').live('click', function()    {
        //Change subcategory back to category
        $.post('ajax/main.php', {showCat : ''}, function(result)    {
           if (result != '')    {
               $('#mIndexLeftContent').html(result);
               $('#leftIndexHeading').html('Categories');
           }
        });
    });
    
    $('.subCatName').live('click', function()    {
            //Used to load the items of the paticular category into the rightPane.
            var subCatId = $(this).data('subcatid');
            $.post('ajax/main.php', {subCatId : subCatId}, function(result) {
                if (result != '')   {
                    $('#mRightIndexContent').html(result);
                }
            });
        }
    );
    
    $('#sellerAddItem').on({
        'click' : function()    {
            //Used to href to the page with add item code.
            window.location = 'addItem.php';
        }
    });
    
    /* On Hold
    $('#sellerUpdateStock').on({
        'click' : function()    {
            //Have to load the items to the right side and ask to update.
            window.location = 'addItem.php';
        }
    });*/
    
    $('#sellerViewStats').on({
        'click' : function()    {
            //Used to show the stats of all the items in a table.
            $.post('ajax/main.php', {showSellerStats : true} , function(result) {
                $('#mSellerRight').html(result);
            });
        }
    });
});