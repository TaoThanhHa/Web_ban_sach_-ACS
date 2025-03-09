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

function previewImage(event) {
    const preview = document.getElementById('preview-image');
    preview.src = URL.createObjectURL(event.target.files[0]);
    preview.style.display = 'block';
}

function saveStory() {
    const name = document.getElementById('story-title').value;
    const description = document.getElementById('story-content').value;
    const publisher = document.getElementById('publisher').value;
    const size = document.getElementById('size').value;
    const price = document.getElementById('price').value;
    const discount = document.getElementById('discount').value;
    const quantity = document.getElementById('quantity').value;
    const category = document.querySelector('input[name="category"]:checked').value;
    const image = document.getElementById('preview-image').src;

    const products = JSON.parse(localStorage.getItem('products')) || [];
    products.push({ name, description, publisher, size, price, discount, quantity, category, image });
    localStorage.setItem('products', JSON.stringify(products));

}