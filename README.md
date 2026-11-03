# TalaTrade

**An Inventory and Online Shopping System for General Merchandise Stores**

TalaTrade is a web-based platform that combines inventory management and e-commerce for May's Sari-Sari Store, a locally owned general merchandise store in Liloan, Cebu. The system digitizes the store's manual stock tracking and sales recording while giving customers an online shopping alternative to the physical store.

## What It Does

- **For Customers:** Browse a real-time product catalog, search and filter by category, add items to a cart, place orders online, pay via Cash on Delivery (COD) or QR code, apply discount codes, and track order status through a personal account dashboard.
- **For Employees/Admins:** Manage products (add, update, delete), update stock and prices, manage and fulfill online orders, and create promotional discounts.

## Features

- Role-based features (Customer and Admin)
- Real-time product catalog with category filters
- Shopping cart and online checkout with integrated payment
- Discount and promotional code features

## API Integrations

- **Payment Handling:** Securely processes online transactions and automatically updates order statuses via webhooks upon successful payment.
- **Location:** Validates user-inputted shipping addresses against geocoded coordinates to ensure deliveries fall within the store's serviceable area.
- **Email Notifications:** Automates transactional emails to customers, triggering order confirmation receipts or status updates when database records change.
- **QR Code Generation:** Dynamically generates unique, scannable payment QR codes containing specific order IDs and total amounts to reduce user error.

## Scope & Limitations

- Single currency (PHP) and single language (English) only
- Deliveries limited to Cebu City
- No barcode scanning, product reviews, real-time courier tracking, or advanced analytics
- Designed to complement and not replace the physical store
