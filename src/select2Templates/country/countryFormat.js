
var formatCountry = function (data) {



    if (data.loading) {
        return data.text;
    }

       if (data.id !== '')
    {
        var markup =
                '<div class="row">' +
                '<div class="col-12">' +
                '<img src="/images/flags/isoAlpha2/country-4x3/' + data.isoAlpha2 + '.svg" class="select2Flags">' +
                '<span class="ms-2">' + data.name + '</span>' +
                '<span class="badge bg-secondary ms-2">' + data.currencyCode + '</span>' +
                '</div>' +
                '</div>';

        return markup;
    } else
        return data.text;


};

var formatCountrySelection = function (data) {

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

        return formatCountry(responseData);
    }

    // return in case of selection
    return formatCountry(data);

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