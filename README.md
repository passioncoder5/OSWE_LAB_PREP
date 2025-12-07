# OSWE_LAB_PREP
OSWE CUSTOM LABS AND PREPARATION MASTER GUIDE
# Topic:php type juggling custom lab for OSWE
# 🐧 LAMPP (Linux)

1. **Start LAMPP**

   ```bash
   sudo /opt/lampp/lampp start
   ```

2. **Copy PHP Files**

   * Place your `.php` files into the LAMPP web directory:

     ```bash
     cd OSWE_LAB_PREP/php_type_juggling/lab
     sudo cp -r . /opt/lampp/htdocs/.
     ```
   * Access them at:
     👉 `http://localhost/vulnerable_login.php`

3. **Import Database via phpMyAdmin**

   * Open: 👉 [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)
   * Click **Databases** → Create new DB .
   * Select  **Import** tab.
   * Browse and upload `database.sql`.
   * Click **Go** → schema + data imported.

4. **Update PHP Config**
   Inside your PHP connection file (e.g., `config.php`):

```php
   // Database configuration
  define('DB_HOST', 'localhost');
  define('DB_USER', 'root');
  define('DB_PASS', '');
  define('DB_NAME', 'type_juggling_lab');
```

go to the folder where there is php.py and run python3 php.py http://localhost/vulnerable_login.php change the payload in return to the magic hash password you want 
```bash

def php_magic_hashes():
	print("[+] Returning php magic hashes whose md5 evaluate to 0")
	return "aabg7XSs"
```
