
$(document).ready(function(){
    refreshProductList();
});

function refreshProductList()
{
    var url = $('#productServiceUrl').attr('value');
    var params = {};
    var lodgingSizes = [];
    $('.lodgingSizes input:checked').each(function() {
        lodgingSizes.push($(this).attr('name'));
    });

    var lodgingCategories = [];
    $('.lodgingCategories input:checked').each(function() {
        lodgingCategories.push($(this).attr('name'));
    });

    var lodgingTypes = [];
    $('.lodgingTypes input:checked').each(function() {
        lodgingTypes.push($(this).attr('name'));
    });

    var regions = [];
    regions.push(1);

    params['lodgingSizes'] = lodgingSizes;
    params['lodgingCategories'] = lodgingCategories;
    params['lodgingTypes'] = lodgingTypes;
    params['regions'] = regions;

    console.log(params);

    $.ajax({
        url: url,
        dataType: 'jsonp',
        method: 'post',
        data: JSON.stringify(params),
        success: function(dataWeGotViaJsonp){
            var text = '';
            var len = dataWeGotViaJsonp.length;
            for(var i=0;i<len;i++){
                productEntry = dataWeGotViaJsonp[i];
                var productElement = $('#productTemplate').clone();
                productElement.find('.mainLogo').attr('src', productEntry['logo']);
                productElement.find('.product_name')
                    .html(productEntry['name'])
                    .attr('href', Routing.generate('distributium_frontend_product_show', {id : productEntry['id']}));
                productElement.find('.product_company').html('by ' + productEntry['companyName']);
                productElement.find('.productShortDescription').html(productEntry['shortDescription']);
                text += productElement.html();
            }
            $('#productList').html(text);
        }
    }); 
}

$( "input[type='checkbox']" ).change(function() {
    refreshProductList();
});
