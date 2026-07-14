<?php
session_start();

$menu = "";
$message = "";
$messageCode = "";

// Redirect to login page if session is empty
if (empty($_SESSION['user_id'])) {
    header("Location: module/login/login.php");
    exit();
} else {
    $user_id = $_SESSION['user_id'];
}

require "config/database.php";

// Current logged-in user, used for the navbar avatar (soal #1)
$stmt = $pdo->prepare("SELECT firstname, lastname, image FROM tb_users WHERE user_id = ? LIMIT 1");
$stmt->execute([$user_id]);
$currentUser = $stmt->fetch();

$profileImage = 'https://placehold.co/72x72/png?text=User';
if ($currentUser && !empty($currentUser['image']) && file_exists(__DIR__ . '/asset/profile/' . $currentUser['image'])) {
    $profileImage = 'asset/profile/' . $currentUser['image'];
}
$currentUserName = $currentUser ? trim($currentUser['firstname'] . ' ' . $currentUser['lastname']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/custom.css">
</head>
<body>

<!-- SIDEBAR -->
<section id="sidebar">
    <a href="index.php" class="brand">
        <img src="asset/img/logo-uia.png" alt="UIA" class="brand-logo" onerror="this.style.display='none'">
    </a>

    <ul class="side-menu top">
        <li class="active">
            <a href="index.php">
                <i class='bx bxs-dashboard bx-sm'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>

        <li>
            <a href="#">
                <i class='bx bxs-shopping-bag-alt bx-sm'></i>
                <span class="text">My Store</span>
            </a>
        </li>

        <li>
            <a href="#">
                <i class='bx bxs-doughnut-chart bx-sm'></i>
                <span class="text">Analytics</span>
            </a>
        </li>

        <li>
            <a href="#">
                <i class='bx bxs-message-dots bx-sm'></i>
                <span class="text">Message</span>
            </a>
        </li>

        <li>
            <a href="index.php?menu=team/team">
                <i class='bx bxs-group bx-sm'></i>
                <span class="text">Team</span>
            </a>
        </li>
    </ul>

    <ul class="side-menu bottom">
        <li>
            <a href="#">
                <i class='bx bxs-cog bx-sm bx-spin-hover'></i>
                <span class="text">Settings</span>
            </a>
        </li>

        <li>
            <a href="module/login/logout.php" class="logout">
                <i class='bx bx-power-off bx-sm bx-burst-hover'></i>
                <span class="text">Logout</span>
            </a>
        </li>
    </ul>
</section>
<!-- SIDEBAR -->

<!-- CONTENT -->
<section id="content">

    <!-- NAVBAR -->
    <nav>

        <i class='bx bx-menu bx-sm'></i>

        <a href="#" class="nav-link">Categories</a>

        <form action="#">
            <div class="form-input">
                <input type="search" placeholder="Search...">
                <button type="submit" class="search-btn">
                    <i class='bx bx-search'></i>
                </button>
            </div>
        </form>

        <input type="checkbox" class="checkbox" id="switch-mode" hidden />

        <label class="switch-lm" for="switch-mode">
            <i class='bx bxs-moon'></i>
            <i class='bx bx-sun'></i>
            <div class="ball"></div>
        </label>

        <!-- Notification Bell -->
        <a href="#" class="notification" id="notificationIcon">
            <i class='bx bxs-bell bx-tada-hover'></i>
            <span class="num">8</span>
        </a>

        <div class="notification-menu" id="notificationMenu">
            <ul>
                <li>New message from John</li>
                <li>Your order has been shipped</li>
                <li>New comment on your post</li>
                <li>Update available for your app</li>
                <li>Reminder: Meeting at 3PM</li>
            </ul>
        </div>

        <!-- Profile Menu (soal #1: dynamic photo top-right) -->
        <a href="#" class="profile" id="profileIcon">
            <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile">
        </a>

        <div class="profile-menu" id="profileMenu">
            <ul>
                <?php if ($currentUserName): ?>
                    <li class="profile-menu-name"><?php echo htmlspecialchars($currentUserName); ?></li>
                <?php endif; ?>
                <li><a href="index.php?menu=account/profile">My Profile</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="module/login/logout.php">Log Out</a></li>
            </ul>
        </div>

    </nav>
    <!-- NAVBAR -->

    <!-- MAIN -->
    <main>
        <?php
        if (isset($_GET['menu'])) {
            $menu = $_GET['menu'];
        }

        if (isset($_GET['code']) && isset($_GET['message'])) {
            $messageCode = $_GET['code'];
            $message = $_GET['message'];
        }

        if ($menu == "") {
            $menu = "dashboard/dashboard";
        }

        include "module/".htmlspecialchars($menu).".php";
        ?>
    </main>
    <!-- MAIN -->

</section>
<!-- CONTENT -->

<!-- partial -->

</body>
</html>
<script type="text/javascript">

const allSideMenu = document.querySelectorAll("#sidebar .side-menu.top li a");

allSideMenu.forEach((item) => {
    const li = item.parentElement;

    item.addEventListener("click", function () {
        allSideMenu.forEach((i) => {
            i.parentElement.classList.remove("active");
        });
        li.classList.add("active");
    });
});

// TOGGLE SIDEBAR
const menuBar = document.querySelector("#content nav .bx.bx-menu");
const sidebar = document.getElementById("sidebar");

// Sidebar toggle işlemi
menuBar.addEventListener("click", function () {
    sidebar.classList.toggle("hide");
});

// Sayfa yüklendiğinde ve boyut değişimlerinde sidebar durumunu ayarlama
function adjustSidebar() {
    if (window.innerWidth <= 576) {
        sidebar.classList.add("hide"); // 576px ve altı için sidebar gizli
        sidebar.classList.remove("show");
    } else {
        sidebar.classList.remove("hide"); // 576px'den büyükse sidebar görünür
        sidebar.classList.add("show");
    }
}

// Sayfa yüklendiğinde ve pencere boyutu değiştiğinde sidebar durumunu ayarlama
window.addEventListener("load", adjustSidebar);
window.addEventListener("resize", adjustSidebar);

// Arama butonunu toggle etme
const searchButton = document.querySelector(
    "#content nav form .form-input button"
);

const searchButtonIcon = document.querySelector(
    "#content nav form .form-input button .bx"
);

const searchForm = document.querySelector("#content nav form");

searchButton.addEventListener("click", function (e) {
    if (window.innerWidth < 768) {
        e.preventDefault();
        searchForm.classList.toggle("show");

        if (searchForm.classList.contains("show")) {
            searchButtonIcon.classList.replace("bx-search", "bx-x");
        } else {
            searchButtonIcon.classList.replace("bx-x", "bx-search");
        }
    }
});

// Dark Mode Switch
const switchMode = document.getElementById("switch-mode");

switchMode.addEventListener("change", function () {
    if (this.checked) {
        document.body.classList.add("dark");
    } else {
        document.body.classList.remove("dark");
    }
});

// Notification Menu Toggle
document.querySelector(".notification").addEventListener("click", function () {
    document.querySelector(".notification-menu").classList.toggle("show");
    document.querySelector(".profile-menu").classList.remove("show"); // Close profile menu if open
});

// Profile Menu Toggle
document.querySelector(".profile").addEventListener("click", function () {
    document.querySelector(".profile-menu").classList.toggle("show");
    document.querySelector(".notification-menu").classList.remove("show"); // Close notification menu if open
});

// Close menus if clicked outside
window.addEventListener("click", function (e) {
    if (!e.target.closest(".notification") &&
        !e.target.closest(".profile")) {

        document.querySelector(".notification-menu").classList.remove("show");
        document.querySelector(".profile-menu").classList.remove("show");
    }
});

// Menülerin açılıp kapanması için fonksiyon
function toggleMenu(menuId) {
    var menu = document.getElementById(menuId);
    var allMenus = document.querySelectorAll(".menu");

// Diğer tüm menüleri kapat
allMenus.forEach(function (m) {
    if (m !== menu) {
        m.style.display = "none";
    }
});

// Tıklanan menü varsa aç, yoksa kapat
if (menu.style.display === "none" || menu.style.display === "") {
    menu.style.display = "block";
} else {
    menu.style.display = "none";
}
}

// Başlangıçta tüm menüleri kapalı tut
document.addEventListener("DOMContentLoaded", function () {
    var allMenus = document.querySelectorAll(".menu");

    allMenus.forEach(function (menu) {
        menu.style.display = "none";
    });
});

setTimeout(() => {
  const msg = document.querySelector('.message');
  if (msg) {
    msg.style.transition = 'opacity 1s';
    msg.style.opacity = 0;
    msg.style.display = 'none';
  }
}, 4000); // Hide after 4 seconds

</script>