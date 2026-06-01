# Security Policy

## Reporting Security Vulnerabilities

Jika Anda menemukan kerentanan keamanan dalam SIG-Udeung, **jangan** membuat public issue. Sebaliknya, silakan kirim email ke:

**Email**: security@gampong-udeung.go.id

Harap sertakan:
- Deskripsi kerentanan
- Langkah-langkah untuk mereproduksi
- Dampak potensial
- Saran perbaikan (jika ada)

Kami akan merespons dalam 48 jam dan bekerja dengan Anda untuk menyelesaikan masalah.

## Security Features

### Authentication & Authorization
- ✅ Laravel Sanctum untuk token-based authentication
- ✅ Bearer token dengan HTTP-Only cookies
- ✅ Role-based access control (RBAC)
- ✅ NIK-based authentication untuk warga
- ✅ Username/password untuk admin

### Data Protection
- ✅ TLS 1.3 encryption (HTTPS)
- ✅ SHA-256 hashing untuk QR Code TTE
- ✅ Bcrypt password hashing (12 rounds)
- ✅ Input validation pada semua endpoint
- ✅ Output encoding untuk XSS prevention

### Database Security
- ✅ Prepared statements (Eloquent ORM)
- ✅ SQL injection protection
- ✅ Foreign key constraints
- ✅ Proper indexing
- ✅ Regular backups

### API Security
- ✅ Rate limiting (60 req/min per IP)
- ✅ CORS configuration
- ✅ CSRF protection
- ✅ Request validation
- ✅ Response sanitization

### Audit & Logging
- ✅ Comprehensive audit logging
- ✅ User action tracking
- ✅ Data change history
- ✅ IP address logging
- ✅ User agent logging

## Security Best Practices

### For Developers

1. **Never commit secrets**
   - Use `.env` file untuk sensitive data
   - Add `.env` ke `.gitignore`
   - Use environment variables

2. **Input Validation**
   ```php
   $request->validate([
       'nik' => 'required|string|size:16',
       'email' => 'required|email',
   ]);
   ```

3. **Output Encoding**
   ```blade
   {{ $user->name }}  {{-- Auto-escaped --}}
   {!! $html !!}      {{-- Only for trusted HTML --}}
   ```

4. **SQL Injection Prevention**
   ```php
   // ✅ Good - Using Eloquent
   $user = User::where('nik', $nik)->first();
   
   // ❌ Bad - Raw SQL
   $user = DB::select("SELECT * FROM users WHERE nik = '$nik'");
   ```

5. **Authentication**
   ```php
   // ✅ Good - Using middleware
   Route::middleware('auth:sanctum')->group(function () {
       Route::get('/profile', [ProfileController::class, 'show']);
   });
   ```

6. **Password Hashing**
   ```php
   // ✅ Good - Using Hash facade
   $user->password = Hash::make($password);
   
   // ❌ Bad - Plain text
   $user->password = $password;
   ```

### For DevOps

1. **Environment Setup**
   - Use strong database passwords
   - Enable SSL/TLS
   - Configure firewall rules
   - Use VPN untuk admin access

2. **Database Security**
   - Regular backups
   - Encryption at rest
   - Restrict database access
   - Monitor database logs

3. **Server Security**
   - Keep OS updated
   - Keep dependencies updated
   - Use strong SSH keys
   - Disable root login
   - Configure fail2ban

4. **Monitoring**
   - Monitor error logs
   - Monitor access logs
   - Setup alerts
   - Regular security audits

## Dependency Management

### Keeping Dependencies Updated

```bash
# Check for outdated packages
composer outdated
npm outdated

# Update packages
composer update
npm update

# Check for vulnerabilities
composer audit
npm audit
```

### Vulnerable Dependencies

Jika ditemukan vulnerable dependency:

1. Update ke versi yang aman
2. Test thoroughly
3. Deploy ke production
4. Monitor untuk issues

## Deployment Security

### Pre-Deployment Checklist

- [ ] Set `APP_DEBUG=false` di production
- [ ] Set `APP_ENV=production`
- [ ] Generate strong `APP_KEY`
- [ ] Configure SSL certificate
- [ ] Setup firewall rules
- [ ] Configure rate limiting
- [ ] Setup monitoring & logging
- [ ] Configure backups
- [ ] Test disaster recovery
- [ ] Review security headers

### Security Headers

```nginx
# Nginx configuration
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-Content-Type-Options "nosniff" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Referrer-Policy "no-referrer-when-downgrade" always;
add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;
```

## Incident Response

### If a Security Issue is Discovered

1. **Immediate Actions**
   - Isolate affected systems
   - Stop data leakage
   - Preserve evidence

2. **Investigation**
   - Determine scope
   - Identify root cause
   - Assess impact

3. **Remediation**
   - Fix vulnerability
   - Test fix thoroughly
   - Deploy to production

4. **Communication**
   - Notify affected users
   - Provide guidance
   - Update documentation

5. **Post-Incident**
   - Conduct review
   - Update security measures
   - Document lessons learned

## Security Resources

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Laravel Security](https://laravel.com/docs/security)
- [PostgreSQL Security](https://www.postgresql.org/docs/current/sql-syntax.html)
- [CWE Top 25](https://cwe.mitre.org/top25/)

## Compliance

### Data Protection
- Comply dengan regulasi data protection lokal
- Implement privacy by design
- Regular privacy audits
- User consent management

### Audit Trail
- Maintain comprehensive audit logs
- Retain logs untuk minimum 1 tahun
- Secure log storage
- Regular log review

## Support

Untuk pertanyaan keamanan:

- **Email**: security@gampong-udeung.go.id
- **Documentation**: [docs/README.md](docs/README.md)
- **Issues**: GitHub Issues (untuk non-security issues)

---

**Last Updated**: June 1, 2026
**Version**: 1.0.0
