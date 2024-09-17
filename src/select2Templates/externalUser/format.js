
var formatExternalUser = function (data) {

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
                                        <span class="fs-16">${data.company_name}</span>                                
                                    </div>
                                    <div class="col-6">
                                        <span class="text">${data.first_name} ${data.last_name}</span>                                
                                    </div> 
                                    <div class="col-6">
                                        <span class="text">${data.email}</span>                                
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

var formatExternalUserInit = function (data) {

    if (data == undefined)
        return lajax.t('(origin data not found!)');

    if (data.loading) {
        return data.text;
    }

    if (data.id !== '')
    {
        var markup = data.company_name + ' (' + (data.second_name + ' ' + data.first_name).trim() + ')';

        return markup;
    } else
        return data.text;
};

// return the value/format which will be shown in select2 field after selection
var formatExternalUserSelection = function (data) {

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

        return formatExternalUserInit(responseData);
    } else
        // return in case of selection
        return formatExternalUserInit(data);
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