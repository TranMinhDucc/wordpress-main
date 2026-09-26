# Tư duy hệ thống và thiết kế WordPress

## Trước khi chọn giải pháp

Với mỗi yêu cầu, xác định:

1. Mục tiêu của trang và việc người xem cần hoàn thành.
2. Nội dung khách phải tự cập nhật sau bàn giao.
3. Nguồn dữ liệu thật và các quan hệ giữa Page, post, CPT, taxonomy, meta, option hoặc sản phẩm.
4. Template, hook, CSS/JS và trang quản trị đang tham gia vào chức năng.
5. Điều gì xảy ra khi dữ liệu rỗng, chỉ có một mục, rất nhiều mục, thiếu ảnh hoặc có nội dung dài.

Không xem ảnh thiết kế là bản đặc tả đầy đủ hành vi và dữ liệu.

## Nguồn dữ liệu và khả năng quản trị

Với mỗi nhóm nội dung, lần theo: nơi nhập → nơi lưu → cách truy vấn → nơi hiển thị → cách sửa/xóa.

Một thông tin cần có nguồn chính rõ ràng. Không sao chép danh mục, sản phẩm hoặc quan hệ dữ liệu sang một danh sách thủ công khác nếu WordPress/WooCommerce đã quản lý chúng. Chọn nơi lưu theo bản chất và vòng đời dữ liệu, dựa trên `keyweb-options.md`.

Thiết kế màn hình quản trị cùng lúc với frontend. Khách phải biết sửa ở đâu; trường tùy chọn được phép để trống mà không làm vỡ bố cục.

## Thiết kế theo nhiệm vụ người dùng

Sắp xếp thông tin theo câu hỏi và hành động của người xem, không chỉ theo thứ tự các khối trong ảnh mẫu. Với trang danh sách, xác định người xem khám phá, lọc, chuyển trang và quay lại như thế nào. Với trang chi tiết, xác định thông tin tối thiểu để hiểu đối tượng và hành động tiếp theo.

Chọn điều hướng, breadcrumb, cây danh mục và bộ lọc theo cấu trúc dữ liệu thực tế. Kiểm tra cả desktop và mobile, trạng thái đang chọn, không có kết quả, danh mục nhiều cấp và tên dài. Không thêm tính năng chỉ vì nó phổ biến trên website khác.

## Ranh giới trách nhiệm và tái sử dụng

Xác định template thực sự đang render trước khi sửa. Template chịu trách nhiệm trình bày; partial chia sẻ markup; helper chia sẻ logic phù hợp; hook/filter tích hợp WordPress/WooCommerce. Giữ nghiệp vụ độc lập giao diện theo kiến trúc dự án đang có.

Khi cùng một khối hoặc quy tắc xuất hiện ở nhiều nơi, rà soát khả năng dùng chung. Chỉ tách phần thực sự có cùng cấu trúc hoặc hành vi. Không tạo abstraction chỉ vì hai khối trông giống nhau.

## Phạm vi ảnh hưởng

Trước khi đổi taxonomy, query, field, component, token hoặc CSS toàn cục, tìm tất cả nơi đọc và hiển thị chúng. Ghi lại các template và trạng thái cần kiểm tra. Sau khi sửa, kiểm tra nơi nhập dữ liệu, nơi lưu và những giao diện chịu ảnh hưởng.

Nếu archive chuẩn và Page template riêng cùng thể hiện một catalog, làm rõ vai trò mỗi trang. Các quy tắc chung như phân cấp danh mục, bộ lọc và phân trang cần cho kết quả nhất quán.

## Chọn phương án

Khi có nhiều cách làm, ưu tiên theo thứ tự: đúng nhu cầu và dữ liệu thật → bảo toàn dữ liệu hiện có → phù hợp kiến trúc dự án → dễ quản trị → nhất quán giao diện → dễ bảo trì.

Chọn thay đổi nhỏ nhất giải quyết được bài toán, nhưng kiểm tra đầy đủ tác động của thay đổi đó. Không mở rộng phạm vi refactor nếu nhiệm vụ không đòi hỏi.
