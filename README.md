# Backend Developer Intern Test - PT Aksamedia Mulia Digital

Tes magang ini dirancang untuk mengevaluasi pemahaman dan kemampuan teknis Anda dalam membangun API menggunakan Laravel serta pemahaman terhadap SQL, validasi, dan autentikasi.

## 🛠 Tech Stack

-   Laravel
-   MySQL / MariaDB
-   PhpMyAdmin / Adminer / HeidiSQL
-   Postman (for API testing)

---

## 📋 Fitur API

### ✅ 1. Login

-   **Endpoint:** `POST /login`
-   **Deskripsi:** Autentikasi user.
-   **Catatan:** Hanya dapat diakses **tanpa** token autentikasi.
-   **Contoh Request:**

```json
{
    "username": "admin",
    "password": "pastibisa"
}
```

-   **Contoh Response:**

```json
{
    "status": "success",
    "message": "Login berhasil",
    "data": {
        "token": "jwt-token",
        "admin": {
            "id": "uuid",
            "name": "Admin",
            "username": "admin",
            "phone": "081234567890",
            "email": "admin@example.com"
        }
    }
}
```

---

### 📁 2. Get All Divisions

-   **Endpoint:** `GET /divisions`
-   **Filter:** Berdasarkan nama (opsional).
-   **Deskripsi:** Mengambil semua data divisi.
-   **Contoh Request Body (opsional):**

```json
{
    "name": "Backend"
}
```

-   **Contoh Response:**

```json
{
    "status": "success",
    "message": "Data divisi berhasil diambil",
    "data": {
        "divisions": [{ "id": "uuid", "name": "Backend" }]
    },
    "pagination": {
        "...": "..."
    }
}
```

---

### 👥 3. Get All Employees

-   **Endpoint:** `GET /employees`
-   **Filter:** Nama dan Divisi.
-   **Contoh Request Body (opsional):**

```json
{
    "name": "Bagas",
    "division_id": "uuid-divisi"
}
```

-   **Contoh Response:**

```json
{
    "status": "success",
    "message": "Employees retrieved successfully",
    "data": {
        "employees": [
            {
                "id": "uuid",
                "image": "url",
                "name": "Bagas",
                "phone": "0812...",
                "division": { "id": "uuid", "name": "Backend" },
                "position": "Developer"
            }
        ]
    },
    "pagination": {
        "...": "..."
    }
}
```

---

### ➕ 4. Create Employee

-   **Endpoint:** `POST /employees`
-   **Deskripsi:** Menambahkan data karyawan.
-   **Contoh Request:**

```json
{
    "image": "file",
    "name": "Bagas",
    "phone": "081234567890",
    "division": "uuid-divisi",
    "position": "Backend Developer"
}
```

---

### ✏️ 5. Update Employee

-   **Endpoint:** `PUT /employees/{uuid}`
-   **Deskripsi:** Mengubah data karyawan.
-   **Contoh Request:** (sama seperti create)

---

### ❌ 6. Delete Employee

-   **Endpoint:** `DELETE /employees/{uuid}`
-   **Deskripsi:** Menghapus data karyawan.

---

### 🚪 7. Logout

-   **Endpoint:** `POST /logout`
-   **Deskripsi:** Mengakhiri sesi login.

---

## 🔐 Akses API

| Endpoint   | Login Diperlukan     |
| ---------- | -------------------- |
| /login     | ❌ Tidak boleh login |
| /logout    | ✅ Harus login       |
| /divisions | ✅ Harus login       |
| /employees | ✅ Harus login       |

> Semua endpoint selain `/login` wajib menggunakan **Bearer Token Authorization**.

---

## ⚙️ Fitur Laravel yang Digunakan

-   **Auth Sanctum**
-   **Validation Requests**
-   **Eloquent ORM**
-   **Resource & Collection**
-   **Seeder & Factory**
-   **File Upload**

---

## 🚀 Deployment

> Link Repository: [https://github.com/namakamu/backend-test](#)
