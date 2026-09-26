# Keyweb Options và kiến trúc nội dung

## Mục lục

1. [Chọn nơi lưu](#chọn-nơi-lưu)
2. [Ngoại lệ nội dung nhẹ](#ngoại-lệ-nội-dung-nhẹ)
3. [Hiệu năng và dữ liệu thực tế](#hiệu-năng-và-dữ-liệu-thực-tế)
4. [Schema option Keyweb](#schema-option-keyweb)
5. [Selective Refresh](#selective-refresh)
6. [Danh sách động và chuyển dữ liệu](#danh-sách-động-và-chuyển-dữ-liệu)

## Chọn nơi lưu

Tài liệu này là nguồn chính của skill về lựa chọn Customizer / Page / Custom Fields. Quy tắc đã được chủ skill làm rõ: Home/Contact dùng option theo Keyweb; Page khác ưu tiên dữ liệu gắn Page, nhưng nội dung nhỏ và ổn định có thể dùng Customizer. Không dùng số field làm ranh giới kỹ thuật.

### Bảng tư duy kiến trúc chuẩn (Architectural Mental Model):

| Nhu cầu nội dung                                                       | Nên dùng                                 | Ghi chú & Thực thi                                                                                                                |
| :--------------------------------------------------------------------- | :--------------------------------------- | :-------------------------------------------------------------------------------------------------------------------------------- |
| **Một trang cố định** _(About, Capabilities, Contact...)_              | **Page**                                 | Thêm **Custom Field / Metabox** nếu có dữ liệu cần nhập riêng như ảnh banner, số liệu thống kê, danh sách các bước.               |
| **Nhiều mục cùng loại** _(Sản phẩm, Dự án, Dịch vụ...)_                | **CPT (Custom Post Type)**               | Tạo CPT cho từng _loại nội dung_ (không tạo CPT cho từng trang); thêm **Taxonomy** cho danh mục và **Custom Field** cho thông số. |
| **Tin tức, bài viết**                                                  | **Post & Category có sẵn**               | Tận dụng Post Type mặc định và Taxonomy của core WordPress.                                                                       |
| **Một giá trị dùng trên nhiều trang** _(Hotline, Email, Social, Logo)_ | **Cấu hình toàn site (Global Settings)** | Với classic theme đặt trong **Customizer / Theme Options (`wp_options`)** để sửa 1 nơi cập nhật toàn site.                        |
| **Header, footer, màu sắc, kiểu chữ của Block Theme**                  | **Site Editor / `theme.json`**           | Tùy phần cần chỉnh theo chuẩn FSE; không mặc định đưa hết vào Customizer.                                                         |

“Đưa vào Page” là gắn nội dung với Page tương ứng, không phải viết nội dung cứng vào `page-*.php`. Khách phải có nơi sửa thuận tiện. Không bắt buộc cài ACF: ưu tiên cơ chế có sẵn; có thể dùng metabox và Metadata API gốc.

#### Nguyên tắc triển khai Custom Fields cho từng Trang:

- **Tách biệt và đặc thù (Page-specific):** Mỗi trang tĩnh (About Us, Capabilities, Partners, Contact...) có hệ thống Custom Fields (Metabox) riêng biệt, bám sát 100% nội dung và từng section thực tế của trang đó.
- **Trọn gói (Self-contained):** Banner đầu trang (Hero / Breadcrumb) và toàn bộ các phần nội dung của trang nào thì nằm trọn vẹn trong file metabox của trang đó, không tạo một metabox banner chung áp đặt lên toàn bộ các trang.
- **Đăng ký có điều kiện (Conditional Registration):** Metabox của trang nào chỉ được đăng ký (`add_meta_box`) khi đang chỉnh sửa đúng trang đó (kiểm tra qua `_wp_page_template` hoặc `post_name`), không hiển thị tràn lan trên màn hình sửa các trang khác.

Không tự tạo CPT chỉ vì danh sách đạt một số lượng item cụ thể. Nếu danh sách chỉ phục vụ một Page và không cần trang riêng/tìm kiếm/phân loại/lifecycle riêng, repeater gắn Page vẫn có thể phù hợp; quyết định dựa trên cách quản trị và truy vấn thực tế.

Nếu brief/Lead của dự án hiện tại quy định tuyệt đối chỉ Home/Contact được vào option, tuân thủ quy định đó. Không tự suy diễn lời nhắc cũ thành quy định mới cho mọi dự án, cũng không tự tuyên bố đã được quản lý phê duyệt ngoại lệ.

## Ngoại lệ nội dung nhẹ

Có thể chọn Customizer cho Page nhỏ khi tổng thể đáp ứng các tiêu chí:

- Nội dung ngắn, cấu trúc đơn giản, ít nhóm chỉnh sửa và ít khả năng tăng mạnh.
- Không có danh sách lặp lớn/phức tạp hoặc nội dung dài cần quản trị theo từng đối tượng.
- Tổng dữ liệu theme và chi phí tạo controls/live preview vẫn phù hợp; không kết luận chỉ từ số field của riêng trang.
- Việc gom ở Customizer thuận tiện cho người quản trị và nhất quán với dự án.
- Không có chỉ dẫn hiện hành bắt buộc lưu nội dung đó vào Page.

Ghi một lý do ngắn trong kế hoạch, rồi triển khai trong phạm vi đã được giao; không cần bảng đếm field và dừng chờ xác nhận cho mọi trang nhẹ. Khi chưa đủ bằng chứng rằng dữ liệu nhỏ và ổn định, ưu tiên Page / Custom Fields.

Ví dụ: vài tiêu đề, đoạn mô tả ngắn và một nút có thể phù hợp với Customizer. Một danh sách nhà máy có địa chỉ, ảnh và thông tin mở rộng theo thời gian phù hợp hơn với Page/CPT. Không biến ví dụ thành giới hạn cứng 10 field, cấm ảnh hoặc cấm mọi repeater trong Customizer.

## Hiệu năng và dữ liệu thực tế

- Customizer là giao diện/API quản trị; nơi lưu phụ thuộc `type` và implementation. WordPress hỗ trợ `theme_mod`, `option` và custom setting types. Kiểm tra framework Keyweb thực tế trước khi kết luận.
- Với `theme_mod`, các giá trị nằm trong option `theme_mods_{stylesheet}`. Kiểm tra dung lượng serialized, autoload thực tế và cách đọc/cache; không khẳng định mọi phiên bản/cấu hình đều có `autoload='yes'`.
- Đăng ký control không đồng nghĩa mọi default/field trống đã được lưu. Nhiều controls vẫn có chi phí dựng giao diện dù giá trị chưa được lưu.
- Một field JSON chứa danh sách lớn vẫn có thể nặng hơn nhiều field văn bản ngắn. Gom JSON không tự giảm kích thước nội dung hoặc khắc phục mọi vấn đề hiệu năng.
- Nếu ảnh lưu dưới dạng attachment ID/URL, option chứa ID/URL chứ không chứa toàn bộ byte của ảnh. Tốc độ tải ảnh ngoài frontend cần tối ưu riêng.
- Post meta gắn với post ID; nó có thể được đọc/prime cache khi query nhiều bài, không chỉ khi mở trang chi tiết. Tránh đọc mọi meta của mọi Page trên mọi request.

Khi cần chẩn đoán site chậm, đo trên môi trường phù hợp: kích thước option/meta, số controls, thời gian mở/lưu Customizer và query thực tế. Không tự đặt ngưỡng “từ N field là vỡ data”.

## Schema option Keyweb

Đọc loader option, `_opt()` và một file đang chạy trước khi thêm schema. Pattern thường gặp: từng file tạo `$options`, rồi gộp vào `$arrOpt`; framework xử lý panel/section/setting/control. Đây là API nội bộ, không phải cú pháp Customizer core.

```php
<?php
// Chỉ dùng sau khi xác nhận loader đã khởi tạo $arrOpt và nhận các key này.
$options = array(
    array('namePanel' => '3. Trang chủ'),
    array('nameSection' => '1. Banner'),
    array(
        'name'        => 'home_hero_title',
        'label'       => 'Tiêu đề banner',
        'description' => 'Nhập tiêu đề ngắn cho banner.',
        'default'     => '',
        'partial'     => '.home-hero-title',
        'type'        => 'text',
    ),
);
$arrOpt = array_merge($arrOpt, $options);
```

| Type thường gặp trong bộ Keyweb cung cấp | Kiểm tra trước khi dùng                                       |
| ---------------------------------------- | ------------------------------------------------------------- |
| `text`, `textarea`, `image`, `color`     | Kiểu giá trị, sanitizer, URL hay attachment ID                |
| `select`, `radio`                        | Schema `choices` và whitelist phía server                     |
| `checkbox`, `number`                     | Giá trị boolean, min/max/step và validation server            |
| `drop_cat`, `drop_product_cat`           | Return type, plugin cần thiết và xử lý term không còn tồn tại |

Không tự thêm `type: repeater`, `sanitize_callback` hoặc một key nội bộ khác nếu loader chưa hỗ trợ. Khi framework thiếu tính năng, mở rộng đúng lớp control/API hiện có theo phạm vi công việc. Các ràng buộc HTML như `min`/`max` không thay validation server.

Lấy dữ liệu bằng `_opt()` theo return type thật và escape tại output:

```php
<?php $hero_title = _opt('home_hero_title', ''); ?>
<?php if (is_string($hero_title) && '' !== trim($hero_title)) : ?>
    <h1 class="home-hero-title"><?php echo esc_html($hero_title); ?></h1>
<?php endif; ?>
```

Dùng `esc_attr()` cho thuộc tính, `esc_url()` cho URL và whitelist HTML phù hợp cho rich text. Phân biệt key chưa có với giá trị rỗng khách chủ động lưu; không dùng default để làm nội dung đã xóa tự xuất hiện trở lại.

## Selective Refresh

`partial` là key framework cần được kiểm tra cách ánh xạ sang Customizer API. Một selector tự nó không bảo đảm có render callback, live preview hoặc edit shortcut.

Chọn phạm vi DOM nhỏ nhất có thể cập nhật đúng. Các field liên quan được phép dùng chung partial ở cấp component khi cần thay đổi cấu trúc, ví dụ ảnh + link + tên đối tác. Không buộc mọi field phải có selector độc lập.

Đảm bảo partial có thể render khi ban đầu component rỗng/ẩn. Với slider/menu trong vùng được thay DOM, xử lý hủy/khởi tạo lại có kiểm soát để không gắn trùng event. Không ghi dữ liệu chỉ để làm preview.

## Danh sách động và chuyển dữ liệu

Với danh sách do khách tự nhập, dùng repeater có thêm/xóa/sắp xếp, label và điều kiện rỗng rõ ràng. Không tạo sẵn N bộ field làm giới hạn nghiệp vụ. Vòng lặp render dữ liệu thật hoặc schema cố định hợp lý vẫn được dùng.

Nếu có ô nhập số lượng, dùng nó để hỗ trợ thêm dòng; giảm số lượng không được âm thầm xóa dữ liệu đã nhập. Ưu tiên thao tác thêm/xóa trực tiếp. Giới hạn kỹ thuật theo nhu cầu có thể tồn tại nhưng phải validate và báo lỗi, không cắt bớt item khi lưu.

Customizer dùng control được hỗ trợ; Page dùng metabox/repeater theo [page-custom-fields.md](page-custom-fields.md). Lưu mảng hoặc JSON theo implementation; khách không phải chỉnh JSON. Không gom toàn bộ nội dung một Page vào một field chỉ để giảm số key.

Nếu nhiệm vụ bao gồm chuyển option cũ sang Page:

1. Xác định chính xác Page ID, key cũ/mới và kiểu dữ liệu; không đoán ID production.
2. Chuẩn bị sao lưu/export và cách đối chiếu trước khi chuyển.
3. Migration phải chạy có chủ đích và lặp lại an toàn, không ghi đè meta đã có; không chạy chuyển dữ liệu trên mỗi frontend request.
4. Dùng `metadata_exists()` hoặc dấu migration để phân biệt chưa chuyển với giá trị rỗng hợp lệ. Fallback chỉ khi dữ liệu mới chưa tồn tại.
5. Kiểm tra admin, lưu/đọc lại và frontend trước khi bỏ fallback hoặc xóa key cũ. Không tự xóa dữ liệu nếu phạm vi chỉ yêu cầu đổi cách đọc.

## Tài liệu kỹ thuật

- [Customizer Objects](https://developer.wordpress.org/themes/classic-themes/customize-api/customizer-objects/)
- [set_theme_mod()](https://developer.wordpress.org/reference/functions/set_theme_mod/)
- [Managing Post Metadata](https://developer.wordpress.org/plugins/metadata/managing-post-metadata/)
