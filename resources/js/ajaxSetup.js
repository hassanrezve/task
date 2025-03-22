document.addEventListener("DOMContentLoaded", function () {
    console.log('jQuery Loaded:', typeof $ !== 'undefined'); // Debugging
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': 'your_token_here'
        }
    });
});
