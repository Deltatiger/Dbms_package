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
});