
var formatEntity = function (data) {

    if (data == undefined)
        return lajax.t('(orgin data not found!)');

    if (data.loading) {
        return data.text;
    }

    if (data.id !== '')
    {
        var markup = `
                    <div class="m-2">
                                
                                <div class="row my-2">
                                    <div class="col-12">
                                        <i class="fa-regular fa-file fa-lg"></i> <b>${lajax.t('Name')}</b> ${data.name}                                        
                                    </div>                                   
                                </div>
                                   
                                   
                                <div class="row my-2">
                                    <div class="col-4">
                                        <i class="fa-solid fa-bars fa-lg"></i> <b>${lajax.t('ID')}:</b> ${data.id}
                                    </div>
                                    <div class="col-8">
                                        <i class="fa-solid fa-gear fa-lg"></i> <b>${lajax.t('Type')}</b> ${data.entity_type_name}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-4">
                                        <i class="fa-solid fa-list-check  fa-lg"></i><b> ${lajax.t('Status')}</b> ${data.entity_status}
                                    </div>
                                    <div class="col-8">
                                         <i class="fa-solid fa-list-check fa-lg"></i></i> <b>${lajax.t('Further/Legal')}</b> ${data.entity_type_further}
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                `;


        return markup;
    } else
        return data.text;
};

var formatEntityInit = function (data) {

    console.log(data);

    if (data == undefined)
        return lajax.t('(orgin data not found!)');

    if (data.loading) {
        return data.text;
    }

    if (data.id !== '')
    {
        var markup =
                data.id + ': ' + data.entity_type_name + ': '
                + ': ' + data.name;

        return markup;
    } else
        return data.text;
};

// return the value/format which will be shown in select2 field after selection
var formatEntitySelection = function (data) {

    // return for init
    if (data.selected)
    {
        var select2Id = extractIdFromSelect2String(data._resultId);
        var select2IdEl = $('#' + select2Id);

        // get options
        var s2Options = select2IdEl.data('krajee-select2');

        // Access the variable content using the window object
        var s2OptionsContent = window[s2Options];

        var ajaxUrl = s2OptionsContent.ajax.url;

        // Use AJAX to get the text based on the ID
        var responseData = $.ajax({
            url: ajaxUrl,
            data: {id: data.id},
            dataType: 'JSON',
            async: false,
        }).responseJSON;

        return formatEntityInit(responseData);
    } else
        // return in case of selection
        return formatEntityInit(data);
}


var extractIdFromSelect2String = function (str) {
    var prefix = 'select2-';
    var suffix = '-result';

    // Find the index of the prefix
    var startIndex = str.indexOf(prefix);
    if (startIndex !== -1) {
        // Adjust the start index to exclude the prefix
        startIndex += prefix.length;

        // Find the index of the suffix
        var endIndex = str.indexOf(suffix, startIndex);
        if (endIndex !== -1) {
            // Extract the substring between the adjusted start index and the end index
            return str.substring(startIndex, endIndex);
        }
    }

    // Return the original string if the prefix and suffix are not found
    return str;
}