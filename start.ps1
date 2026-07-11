<#
.SYNOPSIS
    Menjalankan seluruh layanan Laravel SIG-Udeung dalam satu jendela Windows Terminal.
.DESCRIPTION
    Layout: | Server (serve) | Vite+Queue (atas-bawah) |
    Membutuhkan Windows Terminal (wt.exe) — sudah built-in di Windows 11.
#>

$ProjectDir = "C:\laragon\www\desaku"

if (-not (Get-Command wt.exe -ErrorAction SilentlyContinue)) {
    Write-Host "Error: Windows Terminal (wt.exe) tidak ditemukan." -ForegroundColor Red
    Write-Host "Install dari Microsoft Store atau buka terminal biasa lalu jalankan:" -ForegroundColor Yellow
    Write-Host "  php artisan serve" -ForegroundColor Green
    Write-Host "  npm run dev" -ForegroundColor Green
    exit 1
}

Write-Host "Memulai layanan SIG-Udeung..." -ForegroundColor Cyan

# Layar kiri: Laravel API server
wt --window 0 -d "$ProjectDir" cmd /c "title [API] php artisan serve & echo === SIG-Udeung - API Server (http://localhost:8000) === & php artisan serve --host=0.0.0.0 --port=8000" `

# Layar kanan atas: Vite
; split-pane -V -d "$ProjectDir" cmd /c "title [Vite] npm run dev & echo === SIG-Udeung - Vite Dev Server (http://localhost:5173) === & npm run dev" `

# Layar kanan bawah: Queue worker
; wt -w 0 sp -H -d "$ProjectDir" cmd /c "title [Queue] queue:work & echo === SIG-Udeung - Queue Worker === & php artisan queue:work --verbose"

Write-Host ""
Write-Host "  ✓ Server  → http://localhost:8000" -ForegroundColor Green
Write-Host "  ✓ Vite    → http://localhost:5173" -ForegroundColor Green
Write-Host "  ✓ Queue   → Active" -ForegroundColor Green
Write-Host ""
Write-Host "  Layout:  [Server] | [Vite]" -ForegroundColor Cyan
Write-Host "                      [Queue]" -ForegroundColor Cyan
Write-Host ""
Write-Host "  Tutup pane individual → Ctrl+C atau klik X." -ForegroundColor Yellow
