function validatePayment() {
  var methods = document.querySelectorAll('input[name="payment_method"]');
  var selected = false;

  for (var i = 0; i < methods.length; i++) {
    if (methods[i].checked) {
      selected = true;
      break;
    }
  }

  if (!selected) {
    document.getElementById("err_payment").innerHTML =
      "Please select a payment method.";
    return false;
  }

  return true;
}
