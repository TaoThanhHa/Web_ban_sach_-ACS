// Dữ liệu giả lập các sản phẩm
function saveStory() {
    const products = [];
    for (let i = 1; i <= 50; i++) {
        const title = document.getElementById('story-title').value || `Sản phẩm ${i}`;
        const description = document.getElementById('story-content').value || `Mô tả sản phẩm ${i}`;
        const priceInput = document.querySelector('input[type="number"][placeholder="Nhập số tiền"]');
        const price = priceInput ? parseInt(priceInput.value) : Math.floor(Math.random() * 100000) + 50000; // Giá ngẫu nhiên
        const discountInput = document.querySelector('input[type="number"][placeholder="Phần trăm giảm"]');
        const discount = discountInput ? parseFloat(discountInput.value) : 0; // Phần trăm giảm mặc định là 0
        const quantityInput = document.querySelector('input[type="number"][placeholder="Nhập số lượng"]');
        const quantity = quantityInput ? parseInt(quantityInput.value) : 1; // Số lượng mặc định là 1

        products.push({
            id: i,
            name: title,
            description: description,
            price: price,
            discount: discount,
            quantity: quantity
        });
    }

    // In ra mảng sản phẩm ra console
    console.log(products);
}


const itemsPerPage = 10; // Số sản phẩm mỗi trang
let currentPage = 1; // Trang hiện tại

// Hiển thị danh sách sản phẩm dựa trên trang hiện tại
function displayProducts(page) {
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    const productList = document.getElementById('product-list');
    productList.innerHTML = '';

    const productsToShow = products.slice(start, end);
    productsToShow.forEach(product => {
        const productItem = document.createElement('div');
        productItem.classList.add('product-item');
        productItem.innerHTML = `
            <h3>${product.name}</h3>
            <p>Giá: ${product.price} VND</p>
        `;
        productList.appendChild(productItem);
    });
}

// Tạo nút chuyển trang
function setupPagination(totalItems, itemsPerPage) {
    const totalPages = Math.ceil(totalItems / itemsPerPage);
    const pagination = document.getElementById('pagination');
    pagination.innerHTML = '';

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.classList.add('pagination-button');
        button.innerText = i;

        if (i === currentPage) {
            button.classList.add('active-page');
        }

        button.addEventListener('click', function () {
            currentPage = i;
            displayProducts(currentPage);

            // Cập nhật nút trang hiện tại
            const buttons = document.querySelectorAll('.pagination-button');
            buttons.forEach(btn => btn.classList.remove('active-page'));
            button.classList.add('active-page');
        });

        pagination.appendChild(button);
    }
}

// Khi tải trang, hiển thị sản phẩm và cài đặt pagination
window.onload = function () {
    displayProducts(currentPage);
    setupPagination(products.length, itemsPerPage);
};
