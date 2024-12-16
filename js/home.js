let menu = document.querySelector('#menu-btn');
let navbar = document.querySelector('.navbar');

menu.onclick = () => {
    menu.classList.toggle('fa-times');
    navbar.classList.toggle('active');
};

document.addEventListener("DOMContentLoaded", () => {
    console.log("Script loaded successfully");
    const params = new URLSearchParams(window.location.search);
    const redirect = params.get('redirect');
    console.log("Redirect value:", redirect);

    if (redirect === 'menu') {
        console.log("Redirecting to menu.html");
        window.location.href = "menu.html";
    }
});


window.onscroll = () => {
    menu.classList.remove('fa-times');
    navbar.classList.remove('active');
};

document.querySelectorAll('.image-slider img').forEach(images => {
    images.onclick = () => {
        var src = images.getAttribute('src');
        document.querySelector('.main-home-image').src = src;
    };
});

var swiper = new Swiper(".review-slider", {
    spaceBetween: 20,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    loop: true,
    grabCursor: true,
    autoplay: {
        delay: 7500,
        disableOnInteraction: false,
    },
    breakpoints: {
        0: {
            slidesPerView: 1
        },
        768: {
            slidesPerView: 2
        }
    },
});

// Cart functionality (basic example)
const cartCount = document.querySelector('.cart-count');
const addToCartButtons = document.querySelectorAll('.add-to-cart');

addToCartButtons.forEach(button => {
    button.addEventListener('click', (e) => {
        const product = e.target.closest('.box');
        const name = product.querySelector('h3').textContent;
        const price = product.querySelector('span').textContent;
        
        // Get existing cart items from localStorage
        let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

        // Check if the item already exists in the cart
        const existingItemIndex = cartItems.findIndex(item => item.name === name);
        if (existingItemIndex > -1) {
            cartItems[existingItemIndex].quantity += 1;
        } else {
            cartItems.push({
                name: name,
                price: price,
                quantity: 1,
            });
        }

        // Save the updated cart items to localStorage
        localStorage.setItem('cartItems', JSON.stringify(cartItems));

        // Update the cart count displayed
        cartCount.textContent = cartItems.length;
    });
});

