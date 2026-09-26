# Lộ trình đọc skill — theo tình huống

File này không thay `SKILL.md`. Nó trả lời câu hỏi: **"Tôi đang ở tình huống X
thì đọc file nào trước, file nào sau, bỏ qua file nào."**

Quy tắc chung: **luôn đọc `SKILL.md` trước** để nắm phạm vi và các rule bắt
buộc. Sau đó chọn đúng kịch bản dưới đây.

> **Về đường dẫn:** Mọi file `*.md` nội dung nằm trong `references/`.
> Ví dụ đặt trong `assets/examples/`. Rule bàn giao nằm ngoài skill, tại
> `.agents/rules/handover-list-files.md` — không nằm trong thư mục skill này.

---

## Kịch bản 1 — Lần đầu vào một dự án Keyweb mới

**Khi nào:** Nhận dự án từ người khác, mở repo lần đầu, chưa biết cấu trúc.

**Thứ tự đọc:**

1. `SKILL.md` — nắm phạm vi, rule bắt buộc, quy trình 7 bước.
2. `references/company-conventions.md` — cấu trúc theme, cách đặt file,
   pipeline auto-enqueue.
3. `references/keyweb-options.md` §"Chọn nơi lưu" và §"Schema option Keyweb"
   — hiểu framework `_opt()` và bảng chọn nơi lưu.
4. `references/library-catalog.md` + `references/font-catalog.md` — **skim,
   không đọc hết**. Chỉ để biết có gì trong kho khi cần.
5. Mở code thật trong theme đang active: `functions.php`, `option/*.php`,
   `css/1.*.css`, `js/2.*.js` — đối chiếu với tài liệu, phát hiện drift.

**Bỏ qua ở giai đoạn này:** `references/page-custom-fields.md`,
`references/security-and-data-handling.md`, `references/block-theme-workflow.md`,
các checklist QA — chưa cần.

**Đầu ra mong đợi:** Một bảng ngắn trong đầu (hoặc ghi ra file tạm):
- Theme đang active, parent/child.
- `_opt()` dùng ở đâu, có bao nhiêu file `option/NN_*.php`.
- Cách Keyweb auto-load CSS/JS thực tế trong dự án này.
- Prefix hàm thật đang dùng.

---

## Kịch bản 2 — Chỉ sửa 1 section đã có trên 1 Page

**Khi nào:** "Chỉnh lại banner trang chủ", "đổi layout card dịch vụ", "thêm
1 field nhỏ vào section đã có".

**Thứ tự đọc:**

1. `SKILL.md` — nhớ lại rule `.full-row`/`.inner-container` và bước 7 bàn giao.
2. `references/company-conventions.md` §"Quy tắc bắt buộc tuyệt đối" —
   decision tree wrapper. Bắt buộc, vì đây là lỗi hay gặp nhất.
3. `references/senior-playbook.md` §1 và §2 — 4 câu hỏi khi đọc design, quyết
   định động/tĩnh. Đọc kỹ trước khi chạm code.
4. Mở code thật: template đang render section đó, wrapper cha, CSS/JS liên quan.
5. `references/qa-checklist.md` §"Chọn kiểm tra cần thiết" — chọn đúng dòng
   cho loại thay đổi của mình (markup/CSS/field…).

**Bỏ qua:** `references/keyweb-options.md` (trừ khi section có field mới cần
lưu), `references/page-custom-fields.md` (trừ khi sửa repeater),
`references/project-audit-checklist.md` (không cần audit toàn site cho 1 section).

**Cảnh báo:** Nếu section cũ đang thiếu `full-row`/`inner-container` — **không
tự ý thêm vào** vì có thể vỡ layout cũ. Ghi nhận, hỏi trước, hoặc note rõ
trong bàn giao.

---

## Kịch bản 3 — Thêm tính năng lưu dữ liệu mới (repeater/metabox/option)

**Khi nào:** "Cho khách tự sửa danh sách FAQ", "thêm field banner cho Page
About", "tạo option cho section mới ở Home".

**Thứ tự đọc:**

1. `SKILL.md` — bảng "Architectural Mental Model" quyết định Page/CPT/Option.
2. `references/keyweb-options.md` — **đọc toàn bộ**. Đây là file quyết định
   nơi lưu, có ngoại lệ nội dung nhẹ, schema option Keyweb, migration.
3. `references/page-custom-fields.md` — nếu chọn Page Custom Fields / repeater.
   Bao gồm contract field, bảo toàn dữ liệu, ví dụ FAQ.
4. `assets/examples/page-faq-fields.php` và `assets/examples/page-faq-fields.js`
   — **đọc như ví dụ, không copy**. Nắm pattern: `metadata_exists()`,
   `wp_slash()`, notice theo user+post, giữ dữ liệu cũ khi input lỗi.
5. `references/security-and-data-handling.md` — bắt buộc. Nonce, capability,
   `wp_unslash`, validate/sanitize, escaping output, slashing khi lưu JSON.
6. `references/system-design-thinking.md` §"Nguồn dữ liệu" và §"Thiết kế theo
   nhiệm vụ" — thiết kế màn hình admin cùng lúc với frontend.
7. `references/qa-checklist.md` §"Field/repeater/lưu meta" — dòng kiểm tra
   tương ứng.

**Nếu là migration từ option cũ sang Page:** tuân thủ 5 nguyên tắc trong
`references/keyweb-options.md` §"Danh sách động và chuyển dữ liệu". Skill này
**chưa có playbook migration riêng** — nếu phải làm migration phức tạp, note
rõ giới hạn trong bàn giao và đề xuất bổ sung.

**Đầu ra mong đợi — trước khi code, có bảng ngắn:**
- Field key, kiểu, default, giới hạn.
- Nơi lưu (meta key / option key).
- Nơi admin sửa (metabox nào, Page nào).
- Hành vi khi rỗng / khi vượt giới hạn.
- Cách render frontend + empty state.

---

## Kịch bản 4 — Tích hợp một section có query động (CPT/Post/WooCommerce)

**Khi nào:** "Làm section tin tức mới nhất", "grid dịch vụ lấy từ CPT", "danh
mục sản phẩm lọc theo category".

**Thứ tự đọc:**

1. `SKILL.md` — bảng quyết định CPT vs Post vs Page.
2. `references/system-design-thinking.md` — đọc toàn bộ. Đặc biệt §"Nguồn dữ
   liệu" và §"Phạm vi ảnh hưởng".
3. `references/classic-theme-workflow.md` §"Query và trạng thái rỗng" — main
   query vs secondary query, `wp_reset_postdata()`, phân trang, `posts_per_page`.
4. `references/wordpress-integration-checklist.md` §"Query, CPT và dữ liệu"
   — registration, không `flush_rewrite_rules()` mỗi request, archive dùng
   main query.
5. Nếu có WooCommerce: `references/wordpress-integration-checklist.md`
   §"WooCommerce và catalog giới thiệu" — đọc kỹ 8 điểm. Không tự thêm
   giá/giỏ hàng nếu brief không yêu cầu.
6. `references/senior-playbook.md` §3 — bẫy N+1 query, empty state, ảnh
   không đúng tỉ lệ.
7. `references/qa-checklist.md` §"Query/WooCommerce" — dòng kiểm tra tương ứng.

**Bắt buộc kiểm tra thực tế:**
- 0 item → section ẩn hoàn toàn (không để tiêu đề trơ).
- 1 item → layout không vỡ.
- Nhiều item + phân trang → URL đúng.
- Item thiếu ảnh/thiếu field → placeholder hợp lý, không lỗi.

---

## Kịch bản 5 — Sửa asset, enqueue, thư viện, font

**Khi nào:** "Nhúng slider", "đổi font tiêu đề", "thêm thư viện chart".

**Thứ tự đọc:**

1. `SKILL.md` §"Thư viện, font và layout Keyweb" — nhắc lại quy tắc ưu tiên
   Keyweb và cảnh báo enqueue trùng.
2. `references/library-catalog.md` — tra slug có sẵn trước khi nghĩ đến nguồn
   ngoài. Đọc §"Cách gọi và thứ tự thực thi" để biết `_kw_get_lib()` hoạt động.
3. `references/font-catalog.md` — kiểm tra font trong kho, không tự tải
   Google Fonts.
4. `references/company-conventions.md` §"Assets và thư viện" — nhắc lại
   pipeline auto-enqueue: **file `css/1.*`, `css/2.*`, `js/1.*`, `js/2.*` đã
   được auto-load, KHÔNG enqueue thủ công.**
5. `references/wordpress-integration-checklist.md` §"Enqueue tự viết" — chỉ
   dùng khi asset nằm ngoài pipeline đánh số.
6. `references/qa-checklist.md` §"Script/slider/modal" — dependency, init
   không trùng, 0/1/nhiều item, lỗi network.

**Cảnh báo đặc biệt:**
- Trước khi viết `wp_enqueue_style/script` thủ công, `grep` tên file trong
  `functions.php` để chắc chắn chưa có enqueue nào.
- Không nạp cùng thư viện hai lần (Keyweb + CDN/npm).
- Font icon (Font Awesome, Linearicons, themify…) không dùng làm font nội dung.

---

## Kịch bản 6 — Bàn giao final / audit toàn site

**Khi nào:** Xong một cụm trang lớn, chuẩn bị bàn giao khách, hoặc nhận bàn
giao lại dự án từ dev khác.

**Thứ tự đọc:**

1. `SKILL.md` §Bước 7 — quy tắc bàn giao bắt buộc.
2. `.agents/rules/handover-list-files.md` — format danh sách file, clickable
   link, tóm tắt. *(Nằm ngoài thư mục skill; đường dẫn tính từ repo root
   `wordpress-main/`.)*
3. `references/qa-checklist.md` — đọc toàn bộ. Kiểm tra theo phạm vi thay đổi.
4. `references/project-audit-checklist.md` — đọc toàn bộ. Đây là audit **giữa
   các trang**, không phải kiểm tra từng trang riêng lẻ.
5. `references/senior-playbook.md` §4 — 5 câu hỏi "xong thật sự".
6. `references/keyweb-options.md` §"Danh sách động và chuyển dữ liệu" — nếu
   có migration chưa xác nhận.
7. `references/security-and-data-handling.md` — rà lại nonce/capability/
   escaping ở các chỗ đã sửa.

**Đầu ra mong đợi (theo `references/project-audit-checklist.md` §5):**
- Bảng kiểm kê nơi lưu dữ liệu toàn site.
- Danh sách nội dung "cùng bản chất lưu khác cách".
- Danh sách orphaned (option/meta/CPT/CSS-JS không còn dùng).
- Bảng trạng thái từng hạng mục: đã kiểm tra / chưa có điều kiện / vấn đề
  còn lại.

**Nguyên tắc vàng:** Audit **không tự sửa** các mục rủi ro mất dữ liệu (gộp
nguồn, xóa option, đổi cơ chế lưu). Chỉ báo cáo + đề xuất, để người có quyền
quyết định.

---

## Kịch bản 7 — Block theme hoặc hybrid theme (hiếm)

**Khi nào:** Dự án **đã dùng** block templates/`theme.json` và cần sửa template.
**Không dùng kịch bản này để chuyển classic theme sang FSE** — đó là quyết
định kiến trúc, không phải việc của một section.

**Thứ tự đọc:**

1. `references/block-theme-workflow.md` — đọc toàn bộ. Đặc biệt §"Xác định
   nguồn template" (file theme vs DB override) và §"HTML templates và PHP".
2. `SKILL.md` §Bước 2 — chọn đúng nhánh triển khai.
3. `references/wordpress-integration-checklist.md` §"Setup và version" —
   theme supports, text domain, loader.
4. `references/qa-checklist.md` §"Block template" — frontend + save/reopen
   editor, block validation, nguồn template database/file.

**Cảnh báo:** Không xóa/reset bản tùy chỉnh Site Editor của khách để file theme
có tác dụng. Xác định nguồn đang dùng trước khi sửa.

---

## Kịch bản 8 — Debug một lỗi cụ thể

**Khi nào:** "Trang X trắng", "lưu field không được", "slider không chạy".

**Cách đọc khác các kịch bản khác — đọc ngược từ triệu chứng:**

| Triệu chứng | File cần đọc trước | Mục cụ thể |
| --- | --- | --- |
| Lưu field không được, mất dữ liệu | `references/page-custom-fields.md` | §"Lưu dữ liệu không mất nội dung cũ" |
| Frontend hiện `Array`, `false`, ảnh vỡ | `references/senior-playbook.md` | §3(d) "Field ảnh/text không có giá trị mặc định" |
| Layout vỡ khi nội dung dài | `references/senior-playbook.md` | §3(a) "Nội dung dài làm vỡ layout" |
| Section trắng khi xóa hết item | `references/senior-playbook.md` §3(c) + `references/classic-theme-workflow.md` | §"Query và trạng thái rỗng" |
| CSS/JS không load, load 2 lần | `references/company-conventions.md` §"Assets và thư viện" + `references/library-catalog.md` | §"Cách gọi và thứ tự thực thi" |
| Query chậm, N+1 | `references/senior-playbook.md` | §3(e) "Query thêm trong vòng lặp" |
| Nonce/capability sai | `references/security-and-data-handling.md` | Toàn bộ |
| Block validation error | `references/block-theme-workflow.md` | §"HTML templates và PHP" |
| Hai nguồn dữ liệu trùng cho cùng khái niệm | `references/project-audit-checklist.md` | §2 |

Sau khi fix xong, chạy `references/qa-checklist.md` dòng tương ứng để không
bỏ sót regression.

---

## Bảng tóm tắt nhanh (in ra dán tường)

| Tình huống | Đọc chính | Bỏ qua |
| --- | --- | --- |
| Dự án mới | `SKILL.md` → `references/company-conventions.md` → `references/keyweb-options.md` | QA checklist, security chi tiết |
| Sửa 1 section | `references/company-conventions.md` (wrapper) + `references/senior-playbook.md` §1-2 | `references/keyweb-options.md` (trừ khi có field mới) |
| Thêm field/repeater | `references/keyweb-options.md` + `references/page-custom-fields.md` + `references/security-and-data-handling.md` | `references/block-theme-workflow.md` |
| Query động | `references/system-design-thinking.md` + `references/classic-theme-workflow.md` (§query) | `references/font-catalog.md` |
| Asset/font/library | `references/library-catalog.md` + `references/font-catalog.md` + `references/company-conventions.md` (§assets) | `references/page-custom-fields.md` |
| Bàn giao final | `.agents/rules/handover-list-files.md` + `references/qa-checklist.md` + `references/project-audit-checklist.md` | — |
| Block theme | `references/block-theme-workflow.md` | `references/classic-theme-workflow.md` |
| Debug | Theo bảng triệu chứng ở kịch bản 8 | — |

---

## Khi nào quay lại file này

- Bắt đầu một task mới mà không chắc nên đọc gì.
- Sau khi đọc `SKILL.md` mà vẫn không rõ thứ tự ưu tiên.
- Khi thấy mình đọc quá nhiều file cho một việc nhỏ — quay lại đây chọn
  kịch bản.

Nếu một tình huống thực tế không khớp bất kỳ kịch bản nào ở trên, ghi lại và
đề xuất bổ sung kịch bản mới — file này sống, không phải đóng băng.