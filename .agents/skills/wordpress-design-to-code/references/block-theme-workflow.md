# Hybrid / Block theme trong dự án Keyweb

Chỉ đọc nhánh này khi code thực tế sử dụng block templates/theme.json hoặc nhiệm vụ yêu cầu tính năng editor tương ứng. Keyweb vẫn là nền tảng mặc định; không chuyển classic theme sang FSE để làm một trang.

## Xác định nguồn template

- Đọc `theme.json`, `templates/*.html`, `parts/*.html`, `patterns/`, theme supports và parent/child theme. Có `theme.json` chưa đủ kết luận là block theme: classic theme cũng có thể dùng nó.
- Kiểm tra template/template part do Site Editor lưu trong database. Bản tùy chỉnh tương ứng trong database có thể ghi đè file theme; sửa file không bảo đảm giao diện thay đổi nếu bản override vẫn hoạt động.
- Không xóa/reset bản tùy chỉnh của khách để làm file mới có tác dụng. Xác định nguồn đang dùng, phạm vi thay đổi và xuất bản sao trước khi thao tác dữ liệu editor nếu nhiệm vụ cần.

## HTML templates và PHP

File `.html` chứa block markup không thực thi PHP được nhúng trực tiếp. Dùng core blocks, patterns hoặc dynamic block phù hợp khi cần dữ liệu động. `functions.php` vẫn có vai trò setup/enqueue/hook; PHP có thể tồn tại trong block theme.

Sự có mặt của `header.php` hoặc `footer.php` không tự vô hiệu hóa Site Editor. Không tự tạo PHP templates để thay block templates đang hoạt động; chọn đúng cơ chế theo yêu cầu.

Template part minh họa:

```html
<!-- wp:template-part {"slug":"header","area":"header","tagName":"header"} /-->
<!-- wp:group {"tagName":"main","layout":{"type":"constrained"}} -->
<main class="wp-block-group">
    <!-- wp:post-content /-->
</main>
<!-- /wp:group -->
<!-- wp:template-part {"slug":"footer","area":"footer","tagName":"footer"} /-->
```

Đây là minh họa block grammar, không phải layout Keyweb đầy đủ để chép nguyên. Giữ wrapper/class của dự án qua block attributes và markup tương ứng. Tránh kết hợp thêm container khi constrained layout đã giới hạn cùng vùng nội dung. Kiểm tra cả frontend và editor để tránh block validation errors.

## Theme JSON, patterns và nội dung

- Chọn schema/version `theme.json` theo phiên bản WordPress dự án hỗ trợ. Không tự nâng lên version mới hoặc dùng schema `trunk` như bằng chứng tương thích với production cũ.
- Lấy màu, spacing, font từ design/tokens thật. Font được nạp theo [font-catalog.md](font-catalog.md), không giả định khai báo family trong JSON là đã tải file font.
- Trong `patterns/*.php`, PHP có thể dùng để dựng nội dung pattern ban đầu. Không coi PHP trong pattern là nguồn động chạy lại mỗi lần khách sửa một block đã được chèn. Nội dung cần cập nhật động dùng block/API phù hợp.
- Dùng markup sinh ra từ editor/tài liệu khớp version thay vì tự đoán attributes. Link/CTA phải có đích thật; không để anchor không có `href` chỉ để giống nút.
- Chuỗi tĩnh cần dịch trong pattern PHP dùng text domain thật; không chèn hàm PHP vào `.html` để dịch.

## Hybrid

Classic theme có thể dùng `theme.json`, block styles và `block-template-parts`. Chỉ bật thêm supports khi yêu cầu chức năng và kiểm tra template đang sử dụng; không thay toàn bộ header/footer đang chạy. Giữ workflow dữ liệu và thư viện Keyweb, nhưng kiểm tra helper nạp assets có được gọi trong nhánh block đó không.

## Kiểm tra

Kiểm tra save/reopen trong editor, frontend, responsive và nguồn override thật. Ghi rõ chưa xác minh editor nếu môi trường không có WordPress hoạt động.

[Templates](https://developer.wordpress.org/themes/templates/templates/), [theme.json](https://developer.wordpress.org/themes/global-settings-and-styles/) và [Patterns](https://developer.wordpress.org/themes/patterns/).
