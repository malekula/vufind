/*global VuFind */

// function getBookStatus(bookID) {
//     return $.ajax({
//         dataType: 'json',
//         method: 'POST',
//         async: true,
//         url: VuFind.path + '/STATUS/JSON?method=getBookStatuses',
//         data: {'id': bookID}
//     }).promise();
// }
//
// function checkBookStatuses() {
//
//     var container = $('body');
//
//     var elements = {};
//     var data = $.map(container.find('.ajaxItem'), function ajaxItemMap(record) {
//         if ($(record).find('.hiddenId').length === 0) {
//             return null;
//         }
//         var datum = $(record).find('.hiddenId').val();
//         if (typeof elements[datum] === 'undefined') {
//             elements[datum] = $();
//         }
//         elements[datum] = elements[datum].add($(record));
//         return datum;
//     });
//     if (!data.length) {
//         return;
//     }
//     $(".ajax-availability").removeClass('hidden');
//     $.each(data, function(index, bookID) {
//         getBookStatus(bookID).done(function (book) {
//             $.each(book, function checkItemDoneEach(i, result) {
//                 var item = elements[result.id];
//                 if (!item) {
//                     return;
//                 }
//                 item.find('.status').empty().append(result.availability_message);
//                 item.find('.access-method').removeClass('hidden');
//             });
//         });
//     });
// }
//
// $(document).ready(function checkBookStatusReady() {
//     checkBookStatuses();
// });

function getBookStatus(bookID) {
    return $.ajax({
        dataType: 'json',
        method: 'POST',
        async: true,
        url: VuFind.path + '/STATUS/JSON?method=getBookStatuses',
        data: {'id': bookID}
    }).promise();
}

function checkBookStatuses() {
    var bookID = $('.hiddenId').val();
    getBookStatus(bookID)
    .done(function (result) {
        console.log(result);
        $.each(result.data, function (index, info) {
            $('.m' + info[0]).addClass(info[2].toLowerCase());
        });
    })
}

$(document).ready(function checkBookStatusReady() {
    checkBookStatuses();
});
