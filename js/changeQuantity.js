function changeQuantity(amount, inputId) {
    var input = document.getElementById(inputId);
    var value = parseInt(input.value) + amount;
    if (value >= 1 && value <= 30) {
        input.value = value;
    }
}