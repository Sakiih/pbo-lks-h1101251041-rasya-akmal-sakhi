#!/usr/bin/env bash

set -e

BASE_URL="http://localhost:8000/api/books"

echo "=================================="
echo "   OTOMATISASI PENGUJIAN REST API"
echo "=================================="

# Test 1: GET semua buku
echo ""
echo "== Test 1: GET semua buku =="

RESPONSE=$(curl -sS "$BASE_URL")
echo "$RESPONSE" | jq '.'
echo "$RESPONSE" | jq -e '.success == true' > /dev/null

echo "Test 1 berhasil"

# Test 2: POST buku baru
echo ""
echo "== Test 2: POST buku baru =="

RESPONSE=$(curl -sS -X POST "$BASE_URL" \
    -H "Content-Type: application/json" \
    -d '{"judul":"Buku Uji Otomatis","penulis":"Penulis Pengujian","tahun":2024,"stok":5}')

echo "$RESPONSE" | jq '.'

BOOK_ID=$(echo "$RESPONSE" | jq -r '.data.id')

if [ -z "$BOOK_ID" ] || [ "$BOOK_ID" = "null" ]; then
    echo "Gagal mendapatkan ID buku baru"
    exit 1
fi

echo "ID baru: $BOOK_ID"

# Test 3: GET buku berdasarkan ID
echo ""
echo "== Test 3: GET buku ID $BOOK_ID =="

RESPONSE=$(curl -sS "$BASE_URL/$BOOK_ID")
echo "$RESPONSE" | jq '.'

echo "$RESPONSE" | jq -e '.success == true' > /dev/null

echo "Test 3 berhasil"

# Test 4: PUT ubah buku
echo ""
echo "== Test 4: PUT ubah buku =="

RESPONSE=$(curl -sS -X PUT "$BASE_URL/$BOOK_ID" \
    -H "Content-Type: application/json" \
    -d '{"judul":"Buku Uji Otomatis Revisi","penulis":"Penulis Pengujian","tahun":2025,"stok":8}')

echo "$RESPONSE" | jq '.'
echo "$RESPONSE" | jq -e '.success == true' > /dev/null

echo "Test 4 berhasil"

# Test 5: DELETE buku
echo ""
echo "== Test 5: DELETE buku =="

RESPONSE=$(curl -sS -X DELETE "$BASE_URL/$BOOK_ID")
echo "$RESPONSE" | jq '.'

echo "$RESPONSE" | jq -e '.success == true' > /dev/null

echo "Test 5 berhasil"

# Test 6: Validasi erro
echo ""
echo "== Test 6: Validasi error =="

RESPONSE=$(curl -sS -X POST "$BASE_URL" \
    -H "Content-Type: application/json" \
    -d '{"judul":""}')

echo "$RESPONSE" | jq '.'

echo "$RESPONSE" | jq -e '.success == false and .message == "Judul wajib diisi"' > /dev/null

echo "Test 6 berhasil"

echo ""
echo "=================================="
echo "   SEMUA 6 TEST BERHASIL"
echo "=================================="