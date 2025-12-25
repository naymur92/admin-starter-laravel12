$(document).ready(function () {
    var isBanglaInputAllow = false;
    // when input
    $(document).on('keypress', 'input[type="text"].custom-number-input', function (event) {
        var isNegativeAllowed = false;
        var step = $(this).attr('step') ?? 1;
        var min = $(this).attr('min');
        if (min && min < 0) {
            isNegativeAllowed = true;
        }

        const decimalIndexInStep = step.toString().indexOf('.');
        var decimalPrecision = decimalIndexInStep >= 0 ? step.toString().length - decimalIndexInStep - 1 : 0; // Change this value to set the desired decimal precision
        // console.log(decimalPrecision)

        var dotPressCount = $(this).data('dotPressCount') || 0;
        var charCode = (event.which) ? event.which : event.keyCode;
        // console.log(charCode)

        // set isBanglaTyped for manual control bangla type
        // var isBanglaTyped = $(this).data('isBanglaTyped') || false;
        var isBanglaTyped = containsBanglaDigits($(this).val());
        if (isBanglaInputAllow && ($(this).val().toString().length == 0 || $(this).val() == '-')) {
            if (charCode >= 2534 && charCode <= 2543) {
                isBanglaTyped = true;
            }

            $(this).data('isBanglaTyped', isBanglaTyped);
        }

        // when press minus button
        if (isNegativeAllowed && charCode === 45 && $(this).val().toString().length > 0) {
            if (isBanglaTyped) {
                var convertedNumber = -1 * convertToEnglishNumber($(this).val());
                $(this).val(convertToBengaliNumber(convertedNumber))
            } else {
                $(this).val(-1 * Number($(this).val()));
            }
            event.preventDefault();
            return false;
        }

        // control continuous press 0 in first position
        if (Number($(this).val()) == 0 && $(this).val().length > 0 && (charCode === 48 || charCode === 2534)) {
            event.preventDefault();
        }

        if (decimalPrecision > 0) {
            if (charCode === 46) {
                dotPressCount++;
                if (dotPressCount > 1) {
                    event.preventDefault();
                    return false;
                }
            }

            //  || (charCode >= 2534 && charCode <= 2543)
            // Allow numbers (48-57), backspace (8), and dot (46), bangla inputs (2534-2543), minus(45)
            var condition = (charCode >= 48 && charCode <= 57) || charCode === 8 || charCode === 46;
            if (isBanglaInputAllow && isBanglaTyped) var condition = condition || (charCode >= 2534 && charCode <= 2543);
            if (isNegativeAllowed) condition = condition || charCode === 45;
            if (condition) {
                // Ensure only one dot is entered
                if (charCode === 46 && $(this).val().indexOf('.') !== -1) {
                    event.preventDefault();
                    return false;
                }
                // console.log(currentValue)

                // Limit decimal precision
                var currentValue = $(this).val();
                var dotIndex = currentValue.indexOf('.');
                if (dotIndex !== -1 && currentValue.length - dotIndex > decimalPrecision) {
                    event.preventDefault();
                    return false;
                }
            } else {
                event.preventDefault();
                return false;
            }

        } else {
            var condition = charCode !== 8 && !(charCode >= 48 && charCode <= 57);
            if (isBanglaInputAllow && isBanglaTyped) var condition = condition && !(charCode >= 2534 && charCode <= 2543);
            if (isNegativeAllowed) condition = condition && charCode !== 45;
            if (condition) {
                event.preventDefault();
            }
        }

        // Store updated dotPressCount for this input field
        $(this).data('dotPressCount', dotPressCount);

    });

    // when backspace and key up/down
    $(document).on('keydown', 'input[type="text"].custom-number-input', function (event) {
        var dotPressCount = $(this).data('dotPressCount') || 0;
        // var isBanglaTyped = $(this).data('isBanglaTyped') || false;
        var isBanglaTyped = containsBanglaDigits($(this).val());
        // console.log(containsBanglaDigits($(this).val()));

        var isNegativeAllowed = false;
        var step = parseFloat($(this).attr('step') ?? 1);
        var min = $(this).attr('min');
        var max = $(this).attr('max');
        // if (max === undefined) console.log('check')
        if (min && min < 0) {
            isNegativeAllowed = true;
        }
        const decimalIndexInStep = step.toString().indexOf('.');
        var decimalPrecision = decimalIndexInStep >= 0 ? step.toString().length - decimalIndexInStep - 1 : 0;

        var charCode = event.which || event.keyCode;

        if (charCode === 8 && dotPressCount > 0) { // Backspace
            event.preventDefault();
            var inputValue = $(this).val();

            // if last digit is dot then set reset dotPressCount first
            if (inputValue.endsWith('.')) {
                dotPressCount = 0;
            }

            // slice lastt digit
            number = inputValue.slice(0, -1);

            // convert number to bangla
            if (isBanglaInputAllow && isBanglaTyped) {
                $(this).val(convertToBengaliNumber(number));
            } else {
                $(this).val(number);
            }

        }

        var value = isBanglaTyped ? Number(convertToEnglishNumber($(this).val())) : Number($(this).val());
        if (charCode === 38) {  // key up
            event.preventDefault();
            value = parseFloat((value + step).toFixed(decimalPrecision));
            if (max && value > Number(max)) value = Number(max);
            let newValue = isBanglaTyped ? convertToBengaliNumber(value) : value
            $(this).val(newValue)
        }

        if (charCode === 40) {  // key down
            event.preventDefault();
            value = parseFloat((value - step).toFixed(decimalPrecision));
            if (min && value < Number(min)) value = Number(min);
            let newValue = isBanglaTyped ? convertToBengaliNumber(value) : value
            $(this).val(newValue)
        }

        $(this).data('dotPressCount', dotPressCount);

        // Dispatch an 'input' event after modifying the value
        var inputEvent = new Event('input', { bubbles: true });
        $(this).get(0).dispatchEvent(inputEvent);
    });

});