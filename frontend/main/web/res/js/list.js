/**
 * Created by Johannes Teklote on 17.07.2016.
 */
$("body").on('click', '.more-button', function() {
    $(this).parent().children(".description").slideToggle("slow");
});
