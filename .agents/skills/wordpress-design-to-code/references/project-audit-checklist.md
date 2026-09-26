# Audit toàn site — Sau khi code xong nhiều trang

Khác với `qa-checklist.md` (kiểm tra đúng phạm vi vừa sửa), tài liệu này dùng
để **lùi lại nhìn toàn bộ site** sau khi đã code xong một cụm trang hoặc trước
khi bàn giao final. Mục tiêu: phát hiện sự **không nhất quán giữa các trang**
mà kiểm tra từng phần không bao giờ thấy được, vì lúc code từng trang bạn luôn
thấy quyết định của mình hợp lý — chỉ khi đặt cạnh nhau mới lộ vênh.

Chạy audit này khi: xong một cụm trang lớn, trước khi bàn giao final cho
khách, hoặc khi nhận bàn giao lại dự án của người khác.

---

## 1. Kiểm kê nơi lưu dữ liệu toàn site

Trước khi đánh giá đúng/sai, phải có bảng kiểm kê thật — không nhớ áng chừng.
Lập bảng theo từng Page/khu vực đã code:

| Trang / khu vực | Nơi lưu đang dùng          | Loại nội dung                  | Đúng theo `keyweb-options.md`? |
| --------------- | -------------------------- | ------------------------------ | ------------------------------ |
| Home            | Customizer (option Keyweb) | Banner, giới thiệu ngắn        | ✅ đúng nhóm mặc định          |
| Contact         | Customizer                 | Hotline, địa chỉ, giờ làm việc | ✅                             |
| Services        | Custom Field trên Page     | Danh sách dịch vụ (repeater)   | ? — kiểm tra lại               |
| Projects        | CPT `project`              | Danh sách dự án có trang riêng | ✅                             |
| About           | ???                        | Đội ngũ nhân sự                | ⚠️ cần xác định                |

Cách lấy dữ liệu thật thay vì đoán:

```bash
# Liệt kê toàn bộ file khai báo option Keyweb
ls wp-content/themes/keyweb/option/

# Tìm mọi custom field/repeater đang đăng ký (metabox) trong theme
grep -rn "add_meta_box\|register_post_meta" wp-content/themes/keyweb/ wp-content/plugins/

# Liệt kê toàn bộ CPT đang đăng ký
grep -rn "register_post_type" wp-content/themes/keyweb/ wp-content/plugins/
```

Với mỗi dòng trong bảng, đối chiếu lại với bảng "Chọn nơi lưu" trong
`keyweb-options.md`. Nếu một trang không khớp nhóm mặc định (Home/Contact →
Customizer, còn lại → Page/Custom Field, danh sách có trang riêng/lifecycle →
CPT), đây là ứng viên cần xem lại — không tự sửa ngay, ghi vào mục "Cần xem
lại" để trao đổi trước khi đổi cơ chế lưu dữ liệu đã có khách nhập.

---

## 2. Tìm nội dung _cùng bản chất_ nhưng đang lưu _khác cách nhau_

Đây là loại lỗi nguy hiểm nhất vì không lỗi cú pháp nào bắt được nó. Ví dụ
thực tế: trang Home lưu danh sách "Đối tác" bằng Custom Field repeater, nhưng
trang About lại lưu danh sách "Đối tác" y hệt bằng cách khác (option riêng
hoặc hardcode) — vì hai trang do hai lần code khác thời điểm.

Cách rà:

- Liệt kê tất cả các "danh sách lặp" đang có trên site (dịch vụ, đối tác, dự
  án, tin tức, nhân sự, FAQ, chứng nhận...).
- Với mỗi danh sách, ghi nơi lưu + nơi hiển thị (có thể hiển thị ở nhiều
  trang từ cùng 1 nguồn, hoặc bị lưu trùng ở nhiều nơi khác nhau).
- Nếu phát hiện 2 nguồn dữ liệu khác nhau cho cùng 1 khái niệm nghiệp vụ
  (2 danh sách "Đối tác" riêng biệt) → đây là bug tiềm ẩn: khách sửa một chỗ,
  tưởng đã cập nhật toàn site, nhưng chỗ kia không đổi.

## 3. Dữ liệu/mã mồ côi (orphaned)

Sau nhiều lần sửa theo yêu cầu khách, rất dễ còn sót:

- Option đã khai báo trong `option/NN_opt_*.php` nhưng không còn `_opt()` nào
  gọi tới trong template — field vẫn hiện trong admin nhưng vô nghĩa.
- Custom field/meta key không còn field nào trong admin để nhập, nhưng
  template vẫn đọc giá trị cũ (dữ liệu "ma" từ trước).
- CPT/taxonomy đã đăng ký nhưng không có template nào dùng, hoặc archive
  không được liên kết từ đâu trên site (khách không tìm ra đường vào).
- File CSS/JS đánh số (`css/2.*.css`) còn tồn tại nhưng section liên quan đã
  bị xoá khỏi thiết kế — vẫn được auto-enqueue mỗi request dù vô dụng.

```bash
# Tìm _opt('key') không còn khai báo tương ứng trong option/*.php (đối chiếu thủ công)
grep -rn "_opt(" wp-content/themes/keyweb/*.php wp-content/themes/keyweb/**/*.php

# Tìm hàm/hook trùng tên do nhiều lần code chồng lên nhau
grep -rn "^function " wp-content/themes/keyweb/functions.php | sort | uniq -d -f1
```

## 4. Nhất quán chuẩn hệ thống WordPress (không riêng Keyweb)

Đây là các điểm chuẩn WordPress nói chung, nên rà ở mức toàn site một lần
thay vì kiểm tra lẻ tẻ từng trang:

| Hạng mục                                      | Kiểm tra                                                                                                                                                             |
| --------------------------------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Wrapper Keyweb (.full-row / .inner-container) | **100% thẻ `<section>` và outer blocks trên mọi template** có class `full-row`, khung chứa bên trong có `inner-container`; không để sót section nào thiếu `full-row` |
| Debug/hiển thị lỗi                            | `WP_DEBUG`, `WP_DEBUG_DISPLAY` phải tắt trên production; log lỗi (nếu bật) không public accessible                                                                   |
| Prefix hàm/hook                               | Toàn bộ function tự viết trong `functions.php`/plugin dùng chung 1 prefix, không lẫn lộn giữa các lần code khác thời điểm                                            |
| Text domain                                   | Toàn bộ chuỗi tĩnh dịch được dùng đúng 1 text domain của theme, không lẫn domain plugin khác dán nhầm                                                                |
| Deprecated function                           | Không còn gọi hàm WordPress đã deprecated ở phiên bản đang chạy (`wp-admin/includes/deprecated.php` là nguồn tham chiếu)                                             |
| Direct DB query                               | Không có `$wpdb->query()` thô thay được bằng `WP_Query`/Metadata API còn sót lại từ bản nháp                                                                         |
| Permalink & rewrite                           | Không có chỗ nào gọi `flush_rewrite_rules()` trên mỗi request; permalink hoạt động đúng sau khi thêm CPT mới                                                         |
| Trùng thư viện                                | Không có 2 phiên bản của cùng 1 thư viện (jQuery plugin, slider...) được nạp từ 2 nguồn khác nhau ở 2 trang khác nhau                                                |
| SEO cơ bản                                    | Mỗi loại trang có title/meta description khác nhau theo nội dung thật, không phải cùng 1 chuỗi mặc định dán cho mọi Page                                             |
| robots/sitemap                                | Trang chưa hoàn thiện/nháp không bị index; sitemap không trỏ vào trang lỗi/404                                                                                       |
| Quyền người dùng                              | Không còn tài khoản admin/test dùng để bàn giao; role của khách đúng mức cần thiết, không cấp `administrator` cho mọi user quản trị nội dung                         |

## 5. Đầu ra của audit

Không chỉ nói "đã kiểm tra" — ghi thành bảng cụ thể để khách/dev sau đọc lại
được:

| Hạng mục                        | Trạng thái                   | Hành động                                                                              |
| ------------------------------- | ---------------------------- | -------------------------------------------------------------------------------------- |
| Nơi lưu Services                | Đang lệch khỏi convention    | Đề xuất chuyển, chờ xác nhận trước khi đổi (không tự đổi khi đã có dữ liệu khách nhập) |
| Danh sách "Đối tác"             | Trùng 2 nguồn (Home + About) | Cần gộp về 1 nguồn, đã note rủi ro mất dữ liệu nếu gộp sai chiều                       |
| Option mồ côi `home_old_banner` | Không còn dùng               | Đề xuất xoá sau khi xác nhận không còn migration cần đến                               |
| WP_DEBUG                        | Đang bật trên production     | Cần tắt trước khi bàn giao                                                             |

Việc audit **không tự động sửa** các mục có rủi ro mất dữ liệu (gộp 2 nguồn,
xoá option, đổi cơ chế lưu) — chỉ báo cáo và đề xuất, để người quyết định
cuối cùng là người có quyền chấp nhận rủi ro đó, đúng nguyên tắc "bảo toàn dữ
liệu và hành vi hiện có" đã nêu trong `SKILL.md`.
