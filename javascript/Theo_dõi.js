// Theo_dõi.js

// Hiển thị danh sách đơn hàng
function displayOrders() {
    const orderList = document.getElementById('order-list');
    orderList.innerHTML = 'Loading...'; // Hiển thị thông báo loading


    fetch('get_orders.php')
        .then(response => {
            return response.json();
        })
        .then(data => {
            if (data.error) {
                orderList.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }

            orderList.innerHTML = ''; 

            data.forEach(order => {
                const orderItem = document.createElement('div');
                orderItem.classList.add('order-item');
                // Sử dụng order_date_local nếu có, nếu không thì sử dụng order_date
                orderItem.innerHTML = `
                    <h3>Đơn hàng #${order.id_order} - ${order.order_date_local || order.order_date}</h3>
                    <p>Trạng thái: <span class="order-status">${order.order_status}</span></p>
                `;
                orderItem.addEventListener('click', () => displayOrderDetails(order.id_order));
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

 

    fetch(`get_order_details.php?id=${orderId}`)
        .then(response => {
            return response.json();
        })
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
            `;
        })
        .catch(error => {
            orderDetails.innerHTML = `<p class="error">Có lỗi xảy ra: ${error}</p>`;
        });
}

// Gọi hàm hiển thị danh sách đơn hàng khi trang được tải
window.onload = function() {
    displayOrders();
};