document.getElementById("registerForm").addEventListener("submit", function (e) {
    e.preventDefault();

    // Get form values
    const name = document.getElementById("name").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const role = document.getElementById("role").value;

    // Simulate storing data and sending email confirmation
    localStorage.setItem("user", JSON.stringify({ name, email, password, role }));
    alert("Đăng ký thành công! Vui lòng xác nhận tài khoản qua email.");
});

document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();

    // Get form values
    const loginEmail = document.getElementById("loginEmail").value;
    const loginPassword = document.getElementById("loginPassword").value;

    // Simulate authentication
    const user = JSON.parse(localStorage.getItem("user"));

    if (user && user.email === loginEmail && user.password === loginPassword) {
        document.getElementById("userName").innerText = user.name;
        document.getElementById("userRole").innerText = user.role;

        // Show user access section based on role
        document.getElementById("userAccess").classList.remove("hidden");
        alert("Đăng nhập thành công!");

        // Different role-based access can be implemented here
        if (user.role === "admin") {
            // Quản trị viên logic
            console.log("Admin access granted");
        } else if (user.role === "support") {
            // Nhân viên hỗ trợ logic
            console.log("Support staff access granted");
        } else {
            // Khách hàng logic
            console.log("Customer access granted");
        }
    } else {
        alert("Thông tin đăng nhập không chính xác!");
    }
});
