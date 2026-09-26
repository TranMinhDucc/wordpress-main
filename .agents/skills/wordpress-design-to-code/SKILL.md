---
name: wordpress-design-to-code
description: "Chuyển thiết kế Figma/PSD/ảnh thành giao diện WordPress Keyweb; sửa theme, template, CSS/JS, Customizer, Custom Fields và tích hợp WooCommerce theo cấu trúc công ty. Dùng khi triển khai hoặc chỉnh sửa code WordPress trong dự án Keyweb bằng Antigravity hoặc coding agent."
---

> **Chưa rõ nên đọc gì theo thứ tự nào?** Xem [READING-ORDER.md](READING-ORDER.md) — lộ trình đọc theo 8 kịch bản thực tế (dự án mới, sửa 1 section, thêm field/repeater, query động, asset/font, bàn giao final, block theme, debug).

# WordPress Design-to-Code — Keyweb

## Phạm vi và yêu cầu cốt lõi

Mọi dự án thuộc phạm vi skill này đều dùng Keyweb. Mặc định áp dụng convention công ty; đọc implementation của từng dự án để mở rộng đúng cách, không hỏi lại dự án có dùng Keyweb hay không.

- **BẮT BUỘC TUYỆT ĐỐI cấu trúc `.full-row` và `.inner-container`**: MỌI thẻ `<section>` hoặc khối wrapper ngoài cùng (outer block / section) trong bất kỳ template PHP nào BẮT BUỘC phải mang class `full-row [custom-class]` (tuyệt đối không viết `<section class="[custom-class]">` đứng một mình). Khung chứa nội dung trung tâm bên trong BẮT BUỘC là `<div class="inner-container [custom-class]">`. Giữ cách đặt file, CSS đánh số và helper đang có. Hiểu rõ cơ chế Keyweb tự động nạp các file `css/1.*.css`, `css/2.*.css`, `js/2.*.js`; không viết thêm hàm enqueue thủ công lặp lại các file này. Kiểm tra wrapper cha để không bọc container thừa.
- Ưu tiên bắt buộc thư viện và font Keyweb. Có thư viện Keyweb đáp ứng chức năng thì dùng thư viện đó; chỉ dùng ngoài khi kho không có thư viện đáp ứng. Font cần dùng có trong kho thì dùng nguồn Keyweb; chỉ dùng ngoài khi kho không có font đó. Tái sử dụng tài nguyên đã tải, tránh nạp trùng.
- Dùng HTML/CSS/JS/PHP theo stack hiện có. Không tự thêm framework, page builder hoặc plugin như React, Tailwind, Bootstrap, ACF, Timber để thay kiến trúc. Quyền dùng tài nguyên ngoài khi kho thiếu không đồng nghĩa tự đổi stack.
- Nội dung khách cần sửa phải có giao diện quản trị rõ ràng. Danh sách thay đổi số lượng dùng nguồn dữ liệu thật hoặc repeater; không cố định số item theo ảnh và không yêu cầu khách nhập JSON.
- Bảo toàn dữ liệu và hành vi hiện có khi sửa code. Không đổi key, migrate dữ liệu, di chuyển logic hoặc sửa core/plugin ngoài phạm vi công việc đã được yêu cầu.

## Quy trình làm việc

1. **Đọc đầu vào và dự án.** Xác định trang/section, design, nội dung thật, asset, theme đang active và parent/child theme. Đọc quy định dự án, cây file, template liên quan, `functions.php`, file include, CSS/JS, `_opt()`, `_kw_get_lib()`, cách nạp font, options và custom fields. Áp dụng tư duy hệ thống từ [system-design-thinking.md](references/system-design-thinking.md) để hiểu mục tiêu người dùng và vòng đời dữ liệu trước khi chọn giải pháp.
2. **Chọn nhánh triển khai.** Theo classic theme đang có; nếu dự án thực tế dùng hybrid hoặc block templates thì đọc workflow tương ứng. Không chuyển loại theme chỉ để làm một section. Xác định đúng template đang được sử dụng, kể cả override trong database với block theme.
3. **Phân tích thiết kế và dữ liệu.** Tách các section; với mỗi phần, xác định nguồn dữ liệu, trường khách được sửa, số item thay đổi, cách xử lý thiếu dữ liệu và nội dung dài. Chọn nơi lưu theo [keyweb-options.md](references/keyweb-options.md), tư duy hệ thống theo [system-design-thinking.md](references/system-design-thinking.md) và tư duy nghiệp vụ theo [senior-playbook.md](references/senior-playbook.md). Nếu có nhiều nhóm mới, lập bảng ngắn: section → nguồn dữ liệu → nơi sửa → hành vi khi rỗng.
4. **Lập phạm vi thay đổi.** Nêu file sửa/tạo, phần dùng lại, tài nguyên cần nạp và giả định còn thiếu. Đánh giá ranh giới trách nhiệm và phạm vi ảnh hưởng tới các template/taxonomy khác. Kiểm tra kho thư viện/font trước khi chọn tài nguyên ngoài. Không yêu cầu duyệt lại các quyết định triển khai thông thường đã nằm trong phạm vi được giao.
5. **Code và tích hợp.** Dựng semantic markup với **BẮT BUỘC 100% outer section/block có class `full-row`** và container con bên trong có class `inner-container`. CSS theo module/tokens và JS theo dự án. Dùng dữ liệu WordPress/WooCommerce thật, hook/API phù hợp; chỉ nạp tài nguyên nơi cần. Đảm bảo thao tác admin thêm/sửa/xóa/sắp xếp khớp với frontend.
6. **Kiểm tra theo thay đổi & Tự rà soát (Self-Verification).** Chạy những kiểm tra có ý nghĩa cho phần đã sửa: cú pháp/build, visual/responsive, lưu và đọc lại dữ liệu, input lỗi, quyền, empty state, console và asset. **Tự rà soát lại 100% thẻ `<section>` và wrapper trong file vừa tạo/sửa để đảm bảo không bỏ sót class `full-row` và `inner-container`.** Theo [qa-checklist.md](references/qa-checklist.md) và tiêu chí "xong thật sự" trong [senior-playbook.md](references/senior-playbook.md); chỉ ghi đã kiểm tra khi thực sự chạy được.
7. **Bàn giao (Bắt buộc).** Trong mọi phản hồi hoàn thành công việc, BẮT BUỘC liệt kê danh sách đầy đủ tất cả các file đã sửa/tạo mới kèm clickable link `[filename](file:///...)` trực tiếp trong tin nhắn chat, tóm tắt thay đổi theo từng file, chỉ rõ nơi khách/admin chỉnh sửa nội dung, dependency/font, giả định và kết quả kiểm tra. Nêu phần chưa xác minh được và các bước kiểm tra lại cần thiết. Không yêu cầu xóa mọi cache cho mọi thay đổi. Trước khi bàn giao final (không phải sau mỗi sửa nhỏ), chạy audit theo [project-audit-checklist.md](references/project-audit-checklist.md).

## Quyết định nơi lưu và khả năng tùy chỉnh

[keyweb-options.md](references/keyweb-options.md) là nguồn chính về Customizer / Page / Custom Fields, ngoại lệ nội dung nhẹ, repeater và chuyển dữ liệu cũ. Luôn đọc tài liệu này trước khi thêm nhóm field mới; các workflow khác chỉ dẫn tới tài liệu, không đặt ngưỡng hoặc ngoại lệ riêng.

### Bảng tư duy kiến trúc chuẩn (Architectural Mental Model):

| Nhu cầu nội dung                                                       | Nên dùng                                 | Ghi chú & Thực thi                                                                                                                |
| :--------------------------------------------------------------------- | :--------------------------------------- | :-------------------------------------------------------------------------------------------------------------------------------- |
| **Một trang cố định** _(About, Capabilities, Contact...)_              | **Page**                                 | Thêm **Custom Field / Metabox** nếu có dữ liệu cần nhập riêng như ảnh banner, số liệu thống kê, danh sách các bước.               |
| **Nhiều mục cùng loại** _(Sản phẩm, Dự án, Dịch vụ...)_                | **CPT (Custom Post Type)**               | Tạo CPT cho từng _loại nội dung_ (không tạo CPT cho từng trang); thêm **Taxonomy** cho danh mục và **Custom Field** cho thông số. |
| **Tin tức, bài viết**                                                  | **Post & Category có sẵn**               | Tận dụng Post Type mặc định và Taxonomy của core WordPress.                                                                       |
| **Một giá trị dùng trên nhiều trang** _(Hotline, Email, Social, Logo)_ | **Cấu hình toàn site (Global Settings)** | Với classic theme đặt trong **Customizer / Theme Options (`wp_options`)** để sửa 1 nơi cập nhật toàn site.                        |
| **Header, footer, màu sắc, kiểu chữ của Block Theme**                  | **Site Editor / `theme.json`**           | Tùy phần cần chỉnh theo chuẩn FSE; không mặc định đưa hết vào Customizer.                                                         |

Mặc định dùng option cho Home, Contact và cấu hình toàn site. Nội dung riêng các Page khác ưu tiên Page editor / Custom Fields. Theo yêu cầu của chủ skill, trang nhỏ và ổn định có thể dùng Customizer sau khi đánh giá đặc điểm dữ liệu; không dùng ngưỡng cứng 10 field và không dừng chờ duyệt ngoại lệ thông thường này. Nếu brief hiện tại của một dự án có quy định chặt hơn, tuân thủ brief đó.

## Thư viện, font và layout Keyweb

- Đọc [company-conventions.md](references/company-conventions.md) khi sửa layout, template parts, CSS, enqueue hoặc helper.
- Đọc [library-catalog.md](references/library-catalog.md) trước khi thêm thư viện. `_kw_get_lib('slug')` là hàm PHP. Với tài nguyên cần ở header, đặt tích hợp trong `functions.php` hoặc file được include từ đó; mọi lời gọi phải chạy trước `wp_footer()`. Đọc helper để xác định hook/thứ tự thực sự, không chép lời gọi PHP vào CSS/JS.
- Đọc [font-catalog.md](references/font-catalog.md) trước khi thêm/đổi font. Danh sách tên không xác định sẵn URL, weight hoặc API nạp; kiểm tra code và `@font-face` thực tế.
- Nếu helper dự kiến bị thiếu, tìm nơi định nghĩa, autoload và include; báo phần phụ thuộc thực sự bị chặn. Không tự tạo helper nhái hoặc ngầm thay cơ chế của công ty.

## Tích hợp, bảo mật và chất lượng

- Đọc [wordpress-integration-checklist.md](references/wordpress-integration-checklist.md) khi sửa hooks, query, menu, asset hoặc WooCommerce. Giữ nghiệp vụ độc lập giao diện trong plugin/mu-plugin theo kiến trúc dự án; không tự di chuyển tính năng đang hoạt động.
- Đọc [security-and-data-handling.md](references/security-and-data-handling.md) khi có request, lưu dữ liệu, AJAX/REST, query, upload hoặc dữ liệu người dùng. Phân biệt thao tác admin với form công khai; nonce không thay authentication/authorization.
- Đọc [page-custom-fields.md](references/page-custom-fields.md) khi tạo/sửa metabox hoặc repeater trên Page. Ví dụ đi kèm là mẫu tham khảo để tích hợp có chủ đích, không tự bật trên mọi Page.
- Giữ label, focus, keyboard interaction, heading logic, alt phù hợp, thông báo lỗi và reduced motion. Phân biệt button hành động và link điều hướng. Dùng đúng text domain cho chuỗi giao diện tĩnh.

## Khi thiếu thông tin

Tự đọc workspace, thiết kế và dữ liệu đã có trước. Với phần có thể suy ra hợp lý như breakpoint thiếu bản mobile, chọn theo layout/convention và ghi giả định. Chỉ hỏi khi thiếu design bắt buộc để bám ảnh, thiếu quyền truy cập code cần sửa, chưa rõ API nội bộ thiết yếu sau khi đã tìm, hoặc có xung đột làm thay đổi đáng kể kết quả. Tiếp tục các phần không bị chặn.

## Tài liệu tham chiếu

| Tài liệu                                                                            | Đọc khi                                                               |
| ----------------------------------------------------------------------------------- | --------------------------------------------------------------------- |
| [company-conventions.md](references/company-conventions.md)                         | Wrapper, file layout, CSS/JS và helper Keyweb                         |
| [system-design-thinking.md](references/system-design-thinking.md)                   | Tư duy hệ thống, vòng đời dữ liệu, nhiệm vụ người dùng và phạm vi     |
| [senior-playbook.md](references/senior-playbook.md)                                 | Quyết định động/tĩnh, xử lý dữ liệu thiếu/dài, tránh lỗi khi bàn giao |
| [keyweb-options.md](references/keyweb-options.md)                                   | Chọn nơi lưu, schema options, selective refresh, ngoại lệ nhẹ         |
| [page-custom-fields.md](references/page-custom-fields.md)                           | Metabox/repeater, bảo toàn dữ liệu, tích hợp ví dụ                    |
| [library-catalog.md](references/library-catalog.md)                                 | Chọn và nạp thư viện Keyweb hoặc tài nguyên ngoài                     |
| [font-catalog.md](references/font-catalog.md)                                       | Chọn và nạp font công ty                                              |
| [classic-theme-workflow.md](references/classic-theme-workflow.md)                   | PHP templates và hierarchy của classic theme                          |
| [block-theme-workflow.md](references/block-theme-workflow.md)                       | Hybrid/block theme thực tế, editor và template overrides              |
| [wordpress-integration-checklist.md](references/wordpress-integration-checklist.md) | WordPress/WooCommerce API, query, menu và enqueue                     |
| [security-and-data-handling.md](references/security-and-data-handling.md)           | Request, lưu dữ liệu, phân quyền, escaping và endpoint                |
| [qa-checklist.md](references/qa-checklist.md)                                       | Kiểm tra theo phạm vi và bàn giao trung thực                          |
| [project-audit-checklist.md](references/project-audit-checklist.md)                 | Rà toàn site sau khi code xong nhiều trang, trước khi bàn giao final  |
