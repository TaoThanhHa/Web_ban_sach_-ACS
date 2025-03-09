    const paymentMethodSelect = document.getElementById('payment-method');
    const shippingMethodContainer = document.getElementById('shipping-method-container');
    const transferDetails = document.getElementById('transfer-details');
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('email-error');

    paymentMethodSelect.addEventListener('change', function() {
        if (this.value === 'online') {
            shippingMethodContainer.style.display = 'none';
            transferDetails.style.display = 'none';
        } else if (this.value === 'transfer') {
            shippingMethodContainer.style.display = 'block';
            transferDetails.style.display = 'block';
        } else {
            shippingMethodContainer.style.display = 'block';
            transferDetails.style.display = 'none';
        }
    });

    document.getElementById('order-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const phoneInput = document.getElementById('phone');
        const phoneError = document.getElementById('phone-error');
        const phoneValue = phoneInput.value;

        // Kiểm tra số điện thoại
        const phoneRegex = /^0\d{9}$/;
        if (!phoneRegex.test(phoneValue)) {
            phoneError.style.display = 'block';
            return;
        } else {
            phoneError.style.display = 'none';
        }

        // Kiểm tra email
        const emailValue = emailInput.value;
        const emailRegex = /^[a-zA-Z0-9._%+-]+@gmail\.com$/;
        if (!emailRegex.test(emailValue)) {
            emailError.style.display = 'block';
            return;
        } else {
            emailError.style.display = 'none';
        }

        alert('Đơn hàng của bạn đã được hoàn tất!');
    });