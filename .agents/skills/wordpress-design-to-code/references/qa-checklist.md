# QA theo phạm vi thay đổi

## Chọn kiểm tra cần thiết

| Phần thay đổi                  | Kiểm tra tối thiểu liên quan                                                                                                                               |
| ------------------------------ | ---------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Markup section / Page template | **BẮT BUỘC 100% thẻ `<section>` ngoài cùng có class `full-row`** và khung chứa bên trong có `inner-container`; không có section đứng trần thiếu `full-row` |
| CSS/markup một section         | Trang đó, wrapper cha, desktop/mobile, nội dung dài và empty state                                                                                         |
| Header/footer/global tokens    | Các loại trang đại diện đang có; navigation và responsive                                                                                                  |
| Field/repeater/lưu meta        | Quyền, input lỗi, bỏ qua field vắng mặt, lưu/đọc lại, xóa hết và thứ tự item                                                                               |
| Script/slider/modal            | Dependency/order, keyboard/focus, init không trùng, 0/1/nhiều item, lỗi network nếu dùng                                                                   |
| AJAX/REST/form                 | Public/private access đúng chủ đích, request hợp lệ/lỗi, response và trạng thái frontend                                                                   |
| Query/WooCommerce              | Dữ liệu public, taxonomy/attributes thật, phân trang, filter/empty state                                                                                   |
| Block template                 | Frontend + save/reopen editor, block validation, nguồn template database/file                                                                              |
| Migration                      | Backup/đối chiếu, lặp lại an toàn, không ghi đè dữ liệu đích, không làm nội dung đã xóa xuất hiện lại                                                      |

Chạy kiểm tra phù hợp và dừng khi rủi ro của thay đổi đã được xác minh. Sửa nhỏ không bắt buộc test lại toàn website hoặc tất cả trình duyệt. Không tạo test chỉ để so khớp câu chữ/implementation; ưu tiên hành vi khách nhìn thấy và dữ liệu không mất.

## Visual, Cấu trúc HTML và khả năng quản trị

- **Tuân thủ cấu trúc HTML Keyweb**: 100% thẻ `<section>` và wrapper cấp 1 phải có tiền tố class `full-row` (`<section class="full-row [name]">`), khung nội dung bên trong là `<div class="inner-container">`. Không viết thẻ `<section>` đứng trần.
- Bám bố cục, font, màu, spacing, tỷ lệ ảnh; font từ kho Keyweb khi có. Không dùng placeholder/demo data như kết quả production thật.
- Wrapper đúng cấu trúc cha, không lồng thêm container gây padding kép, không ghi đè global từ CSS trang. Không bắt mọi dự án dùng width 1240px.
- Kiểm tra tại viewport của design và điểm layout bắt đầu vỡ; kéo qua các khoảng giữa breakpoint. Màn hình nhỏ khoảng 360–390px là tình huống hữu ích, không phải ngưỡng áp dụng cố định cho mọi dự án.
- Không có overflow ngang ngoài ý muốn; sửa phần tử gây tràn thay vì che bằng `body { overflow-x: hidden; }`.
- H1/heading logic, label thật, focus, keyboard, menu nhiều cấp, zoom 200% và reduced motion khi liên quan. Touch target đủ dễ bấm theo chức năng.
- Ảnh đầu trang có khả năng LCP không bị ép lazy; các ảnh khác để WordPress/chiến lược tải phù hợp xử lý. Kiểm tra kích thước/srcset thay vì thêm `loading="lazy"` cho mọi ảnh.
- Khách tìm được nơi sửa; repeater thêm/xóa/sắp xếp đúng, không nhập JSON; dữ liệu 0/1/nhiều item và nội dung dài vẫn hiển thị hợp lý.

## Dữ liệu và security

- Chọn nơi lưu theo [keyweb-options.md](keyweb-options.md), bao gồm ngoại lệ nội dung nhỏ và ổn định. Không duyệt theo số field và không tự loại trừ ảnh khỏi ngoại lệ.
- Validate kiểu và schema phía server; nonce/capability/object đúng ngữ cảnh theo [security-and-data-handling.md](security-and-data-handling.md).
- Round-trip tiếng Việt, dấu nháy, backslash, xuống dòng, ký tự giống HTML; escape đúng khi render. Input lỗi không thay giá trị cũ.
- Thiếu payload/Quick Edit/autosave không làm mất field. Xóa hết có chủ đích lưu trạng thái rỗng, không khôi phục default/legacy data ngoài ý muốn.
- Nếu không hỗ trợ revisions của meta hoặc concurrent editing ở mức riêng, không tuyên bố đã hỗ trợ; giữ cơ chế post lock WordPress và nêu giới hạn khi liên quan.
- Không nạp đồng thời thư viện/font công ty và bản ngoài trùng chức năng. Kiểm tra hook/header/footer và asset 404.

## Môi trường và bằng chứng

- Chạy `php -l` cho file PHP đã sửa nếu có PHP runtime; JS build/lint/check theo toolchain hiện có. Syntax pass không chứng minh WordPress integration đã chạy.
- Dùng `WP_DEBUG`/log trên local hoặc staging phù hợp; không tự bật hiển thị lỗi trên production. Phân biệt lỗi mới do thay đổi với lỗi có sẵn, không âm thầm sửa toàn bộ ngoài phạm vi.
- Kiểm tra console/network khi có browser; trình duyệt khác khi có thay đổi tương thích cần xác minh. Không ghi “đã kiểm tra Safari” nếu không chạy được Safari.
- Không có môi trường WordPress, browser hoặc API nội bộ thì báo chính xác phần chưa chạy được và cách kiểm tra tại dự án. Không biến thiếu công cụ thành lý do ngừng các kiểm tra có thể làm.

## Bàn giao

Liệt kê đầy đủ file sửa/tạo và link phù hợp với môi trường, mô tả tác dụng của từng file/nhóm file. Nêu nơi chỉnh nội dung trong admin, thư viện/font dùng, lý do dùng ngoài nếu có, giả định responsive và kết quả kiểm tra thực tế. Với file đóng gói, mô tả nội dung gói và cách thay thế phần tương ứng; không tuyên bố đã cài vào môi trường của người dùng.

Phân loại rõ: đã kiểm tra / chưa có điều kiện kiểm tra / vấn đề còn lại. Hướng dẫn reload/xóa cache đúng tầng khi thực sự cần, không mặc định yêu cầu xóa mọi cache sau mỗi thay đổi.
