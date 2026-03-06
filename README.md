# StudyNest SPPU

StudyNest SPPU is a student-focused web platform designed to help Savitribai Phule Pune University (SPPU) students access academic resources in one place.  
The platform allows students to browse study notes, previous year question papers (PYQs), and purchase useful academic materials through an integrated store.

This project is built using **PHP, MySQL, HTML, CSS, and JavaScript**, with a secure authentication system and an admin dashboard for managing content and users.

---

## 🎯 Project Purpose

The goal of StudyNest SPPU is to simplify access to academic resources for students by providing:

- Centralized study materials
- Previous year university question papers
- Academic product store
- Secure user authentication
- Admin management system

---

## 🚀 Features

### 👤 User Features
- User registration and login system
- Access to study notes
- Access to previous year question papers (PYQs)
- Academic product store
- Product detail and checkout pages

### 🔐 Authentication System
- Secure password hashing
- Session-based login system
- CAPTCHA protection against bots
- Role-based authentication

### 🛠 Admin Features
- Admin dashboard
- Add and manage products
- Manage orders
- Manage users
- View login logs

---

## 🏗 Project Structure
studynest/
│
├── index.php
│
├── config/
│ └── db.php
│
├── assets/
│ ├── css/
│ ├── js/
│ └── images/
│
├── includes/
│ ├── header.php
│ ├── navbar.php
│ └── footer.php
│
├── auth/
│ ├── login.php
│ ├── register.php
│ ├── logout.php
│ └── captcha.php
│
├── user/
│ ├── notes.php
│ ├── pyq.php
│ ├── store.php
│ ├── product-detail.php
│ ├── cart.php
│ └── checkout.php
│
├── admin/
│ ├── dashboard.php
│ ├── add-product.php
│ ├── manage-products.php
│ ├── manage-orders.php
│ ├── manage-users.php
│ └── logs.php
│
├── logs/
│ └── login_logs.txt
│
└── lab-analysis/
└── log_analyzer.py

---

## 🗄 Database Tables

The project uses MySQL with the following tables:

- users
- products
- notes
- pyqs
- orders
- order_items
- login_logs

---

## ⚙️ Technologies Used

- **Backend:** PHP
- **Database:** MySQL
- **Frontend:** HTML, CSS, JavaScript
- **Server:** Apache
- **Local Environment:** XAMPP

---

## 🔒 Security Features

- Password hashing using `password_hash()`
- Password verification using `password_verify()`
- Prepared statements to prevent SQL injection
- CAPTCHA verification for login
- Session-based authentication
- Role-based access control for admin panel

---

