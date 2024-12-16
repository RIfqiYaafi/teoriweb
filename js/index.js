// Mengambil elemen tombol "Add to Cart"
const addToCartButtons = document.querySelectorAll('.add-to-cart');

// Mengambil cart dari localStorage
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Menampilkan jumlah item di cart
function updateCartCount() {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = cart.length; // Menampilkan jumlah total item di cart
    }
}

// Menambahkan item ke cart
function addToCart(item) {
    // Mengecek apakah item sudah ada di cart
    const existingItemIndex = cart.findIndex(cartItem => cartItem.name === item.name);

    if (existingItemIndex > -1) {
        // Jika item sudah ada, tambahkan quantity
        cart[existingItemIndex].quantity += 1;
    } else {
        // Jika item belum ada, tambahkan item dengan quantity 1
        item.quantity = 1;
        cart.push(item);
    }

    // Menyimpan cart di localStorage
    localStorage.setItem('cart', JSON.stringify(cart));

    // Update jumlah item di cart
    updateCartCount();
}

// Menambahkan event listener pada setiap tombol "Add to Cart"
addToCartButtons.forEach(button => {
    button.addEventListener('click', function () {
        // Mengambil data dari tombol untuk item yang ingin ditambahkan ke keranjang
        const item = {
            name: this.getAttribute('data-name'), // Nama produk
            price: parseFloat(this.getAttribute('data-price')), // Pastikan harga dalam angka
            image: this.getAttribute('data-image') // Gambar produk
        };

        // Menambahkan item ke keranjang
        addToCart(item);
    });
});

// Memperbarui jumlah item yang ada di cart saat halaman dimuat
updateCartCount();
