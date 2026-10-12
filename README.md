# Chamunda Electrics ⚡
**Full-Featured Electrical & Industrial Goods E-Commerce Platform in Laravel & Bootstrap 5**

Welcome to **Chamunda Electrics**, a complete, production-ready ecommerce platform engineered specifically for retail electrical supplies, contractor wiring projects, and wholesale equipment.

---

## 🛠️ Technology Stack
- **Framework**: Laravel 12 / 13 (PHP 8.3)
- **Database**: MySQL (`chamunda_electrics`) via phpMyAdmin / XAMPP
- **Frontend**: Bootstrap 5.3.3 & Bootstrap Icons (compiled via Vite)
- **Architecture**: MVC with Eloquent ORM, Seeders, Migrations, and Role-Based Middleware

---

## 🔑 Pre-Seeded Accounts

### 👑 Super Administrator
- **URL**: `http://localhost:8000/admin` or `http://localhost:8000/login`
- **Email**: `admin@chamundaelectrics.com`
- **Password**: `password123`
- *Features*: Sales Analytics, Order Pipeline Management, Product & Specification CRUD, Category & Brand Management, Discount Coupons, Customer Spend Insights, and Customer Inquiries Inbox.

### 👤 Customer / Contractor
- **URL**: `http://localhost:8000/login`
- **Email**: `customer@example.com`
- **Password**: `password123`
- *Features*: Shopping Cart, Wishlist, Order Tracking Timeline, Printable GST Tax Invoice, and Profile Address Book.

*(Note: The login screen includes 1-click demo filler buttons for instant access).*

---

## 🏷️ Pre-Configured Discount Coupons
Try applying these at the Shopping Cart or Checkout:
- `CHAMUNDA10`: 10% instant discount on orders above ₹1,000 (up to ₹500 discount)
- `FLAT500`: Flat ₹500 off on bulk orders above ₹5,000
- `DIWALI20`: 20% discount on orders above ₹3,000 (up to ₹1,000 discount)

---

## 🛒 Features Breakdown

### 1. Storefront & Catalog
- **Top Announcement Bar**: Contact helpline (`+91 98765 43210`), Store Timings, and GST Tax Invoicing badge.
- **Dynamic Category & Department Navigation**: Wires & Cables, Modular Switches & Sockets, Lighting & LED, BLDC Ceiling Fans, MCBs & Switchgears, Home Appliances, Testing Tools, and PVC Conduit Pipes.
- **Multi-parameter Search & Filters**: Search by keyword/SKU, filter by Category, Brand, Price Range, and In-Stock availability.
- **Product Details & Technical Specifications**: Complete technical spec table (Voltage, Wattage, Conductor material, ISI certification, Warranty), verified customer ratings, and related products recommendations.

### 2. Shopping Cart & Checkout
- **Real-Time Cart**: Quantity +/- counters, stock availability checks, subtotal calculation, coupon discounts, 18% electrical GST breakdown, and free shipping over ₹999.
- **Checkout Process**: Customer delivery address, site instructions, payment method options (Cash on Delivery, Instant UPI/QR, Card/NetBanking).
- **Atomic Stock Management**: Database transactions decrement product stock safely upon order placement.
- **Printable GST Tax Invoice**: Full tax breakdown with HSN codes, billing & shipping address, and print button.

### 3. Customer Portal
- Order History with live status indicators (`Pending` ➔ `Processing` ➔ `Shipped` ➔ `Delivered`).
- Interactive delivery tracking timeline with courier AWB numbers.
- Wishlist with 1-click move to cart.
- Saved profile and delivery addresses.

### 4. Admin Management Dashboard
- **Revenue & Order KPI Cards**: Total revenue, total orders, active catalog count, customer counts.
- **Pipeline Status Tabs**: Filter orders by `Pending`, `Processing`, `Shipped`, `Delivered`, and `Cancelled`.
- **Order Management**: Change order progress, mark payments as Paid, assign tracking numbers, and view order receipts.
- **Product Management**: Create, edit, and delete products with image upload, custom key-value electrical technical specifications, MRP and sale pricing.
- **Categories & Brands**: Add and modify product departments and manufacturers (Havells, Polycab, Anchor, Philips, Schneider, Crompton, Finolex, Legrand).
- **Inquiry Inbox**: Review and manage customer inquiries from the contact form.

---

## 🚀 How to Run the Application

Open your terminal in `D:\laravel\chamund_electrics`:

```bash
# 1. Start the Laravel development server
php artisan serve
```

Then visit:
- **Storefront**: [http://127.0.0.1:8000](http://127.0.0.1:8000)
- **Admin Panel**: [http://127.0.0.1:8000/admin](http://127.0.0.1:8000/admin)
