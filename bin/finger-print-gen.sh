#!/usr/bin/env bash

# This script is used to generate a fingerprint from Apple's certificates

# Generate lowercase fingerprint of AppleRootCA-G3.cer
apple_root_ca_g3_fingerprint=$(openssl x509 -inform der -in AppleRootCA-G3.cer -noout -fingerprint | sed -e 's/://g' | cut -d'=' -f2 | tr '[:upper:]' '[:lower:]')

# Generate lowercase fingerprint of AppleWWDRCAG6.cer
apple_wwdr_ca_g6_fingerprint=$(openssl x509 -inform der -in AppleWWDRCAG6.cer -noout -fingerprint | sed -e 's/://g' | cut -d'=' -f2 | tr '[:upper:]' '[:lower:]')

# Create json file containing the fingerprints
echo "{\"apple_root_ca_g3_fingerprint\": \"$apple_root_ca_g3_fingerprint\", \"apple_wwdr_ca_g6_fingerprint\": \"$apple_wwdr_ca_g6_fingerprint\"}" > fingerprints.json
