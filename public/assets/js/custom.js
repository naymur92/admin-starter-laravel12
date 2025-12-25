function setUnsetIsInvalidToSelect2Container(selector = null, operation = "add") {
    var newSelector = '.form-control.is-invalid.select2-hidden-accessible';
    if (selector != null && selector.length > 0) newSelector = '#' + selector

    // Select the element with the specific class and parent structure
    $('.select2-container.select2-container--default').each(function () {
        // Check if the previous sibling has the specified class
        if ($(this).prev(newSelector).length) {
            // Find the child with the specified class and add the 'is-invalid' class to it
            if (operation == "add") {
                $(this).find('.select2-selection.select2-selection--single').addClass('is-invalid');
                $(this).find('.select2-selection.select2-selection--multiple').addClass('is-invalid');
            } else {
                $(this).find('.select2-selection.select2-selection--single').removeClass('is-invalid');
                $(this).find('.select2-selection.select2-selection--multiple').removeClass('is-invalid');
            }
        }
    });
}
$(document).ready(function () {
    setUnsetIsInvalidToSelect2Container();
});

const sleep = ms =>
    new Promise(resolve => setTimeout(resolve, ms));