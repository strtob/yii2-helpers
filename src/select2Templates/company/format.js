
var formatCompany = function (data) {

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
                                    <div class="col-2">
                                        <img src="${data.logoUrl}" style="max-width:75px; max-height:50px;" class="mt-2">                                        
                                    </div>   
        
                                    <div class="col-10">                                            
                                             
                                        <div class="row my-2">
        
                                           <div class="col-9">
                                                <i class="fa-solid fa-bars fa-lg"></i> <b>${lajax.t('Name')}</b> <div>${data.name}</div>
                                           </div>
        
                                           <div class="col-3">
                                               <b> ${lajax.t('ID')}</b> ${data.id}
                                            </div>  
                                                   
        
                                       </div>


                                       <div class="row">                                             
                                            <div class="col-9">
                                               <i class="fa-solid fa-location-dot  fa-lg"></i><b> ${lajax.t('Address')}</b> ${data.latestAddress}
                                           </div> 
                                            <div class="col-3">
                                                <b>${lajax.t('Active')}
                                                <i class="fa-solid fa-power-off fa-md ` + (data.isActive == 1 ? 'text-success' : 'text-danger') + `"></i></b>
                                           </div>
        
                                       </div>                                            
                                             
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

var formatCompanyInit = function (data) {

    console.log(data);

    if (data == undefined)
        return lajax.t('(orgin data not found!)');

    if (data.loading) {
        return data.text;
    }

    if (data.id !== '')
    {
        var markup =
                data.id + ': '
                + ': ' + data.name;

        return markup;
    } else
        return data.text;
};

// return the value/format which will be shown in select2 field after selection
var formatCompanySelection = function (data) {

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

        return formatCompanyInit(responseData);
    } else
        // return in case of selection
        return formatCompanyInit(data);
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