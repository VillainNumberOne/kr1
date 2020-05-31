$(document).ready(function(){
  $("button").click(function(){
    $.ajax({
      url: "addindicator.php",
      type: "get",
      data: {
        indicator: document.getElementById("indicator").value,
        period: Math.abs(document.getElementById("period").value),
        apply: document.getElementById("apply").value,
        color: document.getElementById("color").value
      },
      success: function(response) {
        location.reload();
        return false;
      },
      error: function(xhr) {
        console.log("not success");
      }
    });
  });
});

function delete_indicators(){
  $.ajax({
    url: "assets/php/delindicators.php",
    success: function(response) {
      location.reload();
      return false;
    },
    error: function(xhr) {
      console.log("not success");
    }
  });
}
