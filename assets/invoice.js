function cancelOrder(order_id) {
  if (!confirm("Are you sure you want to cancel this order?")) {
    return;
  }

  var xhttp = new XMLHttpRequest();
  xhttp.open("post", "../controllers/cancelController.php", true);
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.onreadystatechange = function () {
    if (this.readyState == 4) {
      if (this.status == 200) {
        try {
          var data = JSON.parse(this.responseText);
          if (data.success) {
            document.getElementById("cancel_msg").style.color = "green";
            document.getElementById("cancel_msg").innerHTML =
              "Order cancelled. Redirecting...";
            setTimeout(function () {
              window.location.href = "home.php";
            }, 1500);
          } else {
            document.getElementById("cancel_msg").style.color = "red";
            document.getElementById("cancel_msg").innerHTML = data.message;
          }
        } catch (e) {
          document.getElementById("cancel_msg").style.color = "red";
          document.getElementById("cancel_msg").innerHTML =
            "Error: " + this.responseText;
        }
      } else {
        document.getElementById("cancel_msg").style.color = "red";
        document.getElementById("cancel_msg").innerHTML =
          "Request failed: " + this.status;
      }
    }
  };
  xhttp.send("order_id=" + order_id);
}
