document.getElementById('cart').addEventListener('click', function() {
    window.location.href = 'Giỏ_hàng.php';
});

const countElement = document.getElementById('count');
const decreaseButton = document.getElementById('decrease');
const increaseButton = document.getElementById('increase');
const quantityInput = document.getElementById('quantity');

decreaseButton.addEventListener('click', () => {
    let currentCount = parseInt(countElement.textContent);
    if (currentCount > 1) {
        countElement.textContent = currentCount - 1;
        quantityInput.value = currentCount - 1;
    }
});

increaseButton.addEventListener('click', () => {
    let currentCount = parseInt(countElement.textContent);
    countElement.textContent = currentCount + 1;
    quantityInput.value = currentCount + 1;
});

document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');

    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });
});