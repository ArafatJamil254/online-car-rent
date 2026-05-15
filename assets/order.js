function calcCost() {
  var car_id = document.getElementById("car_id").value;
  var start_date = document.getElementById("start_date").value;
  var end_date = document.getElementById("end_date").value;

  if (start_date == "" || end_date == "") {
    document.getElementById("cost_display").innerHTML = "";
    return;
  }

  var xhttp = new XMLHttpRequest();
  xhttp.open(
    "get",
    "../api/calculate_cost.php?car_id=" +
      car_id +
      "&start_date=" +
      start_date +
      "&end_date=" +
      end_date,
    true,
  );
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      var data = JSON.parse(this.responseText);
      if (data.error) {
        document.getElementById("cost_display").innerHTML =
          "<span style='color:red;'>" + data.error + "</span>";
      } else {
        document.getElementById("cost_display").innerHTML =
          "Total Cost: <strong>BDT " +
          data.total_cost +
          "</strong> (" +
          data.days +
          " day/s)";
      }
    }
  };
  xhttp.send();
}

function validateOrder() {
  var start = document.getElementById("start_date").value;
  var end = document.getElementById("end_date").value;
  var today = new Date().toISOString().split("T")[0];
  var valid = true;

  document.getElementById("err_start").innerHTML = "";
  document.getElementById("err_end").innerHTML = "";

  if (start == "") {
    document.getElementById("err_start").innerHTML = "Start date is required.";
    valid = false;
  } else if (start < today) {
    document.getElementById("err_start").innerHTML =
      "Start date cannot be in the past.";
    valid = false;
  }

  if (end == "") {
    document.getElementById("err_end").innerHTML = "End date is required.";
    valid = false;
  } else if (end <= start) {
    document.getElementById("err_end").innerHTML =
      "End date must be after start date.";
    valid = false;
  }

  return valid;
}
