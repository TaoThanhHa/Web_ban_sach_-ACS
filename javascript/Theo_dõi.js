// Giả lập dữ liệu đơn hàng
const orders = [
    {
        id: 1,
        date: '2024-09-24',
        status: 'Đang xử lý',
        items: [
            { name: 'Sách A', quantity: 1, price: 100000 },
            { name: 'Sách B', quantity: 2, price: 150000 }
        ],
        total: 400000,
        shippingAddress: '123 Đường ABC, Thành phố XYZ'
    },
    {
        id: 2,
        date: '2024-09-20',
        status: 'Đã giao hàng',
        items: [
            { name: 'Sách C', quantity: 1, price: 200000 }
        ],
        total: 200000,
        shippingAddress: '456 Đường DEF, Thành phố XYZ'
    }
];

// Hiển thị danh sách đơn hàng
function displayOrders() {
    const orderList = document.getElementById('order-list');
    orderList.innerHTML = '';

    orders.forEach(order => {
        const orderItem = document.createElement('div');
        orderItem.classList.add('order-item');
        orderItem.innerHTML = `
            <h3>Đơn hàng #${order.id} - ${order.date}</h3>
            <p>Trạng thái: <span class="order-status">${order.status}</span></p>
        `;
        orderItem.addEventListener('click', () => displayOrderDetails(order));
        orderList.appendChild(orderItem);
    });
}

// Hiển thị chi tiết đơn hàng
function displayOrderDetails(order) {
    const orderDetails = document.getElementById('order-details');
    orderDetails.classList.remove('hidden');
    
    let itemsList = '';
    order.items.forEach(item => {
        itemsList += `<li>${item.name} - Số lượng: ${item.quantity}, Giá: ${item.price} VND</li>`;
    });

    orderDetails.innerHTML = `
        <h2>Chi tiết đơn hàng #${order.id}</h2>
        <p>Ngày đặt hàng: ${order.date}</p>
        <p>Địa chỉ giao hàng: ${order.shippingAddress}</p>
        <p>Trạng thái: <span class="order-status">${order.status}</span></p>
        <h3>Sản phẩm:</h3>
        <ul>${itemsList}</ul>
        <p><strong>Tổng cộng:</strong> ${order.total} VND</p>
    `;
}

// Gọi hàm hiển thị danh sách đơn hàng khi trang được tải
window.onload = function() {
    displayOrders();
};