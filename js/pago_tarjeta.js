document.getElementById('payment-form').addEventListener('submit', function(event) {
    var cardNumber = document.getElementById('numero_tarjeta').value;
    var expiryDate = document.getElementById('fecha_expiracion').value;
    var cvv = document.getElementById('cvv').value;

    var cardNumberPattern = /^\d{16}$/;
    var expiryDatePattern = /^(0[1-9]|1[0-2])\/\d{2}$/;
    var cvvPattern = /^\d{3}$/;

    if (!cardNumberPattern.test(cardNumber)) {
        alert('El número de tarjeta debe tener 16 dígitos.');
        event.preventDefault();
    }

    if (!expiryDatePattern.test(expiryDate)) {
        alert('La fecha de expiración debe estar en formato MM/AA.');
        event.preventDefault();
    }

    if (!cvvPattern.test(cvv)) {
        alert('El CVV debe tener 3 dígitos.');
        event.preventDefault();
    }
});
