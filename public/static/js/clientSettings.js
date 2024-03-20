$("#nClient").click(function( ){
    $("#eClient").removeClass("bg-purple-600");
    $("#nClient").addClass("bg-purple-600");
    
    $('#existingClient').removeClass('block');
    $('#existingClient').addClass('hidden');
    $('#newClient').removeClass('hidden');
    $('#newClient').addClass('block');
});

$("#eClient").click(function( ){
    $("#eClient").addClass("bg-purple-600");
    $("#nClient").removeClass("bg-purple-600");

    $('#existingClient').addClass('block');
    $('#existingClient').removeClass('hidden');
    $('#newClient').addClass('hidden');
    $('#newClient').removeClass('block');
});