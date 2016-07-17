/**
 * Created by Johannes Teklote on 17.07.2016.
 */
$(".description a").addClass('website_link');
$(".more-button").click(function(){
    alert('test');
    $(this).parent().children(".description").slideToggle("slow");
});
