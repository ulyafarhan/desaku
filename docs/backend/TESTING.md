# 🧪 SIG-Udeung Testing Documentation

Dokumentasi lengkap untuk menjalankan dan memahami test suite SIG-Udeung.

## 📋 Daftar Isi

- [Setup Testing Environment](#setup-testing-environment)
- [Menjalankan Tests](#menjalankan-tests)
- [Test Coverage](#test-coverage)
- [Test Structure](#test-structure)
- [Writing Tests](#writing-tests)
- [Troubleshooting](#troubleshooting)

## 🔧 Setup Testing Environment

### Prerequisites

Pastikan sudah terinstall:
- PHP 8.3+
- PHPUnit (included via Composer)
- SQLite (untuk in-memory testing database)

### Configuration

File `phpunit.xml` sudah dikonfigurasi untuk:
- Menggunakan SQLite in-memory database
- Environment testing
- Cache & queue menggunakan array driver
- Auto-refresh database setiap test

## 🚀 Menjalankan Tests

### Run All Tests

```bash
# Menjalankan semua tests
php artisan test

# Atau menggunakan PHPUnit langsung
./vendor/bin/phpunit
```

### Run Specific Test Suite

```bash
# Unit tests only
php artisan test --testsuite=Unit

# Feature tests only
php artisan test --testsuite=Feature
```

### Run Specific Test File

```bash
# Run specific test file
php artisan test tests/Feature/AuthenticationTest.php

# Run specific test method
php artisan test --filter=test_warga_can_login_with_nik
```

### Run with Coverage

```bash
# Generate coverage report (requires Xdebug)
php artisan test --coverage

# Generate HTML coverage report
php artisan test --coverage-html coverage
```

### Run with Parallel Execution

```bash
# Run tests in parallel (faster)
php artisan test --parallel
```

## 📊 Test Coverage

### Current Test Coverage

#### Unit Tests (3 files)

**Models:**
- ✅ `PengaturanGampongTest` - 6 tests
  - Get/Set settings
  - Type handling (string, integer, boolean, json)
  - Default values
  
- ✅ `PendudukTest` - 4 tests
  - CRUD operations
  - Relationships
  - Umur accessor
  - Aktif scope
  
- ✅ `PengajuanSuratTest` - 3 tests
  - Auto-generate nomor registrasi
  - Relationships
  - Scopes

**Services:**
- ✅ `StatistikServiceTest` - 6 tests
  - Demografi structure & counts
  - Layanan structure
  - Cache functionality

#### Feature Tests (6 files)

**Authentication:**
- ✅ `AuthenticationTest` - 8 tests
  - Warga login (NIK)
  - Admin login (username/password)
  - Profile retrieval
  - Logout
  - Telegram binding
  - Validation & authorization

**Pengajuan Surat:**
- ✅ `PengajuanSuratTest` - 7 tests
  - Get kategori surat
  - Submit pengajuan
  - Get user's pengajuan
  - Admin approve/reject
  - Authorization checks

**Mutasi Penduduk:**
- ✅ `MutasiPendudukTest` - 6 tests
  - Submit mutasi
  - Get user's mutasi
  - Admin approve/reject
  - Status updates (Kematian, Kepindahan)

**Informasi Publik:**
- ✅ `InformasiPublikTest` - 8 tests
  - Public listing (published only)
  - Get by slug
  - Admin CRUD operations
  - Auto-generate slug
  - Filter by kategori

**Statistik:**
- ✅ `StatistikTest` - 3 tests
  - Demografi endpoint
  - Layanan endpoint
  - Cache behavior

**Verifikasi:**
- ✅ `VerifikasiTest` - 3 tests
  - Valid QR hash verification
  - Invalid hash handling
  - Unfinished document handling

### Total Test Count

- **Unit Tests:** 19 tests
- **Feature Tests:** 35 tests
- **Total:** 54 tests

## 📁 Test Structure

```
tests/
├── Feature/
│   ├── AuthenticationTest.php
│   ├── PengajuanSuratTest.php
│   ├── MutasiPendudukTest.php
│   ├── InformasiPublikTest.php
│   ├── StatistikTest.php
│   └── VerifikasiTest.php
├── Unit/
│   ├── Models/
│   │   ├── PengaturanGampongTest.php
│   │   ├── PendudukTest.php
│   │   └── PengajuanSuratTest.php
│   └── Services/
│       └── StatistikServiceTest.php
└── TestCase.php
```

## ✍️ Writing Tests

### Test Naming Convention

```php
// Good
public function test_warga_can_login_with_nik()
public function test_admin_cannot_approve_without_permission()

// Bad
public function testLogin()
public function test1()
```

### Test Structure (AAA Pattern)

```php
public function test_example()
{
    // Arrange - Setup test data
    $user = User::factory()->create();
    
    // Act - Perform action
    $response = $this->actingAs($user)
        ->postJson('/api/endpoint', ['data' => 'value']);
    
    // Assert - Verify results
    $response->assertStatus(200);
    $this->assertDatabaseHas('table', ['field' => 'value']);
}
```

### Using Factories (Optional)

Buat factory untuk model yang sering digunakan:

```php
// database/factories/PendudukFactory.php
public function definition()
{
    return [
        'nik' => $this->faker->numerify('################'),
        'nama_lengkap' => $this->faker->name,
        'tempat_lahir' => $this->faker->city,
        'tanggal_lahir' => $this->faker->date(),
        // ...
    ];
}

// Usage in tests
$penduduk = Penduduk::factory()->create();
```

### Testing API Endpoints

```php
// GET request
$response = $this->getJson('/api/v1/endpoint');

// POST request with authentication
$token = $user->createToken('test')->plainTextToken;
$response = $this->withToken($token)
    ->postJson('/api/v1/endpoint', ['data' => 'value']);

// Assertions
$response->assertStatus(200);
$response->assertJson(['key' => 'value']);
$response->assertJsonStructure(['data' => ['id', 'name']]);
```

### Testing Database

```php
// Assert record exists
$this->assertDatabaseHas('table', ['field' => 'value']);

// Assert record doesn't exist
$this->assertDatabaseMissing('table', ['field' => 'value']);

// Assert count
$this->assertDatabaseCount('table', 5);
```

### Testing Validation

```php
$response = $this->postJson('/api/endpoint', [
    'invalid' => 'data',
]);

$response->assertStatus(422)
    ->assertJsonValidationErrors(['field_name']);
```

## 🔍 Test Examples

### Example 1: Testing Authentication

```php
public function test_user_can_login()
{
    // Arrange
    $user = Penduduk::create([
        'nik' => '1234567890123456',
        // ... other fields
    ]);

    // Act
    $response = $this->postJson('/api/v1/auth/login/warga', [
        'nik' => '1234567890123456',
    ]);

    // Assert
    $response->assertStatus(200)
        ->assertJsonStructure(['token', 'user']);
}
```

### Example 2: Testing Authorization

```php
public function test_warga_cannot_access_admin_endpoint()
{
    // Arrange
    $warga = Penduduk::factory()->create();
    $token = $warga->createToken('test')->plainTextToken;

    // Act
    $response = $this->withToken($token)
        ->getJson('/api/v1/admin/endpoint');

    // Assert
    $response->assertStatus(403);
}
```

### Example 3: Testing Relationships

```php
public function test_penduduk_has_keluarga()
{
    // Arrange
    $keluarga = Keluarga::create([...]);
    $penduduk = Penduduk::create([
        'no_kk' => $keluarga->no_kk,
        // ...
    ]);

    // Act & Assert
    $this->assertInstanceOf(Keluarga::class, $penduduk->keluarga);
    $this->assertEquals($keluarga->no_kk, $penduduk->keluarga->no_kk);
}
```

## 🐛 Troubleshooting

### Common Issues

#### 1. Database Connection Error

```bash
# Error: SQLSTATE[HY000] [14] unable to open database file

# Solution: Check phpunit.xml configuration
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

#### 2. Class Not Found

```bash
# Error: Class 'Tests\Feature\SomeTest' not found

# Solution: Regenerate autoload
composer dump-autoload
```

#### 3. Migration Errors

```bash
# Error: Base table or view not found

# Solution: Ensure migrations run in setUp()
protected function setUp(): void
{
    parent::setUp();
    $this->artisan('migrate');
}
```

#### 4. Token Authentication Fails

```bash
# Error: Unauthenticated

# Solution: Ensure Sanctum is properly configured
// In tests/TestCase.php
use Laravel\Sanctum\Sanctum;

// In test method
$token = $user->createToken('test')->plainTextToken;
$this->withToken($token)->getJson('/api/endpoint');
```

### Debug Tests

```php
// Dump response
$response->dump();

// Dump and die
$response->dd();

// Dump database
dd(DB::table('table_name')->get());

// Enable query log
DB::enableQueryLog();
// ... run queries
dd(DB::getQueryLog());
```

## 📈 Continuous Integration

### GitHub Actions Example

```yaml
# .github/workflows/tests.yml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
      - uses: actions/checkout@v2
      
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: 8.3
          extensions: mbstring, pdo, sqlite
          
      - name: Install Dependencies
        run: composer install --prefer-dist --no-progress
        
      - name: Run Tests
        run: php artisan test --coverage
```

## 🎯 Best Practices

1. **Test Isolation** - Setiap test harus independen
2. **Use RefreshDatabase** - Reset database setiap test
3. **Descriptive Names** - Nama test harus jelas dan deskriptif
4. **One Assertion Per Test** - Fokus pada satu hal per test (flexible)
5. **Test Edge Cases** - Test happy path dan error cases
6. **Mock External Services** - Jangan hit API eksternal di test
7. **Fast Tests** - Keep tests fast dengan in-memory database

## 📝 Test Checklist

Sebelum commit, pastikan:

- [ ] Semua tests passing
- [ ] Coverage minimal 70%
- [ ] No skipped tests
- [ ] No debug statements (dd, dump)
- [ ] Descriptive test names
- [ ] Edge cases covered

## 🚀 Next Steps

1. **Add More Tests:**
   - Jobs testing (PDF generation, Telegram broadcast)
   - Middleware testing
   - Validation testing
   - Error handling testing

2. **Integration Tests:**
   - End-to-end workflow tests
   - External API mocking

3. **Performance Tests:**
   - Load testing
   - Query optimization

---

**Happy Testing! 🎉**
