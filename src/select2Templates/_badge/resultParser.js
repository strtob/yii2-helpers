


function parseBadgeResults(data, params) {

    params.page = params.page || 1;

    return {
        results: data.items,
        pagination: {more: ((params.page * 30) < data.total_count)
        }
    };
}
