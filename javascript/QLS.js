function editBook(id, title) {
  document.getElementById('edit-title').value = title;
  document.getElementById('edit-book-id').value = id;
  document.getElementById('edit-form').style.display = 'block';
}

function deleteBook(bookId) {
  if (confirm("Bạn có chắc chắn muốn xóa sách này?")) {
    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('book_id', bookId);

    fetch('process.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(data => {
      alert(data);
      location.reload(); // Tải lại trang để cập nhật danh sách sách
    });
  }
}

function saveChanges() {
  const title = document.getElementById('edit-title').value;
  const bookId = document.getElementById('edit-book-id').value;

  const formData = new FormData();
  formData.append('action', 'update');
  formData.append('book_id', bookId);
  formData.append('book_title', title);

  fetch('process.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(data => {
    alert(data);
    document.getElementById('edit-form').style.display = 'none';
    location.reload(); // Tải lại trang để cập nhật danh sách sách
  });
}