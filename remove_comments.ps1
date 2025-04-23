# This PowerShell script removes comments from PHP and Blade files

# Get all PHP files
$phpFiles = Get-ChildItem -Path . -Filter *.php -Recurse

# Process each PHP file
foreach ($file in $phpFiles) {
    $content = Get-Content -Path $file.FullName -Raw
    
    # Remove multiline docblock comments
    $content = $content -replace '/\*\*[\s\S]*?\*/', ''
    
    # Remove multiline comments
    $content = $content -replace '/\*[\s\S]*?\*/', ''
    
    # Remove single line comments
    $content = $content -replace '//.*$', ''
    
    # Save the file
    Set-Content -Path $file.FullName -Value $content
}

# Get all Blade files
$bladeFiles = Get-ChildItem -Path . -Filter *.blade.php -Recurse

# Process each Blade file
foreach ($file in $bladeFiles) {
    $content = Get-Content -Path $file.FullName -Raw
    
    # Remove Blade comments
    $content = $content -replace '{{--[\s\S]*?--}}', ''
    
    # Save the file
    Set-Content -Path $file.FullName -Value $content
}

Write-Output "All comments have been removed from PHP and Blade files."
