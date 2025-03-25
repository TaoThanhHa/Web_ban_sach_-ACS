document.getElementById('image-upload').addEventListener('change', function() {
    var file = this.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-image').setAttribute('src', e.target.result);
        }
        reader.readAsDataURL(file);
    }
});

function validateForm() {
    var imageInput = document.getElementById('image-upload');
    if (imageInput.files.length === 0) {
        alert('Vui lòng chọn ảnh bìa cho sách.');
        return false; 
    }
    return true;
}