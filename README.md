# Web Bán Sách - Dự án ACS

## Mô tả

Dự án "Web Bán Sách" là một ứng dụng web thương mại điện tử cho phép người dùng duyệt, tìm kiếm và mua sách trực tuyến. Dự án này được phát triển như một phần của môn học ACS (có thể là An toàn và Bảo mật Hệ thống hoặc một môn học liên quan).

## Chức năng chính

*   **Duyệt và tìm kiếm sách:**
    *   Hiển thị danh sách sách theo danh mục, tác giả, nhà xuất bản.
    *   Tìm kiếm sách theo tên, tác giả, ISBN, v.v.
*   **Xem chi tiết sách:**
    *   Hiển thị thông tin chi tiết về sách (tên, tác giả, mô tả, giá, đánh giá, v.v.).
    *   Hiển thị hình ảnh bìa sách.
*   **Giỏ hàng và thanh toán:**
    *   Thêm sách vào giỏ hàng.
    *   Quản lý giỏ hàng (cập nhật số lượng, xóa sách).
    *   Thanh toán trực tuyến bằng các phương thức khác nhau.
*   **Quản lý tài khoản người dùng:**
    *   Đăng ký tài khoản mới.
    *   Đăng nhập và đăng xuất.
    *   Cập nhật thông tin tài khoản.
    *   Xem lịch sử đơn hàng.
*   **Quản lý sách (dành cho admin):**
    *   Thêm, sửa, xóa sách.
    *   Quản lý danh mục sách.
    *   Quản lý nhà xuất bản, tác giả.
*   **Hỗ trợ khách hàng:**
    *   Form liên hệ.
    *   Hệ thống phản hồi yêu cầu hỗ trợ (dành cho admin).


## Công nghệ sử dụng

*   **Ngôn ngữ lập trình:** PHP, JavaScript, HTML, CSS
*   **Cơ sở dữ liệu:** MySQL
*   **Thư viện/Framework:**
    *   Bootstrap (tùy chọn, để tạo giao diện responsive)
    *   Có thể sử dụng jQuery hoặc các thư viện JavaScript khác
*   **Máy chủ web:** Apache (hoặc Nginx)

## Cài đặt và cấu hình

1.  **Yêu cầu:**
    *   Máy chủ web (Apache hoặc Nginx)
    *   PHP (phiên bản 7.0 trở lên)
    *   MySQL (hoặc MariaDB)
2.  **Cài đặt:**
    *   Sao chép thư mục `Web_ban_sach_-ACS` vào thư mục gốc của máy chủ web (ví dụ: `/var/www/html/` trên Linux, hoặc `htdocs` trên XAMPP).
    *   Tạo một cơ sở dữ liệu MySQL với tên `webbansach`.
    *   Nhập cấu trúc cơ sở dữ liệu và dữ liệu mẫu (nếu có) từ file SQL (có thể có trong thư mục `db/`).
    *   Chỉnh sửa file `db/connect.php` để cập nhật thông tin kết nối cơ sở dữ liệu (host, username, password, database name).
3.  **Cấu hình:**
    *   Cấu hình máy chủ web để trỏ đến thư mục `Web_ban_sach_-ACS`.
    *   Cấu hình các quyền truy cập file và thư mục phù hợp.

## Hướng dẫn sử dụng

1.  **Truy cập trang web:** Mở trình duyệt web và truy cập `http://localhost/Web_ban_sach_-ACS/` (hoặc địa chỉ URL tương ứng).
2.  **Sử dụng các chức năng:**
    *   Duyệt sách, tìm kiếm sách, xem chi tiết sách.
    *   Đăng ký/đăng nhập tài khoản.
    *   Thêm sách vào giỏ hàng và thanh toán.
3.  **Quản lý (dành cho admin):**
    *   Truy cập trang quản lý (ví dụ: `http://localhost/Web_ban_sach_-ACS/admin/Quản_lý_sách.php`).
    *   Đăng nhập bằng tài khoản admin.
    *   Quản lý sách, danh mục, v.v.

## Thông tin liên hệ

*   [Mai Phương Anh - Tào Thanh Hà]
*   [maiphuonganh1505@gmail.com]
*   []

## Ghi chú

*   Đây là một dự án đang phát triển, có thể có lỗi hoặc thiếu sót.
*   Vui lòng báo cáo bất kỳ vấn đề nào bạn gặp phải.
*   Cảm ơn bạn đã sử dụng ứng dụng web của chúng tôi!
