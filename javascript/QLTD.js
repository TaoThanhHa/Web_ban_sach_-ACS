// QLTD.js

// Hiển thị danh sách đơn hàng cho quản trị viên
function displayOrders(searchTerm = '') {
    const orderList = document.getElementById('order-list');
    orderList.innerHTML = 'Loading...'; // Hiển thị thông báo loading

    let url = 'get_all_orders.php';
    if (searchTerm) {
        url += `?searchTerm=${encodeURIComponent(searchTerm)}`;  // Thêm tham số tìm kiếm vào URL và encode
    }

    fetch(url)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                orderList.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }

            // Sắp xếp đơn hàng: 'Đã giao hàng' xuống cuối
            data.sort((a, b) => {
                if (a.order_status === 'Đã giao hàng' && b.order_status !== 'Đã giao hàng') {
                    return 1; // a xuống cuối
                }
                if (a.order_status !== 'Đã giao hàng' && b.order_status === 'Đã giao hàng') {
                    return -1; // b xuống cuối
                }
                return 0; // Giữ nguyên thứ tự
            });

            orderList.innerHTML = ''; // Xóa thông báo loading

            data.forEach(order => {
                const orderItem = document.createElement('div');
                orderItem.classList.add('order-item');
                orderItem.innerHTML = `
                    <h3>Đơn hàng #${order.id_order} - ${order.order_date}</h3>
                    <p>Khách hàng: ${order.user_name}</p>
                    <p>Trạng thái: <span class="order-status">${order.order_status}</span></p>
                    <button class="show-details-button" data-order-id="${order.id_order}">Xem chi tiết</button>
                `;
                orderList.appendChild(orderItem);

                 // Gắn sự kiện click cho phần tử orderItem (bao gồm cả nút)
                orderItem.addEventListener('click', (event) => {
                    // Kiểm tra xem người dùng có click vào nút hay không
                    if (!event.target.classList.contains('show-details-button')) {
                        // Nếu không phải nút, thì bỏ qua (hoặc có thể thực hiện hành động khác)
                        return;
                    }

                    const orderId = event.target.dataset.orderId;
                    toggleOrderDetails(orderId, orderItem);
                });
            });

           
        })
        .catch(error => {
            orderList.innerHTML = `<p class="error">Có lỗi xảy ra: ${error}</p>`;
        });
}

// Hiển thị hoặc ẩn chi tiết đơn hàng
function toggleOrderDetails(orderId, orderItem) {
    let orderDetails = orderItem.nextElementSibling;

    if (orderDetails && orderDetails.classList.contains('order-details')) {
        // Nếu đã hiển thị, ẩn nó đi
        orderDetails.remove();
        return;
    }

    orderDetails = document.createElement('div');
    orderDetails.classList.add('order-details');
    orderDetails.innerHTML = 'Loading...';
    orderItem.parentNode.insertBefore(orderDetails, orderItem.nextSibling);

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
                <p>Email khách hàng: ${data.user_email}</p>
                <h3>Sản phẩm:</h3>
                <ul>${itemsList}</ul>
                <p><strong>Tổng cộng:</strong> ${data.total_amount} VND</p>
                <button class="update-status-button" onclick="changeOrderStatus(${data.id_order})">Cập nhật trạng thái</button>
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

// Hàm tìm kiếm đơn hàng
function searchOrder() {
    const searchTerm = document.getElementById('order-search').value;
    displayOrders(searchTerm);
}

// Gọi hàm hiển thị danh sách đơn hàng khi trang được tải
window.onload = function() {
    displayOrders();
};