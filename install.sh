#!/bin/bash

# SADL Installation Script
set -e

REPO_URL="https://github.com/CooperDActor-bytes/sadl"
VERSION="v1.0.1"
TAR_FILE="sadl-${VERSION}.tar.gz"
DOWNLOAD_URL="${REPO_URL}/archive/refs/tags/${VERSION}.tar.gz"
INSTALL_DIR="/usr/local/bin"

echo "Installing SADL ${VERSION}..."

# Step 1: Download the tarball
echo "Downloading SADL from ${DOWNLOAD_URL}..."
curl -L -o "${TAR_FILE}" "${DOWNLOAD_URL}"

# Step 2: Extract the tarball
echo "Extracting ${TAR_FILE}..."
tar -xzf "${TAR_FILE}"
cd "sadl-${VERSION}" || exit 1

# Step 3: Build the project using Cargo
if ! command -v cargo &> /dev/null; then
  echo "Rust is not installed. Please install Rust and Cargo first."
  exit 1
fi

echo "Building SADL with Cargo..."
cargo build --release

# Step 4: Move the binary to the installation directory
echo "Moving SADL binary to ${INSTALL_DIR}..."
sudo mv target/release/sadl "${INSTALL_DIR}/sadl"

# Step 5: Clean up
echo "Cleaning up..."
cd ..
rm -rf "sadl-${VERSION}" "${TAR_FILE}"

echo "SADL ${VERSION} installed successfully!"
echo "Run 'sadl --help' to get started."
