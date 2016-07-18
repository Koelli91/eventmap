$("body").on('click', '.more-button', function() {
    $(this).parent().children(".description").slideToggle("slow");
});
