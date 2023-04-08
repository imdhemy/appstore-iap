#!/usr/bin/env sh

# Make keys directory
PATH_KEYS="./tests/fixtures/keys"
mkdir -p $PATH_KEYS

# Generate ECDSA key pair
openssl ecparam -genkey -name secp256k1 -noout -out $PATH_KEYS/ecdsa-private.pem
openssl ec -in $PATH_KEYS/ecdsa-private.pem -pubout -out $PATH_KEYS/ecdsa-public.pem

# Generate RSA key pair
openssl genrsa -out $PATH_KEYS/rsa-private.pem 2048
openssl rsa -in $PATH_KEYS/rsa-private.pem -pubout -out $PATH_KEYS/rsa-public.pem
