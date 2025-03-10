// QLTD.js

// Hiển thị danh sách đơn hàng cho quản trị viên
function displayOrders() {
    const orderList = document.getElementById('order-list');
    orderList.innerHTML = 'Loading...'; // Hiển thị thông báo loading

    fetch('get_all_orders.php')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                orderList.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }

            orderList.innerHTML = ''; // Xóa thông báo loading

            data.forEach(order => {
                const orderItem = document.createElement('div');
                orderItem.classList.add('order-item');
                orderItem.innerHTML = `
                    <h3>Đơn hàng #${order.id_order} - ${order.order_date}</h3>
                    <p>Người dùng: ${order.id_user}</p>
                    <p>Trạng thái: <span class="order-status">${order.order_status}</span></p>
                    <button onclick="displayOrderDetails(${order.id_order})">Xem chi tiết</button>
                `;
                orderList.appendChild(orderItem);
            });
        })
        .catch(error => {
            orderList.innerHTML = `<p class="error">Có lỗi xảy ra: ${error}</p>`;
        });
}

// Hiển thị chi tiết đơn hàng
function displayOrderDetails(orderId) {
    const orderDetails = document.getElementById('order-details');
    orderDetails.classList.remove('hidden');
    orderDetails.innerHTML = 'Loading...'; // Hiển thị thông báo loading

    fetch(`get_all_order_details.php?id=${orderId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                orderDetails.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }

            let itemsList = '';
            data.items.forEach(item => {
                itemsList += `<li>${item.book_title} - Số lượng: ${item.quantity}, Giá: ${item.price} VND</li>`;
            });

            orderDetails.innerHTML = `
                <h2>Chi tiết đơn hàng #${data.id_order}</h2>
                <p>Ngày đặt hàng: ${data.order_date}</p>
                <p>Địa chỉ giao hàng: ${data.shipping_address}</p>
                <p>Trạng thái: <span class="order-status">${data.order_status}</span></p>
                <p>Phương thức thanh toán: ${data.payment_method}</p>
                <p>Phương thức vận chuyển: ${data.shipping_method}</p>
                <p>Phí vận chuyển: ${data.shipping_fee} VND</p>
                <h3>Sản phẩm:</h3>
                <ul>${itemsList}</ul>
                <p><strong>Tổng cộng:</strong> ${data.total_amount} VND</p>
                <button onclick="changeOrderStatus(${data.id_order})">Cập nhật trạng thái</button>
            `;
        })
        .catch(error => {
            orderDetails.innerHTML = `<p class="error">Có lỗi xảy ra: ${error}</p>`;
        });
}

// Hàm cập nhật trạng thái đơn hàng
function changeOrderStatus(orderId) {
    const newStatus = prompt("Nhập trạng thái mới cho đơn hàng (ví dụ: 'Đang vận chuyển', 'Đã giao hàng'):");
    if (newStatus) {
        fetch('update_order_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `id=${orderId}&status=${newStatus}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.success);
                displayOrders(); // Cập nhật danh sách đơn hàng
            } else if (data.error) {
                alert(data.error);
            }
        })
        .catch(error => {
            alert(`Có lỗi xảy ra: ${error}`);
        });
    }
}

// Gọi hàm hiển thị danh sách đơn hàng khi trang được tải
window.onload = function() {
    displayOrders();
};