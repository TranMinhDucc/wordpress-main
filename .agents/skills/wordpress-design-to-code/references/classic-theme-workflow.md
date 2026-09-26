# Classic theme Keyweb

## Xác định template trước khi sửa

Đọc theme đang active, parent/child theme, thiết lập Reading, template Page được gán và filter template đang có. Các tên dưới đây thuộc hierarchy; không tạo tất cả nếu dự án không cần.

| Nội dung | Nhánh template chính |
| --- | --- |
| Trang đầu website | `front-page.php` nếu có; nếu không, chọn theo Reading (bài mới nhất hoặc Page tĩnh) |
| Danh sách bài viết | `home.php` → `index.php`; có thể là trang Blog riêng |
| Page tĩnh | Template được gán → `page-{slug}.php` → `page-{id}.php` → `page.php` → `singular.php` → `index.php` |
| Bài viết/CPT đơn | `single-{post_type}-{slug}.php` → `single-{post_type}.php` → `single.php` → `singular.php` → `index.php` |
| Archive | Template taxonomy/category/tag/CPT tương ứng → `archive.php` → `index.php` |
| Tìm kiếm / lỗi 404 | `search.php` / `404.php` → `index.php` |

`home.php` không phải fallback chung cho mọi trang chủ tĩnh. `page-{slug}.php` tự khớp slug khác với named Page template được khách chọn ở editor. Khi site đa ngôn ngữ/đổi slug, cân nhắc named template theo cấu trúc đang dùng; không hard-code ID thật chưa được xác minh.

## Luồng triển khai

1. Đọc template, header/footer, wrapper cha và CSS liên quan.
2. Phân rã design thành section; giữ quy tắc [company-conventions.md](company-conventions.md), tránh thêm container lồng thừa.
3. Xác định nguồn nội dung theo [keyweb-options.md](keyweb-options.md). Không sao chép label giả từ design thành danh mục/attribute thật.
4. Dựng semantic HTML và responsive theo design/convention; ưu tiên một H1 rõ nghĩa cho trang, cấp heading của card phụ thuộc vị trí thực tế.
5. Tái sử dụng partial phẳng của công ty hoặc cấu trúc partial đang có. Dùng `get_template_part()`; nếu cần `$args`, kiểm tra phiên bản WordPress hỗ trợ và contract của partial.
6. Nạp assets và JS interaction theo API và thư viện công ty. Test dữ liệu thật, thiếu dữ liệu và item tăng/giảm.

## Hooks nền tảng

Giữ `language_attributes()`, charset, viewport, `wp_head()`, `body_class()`, `wp_body_open()` trong layout tương ứng. Giữ `wp_footer()` trước đóng `body`, sau điểm nạp thư viện Keyweb đã xác minh. Không chép header/footer vào Page template khi dự án đã gọi `get_header()` / `get_footer()`.

Trong post/card dùng `post_class()` và các template tags phù hợp. Dùng `wp_get_attachment_image()` / thumbnail API để có kích thước/srcset đúng; không ghi đè alt bằng tiêu đề bài một cách máy móc. Ảnh mang thông tin cần alt phù hợp, ảnh trang trí có alt rỗng.

## Query và trạng thái rỗng

Giữ main query cho archive; ưu tiên hook `pre_get_posts` với điều kiện main query/frontend rõ ràng khi chỉnh tiêu chí. Custom query dùng `WP_Query` hoặc API WooCommerce phù hợp. Chỉ `wp_reset_postdata()` sau vòng lặp secondary đã gọi `the_post()`; không reset main query tùy tiện.

Có phân trang khi dữ liệu lớn; `posts_per_page = -1` không phải mặc định cho catalog. Chỉ lấy `publish` với danh sách công khai. Dùng term/product IDs thật và xử lý object không tồn tại. Không tạo query mới trong mỗi card khi có thể lấy dữ liệu theo nhóm.

Khi không có dữ liệu, ẩn phần không có ích hoặc hiện empty state đúng ngữ cảnh; không tự tạo bài viết/sản phẩm giả để lấp chỗ trống. Tối ưu bố cục khi chỉ có một item, không ép slider rỗng.

## Tài liệu chính thức

[Template hierarchy](https://developer.wordpress.org/themes/classic-themes/basics/template-hierarchy/) và [Page templates](https://developer.wordpress.org/themes/classic-themes/templates/page-template-files/).
