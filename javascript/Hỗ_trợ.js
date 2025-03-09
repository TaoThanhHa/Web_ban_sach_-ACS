const supportRequests = [];

document.getElementById('supportForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const name = document.getElementById('customerName').value;
    const email = document.getElementById('customerEmail').value;
    const message = document.getElementById('supportMessage').value;

    const requestId = supportRequests.length + 1; // Tạo ID yêu cầu mới
    const request = {
        id: requestId,
        name,
        email,
        message,
        status: 'Đang chờ xử lý'
    };

    supportRequests.push(request);
    alert(`Yêu cầu hỗ trợ đã được gửi! ID yêu cầu của bạn là: ${requestId}`);
    this.reset(); // Xóa các trường sau khi gửi
});

document.getElementById('trackButton').addEventListener('click', function() {
    const requestId = parseInt(document.getElementById('trackRequest').value);
    const request = supportRequests.find(r => r.id === requestId);
    
    const requestStatus = document.getElementById('requestStatus');
    requestStatus.innerHTML = '';
    
    if (request) {
        requestStatus.innerHTML = `
            <h3>ID Yêu cầu: ${request.id}</h3>
            <p><strong>Tên khách hàng:</strong> ${request.name}</p>
            <p><strong>Email:</strong> ${request.email}</p>
            <p><strong>Nội dung:</strong> ${request.message}</p>
            <p><strong>Trạng thái:</strong> ${request.status}</p>
        `;
    } else {
        requestStatus.innerHTML = '<p>Không tìm thấy yêu cầu với ID này.</p>';
    }
});
