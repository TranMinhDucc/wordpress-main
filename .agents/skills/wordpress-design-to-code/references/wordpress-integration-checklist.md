# WordPress / WooCommerce integration

## Setup và version

Đọc phiên bản WordPress, PHP, WooCommerce và theme supports trước khi dùng API mới. Đăng ký theme supports cần thiết tại `after_setup_theme`; không bật toàn bộ WooCommerce/gallery supports ở website không dùng tính năng đó. Giữ text domain thật và loader hiện có.

## Enqueue tự viết

Tài nguyên công ty theo [library-catalog.md](library-catalog.md) và [font-catalog.md](font-catalog.md).

> [!IMPORTANT]
> **Lưu ý cơ chế Keyweb:** Toàn bộ file CSS/JS có tiền tố số (`css/1.*.css`, `css/2.*.css`, `js/2.*.js`) trong theme **được hệ thống Keyweb tự động nạp (auto-enqueue)**. Không viết thêm hàm enqueue trong `functions.php` cho các file này để tránh bị nạp trùng 2 lần.

Chỉ dùng `wp_enqueue_scripts`, `admin_enqueue_scripts`, `login_enqueue_scripts` khi cần nạp **asset ngoài/custom riêng biệt** không nằm trong quy tắc đánh số tự nạp của theme. Khai báo dependency thật và điều kiện tải chính xác; không giả định script phụ thuộc jQuery nếu dùng vanilla.

Ví dụ nạp asset custom riêng biệt (không dùng tiền tố số tự động):

```php
<?php
function kwdtc_example_enqueue_custom_script() {
    if (!is_page('custom-tool')) {
        return;
    }
    $relative = 'js/vendor-tool.js';
    $path     = get_theme_file_path($relative);
    if (!is_file($path)) {
        return;
    }
    wp_enqueue_script(
        'kwdtc-custom-tool',
        get_theme_file_uri($relative),
        array(),
        (string) filemtime($path),
        true
    );
}
add_action('wp_enqueue_scripts', 'kwdtc_example_enqueue_custom_script');
```

Với JS, đặt footer/strategy theo version và dependency, tránh `async` khi thứ tự bắt buộc. Truyền config qua `wp_add_inline_script()` + JSON encoding an toàn sau register; `wp_localize_script()` phù hợp cho chuỗi dịch. Frontend AJAX dùng `admin_url('admin-ajax.php')` được truyền từ PHP, không giả định `ajaxurl` tồn tại. Nonce action phải khớp handler.

## Menu và widget

Dùng menu WordPress đang có, hỗ trợ danh mục/submenu và trạng thái current theo yêu cầu; không hard-code Men/Women hoặc menu chỉ từ ảnh. `wp_nav_menu()` đặt `fallback_cb => false` nếu chưa có menu. Hướng dẫn “Cài đặt menu” chỉ hiện trong ngữ cảnh admin/preview có quyền `edit_theme_options`; khách công khai không nhận link admin.

Kiểm tra menu nhiều cấp, label dài, keyboard, focus, Escape, mở/đóng mobile và `aria-expanded`. Không cố định depth mới thấp hơn dữ liệu hiện hữu nếu brief không giới hạn. Widget/sidebar chỉ render khi dùng thật và có dữ liệu; không tự thêm sidebar cho mọi Page.

## Query, CPT và dữ liệu

- Đặt registration CPT/taxonomy trong plugin/mu-plugin hoặc file nghiệp vụ theo kiến trúc dự án, tại `init`. Kiểm tra capability và REST exposure theo yêu cầu.
- Không gọi `flush_rewrite_rules()` trên mỗi request. Dùng activation hook plugin, `after_switch_theme` phù hợp, hoặc thao tác migration có chủ đích. MU-plugin không tự có activation hook như plugin thông thường.
- Archive ưu tiên main query; secondary query chỉ reset postdata nếu đã thay `$post` bằng `the_post()`. Dùng phân trang/giới hạn phù hợp.
- Page meta theo [page-custom-fields.md](page-custom-fields.md). Không load scripts/metabox cho mọi màn hình admin.

## WooCommerce và catalog giới thiệu

1. Đọc plugin đang active, phiên bản template gốc và các overrides/hook của theme. Ưu tiên hook/filter khi đủ đáp ứng; chỉ override template khi cần thay markup. Giữ metadata/version template để theo dõi thay đổi plugin.
2. Overrides đặt đúng đường dẫn trong `woocommerce/`; không sửa trực tiếp plugin core. Có `woocommerce.php` có thể ảnh hưởng việc dùng `woocommerce/archive-product.php`, nên xác định template thực tế trước khi tạo override.
3. Dùng WooCommerce product API/query phù hợp; kiểm tra object tồn tại và dữ liệu public. Không lấy tất cả sản phẩm vào bộ nhớ để tự lọc bằng JS.
4. Site giới thiệu/catalog chỉ có nút liên hệ/báo giá nếu brief yêu cầu; không tự thêm giá, giỏ hàng hay checkout. Nếu cần vô hiệu hóa mua hàng thật, xử lý server/API theo phạm vi, không chỉ ẩn nút bằng CSS.
5. Danh mục cha/con và attributes lấy từ dữ liệu thật. Bộ lọc chỉ hiển thị lựa chọn phù hợp tập sản phẩm trong phạm vi đang xem. Xử lý sản phẩm chưa phân loại/thiếu attribute theo dữ liệu WordPress hiện hữu; không âm thầm gán vào danh mục do AI đoán.
6. Nhiều danh mục con có thể dùng trang danh mục trung gian nếu brief chọn cách đó; nhiều filter cần bố cục gọn, trạng thái đang chọn và xóa lọc. Giữ URL/phân trang nhất quán, xử lý không có kết quả.
7. Trang chi tiết ít dữ liệu cần bố cục phù hợp; không tạo thông số, chứng nhận hoặc nội dung quảng cáo giả. Gallery/related products chỉ bật khi có dữ liệu/chức năng.
8. Conditionals WooCommerce cần guard plugin, bao gồm `is_account_page()` khi asset thực sự phục vụ tài khoản; `is_woocommerce()` không bao phủ mọi trang cart/checkout/account.

## Tài liệu chính thức

- [Theme supports](https://developer.wordpress.org/reference/functions/add_theme_support/)
- [wp_enqueue_script()](https://developer.wordpress.org/reference/functions/wp_enqueue_script/)
- [WooCommerce template structure](https://developer.woocommerce.com/docs/theming/theme-development/template-structure/)
