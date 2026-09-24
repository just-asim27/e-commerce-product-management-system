# 🛒 E-Commerce Product Management System

## 📖 Overview

**E-Commerce Product Management System** is a PHP and MySQL web application for an online retailer, covering the full flow from browsing and buying products to fulfilling and returning orders.
The system supports three actors — **Customers**, **Product Managers**, and **Delivery Boys** — each with their own dashboard and set of actions on shared product, order, and return data.

---

## ✨ Features

- **Product Browsing**: Customers can browse featured products by category and view detailed product descriptions.
- **Cart & Checkout**: Products can be added to a shopping cart and checked out into an order.
- **Order Tracking**: Customers can track the status of their orders after purchase.
- **Returns**: Customers can request returns; product managers approve or reject them and assign a delivery boy to pick the item up.
- **Reviews**: Customers can leave and update reviews on purchased products.
- **Product & Inventory Management**: Product managers can add new products, update inventory, change prices, and manage discounts.
- **Order Assignment**: Product managers assign orders to delivery boys for delivery.
- **Delivery & Refunds**: Delivery boys deliver orders and collect payment, and pick up approved returns and hand over the refund amount to the customer.

---

## ⚙️ How It Works

1. **Browse**: A customer signs up, browses products by category, and views product details.
2. **Purchase**: Products are added to the cart and checked out, creating an order.
3. **Fulfillment**: A product manager assigns the order to a delivery boy, who delivers it and collects payment.
4. **Post-Purchase**: The customer can track the order, leave a review, or submit a return request.
5. **Returns**: The product manager approves the return and assigns it to a delivery boy, who picks up the item and pays the refund amount to the customer.

---

## 🚀 How to Run

### Prerequisites
- **XAMPP** (bundles PHP, MySQL, and phpMyAdmin)

### Steps
1. Clone the repository into your XAMPP `htdocs` folder.
2. Start Apache and MySQL from your server environment's control panel.
3. In phpMyAdmin, create a new database named `ecommerce`.
4. Open the **Import** tab for the `ecommerce` database, choose `schema.sql`, and run the import to create all tables.
5. Open `backend/db-connection.php` and confirm the `$server`, `$username`, `$password`, and `$database` values match your local MySQL setup.
6. Visit `index.html` through your local server (e.g. `localhost/e-commerce-product-management-system/index.html`) to use the app.

---

## 📂 Repository Structure

```
e-commerce-product-management-system/
├── backend/            [Server-side scripts that process customer, product-manager, and delivery-boy actions]
├── frontend/           [Pages for customer, product-manager, and delivery-boy dashboards]
├── css/                [Stylesheets per actor]
├── images/             [Product images]
├── index.html          [Landing page]
└── schema.sql          [Database schema]
```

---

## 🔒 Reuse & Contribution

This project is provided publicly **for portfolio and demonstration purposes only**. You may **not copy, modify, redistribute, or use** this code **without explicit permission**.

---

## 👥 Authors

Asim · Husnain
