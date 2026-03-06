document.addEventListener('DOMContentLoaded', () => {
    // 1. Sticky Navbar Effect
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // 2. Mobile Hamburger Menu
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');
    if (hamburger) {
        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
        });
    }

    // 3. Cart Functionality (Badge Update & Animation)
    const cartButtons = document.querySelectorAll('.add-to-cart-btn');
    const cartBadge = document.querySelector('.cart-badge');
    
    // Initialize cart count from LocalStorage or 0
    let cartCount = parseInt(localStorage.getItem('studynest_cart_count')) || 0;
    
    const updateCartUI = () => {
        if(cartBadge) {
            cartBadge.innerText = cartCount;
            // Trigger Pulse Animation
            cartBadge.classList.remove('pulse-anim');
            void cartBadge.offsetWidth; // Trigger reflow
            cartBadge.classList.add('pulse-anim');
        }
    };
    updateCartUI(); // Run on load

    cartButtons.forEach(button => {
        button.addEventListener('click', (e) => {
            e.preventDefault(); 
            const card = e.target.closest('.card') || e.target.closest('.product-detail-grid');
            const productName = card ? card.querySelector('.card-title, h1').innerText : 'Item';
            
            cartCount++;
            localStorage.setItem('studynest_cart_count', cartCount);
            updateCartUI();
            
            alert(`Success! "${productName}" added to your cart.`);
        });
    });

    // 4. Cart Page - Remove Dummy Item
    const removeBtns = document.querySelectorAll('.remove-item-btn');
    removeBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            e.target.closest('tr').remove();
            if(cartCount > 0) {
                cartCount--;
                localStorage.setItem('studynest_cart_count', cartCount);
                updateCartUI();
            }
        });
    });
});