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

//Đổi box tác phẩm
function changeContent(element, text) {
    const chapElement = document.querySelector('.mota');
    const cmtElement = document.querySelector('.cmt');

    if (text === 'Mô tả') {
        chapElement.style.display = 'flex';
        cmtElement.style.display = 'none';
    } else if (text === 'Bình luận') {
        chapElement.style.display = 'none';
        cmtElement.style.display = 'flex';
    }

    const words = document.getElementsByClassName('word');
    for (let i = 0; i < words.length; i++) {
        words[i].classList.remove('selected');
    }

    element.classList.add('selected');
}

document.addEventListener('DOMContentLoaded', () => {
    const menuToggle = document.getElementById('menu-toggle');
    const navMenu = document.getElementById('nav-menu');

    menuToggle.addEventListener('click', () => {
        navMenu.classList.toggle('active');
    });
});