# Custom Fields và repeater gắn Page

## Chọn mô hình trước khi code

Nơi lưu theo [keyweb-options.md](keyweb-options.md). Dùng Page editor cho nội dung văn bản thông thường; Custom Fields cho các dữ liệu riêng có cấu trúc. Field đơn không cần gom vào JSON. Repeater phù hợp danh sách gắn với trang; đối tượng có lifecycle/trang riêng/tìm kiếm nên dùng mô hình dữ liệu sẵn có hoặc CPT.

Ưu tiên field/repeater đã tồn tại trong dự án. Chỉ dùng metabox native khi cần, không tự cài ACF. Scope metabox theo Page/template đã xác minh, không làm mọi Page có các ô nhập không liên quan. Admin assets chỉ tải ở màn hình phù hợp.

## Contract của field

Xác định key, kiểu, default, độ dài/số lượng hợp lý, required/optional, thứ tự item và hành vi khi rỗng trước khi làm UI. ID/URL ảnh theo API dự án, không lưu base64 ảnh vào option/meta.

Danh sách cần có nút Thêm, Xóa và Sắp xếp bằng bàn phím được; drag-and-drop có thể bổ sung. Một giới hạn kỹ thuật phải có lý do và cấu hình, không phải số item cố định lấy từ design. Vượt giới hạn phải báo lỗi, không truncate âm thầm.

## Lưu dữ liệu không mất nội dung cũ

- Scope/capability đúng post, nonce cụ thể, bỏ qua autosave/revision khi chưa triển khai hỗ trợ tương ứng.
- Form không gửi field: không cập nhật. Field có mặt nhưng sai schema: báo lỗi, giữ cũ. `[]` hợp lệ: lưu rỗng theo ý khách.
- Validate raw input rồi sanitize từng field; không coi `json_decode()` thành công là đã validate schema. Không nhận HTML trong trường text chỉ vì output sau đó có escape.
- Metadata API hỗ trợ mảng. Nếu dùng mảng, để WordPress serialize; xử lý slashing theo API để bảo toàn ký tự. Nếu dùng chuỗi JSON, encode thành công rồi `wp_slash()` trước `update_post_meta()`.
- `update_post_meta()` false không luôn là thất bại: có thể giá trị không đổi. Chỉ thông báo đã lưu khi kết quả/giá trị xác minh phù hợp.
- Dữ liệu cũ lỗi định dạng phải được phát hiện, không hiện UI rỗng rồi lưu đè. Không reset/chuẩn hóa toàn database khi chỉ mở màn hình sửa.

## Ví dụ FAQ có thể tái sử dụng

Các file tham khảo:

- [page-faq-fields.php](../assets/examples/page-faq-fields.php): metabox, validator, save handler, notices, helper render.
- [page-faq-fields.js](../assets/examples/page-faq-fields.js): thêm/xóa/đổi thứ tự, serialize JSON vận chuyển qua form; dữ liệu trong database lưu mảng.

Ví dụ này không được tự động include vào site. Trước khi tích hợp:

1. Đổi prefix/text domain/meta key theo dự án và tìm tên trùng. Mẫu dùng prefix `kwdtc_example_` và domain `keyweb`; domain phải khớp theme thật.
2. Đặt hai file theo loader của dự án và include PHP một lần. Cung cấp Page IDs đã xác minh qua filter `kwdtc_example_faq_page_ids`; mặc định danh sách trống nên không hiện metabox ở bất kỳ Page nào. Không chép ID ví dụ vào production.
3. Cung cấp cặp đường dẫn filesystem/URL thật của JS qua filter `kwdtc_example_faq_asset`. File phải tồn tại và URL tương ứng. Không có asset hợp lệ thì editor không cho lưu repeater, tránh mất dữ liệu khi JS thiếu.
4. Scope filter Page IDs được kiểm tra cả khi render, enqueue và save. Mẫu dùng màn hình sửa Page đã có ID; chỉ bật metabox sau khi đã xác định Page.
5. Điều chỉnh filter `kwdtc_example_faq_limits` theo dữ liệu thực tế nếu cần. Default mẫu: 100 item, câu hỏi 500 ký tự, câu trả lời 5.000 ký tự, payload 256 KiB. Đây là guard kỹ thuật của ví dụ, không phải ngưỡng quyết định Option/Page hay giới hạn của Keyweb.
6. Gọi `kwdtc_example_render_faq($page_id)` tại vị trí thích hợp trong template. Hàm chỉ xuất FAQ list; wrapper full-width/container do template cha quản lý.

Mẫu nhận cả thêm/xóa và reorder, xử lý không JS/corrupt data, bỏ qua request không có payload, giữ cũ khi input lỗi, báo lỗi admin theo user+post. Nút reorder lên/xuống là phương án truy cập được bằng bàn phím.

Giới hạn: ví dụ không tự triển khai revisions cho meta, migration từ key cũ hoặc REST editing. Dùng post locking WordPress khi sửa đồng thời; nếu sản phẩm cần giải quyết xung đột riêng, thiết kế version check rõ ràng. Form save của ví dụ dành cho metabox trong editor; kiểm tra trong editor thật mà dự án đang dùng trước khi bàn giao. Nếu editor gắn metabox/form vào DOM sau khi script đã chạy, tích hợp boot vào lifecycle phù hợp; không mặc định lượt quét DOM ban đầu bao phủ mọi editor.

## Tình huống kiểm tra quan trọng

| Đầu vào/hành động | Kết quả cần đạt |
| --- | --- |
| Tiếng Việt, dấu nháy, backslash, xuống dòng | Lưu/đọc lại đúng nội dung đã sanitize |
| Danh sách rỗng chủ động | Lưu `[]`, frontend không còn FAQ |
| Thiếu payload / autosave / Quick Edit | Meta cũ giữ nguyên |
| JSON lỗi, scalar/object thay list, field sai kiểu | Báo lỗi, không cập nhật meta |
| Item thiếu câu hỏi hoặc câu trả lời | Báo lỗi; hàng hoàn toàn trống có thể bỏ qua theo contract mẫu |
| Vượt limits | Báo lỗi, không cắt danh sách |
| Không có quyền hoặc nonce sai | Không thực hiện cập nhật |
| Dữ liệu cũ không đúng schema | Không cho UI ghi đè bằng danh sách rỗng |
| JS không tải hoặc lỗi khởi tạo | Payload không được gửi, meta giữ nguyên |
| Thêm, xóa, chuyển lên/xuống | Nội dung và thứ tự đúng sau lưu/reload |

[Custom Meta Boxes](https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/) và [update_post_meta()](https://developer.wordpress.org/reference/functions/update_post_meta/).
