//Tải ảnh từ thiết bị
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