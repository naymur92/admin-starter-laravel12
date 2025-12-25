$(document).ready(function () {
    // let datepickerVal = document.getElementById('datepicker').value ?? '';
    let datepickerVal = $('.datepicker').val() ?? '';
    // datepicker interactions
    $(".date-selector-icon").on('click', function () {
        var dateInput = $(this).prev();
        // var hasFocus = dateInput.is(':focus');
        dateInput.focus();
        // if (!hasFocus) {
        // } else {
        //     dateInput.focusout();
        // }

        // console.log(dateInput.val())
    })

    // datepicker initializations
    $("#monthpicker").datepicker({
        format: "yyyy-mm",
        viewMode: "months",
        minViewMode: "months",
        orientation: "auto",
        autoClose: true
    });
    $("#yearpicker").datepicker({
        format: "yyyy",
        viewMode: "years",
        minViewMode: "years",
        orientation: "auto",
        autoClose: true
    });
    $("#datepicker").datepicker({
        format: "yyyy-mm-dd",
        orientation: "auto",
        autoClose: true,
        todayHighlight: true,
        value: datepickerVal
    });
    $(".datepicker-selector").datepicker({
        format: "yyyy-mm-dd",
        orientation: "auto",
        autoClose: true,
        todayHighlight: true
    })


    // bangla datepicker
    $(".datepicker-selector-bangla").datepicker({
        format: "yyyy-mm-dd",
        orientation: "auto",
        autoClose: true,
        todayHighlight: true,
        maxViewMode: "years",
        language: "bn",
    }).on('show', function (e) {
        $('.datepicker-days .day, .datepicker-switch, .datepicker-months .month, .datepicker-years .year, .datepicker-decades .decade').each(function () {
            var $this = $(this);
            $this.text(convertToBengaliNumber($this.text()));
        });
    }).on('changeMonth', function (e) {
        $('.datepicker-switch').each(function () {
            var $this = $(this);
            $this.text(convertToBengaliNumber($this.text()));
        });
    }).on('changeYear', function (e) {
        $('.datepicker-switch').each(function () {
            var $this = $(this);
            $this.text(convertToBengaliNumber($this.text()));
        });
    }).on('changeDecade', function (e) {
        $('.datepicker-switch').each(function () {
            var $this = $(this);
            $this.text(convertToBengaliNumber($this.text()));
        });
    });
})