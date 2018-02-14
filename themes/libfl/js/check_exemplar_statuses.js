/*global VuFind */

/*function getExemplarStatus(exemplarID, fund) {
    return $.ajax({
        dataType: 'json',
        method: 'POST',
        async: true,
        url: VuFind.path + '/STATUS/JSON?method=getExemplarStatuses',
        data: {'id': exemplarID, 'fund':fund}
    }).promise();
}

function checkExemplarStatuses() {

    var container = $('body');

    var elements = {};
    var data = $.map(container.find('.exemplar'), function ajaxItemMap(exemplar) {
        if ($(exemplar).find('.exemplarID').length === 0) {
            return null;
        }
        var datum = $(exemplar).find('.exemplarID').val();
        if (typeof elements[datum] === 'undefined') {
            elements[datum] = $();
        }
        elements[datum] = elements[datum].add($(exemplar));
        return datum;
    });
    var fund = $('.record-info .recordID').val();
    if (!data.length || !fund.length) {
        return;
    }
    $(".ajax-availability").removeClass('hidden');
    $.each(data, function(index, exemplarID) {
        getExemplarStatus(exemplarID, fund).done(function (exemplar) {
            $.each(exemplar, function checkItemDoneEach(i, result) {
                var item = elements[result.id];
                if (!item) {
                    return;
                }
                item.find('.status').empty().append('Статус: '+result.availability_message);
                item.find('.access-method').removeClass('hidden');
            });
        });
    });
}

$(document).ready(function checkExemplarStatusReady() {
    checkExemplarStatuses();
});*/

function getExemplarStatus(exemplarGroupID, fund) {
    return $.ajax({
        dataType: 'json',
        method: 'POST',
        async: true,
        url: VuFind.path + '/STATUS/JSON?method=getGroupExemplarStatuses',
        data: {'group_id': exemplarGroupID, 'fund':fund}
    }).promise();
}

function checkExemplarStatuses() {

    var container = $('body');

    var elements = {};
    var data = $.map(container.find('.exemplar'), function ajaxItemMap(exemplar) {
        if ($(exemplar).find('.exemplarGroupID').length === 0) {
            return null;
        }
        var datum = $(exemplar).find('.exemplarGroupID').val();
        if (typeof elements[datum] === 'undefined') {
            elements[datum] = $();
        }
        elements[datum] = elements[datum].add($(exemplar));
        return datum;
    });
    var fund = $('.record-info .recordID').val();
    if (!data.length || !fund.length) {
        return;
    }
    $(".ajax-availability").removeClass('hidden');
    $.each(data, function(index, exemplarGroupID) {
        getExemplarStatus(exemplarGroupID, fund).done(function (exemplar) {
            //console.log(exemplar);
            $.each(exemplar, function checkItemDoneEach(i, result) {
                var item = elements[result.id];
                //console.log(result.exemplar);
                if (!item) {
                    return;
                }
                $.each(result.exemplar, function(exemplarId, exemplarStatus){
                    console.log('.exemplar'+exemplarId);
                    item.find('.exemplar'+exemplarId).addClass('exemplar-status-'+exemplarStatus);
                });
                //item.find('.status').empty().append('Статус: '+result.availability_message);
                item.find('.status').empty().append(result.availability_message);
                //item.find('.'+result.id).addClass('hidden');
                item.find('.access-method').removeClass('hidden');
            });
        }).fail(function (jqXHR, textStatus) {

        });
    });
}

$(document).ready(function checkExemplarStatusReady() {
    checkExemplarStatuses();
});
