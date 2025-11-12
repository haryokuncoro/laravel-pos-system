# Laravel POS System

A simple **Point-of-Sale (POS) application** built with **Laravel**, **Livewire**, and **Tailwind CSS**.

---

## Features

* **User Authentication**: login and register.
* **Product Page**: view and search products.
* **New Sale / Register**: add products to cart, calculate totals, and finalize sales.
* **Sales Report**: view completed sales with item details, totals, and date.

---

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/haryokuncoro/laravel-pos-system.git
   cd laravel-pos-system
   ```
2. Install PHP dependencies:

   ```bash
   composer install
   ```
3. Install Node.js dependencies & build assets:

   ```bash
   npm install
   npm run dev
   ```
4. Set up environment:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
5. Configure `.env` with your database credentials.
6. Run migrations:

   ```bash
   php artisan migrate
   ```
7. Start the server:

   ```bash
   php artisan serve
   ```

---

## Usage

1. Register or login as a user.
2. Go to **Products** to view or search items.
3. Go to **New Sale / Register** to create a sale:

   * Click products to add to cart.
   * Adjust quantities or remove items.
   * Enter tendered amount and finalize sale.
4. Go to **Sales Report** to view all completed sales.

