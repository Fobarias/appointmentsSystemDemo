$(document).ready(function() {
    $("#search").keyup(function() {
        $.ajax({
            url: 'searchClient.php',
            type: 'post',
            data: {search: $(this).val()},
            success: function(result) {
                $("#result").html(result);
            }
        })
    });
  });