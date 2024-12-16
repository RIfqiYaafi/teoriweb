// Mengambil elemen untuk menampilkan produk di keranjang
const cartItemsContainer = document.getElementById('cart-items');
const cartCount = document.querySelector('.cart-count'); // Elemen untuk jumlah keranjang

// Mengambil data produk dari localStorage
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Menampilkan produk di keranjang
function displayCartItems() {
    // Cek jika keranjang kosong
    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<p>Your cart is empty.</p>';
    } else {
        // Menampilkan item-item yang ada di keranjang
        cartItemsContainer.innerHTML = cart.map((item, index) => `
            <div class="cart-item">
                <img src="${item.image}" alt="${item.name}" />
                <div class="cart-item-info">
                    <h3>${item.name}</h3>
                    <p>$${item.price}</p>
                    <button class="remove-btn" onclick="removeItem(${index})">Delete</button>
                </div>
            </div>
        `).join('');
    }

    // Memperbarui jumlah item di keranjang
    cartCount.textContent = cart.length;
}

// Fungsi untuk menghapus item dari keranjang
function removeItem(index) {
    // Menghapus item berdasarkan index
    cart.splice(index, 1);

    // Simpan kembali ke localStorage setelah item dihapus
    localStorage.setItem('cart', JSON.stringify(cart));

    // Memperbarui tampilan keranjang setelah item dihapus
    displayCartItems();
}

// Memperbarui jumlah cart saat halaman dimuat
displayCartItems();
