# IT ReaderShelf. Book Review Website

**Course:** Advanced Web


## About Our Project

We created IT ReaderShelf, a website where people can review IT books. We used tools like PHP, MySQL, Bootstrap and jQuery AJAX(will be implemented at v2) to build it. This website lets users look at IT books rate them with stars write reviews and make lists of their books.

## How to Set Up the Project on Your Computer

To run this project on your computer follow these steps:

### Import the Database

We need a database to make the website work properly. This database has information, about books, users, reviews and categories.

1. Open your XAMPP / MAMP control panel. Start both the **Apache** and **MySQL** services.

2. Go to `http://localhost/phpmyadmin` in your web browser.

3. Create a empty database named **`it_readershelf`**.

4. Select the database click on the **Import** tab.

5. Choose the **`it_readershelf.sql`** file from the **`init_db/`** folder of this project.

6. Click **Import** to execute the SQL script.

### Update the Connection String (If Necessary)

You might need to update the database login details so the PHP application can connect to MySQL.

1. Open the file at `includes/db_connect.php` in your code editor.

2. Update the variables to match your server setup:

**For XAMPP (Windows). Default Settings:**

```php

$server = "localhost";

$username = "root";

$password = "";

$database = "it_readershelf";

```

**For MAMP (Mac). Default Settings:**

```php

$server = "localhost";

$username = "root";

$password = "root";

$database = "it_readershelf";

```

- Note: If the server uses a different port like 8889 change the server to "localhost:8889".