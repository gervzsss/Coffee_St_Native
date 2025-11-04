# Coffee_St Project Setup Guide

Welcome to the Coffee_St project! Follow these steps to set up and run the project after cloning:

## Prerequisites

- **XAMPP** (or any local server with PHP & MySQL)
- **Node.js & npm** (for frontend assets)
- If you have MySQL Workbench installed, it is recommended to use it for database management.

## 1. Clone the Repository

```sh
git clone https://github.com/gervzsss/Coffee_St.git
```

## 2. Backend Setup

1. **Start Apache** using XAMPP Control Panel.
2. **Database Setup:**
   - Open MySQL Workbench.
   - Copy the contents of `backend/schema.sql`.
   - Paste and execute it in your MySQL Workbench to create the required database and tables.

3. **Configure Database Connection:**
   - Navigate to the backend configs directory:

     ```sh
     cd .\backend\configs\
     ```

   - Copy the example config file:

     ```sh
     cp example.config.php config.php
     ```

   - Open `backend/configs/config.php`.
   - Update the `db_pass` value with your MySQL password (leave it empty if you have no password).

## 3. Frontend Setup

1. **Install Node.js dependencies:**

   ```sh
   npm ci
   ```

2. **Build/Process assets (if needed):**

   ```sh
   npm run watch
   ```

## 4. Running the Project

- Place the project folder inside your XAMPP `htdocs` directory (if not already).
- Access the site via: [http://localhost/Coffee_St/public/](http://localhost/Coffee_St/public/)

## 5. Troubleshooting

- Ensure Apache is running.
- Use browser dev tools for frontend errors.

## 6. Useful Scripts

- `npm run build` or `npm run watch` – Build frontend assets

---
