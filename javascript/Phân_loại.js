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