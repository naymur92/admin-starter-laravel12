async function printElement(id, title, orientation = 'portrait') {
    var elementToPrint = document.getElementById(id);
    if (!elementToPrint) {
        console.error('Element with id "' + id + '" not found.');
        return;
    }

    // Create a spinning loader with Font Awesome icon
    var loader = document.createElement('div');
    loader.style.position = 'fixed';
    loader.style.top = '50%';
    loader.style.left = '50%';
    loader.style.transform = 'translate(-50%, -50%)';
    loader.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size: 4em; color: #3498db;"></i>';
    document.body.appendChild(loader);

    // Wait for 2 seconds before initiating printing
    setTimeout(function () {
        printWithLoader(elementToPrint, title, orientation, cssFiles, fontUrls, loader);
    }, 1000);

    // Listen for "afterprint" event to remove loader immediately after canceling print
    window.addEventListener('afterprint', function () {
        document.body.removeChild(loader);
    });

    // Function to print content with loader
    function printWithLoader(elementToPrint, title, orientation, cssFiles, fontUrls, loader) {
        // Create a new hidden iframe
        var iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        document.body.appendChild(iframe);

        // Create the HTML content to be printed
        var htmlContent = '<html><head><meta charset="utf-8"><title>' + title + '</title>';
        htmlContent += '<style>@media print{@page {size: ' + orientation + '}}</style>';
        // fontUrls.forEach(function (url) {
        //     htmlContent += '<link rel="stylesheet" type="text/css" href="' + url + '">';
        // });
        cssFiles.forEach(function (file) {
            htmlContent += '<link rel="stylesheet" type="text/css" href="' + file + '">';
        });
        htmlContent += '</head><body>';
        htmlContent += elementToPrint.innerHTML; // Append the innerHTML of the target element
        htmlContent += '</body></html>';

        // Write HTML content to the iframe
        iframe.contentDocument.open();
        iframe.contentDocument.write(htmlContent);
        iframe.contentDocument.close();

        // Wait for the iframe content to load
        iframe.onload = function () {
            // Print the iframe content
            iframe.contentWindow.print();

            // Remove the iframe and loader after printing
            setTimeout(function () {
                document.body.removeChild(iframe);
                document.body.removeChild(loader);
            }, 100); // Adjust delay as needed
        };
    }
}

function swalConfirmationOnSubmit(event, msg) {
    event.preventDefault();

    Swal.fire({
        title: msg,
        showDenyButton: true,
        confirmButtonText: "Yes",
        denyButtonText: `No`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            event.target.submit();
        }
    });
}

function convertToEnglishNumber(bnNumber) {
    if (!containsBanglaDigits(bnNumber)) return bnNumber;

    const bnDigits = '০১২৩৪৫৬৭৮৯';
    const enDigits = '0123456789';
    return bnNumber.split('').map(char => enDigits[bnDigits.indexOf(char)] || char).join('');
}

function convertToBengaliNumber(num) {
    const bnNumbers = ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'];
    return num.toString().split('').map(char => {
        const parsedChar = parseInt(char);
        if (!isNaN(parsedChar)) {
            return bnNumbers[parsedChar];
        }
        return char;
    }).join('');
}

function containsBanglaDigits(input) {
    // Regular expression to match any Bangla digit
    const banglaDigitRegex = /[\u09E6-\u09EF]/;

    // Test if the input contains any Bangla digit
    return banglaDigitRegex.test(input);
}