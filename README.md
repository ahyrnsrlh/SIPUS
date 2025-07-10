# 📄 Form Desain Aplikasi Pemberkasan

Aplikasi web manajemen berkas dengan sistem rak, role user & admin, serta persetujuan akun.

---

## 🧑 Form Pendaftaran Akun

### Pendaftaran Akun Baru

- **Nama Lengkap**: [______________________]  
- **Email**: [______________________]  
- **Password**: [__________]  
- **Konfirmasi Password**: [__________]  
- **NIK (opsional)**: [__________________]  
- **Instansi/Divisi**: [__________________]  

[📥 Daftar]

> ⚠️ Akun perlu persetujuan dari admin sebelum dapat login.

---

## 🔐 Form Login

### Login Pengguna

- **Email**: [______________________]  
- **Password**: [__________]  

[🔓 Login] [Lupa Password?]

---

## 🧾 Form Persetujuan Akun (Admin)

### Persetujuan Akun User Baru

- **Nama**: Akhyar Nasrullah Adilian  
- **Email**: akhyar@example.com  
- **Instansi**: Universitas Lampung  

[✅ Setujui] [❌ Tolak]

---

## 📁 Form Tambah Rak (Admin)

### Tambah Rak Baru

- **Nama Rak**: [__________________]  
- **Deskripsi (opsional)**: [__________________________]  

[➕ Tambah Rak]

---

## 📂 Form Tambah Sub-Rak (Admin)

### Tambah Sub-Rak Baru

- **Pilih Rak Induk**: [Dropdown Rak]  
- **Nama Sub-Rak**: [___________________]  
- **Deskripsi (opsional)**: [___________________]  

[➕ Tambah Sub-Rak]

---

## 📤 Form Upload File (User/Admin)

### Upload File Baru

- **Pilih Rak**: [Dropdown Rak]  
- **Pilih Sub-Rak (jika ada)**: [Dropdown Sub-Rak]  
- **Judul File**: [___________________]  
- **File**: [Upload Button] (PDF, JPG, PNG, DOCX, ZIP, dll)  
- **Deskripsi (opsional)**: [____________________________]  
- **Tag (opsional)**: [_________, _________] (select2 / chip style)

[⬆️ Upload File]

---

## 🔍 Form Filter & Pencarian File

### Filter & Cari File

- **Kata Kunci**: [__________________]  
- **Rak**: [Dropdown Rak]  
- **Sub-Rak**: [Dropdown Sub-Rak]  
- **Uploader**: [Dropdown User]  
- **Tanggal Upload**: [Dari 📅____] – [Sampai 📅____]  
- **Tag**: [Multi-select]  

[🔎 Cari] [🔁 Reset]

---

## 📝 Form Edit Metadata File

### Edit File (Hanya Uploader atau Admin)

- **Judul File**: [__________________]  
- **Deskripsi**: [__________________]  
- **Tag**: [__________, ___________]  

[💾 Simpan Perubahan]

---

## 🗑️ Form Hapus File

### Konfirmasi Penghapusan File

❗ Anda yakin ingin menghapus file:  
**laporan-keuangan-q1-2025.pdf**?

[🗑️ Hapus File] [❌ Batal]

> ⚠️ User hanya bisa menghapus file miliknya sendiri. Admin bisa menghapus semua file.