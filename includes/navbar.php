<header class="navbar">
    <div class="nav-container">
        <a href="/studynest/index.php" class="logo">
            <img src="/studynest/assets/images/logo.png" alt="Logo" onerror="this.src='https://via.placeholder.com/40x40?text=Logo'">
            StudyNest <span>SPPU</span>
        </a>

        <nav class="nav-links">
            <a href="/studynest/index.php">Home</a>
            <a href="/studynest/user/notes.php">Notes</a>
            <a href="/studynest/user/pyq.php">PYQ</a>
            <a href="/studynest/user/store.php">Store</a>
            <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <a href="/studynest/admin/dashboard.php" style="color:var(--primary); font-weight:bold;">Admin</a>
            <?php endif; ?>
        </nav>

        <div class="nav-icons">
            <div class="search-bar">
                <input type="text" placeholder="Search...">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--text-light);"></i>
            </div>

            <a href="/studynest/user/cart.php" class="cart-icon-wrapper">
                <i class="fa-solid fa-basket-shopping"></i>
                <span class="cart-badge">0</span>
            </a>

            <div class="dropdown">
                <i class="fa-solid fa-circle-user" style="font-size:1.5rem; cursor:pointer; color:var(--primary);"></i>
                <div class="dropdown-content">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <p style="padding: 12px 16px; margin:0; border-bottom:1px solid #eee; font-weight:600; font-size:0.85rem;">
                            Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </p>
                        <a href="/studynest/user/orders.php"><i class="fa-solid fa-box"></i> My Orders</a>
                        <a href="/studynest/auth/logout.php" style="color:red;"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
                    <?php else: ?>
                        <a href="/studynest/auth/login.php"><i class="fa-solid fa-right-to-bracket"></i> Sign In</a>
                        <a href="/studynest/auth/register.php"><i class="fa-solid fa-user-plus"></i> Register</a>
                    <?php endif; ?>
                </div>
            </div>

            <i class="fa-solid fa-bars hamburger"></i>
        </div>
    </div>
</header>