var input = document.getElementsByName("phoneInput"),
    form = document.querySelector("form"),
    result = document.querySelector("#result");

var iti = intlTelInput(input, {
  initialCountry: "tw"
});

form.addEventListener("submit", function(e) {
  e.preventDefault();
  var num = iti.getNumber(),
      valid = iti.isValidNumber();
  result.textContent = "Number: " + num + ", valid: " + valid;
}, false);

input.addEventListener("focus", function() {
  result.textContent = "";
}, false);