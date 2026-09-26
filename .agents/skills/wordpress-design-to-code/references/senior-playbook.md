# Senior Playbook — Tư duy nghiệp vụ khi code WordPress/Keyweb

Tài liệu này không lặp lại quy chuẩn kỹ thuật (đã có ở company-conventions.md,
keyweb-options.md...). Nó ghi lại các **quyết định** mà một dev nhiều năm kinh
nghiệm đưa ra ở những chỗ quy chuẩn không nói rõ, và những **lỗi thực tế** hay
gặp khi bàn giao cho khách không rành kỹ thuật.

**Thứ tự ưu tiên khi có mâu thuẫn:** brief riêng của dự án → convention công ty
(Keyweb) → nguyên tắc trong tài liệu này → best practice chung. Không dùng tài
liệu này để lách hoặc ghi đè convention công ty.

---

## 1. Đọc design như người sẽ bảo trì nó 6 tháng sau

Khi nhận ảnh design, đừng chỉ hỏi "chỗ này căn giữa hay căn trái". Hỏi 4 câu
sau cho từng section trước khi code:

| Câu hỏi                                                                                                 | Vì sao quan trọng                                                                                                                                                        |
| ------------------------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------ |
| Nội dung này khách có tự sửa được không?                                                                | Nếu có, phải có nơi nhập trong admin — không hardcode text vào template dù design chỉ có 1 bản.                                                                          |
| Số lượng item cố định hay động? (3 dịch vụ, N bài viết...)                                              | Nếu ảnh có đúng 3 card nhưng bản chất là danh sách (dịch vụ, tin tức) → phải code như danh sách động/repeater, không code cứng 3 khối rồi bảo khách "muốn thêm gọi dev". |
| Nếu khách nhập thiếu hoặc để trống thì hiển thị gì?                                                     | Design không bao giờ vẽ trạng thái rỗng. Phải tự quyết: ẩn cả section, hiện placeholder, hay dùng giá trị mặc định — và nói rõ giả định này khi bàn giao.                |
| Nếu khách nhập nội dung dài hơn design (title 2 dòng thay vì 1, ảnh tỉ lệ khác) thì layout có vỡ không? | Đây là nguyên nhân số 1 khiến khách quay lại báo "web bị lỗi" sau khi đã nghiệm thu — vì lúc bàn giao dữ liệu mẫu luôn đẹp vừa khít.                                     |

Nguyên tắc: **design là ảnh chụp một trạng thái dữ liệu, không phải đặc tả đầy
đủ.** Việc của dev là suy ra hệ thống đứng sau bức ảnh đó.

---

## 2. Nguyên tắc chọn "động hay tĩnh, admin sửa hay hardcode"

Áp dụng cùng với bảng chọn nơi lưu ở `keyweb-options.md`, nhưng ở lớp quyết
định cao hơn — trước khi hỏi "lưu vào option hay custom field", hỏi "có cần
lưu ở đâu cho khách sửa không":

- **Nội dung xuất hiện đúng 1 lần, không đổi theo ngữ cảnh nghiệp vụ** (label
  cố định kiểu "Xem thêm", icon trang trí, khoảng cách) → hardcode trong
  template, không tạo option. Tạo option cho mọi câu chữ tĩnh là lãng phí và
  làm admin rối vì quá nhiều field.
- **Nội dung là thông tin doanh nghiệp có thể đổi mà không cần đổi code**
  (số điện thoại, giờ làm việc, banner khuyến mãi, mô tả dịch vụ) → phải có
  chỗ sửa trong admin, kể cả khi khách chưa yêu cầu — vì đây là kỳ vọng ngầm
  của khách khi thuê "website WordPress" thay vì trang tĩnh HTML.
- **Nội dung có số lượng thay đổi theo thời gian** (dự án, đối tác, tin tức,
  câu hỏi FAQ) → bắt buộc là danh sách động (CPT, repeater, hoặc query thật),
  không bao giờ code cứng số lượng item theo đúng số ảnh design có.

Dấu hiệu cảnh báo khi tự review code của chính mình: nếu thấy `<h3>Dịch vụ
1</h3>`, `<h3>Dịch vụ 2</h3>` lặp lại y hệt trong markup — dừng lại, đây gần
như luôn là danh sách bị code nhầm thành tĩnh.

---

## 3. Bẫy thực tế hay gặp và cách phòng trước

Đây là các lỗi không nằm trong bất kỳ checklist syntax nào, nhưng là nguyên
nhân phổ biến khiến dự án phải sửa lại sau khi đã bàn giao:

**a. Nội dung dài làm vỡ layout**
Card có `overflow: hidden` che chữ, hoặc chiều cao cố định `height: 300px`
thay vì `min-height`. → Luôn dùng `min-height` cho khối chứa nội dung động,
test bằng cách tự nhập title/description dài gấp đôi mẫu.

**b. Ảnh khách upload không đúng tỉ lệ design**
Design luôn dùng ảnh đã crop đẹp sẵn. Khách sẽ upload ảnh dọc cho ô ngang.
→ Dùng `object-fit: cover` + khai báo tỉ lệ khung (aspect-ratio hoặc
padding-top hack) thay vì để `<img>` tự do co giãn theo file gốc.

**c. Xóa hết item trong danh sách động → section trắng xóa hoặc lỗi PHP**
Khách xóa hết bài viết/dịch vụ trong admin, vòng lặp `foreach` chạy trên mảng
rỗng. → Luôn có nhánh xử lý khi query/mảng rỗng: ẩn cả `.full-row` của section
đó, không để lại tiêu đề section trơ trọi không có nội dung bên dưới.

**d. Field ảnh/text không có giá trị mặc định**
Option mới tạo, DB chưa có giá trị → `_opt()` trả về rỗng/false → hiển thị
`<img src="">` hoặc chữ "false" ngoài frontend. → Luôn set `"default"` hợp lý
trong khai báo option, và luôn kiểm tra rỗng trước khi render ra HTML.

**e. Query thêm trong vòng lặp (N+1)**
Lấy danh sách 10 sản phẩm rồi bên trong loop lại gọi thêm `WP_Query`/`get_
posts()` để lấy dữ liệu liên quan cho từng sản phẩm. → Gộp query, hoặc dùng
`WP_Query` với `posts_per_page` + meta query một lần, tránh nhân N truy vấn.

**f. Enqueue trùng vì không kiểm tra pipeline tự động của Keyweb**
Đã có trong company-conventions.md nhưng đây là lỗi tái diễn nhiều nhất khi
AI/dev mới join dự án: viết `wp_enqueue_style` tay cho file đã nằm trong
`css/1.*` hoặc `css/2.*`. → Luôn `grep` tên file trong `functions.php` trước
khi thêm enqueue thủ công.

**g. Thiếu class `.full-row` / `.inner-container` ở wrapper ngoài**
Viết thẻ `<section class="custom-section">` mà quên class `full-row` khiến float bị vỡ hoặc không khớp quy chuẩn toàn theme. → **Quy tắc cứng:** 100% outer section phải có `class="full-row [custom-section]"`, khung con là `<div class="inner-container">`.

**h. Responsive suy luận sai vì chỉ nhìn 1 breakpoint**
Thấy 3 cột ở desktop thì mobile mặc định chia đều 1 cột — nhưng nếu nội dung
là hero banner có ảnh lớn, co về 1 cột mà không giảm kích thước chữ/khoảng
cách sẽ làm banner quá cao trên mobile. → Ưu tiên giữ tỷ lệ thị giác (chiều
cao khối so với viewport), không chỉ chia lại số cột.

---

## 4. Tiêu chí "xong thật sự" trước khi báo hoàn thành

Code chạy được và giống ảnh design **chưa phải là xong**. Trước khi liệt kê
file bàn giao theo `handover-list-files.md`, tự hỏi:

1. **Cấu trúc HTML**: 100% thẻ `<section>` ngoài cùng đã có `class="full-row ..."` và bên trong có `class="inner-container"` chưa? Có bị sót section nào đứng trần không?
2. Khách có thể tự vào admin sửa nội dung chính của section này mà **không
   cần gọi dev** không?
3. Nếu khách để trống 1 field bất kỳ, frontend có bị lỗi hiển thị (chữ
   "Array", ảnh vỡ, khoảng trắng bất thường) không?
4. Nếu khách nhập nội dung dài hơn/nhiều hơn dữ liệu mẫu, layout còn giữ được
   không?
5. Có phần nào trong design bị bỏ qua vì "chưa rõ xử lý sao" mà chưa nêu rõ
   trong phần bàn giao không? (im lặng bỏ qua khác với báo giả định.)

Nếu câu trả lời cho (1) là "không" mà lẽ ra nội dung đó cần khách sửa được —
đây là lỗi nghiêm trọng hơn lỗi CSS lệch vài pixel, vì nó khiến khách phải
phụ thuộc dev cho việc đáng lẽ tự làm được.

---

## 5. Ví dụ đối chiếu ngắn (sai kiểu non tay / đúng kiểu senior)

**Danh sách dịch vụ có 4 item trong design:**

- ❌ Code 4 khối `<div>` riêng biệt, mỗi khối 1 title/description hardcode.
- ✅ Tạo repeater hoặc CPT "Dịch vụ", loop hiển thị, giới hạn hoặc không giới
  hạn số lượng tuỳ nghiệp vụ thật (hỏi khách nếu không chắc là danh sách cố
  định hay sẽ tăng theo thời gian).

**Banner có nút "Liên hệ ngay":**

- ❌ Hardcode số điện thoại trong `href="tel:..."` ngay trong template.
- ✅ Lấy từ option chung (`_opt('hotline')` hoặc tương đương đã có trong dự
  án) — số điện thoại gần như luôn là dữ liệu khách muốn tự đổi.

**Section trống khi chưa có dữ liệu:**

- ❌ Để nguyên tiêu đề "Tin tức mới nhất" hiển thị dù không có bài viết nào.
- ✅ `if (!$posts) return;` trước khi render cả `.full-row`, ẩn toàn bộ
  section thay vì hiện khung rỗng.

---

## 6. Khi nào KHÔNG áp dụng tài liệu này

- Khi brief dự án nói rõ ngược lại (ví dụ khách chấp nhận nội dung tĩnh do
  ngân sách thấp) — làm theo brief, ghi rõ đã bỏ qua khuyến nghị nào và vì
  sao trong phần bàn giao.
- Khi đây là dự án demo/nội bộ không bàn giao cho khách vận hành — bớt phần
  "khách tự sửa được", tập trung đúng ảnh design.
