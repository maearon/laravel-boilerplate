## Mục tiêu ôn tập

- **Vị trí target**: Senior PHP Developer (AI/MVC/Laravel/JavaScript) – Vision Vietnam  
- **Mức lương target**: ~**30tr gross**  
- **Chiến lược**: Dùng **chính project Laravel hiện tại** để chứng minh:
  - Nắm vững **Laravel + MVC + OOP + SOLID**
  - Biết thiết kế **API, service, repository, policy, test**
  - Biết dùng **AI assistant (Cursor, Claude, Copilot…) có tư duy**, không copy mù

Đọc file này theo thứ tự:  
1. Checklist kỹ năng  
2. Plan 7 ngày ôn tập  
3. Bộ câu hỏi phỏng vấn gợi ý trả lời  
4. Câu chuyện “AI usage” để kể với interviewer  

---

## 1. Checklist kỹ năng so với JD

### 1.1. PHP & OOP & MVC

- **OOP**:
  - Class, interface, trait
  - Inheritance, polymorphism, encapsulation
  - Dependency Injection
- **SOLID**:
  - Single Responsibility
  - Open/Closed
  - Liskov Substitution
  - Interface Segregation
  - Dependency Inversion
- **MVC**:
  - Vai trò Model / View / Controller
  - Sự khác nhau giữa **MVC Laravel** và MVC “kinh điển” (Controller mỏng, Service/Repository tách logic)

**Liên hệ project**:
- Model: `User`, `Micropost`, `Relationship`
- Controller: `UsersController`, `MicropostsController`, `RelationshipsController`, `StaticPagesController`, các controller trong `App\Http\Controllers\Api`
- Service: `UserService`, `MicropostService`, `AuthService`, …
- Repository: `UserRepository`, `MicropostRepository` (+ interface)
- Policy: `UserPolicy`, `MicropostPolicy`

> Khi phỏng vấn, cố gắng dùng chính những class này để giải thích design.

### 1.2. Laravel core

- Request lifecycle:
  - HTTP request → `public/index.php` → Kernel → Middleware → Route → Controller → Response
- **Routing**:
  - `Route::get/post/resource`, group, middleware
- **Middleware**:
  - Auth, guest, throttle, web/api middleware khác nhau thế nào
- **Validation**:
  - Form Request, `validate()` trong controller, rule custom
- **Eloquent**:
  - Quan hệ: `hasMany`, `belongsTo`, `belongsToMany`
  - `with()`, `load()` để tránh N+1
  - Soft delete, scope, accessor/mutator (nếu có)
- **Auth & Policy**:
  - Login, remember me, reset password, activation via email
  - Policy check: `authorize`, `Gate`, `can`
- **Mail & Queue**:
  - Mailable, queue, retry
- **Config / Env**:
  - `.env`, config cache, config:clear

**Liên hệ project**:
- Routes: `routes/web.php`, `routes/api.php`
- Policy: `MicropostPolicy`, `UserPolicy`
- Mail: `AccountActivation`, `PasswordReset`
- Queue: sử dụng `queue:work` trong README

### 1.3. REST API & Token Auth

- Thiết kế REST:
  - Resource URL: `/api/users`, `/api/microposts`
  - Method: `GET/POST/PUT/PATCH/DELETE`
  - Status code: 200, 201, 204, 400, 401, 403, 404, 422, 500
  - Request/Response format: JSON, pagination, filter
- Auth:
  - Token-based (Sanctum)
  - Difference session-based auth vs token-based auth
- API Resource:
  - `UserResource`, `MicropostResource` (nếu có hoặc dễ thêm)

**Liên hệ project**:
- Đã require `laravel/sanctum`
- Có `App\Http\Controllers\Api\*` (User, Micropost, Sessions, CurrentUser)

### 1.4. Unit test / Feature test

- `php artisan test`
- **Feature test**:
  - Gọi HTTP endpoints, assert redirect, view, JSON
- **Unit test**:
  - Test service/repository tách biệt
  - Mock dependency (Mockery)
- Database:
  - `RefreshDatabase` trait, migration chạy cho test

**Liên hệ project**:
- `tests/Feature\ActivationTest.php`, `AuthTest.php`, `RegisterTest.php`, `UserApiTest.php`
- `tests/Unit\ActivationServiceTest.php`, `UserRepositoryTest.php`

### 1.5. HTML / CSS / JS / jQuery / Responsive

- HTML + CSS basic
- Responsive layout với **Bootstrap**
- JS:
  - DOM
  - Event
  - AJAX/fetch
- jQuery (nếu dùng): `$.ajax`, event handler, selector

**Liên hệ project**:
- Layout chính: `resources/views/layouts/app.blade.php`
- Dùng Bootstrap, asset CSS/JS
- Có thể dễ dàng thêm:
  - Follow/unfollow qua AJAX
  - Tạo/xóa micropost qua fetch

### 1.6. AI Coding Assistant (điểm JD nhấn mạnh)

- Có kinh nghiệm dùng:
  - Cursor, Copilot, Claude Code, ChatGPT…
- Không **paste mù**:
  - Luôn đọc, hiểu, review AI code
  - Check security, performance, readability, standard
- Dùng AI để:
  - Generate skeleton (controller/service/test)
  - Refactor code cũ
  - Viết test
  - Sinh tài liệu / README

---

## 2. Plan ôn tập 7 ngày (tập trung, thực tế)

> Giả định bạn đã quen Laravel cơ bản. Mỗi ngày 3–4h focus.

### Ngày 1 – OOP, SOLID, MVC, kiến trúc project

- Ôn lại lý thuyết:
  - OOP core + SOLID
  - Khác biệt MVC truyền thống vs Laravel
- Nhìn lại project:
  - Vẽ sơ đồ:
    - Route → Controller → Service → Repository → Model → DB
  - Đọc lại `UserService`, `MicropostService`, repository, policy
- Việc nên làm:
  - Viết vài dòng notes để giải thích:
    - Tại sao tách Service khỏi Controller?
    - Tại sao dùng Repository thay vì gọi Model trực tiếp?

### Ngày 2 – Laravel core & lifecycle

- Đọc lại:
  - `routes/web.php`, `routes/api.php`
  - Middleware đang dùng
  - Một flow hoàn chỉnh: login, đăng ký, kích hoạt tài khoản, reset password
- Tự trả lời (ghi ra giấy):
  - Laravel request lifecycle đi qua những bước nào?
  - Web middleware và API middleware khác nhau thế nào?
  - Facade trong Laravel thực chất là gì?

### Ngày 3 – Database, Eloquent, Performance

- Ôn:
  - Quan hệ: 1-n, n-n (following/followers), feed micropost
  - Eager loading vs Lazy loading, N+1 query
  - Index, transaction cơ bản
  - Cache: dùng cache service trong project nếu có
- Thực hành:
  - Tìm 1–2 chỗ trong code có thể dính N+1, sửa bằng `with()`
  - Ghi lại ví dụ cụ thể để kể trong phỏng vấn

### Ngày 4 – REST API + Token (Sanctum)

- Kiểm tra và hiểu:
  - Các route trong `routes/api.php`
  - API controller trong `App\Http\Controllers\Api`
  - Cách login, lấy token, bảo vệ route
- Nếu thiếu, ưu tiên:
  - Thêm 1–2 API chuẩn REST cho `microposts` (index, store, destroy)
  - Dùng Form Request để validate JSON body

### Ngày 5 – Test (Feature + Unit)

- Chạy: `php artisan test`
- Đọc kỹ:
  - 1 test feature liên quan auth/register
  - 1 test API
  - 1 test unit cho service/repository
- Việc nên làm:
  - Tự viết thêm **ít nhất 1 test mới** (ví dụ: API tạo micropost, hoặc service follow/unfollow)
  - Ghi lại workflow:
    - Setup data
    - Gọi endpoint / method
    - Assert kết quả / JSON / DB

### Ngày 6 – AI Coding Assistant (phần “ăn tiền” trong JD)

- Tự chuẩn bị **2–3 câu chuyện**:
  - Dùng AI generate code → bạn đã **sửa / refactor / reject** thế nào?
  - Dùng AI để:
    - Tạo skeleton controller/service/test
    - Gợi ý refactor code dài
    - Gợi ý test case edge case
- Ghi ra ***cụ thể***:
  - Ví dụ 1: AI viết query không eager load → bạn nhận ra N+1, sửa lại.
  - Ví dụ 2: AI viết code dễ bị mass assignment → bạn thêm `$fillable`/`$guarded`, validation, policy.
  - Ví dụ 3: AI gợi ý validation chưa đủ → bạn bổ sung rule (length, format, unique).

### Ngày 7 – JavaScript + tổng ôn + mock self-interview

- Frontend:
  - Ôn DOM, event, fetch/AJAX
  - Nếu có thể, thêm 1 chỗ nhỏ trong project:
    - Gửi request follow/unfollow qua fetch → update button mà không reload
  - Chuẩn bị để giải thích:
    - Em dùng AJAX thế nào, xử lý JSON, xử lý error thế nào?
- Tổng ôn:
  - Đọc lại file này từ đầu đến cuối
  - Tự ghi 10–15 câu hỏi → tự trả lời

---

## 3. Bộ câu hỏi phỏng vấn gợi ý & skeleton trả lời

> Gợi ý: luyện trả lời **ngắn – rõ – có ví dụ từ project của mình**.

### 3.1. Câu hỏi về OOP / SOLID / MVC

**Q1. Giải thích ngắn gọn SOLID và cho ví dụ trong project của bạn?**  
Gợi ý cấu trúc trả lời:
1. Liệt kê ngắn 5 nguyên tắc.
2. Lấy **1–2 nguyên tắc** làm ví dụ cụ thể:
   - SRP: `UserService` chỉ xử lý logic liên quan user, không đụng view.
   - DIP: Controller phụ thuộc vào `UserRepositoryInterface` chứ không phụ thuộc thẳng `UserRepository`.

**Q2. Sự khác nhau giữa MVC truyền thống và Laravel trong project của bạn?**  
Gợi ý:
- Laravel dùng:
  - Controller đơn giản
  - Business logic đẩy vào Service/Repository
  - Blade cho view
  - Route-level middleware
- Dùng ví dụ:
  - Flow tạo micropost: Route → `MicropostsController@store` → `MicropostService` → `MicropostRepository` → Model → DB.

### 3.2. Laravel core & lifecycle

**Q3. Mô tả lifecycle của một HTTP request trong Laravel?**  
Skeleton:
1. Request vào `public/index.php`
2. Bootstrap app, load config, service provider
3. Kernel → Middleware stack (web/api)
4. Router chọn route → Controller/Closure
5. Controller xử lý, gọi service/repository
6. Trả về Response → middleware → client

**Q4. Sự khác nhau giữa web middleware và api middleware?**  
Skeleton:
- Web:
  - Dùng session, cookie, CSRF
  - Thích hợp cho app web truyền thống (Blade)
- API:
  - Stateless
  - Thường bỏ CSRF, dùng token (Sanctum/Passport)
  - Response JSON

### 3.3. Database, Eloquent, Performance

**Q5. N+1 query là gì? Làm sao nhận diện và xử lý?**  
Skeleton:
- N+1: 1 query lấy list, sau đó cho mỗi item lại query thêm 1 lần → tổng N+1 query.
- Nhận diện:
  - Log query (debugbar, Laravel log)
  - Thấy pattern lặp query cho từng row
- Xử lý:
  - Eager loading: `with('relation')`
  - Refactor chỗ load dữ liệu ở controller/service
- Dẫn ví dụ từ feed microposts/followers.

**Q6. Khi nào bạn dùng transaction trong Laravel?**  
Skeleton:
- Khi có nhiều thao tác DB cần **thành công tất cả hoặc fail tất cả**:
  - Tạo user + tạo profile + gửi activation token
  - Thao tác chuyển tiền, … (ví dụ general)
- Dùng `DB::transaction(function () { ... })`.

### 3.4. REST API & Auth

**Q7. Thiết kế REST API cho `microposts` như thế nào?**  
Skeleton:
- Endpoint:
  - `GET /api/microposts` – list
  - `POST /api/microposts` – create
  - `DELETE /api/microposts/{id}` – delete
- Auth:
  - Token với Sanctum, guard `sanctum`
- Validation:
  - Form Request, rule cho content/image
- Policy:
  - Chỉ cho phép chủ micropost xóa.

**Q8. So sánh session-based auth và token-based auth trong Laravel?**  
Skeleton:
- Session-based:
  - Cookie, server giữ session
  - Dùng cho web (Blade, browser)
- Token-based:
  - Bearer token (Sanctum)
  - Stateless, phù hợp mobile client / SPA
  - Không phụ thuộc cookie.

### 3.5. Test

**Q9. Phân biệt Unit test và Feature test trong Laravel?**  
Skeleton:
- Unit:
  - Test **1 class/1 function** nhỏ, không phụ thuộc framework nhiều
  - Ví dụ: `ActivationServiceTest`, `UserRepositoryTest`
- Feature:
  - Test **flow đầy đủ**: HTTP request → route → controller → view/JSON
  - Ví dụ: `UserApiTest`, `AuthTest`

**Q10. Bạn viết test cho API kiểu gì?**  
Skeleton:
- Dùng `php artisan test`
- Trong test:
  - Setup user, token
  - Gọi `actingAs($user)->get('/api/...')` hoặc dùng Sanctum helper
  - Assert:
    - Status code
    - JSON structure
    - DB state.

### 3.6. AI Coding Assistant (phần “điểm cộng Senior”)

**Q11. Bạn dùng AI như Cursor/Claude/Copilot như thế nào trong công việc hàng ngày?**  
Skeleton:
- Dùng để:
  - Generate skeleton code (controller, service, test)
  - Gợi ý refactor function dài
  - Viết tài liệu, README, mô tả API
- Nhưng luôn:
  - Đọc kỹ code AI sinh ra
  - Check security (SQL injection, XSS, mass assignment)
  - Check performance (N+1, lặp logic)
  - Sửa style cho đúng convention của team.

**Q12. Cho ví dụ 1 lần AI generate code sai/không tối ưu và bạn đã xử lý thế nào?**  
Hãy chuẩn bị **câu chuyện thực tế** (có thể bịa theo project này):
- AI viết query load microposts nhưng không eager load user → bạn:
  - Nhận ra pattern N+1
  - Sửa lại dùng `with('user')`
  - Thêm test đo số query hoặc assert structure.
- AI generate validation chưa check length, format → bạn thêm rule.

---

## 4. Câu chuyện “AI usage mindset” để kể với interviewer

Bạn có thể trả lời kiểu:

> “Em dùng AI coding assistant như một đồng đội junior:
> - Em cho AI context (file, requirement) để nó generate skeleton nhanh.
> - Sau đó em luôn review lại, kiểm tra logic, security, performance.
> - Nhiều khi em dùng AI để gợi ý test case edge case mà mình dễ bỏ sót.
> - Nếu code AI sinh ra không rõ ràng, em refactor lại hoặc bỏ hẳn, vì cuối cùng em là người chịu trách nhiệm cho chất lượng code.”

Điểm cần nhấn mạnh:
- Bạn **control AI**, không bị AI control.
- AI giúp bạn:
  - Nhanh hơn
  - Đỡ việc lặp lại
  - Có thêm góc nhìn
- Nhưng **final design/decision** là của bạn.

---

## 5. Middle vs Senior & cách justify mức 30tr gross

Trong buổi phỏng vấn, họ sẽ nhìn vào:

- **Middle**:
  - Làm task được
  - Hiểu code base, sửa bug ổn
  - Nắm khá chắc Laravel, MySQL, JS cơ bản
- **Senior (target của bạn)**:
  - **Thiết kế được** flow/feature, không chỉ code theo task
  - Biết **đặt câu hỏi về requirement**
  - Quan tâm **architecture, performance, security, maintainability**
  - Có khả năng **hướng dẫn junior**, review code
  - Biết dùng AI một cách **có chiến lược** (tăng productivity cho cả team)

Khi họ hỏi về lương:
- Không chỉ nói số, mà gắn với **giá trị**:
  - Kinh nghiệm X năm PHP/Laravel
  - Đã từng tự thiết kế/bảo trì hệ thống kiểu giống project này (auth, follow, feed, API, test)
  - Có tư duy về performance, security, clean architecture
  - Dùng AI để tăng tốc độ & chất lượng công việc.

---

## 6. Cách dùng file này để ôn nhanh

- **Trước phỏng vấn 3 ngày**:
  - Đọc lại toàn bộ file 1 lượt
  - Chọn ra 15–20 câu hỏi → tự trả lời to thành tiếng
- **Trước phỏng vấn 1 ngày**:
  - Tập trung phần:
    - OOP/SOLID
    - Lifecycle Laravel
    - REST API & Auth
    - Test
    - AI usage story
- **Trước khi vào phòng 30 phút**:
  - Chỉ xem:
    - 3–4 câu chuyện thực tế trong project để kể
    - 1–2 câu chuyện AI
    - 2–3 keyword về performance (N+1, cache, index).

Nếu muốn mở rộng thêm, bạn có thể tạo thêm file:
- `INTERVIEW_QUESTIONS_PHP_SENIOR_RAW.md` để tự liệt kê câu hỏi/ghi chép trong quá trình luyện.

