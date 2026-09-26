# Bảo mật và xử lý dữ liệu

## Chọn đúng mô hình quyền

| Thao tác | Kiểm tra cần có |
| --- | --- |
| Admin sửa Page/meta/settings | Authentication, capability phù hợp (`edit_post`, `edit_theme_options`...), object/scope, nonce cho request dùng phiên đăng nhập |
| AJAX riêng tư | Quyền/object ownership trước khi thực thi, nonce/CSRF phù hợp cơ chế xác thực, validation dữ liệu |
| Form liên hệ công khai | Chủ đích cho khách gửi, giới hạn input và chống lạm dụng phù hợp; không bắt khách có `manage_options` |
| REST public chỉ đọc | `permission_callback` công khai có chủ đích; chỉ trả dữ liệu công khai, giới hạn query |
| REST riêng tư/thay đổi dữ liệu | Authentication theo cơ chế dùng, `permission_callback` kiểm tra capability/object; cookie auth dùng REST nonce phù hợp |

Nonce hỗ trợ chống CSRF, không xác nhận chắc chắn request “xuất phát từ chính website”, không thay phân quyền và không bảo vệ khỏi replay. Nonce mặc định của khách không đăng nhập dùng chung user ID 0; không coi nó là cơ chế chống spam hoặc phiên khách riêng. Không bắt mọi public GET/REST dùng nonce WordPress nếu cơ chế không cần.

## Quy trình nhận và lưu

1. Kiểm tra quyền, scope/object, phương thức và nonce khi phù hợp.
2. Kiểm tra field có được gửi và kiểu dữ liệu trước khi gọi hàm xử lý chuỗi/số. `$_POST['name']` có thể là mảng.
3. Với `$_POST`/`$_GET` do WordPress thêm slash, dùng `wp_unslash()` trước xử lý. Không tự unslash lần nữa payload JSON đã được REST parser giải mã.
4. Validate cấu trúc, whitelist, giới hạn số lượng/độ dài/range và ý nghĩa nghiệp vụ; sanitize theo loại dữ liệu. `absint(-5)` thành 5, nên không dùng nó để chứng minh input là ID dương hợp lệ.
5. Dữ liệu lỗi phải có thông báo và giữ giá trị cũ. Field vắng mặt không có nghĩa khách muốn xóa. Danh sách rỗng hợp lệ là một trường hợp riêng.
6. Lưu qua WordPress API, kiểm tra kết quả. `update_post_meta()` trả false có thể do giá trị không đổi; kiểm tra lại khi cần, không báo lỗi mọi lần false.

| Dữ liệu | Cách xử lý |
| --- | --- |
| Text / textarea | Kiểm tra string/độ dài; `sanitize_text_field()` / `sanitize_textarea_field()` |
| Email | Kiểm tra string, sanitize phù hợp và `is_email()`; xử lý input lỗi rõ ràng |
| URL | Kiểm tra scheme/đích theo nghiệp vụ; sanitize URL khi lưu, `esc_url()` khi xuất |
| Rich text | Whitelist HTML theo chức năng; `wp_kses_post()` khi cho phép nội dung post |
| Màu / giá trị CSS | Validator chuyên biệt (`sanitize_hex_color`, whitelist đơn vị/range); không chỉ `esc_attr()` |
| ID / số lượng | Validate số nguyên dương/range trước khi chuẩn hóa; kiểm tra object tồn tại/quyền |
| Repeater | Kiểm tra list, từng item, từng field; không chấp nhận object/scalar thay list |

Metabox/repeater tham khảo [page-custom-fields.md](page-custom-fields.md). Scope đúng Page, bỏ qua autosave/revision theo thiết kế; nếu cần revision của meta thì triển khai rõ theo phiên bản, không tuyên bố mặc định đã hỗ trợ.

## Escaping tại output

Dùng `esc_html()` cho text, `esc_attr()` cho attribute, `esc_url()` cho URL và whitelist HTML cho rich text. Escape theo context cuối cùng, không escape trước khi lưu làm dữ liệu bị encode nhiều lần. Template tags/API đã trả markup an toàn cần được dùng theo contract, không bọc tất cả bằng `esc_html()` làm hỏng HTML.

Truyền dữ liệu PHP → JS bằng WordPress script API và `wp_json_encode()`. Nếu nhúng JSON vào inline script, dùng cờ `JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT` theo context. Dùng `textContent`/jQuery `.text()` cho thông báo văn bản từ response; không ghép response chưa tin cậy vào `.html()`/`innerHTML`.

Khi lưu chuỗi JSON bằng `update_post_meta()`, xử lý slashing đúng, ví dụ `wp_slash($encoded_json)` sau khi đã kiểm tra encode thành công. Metadata API cũng hỗ trợ mảng; không tự serialize mảng trước khi đưa vào API. Giá trị chứa backslash, dấu nháy và Unicode phải qua kiểm tra round-trip.

## AJAX và REST

- Namespace/action/nonce phải khớp giữa PHP và JS, không dùng nonce mẫu khác tên trong file enqueue.
- Dùng `wp_send_json_success()` / `wp_send_json_error()` với HTTP status phù hợp. Frontend xử lý network failure, JSON lỗi, nonce hết hạn, duplicate submit và phục hồi trạng thái nút.
- Với `wp_mail()`, true chỉ cho biết yêu cầu gửi đã được hệ thống xử lý chấp nhận, không chứng minh thư vào inbox. Nội dung phản hồi nên là đã tiếp nhận yêu cầu; kiểm tra mail delivery riêng nếu có lỗi thực tế.
- Route REST đăng ký trong `rest_api_init`, có namespace, methods, `permission_callback` và schema/validation args phù hợp. Không công khai meta/private fields chỉ để JS dễ lấy.
- Form public không nhận tùy ý email người nhận, đường dẫn file hoặc trường đặc quyền từ request. Chống spam theo nguy cơ thực tế và hệ thống form đang có, ưu tiên tái sử dụng Contact Form 7 nếu dự án dùng.

## SQL, upload và HTTP

Ưu tiên WordPress/WooCommerce API. Với SQL có giá trị động, dùng `$wpdb->prepare()` và placeholders phù hợp. Whitelist identifier/ORDER BY; không nối tên cột do người dùng nhập. Câu SQL tĩnh không có placeholder không cần bọc `prepare()` vô nghĩa.

Upload dùng Media/Upload API, capability, kiểu file thật và giới hạn hợp lý. Không mở SVG không được sanitize. Không tin MIME khai báo từ client.

Dùng HTTP API của WordPress với timeout, kiểm tra status/error và validate JSON. Nếu URL do người dùng cung cấp, dùng cơ chế safe remote request/allowlist phù hợp để tránh SSRF. Không tắt SSL verification hoặc ghi secret/request nhạy cảm vào log.

## Tài liệu chính thức

- [Nonces](https://developer.wordpress.org/apis/security/nonces/)
- [Data validation](https://developer.wordpress.org/apis/security/data-validation/)
- [update_post_meta()](https://developer.wordpress.org/reference/functions/update_post_meta/)
- [REST authentication](https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/)
