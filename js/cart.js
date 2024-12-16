// Mengambil elemen untuk menampilkan produk di keranjang
const cartItemsContainer = document.getElementById('cart-items');
const cartCount = document.querySelector('.cart-count'); // Elemen untuk jumlah keranjang

// Menampilkan produk di keranjang
function displayCartItems() {
    // Mengambil data keranjang dari server (Read)
    fetch('http://localhost/your_project/api/cart_api.php?user_id=1') // Ganti dengan user_id yang sesuai
    .then(response => response.json())
    .then(data => {
        console.log("Data respons dari server:", data); // Log respons dari server
        if (data.status === 'success') {
            const cart = data.cart_items || [];

            // Cek jika keranjang kosong
            if (cart.length === 0) {
                cartItemsContainer.innerHTML = '<p>Your cart is empty.</p>';
            } else {
                // Menampilkan item-item yang ada di keranjang
                cartItemsContainer.innerHTML = cart.map((item, index) => `
                    <div class="cart-item">
                        <img src="${item.image || 'default-image.png'}" alt="${item.name || 'Unnamed Item'}" />
                        <div class="cart-item-info">
                            <h3>${item.name}</h3>
                            <p>Rp ${item.price.toLocaleString()}</p>
                            <p>Quantity: <input type="number" value="${item.quantity}" min="1" onchange="updateQuantity(${item.cart_id}, this.value)" /></p>
                            <button class="remove-btn" onclick="removeItem(${item.cart_id})">Delete</button>
                        </div>
                    </div>
                `).join('');
            }

            // Memperbarui jumlah item di keranjang
            if (cartCount) {
                cartCount.textContent = cart.length;
            }
        } else {
            alert(data.message || 'Failed to fetch cart data.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error fetching cart data.');
    });
}

// Fungsi untuk menghapus item dari keranjang (Delete)
function removeItem(cart_id) {
    // Mengirimkan permintaan untuk menghapus item dari server
    fetch('http://localhost/your_project/api/cart_api.php', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ cart_id: cart_id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            displayCartItems(); // Memperbarui tampilan setelah item dihapus
        } else {
            alert(data.message || 'Failed to delete item.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error deleting item.');
    });
}

// Fungsi untuk memperbarui jumlah item di keranjang (Update)
function updateQuantity(cart_id, quantity) {
    fetch('http://localhost/your_project/api/cart_api.php', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            cart_id: cart_id,
            quantity: parseInt(quantity, 10)
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert(data.message);
            displayCartItems(); // Memperbarui tampilan setelah quantity diperbarui
        } else {
            alert(data.message || 'Failed to update quantity.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating quantity.');
    });
}

// Menangani tombol checkout
document.getElementById("checkout").addEventListener("click", function(event) {
    event.preventDefault(); // Mencegah form submission biasa

    // Mengambil data checkout (Read)
    fetch('http://localhost/your_project/api/cart_api.php?user_id=1') // Ganti dengan user_id yang sesuai
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            const cart = data.cart_items || [];

            if (cart.length === 0) {
                alert('Your cart is empty. Please add some items before checking out.');
                return;
            }

            // Persiapkan data checkout
            const checkoutData = {
                product_ids: cart.map(item => item.product_id),
                quantities: cart.map(item => item.quantity),
                user_id: 1 // Ganti dengan ID pengguna yang sesuai
            };

            console.log('Data JSON yang dikirim:', JSON.stringify(checkoutData));

            fetch('http://localhost/your_project/api/cart_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(checkoutData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    alert(data.message);
                    displayCartItems(); // Memperbarui tampilan keranjang setelah checkout berhasil
                } else {
                    alert(data.message || 'Failed to process your request.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('There was an error processing your request.');
            });
        } else {
            alert(data.message || 'Failed to fetch cart data for checkout.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error fetching cart data.');
    });
});

// Memperbarui jumlah cart saat halaman dimuat
displayCartItems();
