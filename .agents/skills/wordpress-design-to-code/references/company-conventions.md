# Convention Keyweb

## Phạm vi và cách đọc code

Keyweb là nền tảng mặc định. Đọc `functions.php` và các include để xác định helper, thứ tự tải file và prefix thật. Tên file dưới đây là tín hiệu của convention; không tạo file trùng chức năng chỉ để khớp cây mẫu.

| Thành phần     | Convention thường gặp                                                                   | Cách mở rộng                                                                |
| -------------- | --------------------------------------------------------------------------------------- | --------------------------------------------------------------------------- |
| CSS            | `css/1.layout.css`, `1.header.css`, `1.home.css`, `2.footer.css`                        | Giữ cách chia module/đánh số và thứ tự enqueue thực tế                      |
| JavaScript     | `js/2.header.js` và file theo trang                                                     | Dùng lại module, không dồn tính năng trang riêng vào global nếu không cần   |
| Options        | `option/01_opt_header.php`, `02_opt_footer.php`, `03_opt_home.php`, `05_opt_lienhe.php` | Chọn nơi lưu theo [keyweb-options.md](keyweb-options.md)                    |
| Cấu hình chung | `06_opt_addon.php`, `07_opt_css.php`                                                    | Giữ hệ thống addon/tokens hiện hữu                                          |
| PHP partials   | `zkw_dynamic_css.php`, `zkw_addon.php`, partial phẳng ở gốc                             | Tái sử dụng file và cú pháp `get_template_part()` đang có                   |
| Page templates | `page.php`, `page-{slug}.php`, named templates                                          | Xác định đúng hierarchy; không tự gom file sang thư mục mới                 |
| WooCommerce    | `woocommerce/`                                                                          | Chỉ chứa template overrides; hook/filter có thể nằm trong file tích hợp PHP |

Không mặc định tạo thư mục `inc/` hoặc `template-parts/` nếu dự án chưa dùng. Nếu dự án Keyweb đã tổ chức theo thư mục, tiếp tục cấu trúc đó. File `04_opt_*.php` cũ cần được kiểm tra dữ liệu và callers; tên legacy không tự chứng minh phải xóa hoặc chuyển ngay.

## Quy tắc bắt buộc tuyệt đối: .full-row và .inner-container

MỌI template trang, custom template và WooCommerce template BẮT BUỘC tuân thủ cấu trúc 2 lớp chuẩn Keyweb:

- **Lớp ngoài (Outer wrapper / Section)**: BẮT BUỘC có class `full-row [custom-class]` (`float: left; width: 100%; box-sizing: border-box;`).
- **Khung chứa nội dung trung tâm (Inner Container)**: BẮT BUỘC có class `inner-container [custom-class]` (`max-width: 1240px; margin: auto; padding: 0 15px; width: 100%; box-sizing: border-box;`).

```html
<!-- ✅ ĐÚNG CHUẨN: Bắt buộc full-row ở ngoài và inner-container ở trong -->
<section class="full-row capabilities-section">
  <div class="inner-container">
    <!-- Nội dung section -->
  </div>
</section>

<!-- ❌ SAI (CẤM): Thiếu full-row hoặc thiếu inner-container -->
<section class="capabilities-section">
  <div class="custom-container">...</div>
</section>
```

Trong markup mới, **luôn luôn viết `full-row` đứng trước class riêng** theo convention (`class="full-row section-name"`).

Trước khi thêm section, đọc wrapper cha. Ví dụ nếu template đã bọc:

```html
<div class="full-row full-content">
  <div id="primary" class="inner-content inner-container">
    <!-- Section nội dung đã được giới hạn chiều rộng tại đây -->
  </div>
</div>
```

Section nằm trong `#primary` không cần tự động thêm một `.inner-container` nữa. Nếu design cần nền tràn màn hình, chọn vị trí section phù hợp với wrapper hiện có; tránh mẹo width/negative margin gây overflow khi có thể sửa đúng cấu trúc.

Không ghi đè CSS toàn cục của hai wrapper từ một file trang. Đọc width, max-width, float, padding và box-sizing đang được dùng. Khi xây layout mới, lấy từ design và hệ tokens thực tế.

## CSS, tokens và responsive

- Khai báo màu/spacing/font dùng lại tại hệ tokens của dự án. Giá trị literal hợp lệ tại nơi định nghĩa token; stylesheet của component ưu tiên biến tương ứng. Không tạo biến giả chỉ để đạt quy tắc “100% var”.
- Giữ nguồn tokens hiện có như `07_opt_css.php` và `zkw_dynamic_css.php`; validate giá trị màu/độ dài CSS ở nơi nhận dữ liệu. Không echo CSS tùy ý từ request.
- Scoped selectors theo component, tránh reset toàn site khi làm một section. Không thêm `!important` hàng loạt để che xung đột.
- Theo mobile-first hoặc desktop-down đang dùng. Xác định breakpoint từ thời điểm nội dung không còn vừa; nếu design chỉ có desktop thì suy ra mobile và ghi giả định.
- Giữ font đúng design qua kho công ty; đọc [font-catalog.md](font-catalog.md).

## Assets và thư viện

[library-catalog.md](library-catalog.md) là nguồn chính về lựa chọn, thứ tự và vị trí `_kw_get_lib()`. Chính sách ưu tiên Keyweb áp dụng cho cả frontend/admin khi nguồn công ty thực sự hỗ trợ ngữ cảnh đó; không đưa helper chỉ dành frontend vào admin khi chưa đọc implementation.

- **Cơ chế tự động nạp CSS/JS của Keyweb:** Hệ sinh thái Keyweb tự động quét và nạp (auto-enqueue) toàn bộ các file trong thư mục `css/` và `js/` có tiền tố đánh số (`css/1.*.css`, `css/2.*.css`, `js/1.*.js`, `js/2.*.js` như `1.layout.css`, `1.header.css`, `1.home.css`, `2.footer.css`, `2.header.js`,...).
- **Quy tắc chống nạp trùng lặp:** Tuyệt đối **không** viết thêm hàm `wp_enqueue_style` / `wp_enqueue_script` trong `functions.php` để nạp lại các file đánh số đã nằm trong pipeline của Keyweb. Chỉ enqueue thủ công khi có asset riêng biệt/vendor ngoài không tuân theo tiền tố đánh số tự động.
- Enqueue CSS/JS tự viết qua API WordPress và pipeline dự án. Khai báo dependency thật; số ở tên file không tự quyết định thứ tự tải. Với cache-busting local, dùng `filemtime()` khi file tồn tại hoặc version ổn định của theme/build. Không dùng `time()` cho mọi request. Không enqueue URL tới file không tồn tại chỉ để có fallback version.

Với child theme, chọn API đường dẫn có hỗ trợ override như `get_theme_file_path()` / `get_theme_file_uri()` khi phù hợp; dùng parent-only API khi cố ý lấy asset parent. Chuẩn enqueue xem [wordpress-integration-checklist.md](wordpress-integration-checklist.md).

Ưu tiên vanilla JS cho tương tác đơn giản. Dùng jQuery khi dependency thật sự cần, qua handle WordPress đang có; không nạp thêm `jquery183` để làm chạy một ví dụ cũ. Không inline logic JS tùy tiện trong template. Dữ liệu cấu hình được truyền qua API WordPress với JSON encoding phù hợp.

## Helper và chức năng nghiệp vụ

Tìm tên function/hook/option trên toàn dự án trước khi thêm. Dùng prefix thật và chỉ include một lần theo loader hiện có. Không dùng `function_exists()` để âm thầm che một lỗi đặt tên trùng.

Giữ CPT/taxonomy, dữ liệu nghiệp vụ, endpoint cần tồn tại độc lập giao diện trong plugin/mu-plugin khi đó là cấu trúc dự án. Không tự chuyển code đang chạy hoặc sửa trực tiếp WordPress/WooCommerce core.
