
$(document).ready(function(){
    //refreshProductList();
});

$('.categories div').click(function(){

    $('.categories div').removeClass('categoryClicked');
    $(this).addClass('categoryClicked');
    $('#product-options').html(getProductOptions($(this).attr('id')));
    updateProductList($('.categories .categoryClicked').attr('rel'));

    $( "#product-options .option" ).bind( "click", function() {
        if ($(this).hasClass('selector')) {

            $('#product-options div').removeClass('hidden');
            //console.log(productList);
        }
        if ($(this).hasClass('next')) {
            $('#selectContainer').addClass('hidden');
        }
    }); 
});


function updateProductList(url)
{
    var params = {};
    var lodgingSizes = [];
    var lodgingCategories = [];
    var lodgingTypes = [];
    var regions = [];
    regions.push(1);

    params['lodgingSizes'] = lodgingSizes;
    params['lodgingCategories'] = lodgingCategories;
    params['lodgingTypes'] = lodgingTypes;
    params['regions'] = regions;

    $.ajax({
        url: url,
        dataType: 'jsonp',
        method: 'post',
        data: JSON.stringify(params),
        success: function(dataWeGotViaJsonp){

            selectContainer = $('<div>');
            selectContainer.addClass('col-md-3');
            selectContainer.attr('id', 'selectContainer');
            selectContainer.addClass('hidden');

            select = $('<select>');
            select.addClass("form-control");
            var len = dataWeGotViaJsonp.length;

            for(var i=0;i<len;i++){
                productEntry = dataWeGotViaJsonp[i];
                option = $('<option>');
                option.attr('name', productEntry['id']);
                option.text(productEntry['name']);
                select.append(option);
            }

            selectContainer.html(select);
            $('#product-options').append(selectContainer);
        }
    });
}


function getProductOptions(id) {
    var names = [];
    names[1] = 'Channel Manager';
    names[2] = 'IBE';
    names[3] = 'PMS';
    names[4] = 'RMS';

    productOptions = $('<div>');
    productOptions.addClass('col-md-3');
    productOptions.addClass('col-md-offset-2');

    rowInput = $('<input>');
    rowInput.attr('type', 'radio');
    rowInput.attr('name', 'tools_' + names[id]);

    row1 = $('<div>');
    row1.addClass('row option selector');
    input = rowInput.clone();
    input.attr('value', 1);
    row1.append(input);
    row1.append('Quiero mantener mi ' + names[id] + ' actual');
    
    row2 = $('<div>');
    row2.addClass('row option selector');
    input = rowInput.clone();
    input.attr('value', 2);
    row2.append(input);
    row2.append('Quiero cambiar mi ' + names[id] + ' actual');
    
    row3 = $('<div>');
    row3.addClass('row option selector');
    input = rowInput.clone();
    input.attr('value', 3);
    row3.append(input);
    row3.append('No tengo ' + names[id] + ' y necesito uno');
    
    row4 = $('<div>');
    row4.addClass('row option next');
    input = rowInput.clone();
    input.attr('value', 4);
    row4.append(input);
    row4.append('No tengo ' + names[id] + ' y no necesito tenerlo');

    productOptions.append(row1);
    productOptions.append(row2);
    productOptions.append(row3);
    productOptions.append(row4);

    return productOptions;
}
$( "input[type='checkbox']" ).change(function() {
    refreshProductList();
});
