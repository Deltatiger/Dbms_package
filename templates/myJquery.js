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
                $('#aItemNameExists').html(result);
            });
        }
    );
    $('#aItemSubmit').click(function()  {
        // This is to use Ajax to submit the entire forum.
    });
});