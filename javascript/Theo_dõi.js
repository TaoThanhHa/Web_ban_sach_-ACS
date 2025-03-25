// Hiển thị danh sách đơn hàng
function displayOrders(searchTerm = '') {
    const orderList = document.getElementById('order-list');
    orderList.innerHTML = 'Loading...';


    fetch('get_orders.php')
        .then(response => {
            return response.json();
        })
        .then(data => {
            if (data.error) {
                orderList.innerHTML = `<p class="error">${data.error}</p>`;
                return;
            }

            // Lọc đơn hàng theo searchTerm
            const filteredOrders = data.filter(order => {
                return String(order.id_order).includes(searchTerm); 
            });

            filteredOrders.sort((a, b) => {
                if (a.order_status === 'Đã giao hàng' && b.order_status !== 'Đã giao hàng') {
                    return 1; 
                }
                if (a.order_status !== 'Đã giao hàng' && b.order_status === 'Đã giao hàng') {
                    return -1; 
                }
                return 0;
            });

            orderList.innerHTML = '';

            filteredOrders.forEach(order => {
                const orderItem = document.createElement('div');
                orderItem.classList.add('order-item');
                orderItem.innerHTML = `
                    <h3>Đơn hàng #${order.id_order} - ${order.order_date_local || order.order_date}</h3>
                    <p>Trạng thái: <span class="order-status">${order.order_status}</span></p>
                `;
                orderItem.addEventListener('click', () => displayOrderDetails(order.id_order, orderItem)); // Truyền orderItem
                orderList.appendChild(orderItem);
            });
        })
        .catch(error => {
            orderList.innerHTML = `<p class="error">Có lỗi xảy ra: ${error}</p>`;
        });
}

// Hiển thị chi tiết đơn hàng
function displayOrderDetails(orderId, orderItem) {
    let orderDetails = orderItem.nextElementSibling;

    if (orderDetails && orderDetails.classList.contains('order-details')) {
        orderDetails.remove();
        return;
    }

    orderDetails = document.createElement('div');
    orderDetails.classList.add('order-details');
    orderDetails.innerHTML = 'Loading...';
    orderItem.parentNode.insertBefore(orderDetails, orderItem.nextSibling); 

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

// Hàm tìm kiếm đơn hàng
function searchOrder() {
    const searchTerm = document.getElementById('order-search').value;
    displayOrders(searchTerm);
}

// Gọi hàm hiển thị danh sách đơn hàng khi trang được tải
window.onload = function() {
    displayOrders();
};