#!/usr/bin/env bash

# This script is used to convert a .cer file to a .pem file

# Check if the file exists
if [ ! -f "$1" ]; then
  echo "File $1 does not exist"
  exit 1
fi

# Check if the file is a .cer file
if [[ "$1" != *.cer ]]; then
  echo "File $1 is not a .cer file"
  exit 1
fi

# Get output file name if it exists, otherwise use the input file name with .pem extension
if [ -z "$2" ]; then
  output_file="${1%.*}.pem"
else
  output_file="$2"
fi

# Convert the file and output to the output file
openssl x509 -inform der -in "$1" -out "$output_file"
