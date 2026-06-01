<?php

namespace Tests\Unit\Models;

use App\Models\Penduduk;
use App\Models\Keluarga;
use Tests\TestCase;

class PendudukTest extends TestCase
{
    public function test_penduduk_can_be_created()
    {
        $keluarga = Keluarga::create([
            'no_kk' => '1234567890123456',
            'alamat' => 'Jl. Test No. 123',
            'dusun' => 'Dusun Test',
            'rt_rw' => '001/002',
        ]);

        $penduduk = Penduduk::create([
            'nik' => '1234567890123456',
            'no_kk' => $keluarga->no_kk,
            'nama_lengkap' => 'John Doe',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => 'S1',
            'pekerjaan' => 'Programmer',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anak',
            'status_mutasi' => 'Tetap',
        ]);

        $this->assertDatabaseHas('penduduk', [
            'nik' => '1234567890123456',
            'nama_lengkap' => 'John Doe',
        ]);
    }

    public function test_penduduk_has_keluarga_relationship()
    {
        $keluarga = Keluarga::create([
            'no_kk' => '1234567890123456',
            'alamat' => 'Jl. Test No. 123',
            'dusun' => 'Dusun Test',
            'rt_rw' => '001/002',
        ]);

        $penduduk = Penduduk::create([
            'nik' => '1234567890123456',
            'no_kk' => $keluarga->no_kk,
            'nama_lengkap' => 'John Doe',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => 'S1',
            'pekerjaan' => 'Programmer',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anak',
        ]);

        $this->assertInstanceOf(Keluarga::class, $penduduk->keluarga);
        $this->assertEquals($keluarga->no_kk, $penduduk->keluarga->no_kk);
    }

    public function test_penduduk_umur_accessor()
    {
        $keluarga = Keluarga::create([
            'no_kk' => '1234567890123456',
            'alamat' => 'Jl. Test No. 123',
            'dusun' => 'Dusun Test',
            'rt_rw' => '001/002',
        ]);

        $penduduk = Penduduk::create([
            'nik' => '1234567890123456',
            'no_kk' => $keluarga->no_kk,
            'nama_lengkap' => 'John Doe',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => now()->subYears(30),
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => 'S1',
            'pekerjaan' => 'Programmer',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anak',
        ]);

        $this->assertEquals(30, $penduduk->umur);
    }

    public function test_penduduk_aktif_scope()
    {
        $keluarga = Keluarga::create([
            'no_kk' => '1234567890123456',
            'alamat' => 'Jl. Test No. 123',
            'dusun' => 'Dusun Test',
            'rt_rw' => '001/002',
        ]);

        Penduduk::create([
            'nik' => '1111111111111111',
            'no_kk' => $keluarga->no_kk,
            'nama_lengkap' => 'Active User',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => 'S1',
            'pekerjaan' => 'Programmer',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anak',
            'status_mutasi' => 'Tetap',
        ]);

        Penduduk::create([
            'nik' => '2222222222222222',
            'no_kk' => $keluarga->no_kk,
            'nama_lengkap' => 'Inactive User',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'L',
            'agama' => 'Islam',
            'pendidikan' => 'S1',
            'pekerjaan' => 'Programmer',
            'status_perkawinan' => 'Belum Kawin',
            'status_keluarga' => 'Anak',
            'status_mutasi' => 'Pindah',
        ]);

        $aktif = Penduduk::aktif()->get();

        $this->assertCount(1, $aktif);
        $this->assertEquals('Active User', $aktif->first()->nama_lengkap);
    }
}
