#!/bin/bash

# Remove PHP DocBlock comments
find . -name "*.php" -type f -exec sed -i 's|/\*\*.*\*/||g; s|/\*.*\*/||g; s|//.*$||g' {} \;

# Remove Blade comments
find . -name "*.blade.php" -type f -exec sed -i 's|{{-- .*--}}||g' {} \;

echo "All comments have been removed from PHP and Blade files."
