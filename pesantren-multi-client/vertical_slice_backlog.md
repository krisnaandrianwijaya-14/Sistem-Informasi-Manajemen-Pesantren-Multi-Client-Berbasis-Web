# MVP Backlog – 3‑Person Vertical Slice

## 📂 Struktur Folder Laravel (Modular)
```
laravel-project/
│
├─ app/
│   ├─ Models/
│   │   ├─ Tenant.php                # shared
│   │   ├─ User.php                  # shared (platform admin)
│   │   ├─ Role.php                  # shared
│   │   ├─ Permission.php            # shared
│   │   ├─ AuditLog.php              # shared
│   │   ├─ TenantUser.php            # shared pivot
│   │   ├─ B/                     # Modul B – Akademik
│   │   │   ├─ Student.php
│   │   │   ├─ Teacher.php
│   │   │   ├─ SchoolClass.php
│   │   │   ├─ Subject.php
│   │   │   ├─ Schedule.php
│   │   │   └─ AcademicAttendance.php
│   │   └─ C/                     # Modul C – Asrama & Izin
│   │       ├─ Guardian.php
│   │       ├─ StudentGuardian.php
│   │       ├─ Dormitory.php
│   │       ├─ Room.php
│   │       ├─ RoomAssignment.php
│   │       ├─ DormAttendance.php
│   │       ├─ LeaveRequest.php
│   │       └─ LeaveAction.php
│   └─ Policies/ (per‑module)
│
├─ database/
│   ├─ migrations/ (prefixed with timestamp)
│   │   ├─ 2026_11_19_000001_create_tenants_table.php
│   │   ├─ 2026_11_19_000002_create_users_table.php
│   │   ├─ 2026_11_19_000003_create_roles_table.php
│   │   ├─ 2026_11_19_000004_create_permissions_table.php
│   │   ├─ 2026_11_19_000005_create_audit_logs_table.php
│   │   ├─ 2026_11_19_000010_create_tenant_user_table.php
│   │   ├─ 2026_11_19_001000_create_students_table.php   # B
│   │   ├─ 2026_11_19_001010_create_teachers_table.php   # B
│   │   ├─ 2026_11_19_001020_create_classes_table.php    # B
│   │   ├─ 2026_11_19_001030_create_subjects_table.php   # B
│   │   ├─ 2026_11_19_001040_create_schedules_table.php   # B
│   │   ├─ 2026_11_19_001050_create_academic_attendances_table.php # B
│   │   ├─ 2026_11_19_002000_create_guardians_table.php   # C
│   │   ├─ 2026_11_19_002010_create_student_guardians_table.php # C
│   │   ├─ 2026_11_19_002020_create_dormitories_table.php    # C
│   │   ├─ 2026_11_19_002030_create_rooms_table.php          # C
│   │   ├─ 2026_11_19_002040_create_room_assignments_table.php # C
│   │   ├─ 2026_11_19_002050_create_dorm_attendances_table.php # C
│   │   ├─ 2026_11_19_002060_create_leave_requests_table.php   # C
│   │   └─ 2026_11_19_002070_create_leave_actions_table.php    # C
│   └─ seeders/
│       ├─ TenantSeeder.php           # creates Tenant A & B
│       ├─ RolePermissionSeeder.php   # RBAC basics
│       └─ DemoDataSeeder.php         # demo santri, guru, wali, dll.
│
├─ routes/
│   ├─ web.php   # UI routes – prefixed by /{tenant}
│   └─ api.php   # optional API routes – same prefix
│
├─ resources/
│   ├─ views/
│   │   ├─ layouts/app.blade.php   # shared layout (navbar, tenant‑banner)
│   │   ├─ auth/
│   │   │   └─ login.blade.php      # UI‑A (Platform login)
│   │   ├─ a/   # UI for Person A – tenant onboarding
│   │   │   └─ tenants/create.blade.php
│   │   ├─ b/   # UI for Person B – akademik
│   │   │   ├─ students/index.blade.php
│   │   │   ├─ teachers/index.blade.php
│   │   │   ├─ schedules/index.blade.php
│   │   │   └─ attendances/create.blade.php
│   │   └─ c/   # UI for Person C – asrama & izin
│   │       ├─ dorms/index.blade.php
│   │       ├─ rooms/index.blade.php
│   │       ├─ leave_requests/create.blade.php
│   │       └─ leave_requests/show.blade.php
│   └─ css/custom.css   # styling per mock‑up (color, logo, font)
│
└─ tests/
    ├─ Feature/
    │   ├─ TenantIsolationTest.php      # A
    │   ├─ AcademicFlowTest.php        # B
    │   └─ DormitoryLeaveFlowTest.php  # C
    └─ Unit/   # optional unit tests for policies
```

---

## 📋 Backlog – Per Orang (Vertical Slice)
### 🎯 Person A – Platform & Tenant Isolation
| # | User Story (US) | Tasks | Acceptance Criteria |
|---|----------------|-------|----------------------|
| A1 | **US‑A‑01**: Sebagai *Platform Admin* saya dapat **membuat tenant** baru dengan nama, domain, status aktif. | - Migration `tenants` (name, domain, is_active, tenant_id (PK))<br>- Form UI `a/tenants/create.blade.php` (mock‑up B).<br>- Controller `TenantController@store`.<br>- Policy `TenantPolicy` (only Platform Admin).<br>- Seeder `TenantSeeder` (2 tenant). | ✅ Tenant tersimpan, `tenant_id` di‑generate otomatis.<br>✅ UI menampilkan success toast.<br>✅ Tenant dapat di‑akses via sub‑domain atau prefixed route `/tenant/{id}`. |
| A2 | **US‑A‑02**: Sebagai *Platform Admin* saya dapat **mengaktifkan/menonaktifkan** tenant. | - `is_active` toggle on UI.<br>- Middleware `CheckTenantActive` blocks login for non‑active tenant.<br>- AuditLog entry. | ✅ Non‑aktif tenant tidak bisa login, tapi data tetap ada.<br>✅ Log berisi `{user, action, tenant_id}`. |
| A3 | **US‑A‑03**: Sebagai *Tenant Admin* saya dapat **login** dan diarahkan ke dashboard role‑specific. | - Fortify / Jetstream login (email, password).<br>- `TenantContext` middleware sets `tenant_id` pada request.<br>- `dashboard.blade.php` menampilkan brand tenant. | ✅ Setelah login, URL mengandung `/tenant/{slug}`.<br>✅ Data yang di‑query otomatis ter‑scoped (`where tenant_id`). |
| A4 (pendukung) | **US‑A‑04**: Sebagai *Platform Admin* saya dapat **melihat audit log** perubahan per‑tenant. | - Model `AuditLog`.<br>- UI `a/audit/index.blade.php` (table). | ✅ Log menampilkan user, aksi, timestamp, tenant.

---
### 🎯 Person B – Akademik & Absensi Guru
| # | User Story | Tasks | Acceptance Criteria |
|---|------------|-------|----------------------|
| B1 | **US‑B‑01**: Admin membuat **santri**, **guru**, **kelas**, **mata pelajaran**, **jadwal**. | - Migrations: `students`, `teachers`, `classes`, `subjects`, `schedules` (all tenant‑scoped).<br>- CRUD UI di `resources/views/b/*`.
- Validation: `schedule` tidak conflict (same room, same time). | ✅ Semua entitas dapat dibuat, di‑edit, di‑hapus (soft delete).<br>✅ Konflik jadwal muncul error message. |
| B2 | **US‑B‑02**: Guru login, melihat **jadwal mengajar** yang ditugaskan. | - Guard `role:teacher`.
- Route `/teacher/schedules` → `ScheduleController@index` (filter by `teacher_id`).
- UI list (mock‑up). | ✅ Hanya menampilkan jadwal guru yang login.
| B3 | **US‑B‑03**: Guru dapat **mengisi absensi** kelas (batch). | - Model `AcademicAttendance` (student_id, schedule_id, status, timestamp).<br>- Form batch (checkbox per santri).<br>- Policy: only teacher of the schedule may store. | ✅ Absensi tersimpan, duplikasi ditolak (unique index on schedule_id + student_id). |
| B4 | **US‑B‑04**: Admin dapat **rekap absensi** per kelas/per periode. | - Query with grouping, export CSV button.
- UI `b/attendances/report.blade.php`. | ✅ Tabel rekap menampilkan total hadir, sakit, izin.
| B5 (pendukung) | **US‑B‑05**: Admin menon‑aktifkan santri (soft delete) tetapi histori absensi tetap. | - SoftDeletes on `students`.
- UI toggle aktif/non‑aktif. | ✅ Data santri tidak muncul di daftar aktif, absensi masih dapat dilaporkan.

---
### 🎯 Person C – Asrama, Wali, & Workflow Izin
| # | User Story | Tasks | Acceptance Criteria |
|---|------------|-------|----------------------|
| C1 | **US‑C‑01**: Admin menambahkan **asrama, kamar, penempatan** santri. | - Migrations: `dormitories`, `rooms`, `room_assignments` (tenant‑scoped, capacity check).<br>- UI `c/dorms/*`, `c/rooms/*`.
- Validation: room capacity not exceeded. | ✅ Kamar tidak menerima lebih dari `capacity` santri.
| C2 | **US‑C‑02**: Wali Asrama mengisi **absensi asrama** per kamar. | - Model `DormAttendance` (room_id, date, present_count, notes).<br>- UI `c/dorm_attendances/create.blade.php` (batch per kamar). | ✅ Absensi tersimpan, hanya wali asrama yang dapat mengakses kamar‑nya.
| C3 | **US‑C‑03**: Santri atau Wali Santri dapat **mengajukan izin** (cuti, keluar). | - Model `LeaveRequest` (student_id, guardian_id, type, start_date, end_date, status).<br>- UI `c/leave_requests/create.blade.php`.
- Policy: only student/guardian of that tenant can create. | ✅ Pengajuan masuk dengan status `submitted`.
| C4 | **US‑C‑04**: Wali Asrama **meninjau & memutuskan** izin (approve/reject). | - Model `LeaveAction` (leave_request_id, reviewer_id, decision, comment).<br>- UI `c/leave_requests/show.blade.php` with approve/reject buttons.
- Guard: reviewer must belong to same tenant & have role `guardian`. | ✅ Status berubah menjadi `approved`/`rejected`, audit tercatat.
| C5 | **US‑C‑05**: Wali Santri dapat **melihat status** izin anaknya. | - Dashboard `c/guardians/children.blade.php` showing list of leave_requests with timeline. | ✅ Hanya menampilkan izin milik santri yang ter‑link ke guardian.
| C6 (pendukung) | **US‑C‑06**: Notifikasi in‑app sederhana saat status izin berubah. | - Table `notifications` (tenant‑scoped).<br>- Event `LeaveStatusChanged` fires broadcast to user via Laravel Echo (optional simple refresh). | ✅ Guardian menerima badge “new notification”. |

---
## 📅 Timeline (6‑7 minggu) – Sprints
| Sprint | Fokus | Deliverables |
|-------|-------|--------------|
| **1** (Minggu 1) | Fondasi Platform (A) + **setup repo** & CI. | - Laravel fresh install, `TenantContext` middleware, auth scaffolding, `main`/`develop` branch strategy. <br>- Migration `tenants`, `users`, `roles`, `permissions`, `audit_logs`. <br>- Seeder 2 tenant + admin user per tenant. |
| **2** (Minggu 2) | **A** selesai onboarding + isolasi <br>**B** mulai CRUD santri/kelas <br>**C** mulai asrama & kamar. | - UI onboarding, tenant login, demo data.<br>- Migrations & basic CRUD for B’s core tables (students, teachers, classes).<br>- Migrations & UI for dormitories & rooms (capacity). |
| **3** (Minggu 3) | **B** selesai jadwal + absensi guru <br>**C** selesai absensi asrama + leave request skeleton. | - Schedule conflict validation, teacher schedule view, batch attendance form.<br>- LeaveRequest model + create form, basic policy. |
| **4** (Minggu 4) | **Integrasi**: guru absen, wali asrama absen, workflow izin (submit‑review‑notify). | - End‑to‑end demo: admin → create data → teacher → record attendance → guardian → submit leave → wali approve. |
| **5** (Minggu 5) | Dashboard UI per role, **rekap** absensi, CSV export, in‑app notification UI. | - Dashboard `resources/views/layouts/app.blade.php` shows tenant banner + role badge.<br>- Report pages, CSV routes, simple notification dropdown. |
| **6** (Minggu 6) | **Testing & UAT**. | - Feature tests for each vertical slice (A‑IsolationTest, B‑AcademicFlowTest, C‑DormitoryLeaveFlowTest).<br>- Code review, bug‑fix, CI passes. |
| **7** (Minggu 7) | **Deploy & Demo**. | - Production‑ready `docker-compose.yml` (optional).<br>- README with setup steps, demo script (login as Tenant A admin, as Teacher, as Guardian).<br>- Trello/Jira board export (link). |

---
## 🛠️ Tooling & Conventions
- **Git flow**: `main` = production, `develop` = integration, `feature/<name>` per modul.
- **Branch naming**: `feature/a‑tenant‑onboarding`, `feature/b‑academic‑crud`, `feature/c‑leave‑workflow`.
- **PR policy**: minimum 1 reviewer (prefer lint‑check & unit test pass). Merge only after CI green.
- **Naming**: all tables have `tenant_id` column (unsignedBigInteger, indexed). Unique composite indexes where needed (e.g., `schedule` + `teacher_id`).
- **Policies**: placed under `app/Policies/` with naming `StudentPolicy`, `LeaveRequestPolicy`, etc., using `TenantContext` to restrict.
- **Seeder**: `DemoDataSeeder` creates sample santri, guru, kelas, jadwal, kamar, guardian, leave request untuk **Tenant A** dan **Tenant B**.
- **Testing**: use Laravel **Pest** atau **PHPUnit**, fokus pada:
  - Tenant isolation (cannot query another tenant’s data).
  - Authorization (policy denies cross‑role access).
  - Business rules (no duplicate attendance, room capacity).

---
## 📋 Trello/Jira Board Skeleton (JSON export example)
```json
{
  "name": "MVP – 3‑Person Vertical Slice",
  "lists": [
    {"name": "Backlog (A)"},
    {"name": "Backlog (B)"},
    {"name": "Backlog (C)"},
    {"name": "In Progress"},
    {"name": "Review"},
    {"name": "Done"}
  ],
  "cards": [
    {"list": "Backlog (A)", "title": "US‑A‑01 Create Tenant", "labels": ["A"], "description": "Migration, form, controller, policy, seeder."},
    {"list": "Backlog (A)", "title": "US‑A‑02 Activate/Deactivate Tenant", "labels": ["A"]},
    {"list": "Backlog (B)", "title": "US‑B‑01 CRUD Student & Class", "labels": ["B"]},
    {"list": "Backlog (B)", "title": "US‑B‑03 Teacher Attendance", "labels": ["B"]},
    {"list": "Backlog (C)", "title": "US‑C‑01 Dormitory & Room Setup", "labels": ["C"]},
    {"list": "Backlog (C)", "title": "US‑C‑03 Leave Request Flow", "labels": ["C"]}
  ]
}
```
Copy‑paste ke Trello > Menu > More > Print & Export > Import JSON untuk membuat board otomatis.

---
## ✅ Next Steps for You
1. **Konfirmasi** struktur folder di atas (apakah ingin menambah modul lain).<br>2. Beri **feedback** apakah ada tambahan/kurangi user story.
3. Jika setuju, saya dapat **generate migration skeletons** (PHP files) dan **seeder** untuk demo data.
4. Saya juga dapat **inisialisasi repo** dengan `git`‑commands (buat file `.gitignore`, `composer.json` stub) jika diperlukan.

Silakan pilih mana yang ingin saya kerjakan selanjutnya (migration files, seeder, atau board import). Saya siap melanjutkan!   