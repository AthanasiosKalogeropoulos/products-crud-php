# Products CRUD — PHP & XML

A simple PHP product management application built as part of a technical challenge. It reads product data from an XML file, displays it in a clean table, and allows users to add new products via a modal form.

---

## Features

- **Product listing** — Reads and displays all products from `products.xml` in a responsive HTML table
- **Add product** — Modal form for adding new products, integrated into the main page for a seamless UX
- **Form validation** — Client-side (JS) and server-side (PHP) validation; the Name field is required
- **Form persistence** — Field values are preserved if validation fails, so the user doesn't lose their input
- **PRG pattern** — Post/Redirect/Get implemented to prevent duplicate submissions on page refresh
- **Input UX** — Price field accepts only numbers and a decimal point; Weight field auto-appends "kg"
- **Success/error feedback** — Contextual messages with auto-dismiss on success

---

## Tech Stack

- PHP 8.x (OOP, SimpleXML)
- HTML5 / CSS3 / Vanilla JS
- XML for data storage

---

## Project Structure

```
├── products.php       # Main page: product table + add product modal
├── lib.php            # Products class with print and add_product methods
├── products.xml       # XML data store
└── README.md
```

---

## Design Decisions

- The `add_product()` method lives inside the `Products` class in `lib.php`, as suggested in the brief
- The form is integrated into `products.php` as a modal rather than a separate page — this keeps the user in context and avoids unnecessary navigation
- Styling was kept clean and minimal to reflect professional UI standards, even though it was not explicitly required

---

## How to Run

Requires PHP 8.x CLI or any local web server (XAMPP, MAMP, etc.)

```bash
php -S localhost:8000
```

Then open `http://localhost:8000/products.php` in your browser.