
$(document).ready(function(){

    $(".selector input").each(function(){
    
        //console.log($(this).attr('name'));

        if ($(this).prop('checked')) {
            select = $('#' + $(this).attr('name') + '_val');
            updateProductList(select);
        }
    

    });

    //refreshProductList();
});

$('.selector input').click(function() {
    select = $('#' + $(this).attr('name') + '_val');

    if (! select.is(':empty')) {
        return;
    }

    updateProductList(select);

    //select.append(selectContainer);

    //console.log(selectContainer);
});
$('.noselector input').click(function() {
    select = $('#' + $(this).attr('name') + '_val');
    select.empty();
});


$("#quiz_search").click(function(){
    alert($(this).attr("rel"));
    $.redirectPost($(this).attr("rel"), {x: 'example', y: 'abc'});

    //$().redirect($(this).attr("rel"), quizHome);

});

$('.categories div').click(function(){

    $('.categories div').removeClass('categoryClicked');
    $(this).addClass('categoryClicked');
    $('#product-options').html(getProductOptions($(this).attr('id')));
    updateProductList($('.categories .categoryClicked').attr('rel'));

    $( "#product-options .option" ).bind( "click", function() {
        input = $(this).find('input');

        $("input[type='text'][name="+input.attr('name')+"]").val(input.val());

        quizHome[input.attr('name')] = input.attr('value');
        console.log(quizHome);
        if ($(this).hasClass('selector')) {

            $('#product-options div').removeClass('hidden');
        }
        if ($(this).hasClass('next')) {
            delete quizHome["c_op_val_" + $('.categories .categoryClicked').attr('id')];
            $("input[type='text'][name=c_op_val_"+$('.categories .categoryClicked').attr('id')+"]").val('');
            console.log(quizHome);
            $('#selectContainer').addClass('hidden');
        }
    }); 
});


function refreshCustomerSelection() {
    item = $('<div>');
    $.each(quizHome, function( index, value ) {
        item.append(index + ': ' + value);
    });
    $('.customer-selection').append(item);
}

function registerSelectedProduct() {
    console.log($(this).find("option:selected").text());
    $("label[id="+$(this).attr('name')+"]").text($(this).find("option:selected").text());
    $("input[type='text'][name="+$(this).attr('name')+"]").val($(this).val());
    quizHome[$(this).attr('name')] = $(this).val();
    //refreshCustomerSelection();
    console.log(quizHome);
    //nextStep();
};

function updateProductList(selector)
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
        url: selector.attr('rel'),
        dataType: 'jsonp',
        method: 'post',
        data: JSON.stringify(params),
        success: function(dataWeGotViaJsonp){

            selectContainer = $('<div>');
            selectContainer.addClass('col-md-10');
            selectContainer.attr('id', 'selectContainer');

            select = $('<select>');
            select.attr("name", selector.attr('id'));
            select.addClass("form-control");

            select.bind("click", registerSelectedProduct);
            var len = dataWeGotViaJsonp.length;

            for(var i=0;i<len;i++){
                productEntry = dataWeGotViaJsonp[i];
                option = $('<option>');
                option.attr('value', productEntry['id']);
                option.text(productEntry['name']);
                select.append(option);
            }

            selectContainer.html(select);
            selector.append(selectContainer);
            //console.log(selector.attr('rel'));
            //$('#product-options').append(selectContainer);
            //return selectContainer;
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
    rowInput.attr('name', 'c_op_' + id);

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

    productOptions.append(row1);
    productOptions.append(row2);
    productOptions.append(row3);

    return productOptions;
}
$( "input[type='checkbox']" ).change(function() {
    refreshProductList();
});
