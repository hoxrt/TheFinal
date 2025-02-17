<header>
    <nav>
        <div class="logo">مشروعنا</div>
        <ul>
            <li><a href="index.php">الرئيسية</a></li>
            <li><a href="products.php">المنتجات</a></li>
            <li><a href="search.php">بحث</a></li>
            <li><a href="college-prices.php">أسعار مكتبة الكلية</a></li>
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="dashboard.php">لوحة التحكم</a></li>
                <li><a href="logout.php">تسجيل خروج</a></li>
            <?php else: ?>
                <li><a href="login.php">تسجيل دخول</a></li>
                <li><a href="register.php">تسجيل جديد</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
