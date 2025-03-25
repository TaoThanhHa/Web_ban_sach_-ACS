$(document).ready(function () {
    var changes = [];

    function addChange(id, field, value) {
        var existingChangeIndex = changes.findIndex(function (change) {
            return change.id === id && change.field === field;
        });

        if (existingChangeIndex !== -1) {
            changes[existingChangeIndex].value = value;
        } else {
            changes.push({id: id, field: field, value: value});
        }
    }

    // Xử lý cập nhật Name, Email, Phone trực tiếp
    $('.editable').blur(function () {
        var id = $(this).data('id');
        var field = $(this).data('field');
        var value = $(this).text();
        addChange(id, field, value);
    });

    // Xử lý cập nhật Status trực tiếp
    $('.status-select').change(function () {
        var id = $(this).data('id');
        var value = $(this).val();
        addChange(id, 'status', value);
    });

    // Xử lý sự kiện click nút "Lưu"
    $('#save-changes').click(function () {
        if (changes.length > 0) {
            $.ajax({
                url: 'update_users.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(changes),
                success: function (response) {
                    try {
                        var data = JSON.parse(response);
                        if (data.status === 'success') {
                            alert(data.message);
                            location.reload();
                        } else {
                            alert('Lỗi: ' + data.message);
                        }
                    } catch (e) {
                        alert('Lỗi phân tích JSON: ' + e);
                    }
                },
                error: function (xhr, status, error) {
                    alert('Lỗi AJAX: ' + error);
                }
            });
            changes = [];
        } else {
            alert('Không có thay đổi nào để lưu.');
        }
    });
});