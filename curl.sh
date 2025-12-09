# Create the binary file                                                                                          
echo -n -e '\xac\xed\x00\x05\x74\x00\x04test' > payload.bin

# Send it
curl -X POST http://localhost:8080/api/v1/ingest \
     --data-binary @payload.bin \
     -H "Content-Type: application/octet-stream" \
     -v
