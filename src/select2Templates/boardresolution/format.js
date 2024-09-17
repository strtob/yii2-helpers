
var formatBoardresolution = function (data) {

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
                                    <div class="col-6">
                                        <i class="fa-solid fa-bars fa-lg"></i> <b>${lajax.t('ID')}</b> ${data.id}
                                    </div>
                                    <div class="col-6">
                                        
                                    </div>
                                </div>
                                   
                                   
                                <div class="row my-2">
                                    <div class="col-6">
                                        <i class="fa-regular fa-file fa-lg"></i> <b>${lajax.t('Title')}</b> ${data.name}
                                    </div>
                                    <div class="col-6">
                                        <i class="fa-solid fa-barcode fa-lg"></i> <b>${lajax.t('Reference No')}</b> ${data.id}
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <i class="${data.board_resolution_type_css_class} fa-lg"></i><b> ${lajax.t('Type')}</b> ${data.board_resolution_type}
                                    </div>
                                    <div class="col-6">
                                         <i class="fa-solid fa-file-signature fa-lg"></i></i> <b>${lajax.t('Signature Date')}</b> ${data.signing_date}
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

var formatBoardresolutionInit = function (data) {

    console.log(data);

    if (data == undefined)
        return lajax.t('(orgin data not found!)');

    if (data.loading) {
        return data.text;
    }

    if (data.id !== '')
    {
        var markup =
                '<i class="' + data.board_resolution_type_css_class + ' fa-lg" /></i> ' +
                data.id + ': ' + data.board_resolution_type + ': ' + data.name;

        return markup;
    } else
        return data.text;
};

// return the value/format which will be shown in select2 field after selection
var formatBoardresolutionSelection = function (data) {

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

        return formatBoardresolutionInit(responseData);
    } else
        // return in case of selection
        return formatBoardresolutionInit(data);
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