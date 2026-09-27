---
name: wordpress-design-to-code
description: "Chuyển ảnh design (Figma/PSD/JPG/PNG) thành giao diện WordPress HTML/CSS/JS thuần (không framework), backend PHP thuần trong functions.php theo hệ thống keyweb option của công ty. Dùng khi: người dùng gửi ảnh design muốn code giao diện WP; cần tạo/sửa template, section, block theme; viết functions.php, custom hook, keyweb option (namePanel/nameSection/name/partial); xử lý form/AJAX trong WP; hoặc nhắc đến '.full-row', '.inner-container'. Luôn ưu tiên skill này hơn code WP chung chung vì có quy chuẩn riêng bắt buộc."
---

# WordPress Design-to-Code (Frontend HTML/CSS/JS thuần + Backend PHP thuần)

Skill này giúp code lại giao diện WordPress từ ảnh thiết kế, và xử lý backend bằng PHP thuần, theo đúng quy chuẩn nội bộ của công ty. Không dùng framework CSS (Bootstrap, Tailwind...), không dùng framework JS (React, Vue...), không dùng ACF/Timber cho backend (trừ trường hợp field-cấp-bài-viết của Custom Post Type, xem mục "Quy tắc phân loại nội dung").

## Quy trình tổng quát

1. **Xác nhận input**: Nếu người dùng nói sẽ code theo ảnh design nhưng chưa đính kèm ảnh, hoặc nói sẽ gửi cấu trúc thư mục dự án nhưng chưa gửi, dừng lại và hỏi trước khi code — không tự bịa cấu trúc thư mục hay tự đoán bố cục từ mô tả chung chung. Nếu đây là trang đầu tiên của một dự án mới, hỏi thêm sitemap dự kiến của toàn site (tên trang + loại: trang tĩnh / trang danh sách / trang chi tiết của loại nội dung nào) để dùng xuyên suốt các trang sau, tránh phải đoán lại quan hệ giữa các trang mỗi khi nhận ảnh mới.
2. **Xác nhận cấu trúc thư mục dự án**: Cấu trúc thư mục (assets/css, assets/js, template-parts...) thay đổi theo từng dự án. Nếu chưa có ảnh/thông tin cấu trúc thư mục của dự án hiện tại, hỏi người dùng trước khi tạo file mới. Nếu đang sửa file có sẵn, xem cấu trúc thư mục hiện có của dự án để theo đúng convention đang dùng, không tạo cấu trúc mới song song.
3. **Content Audit (bước bắt buộc, làm trước khi code)**: Trước khi viết bất kỳ dòng HTML/PHP nào cho trang, liệt kê từng khối nội dung trong ảnh thành bảng gồm: [Tên khối] – [Loại: singleton / lặp lại (list) / điều hướng] – [Đề xuất cơ chế: keyweb option / Custom Post Type / wp_nav_menu / static]. Dùng "Quy tắc phân loại nội dung" bên dưới để xếp loại. Gửi bảng này cho người dùng xác nhận trước khi code, trừ khi loại nội dung đã quá rõ ràng (ví dụ copyright text ở footer, hoặc loại đã được xác nhận từ sitemap ở bước 1). Không tự quyết một mình với các khối có khả năng lặp lại nhiều instance (sản phẩm, tin tức, dự án, đội ngũ...) — quyết định sai CPT rất tốn công sửa lại (ảnh hưởng URL, SEO, dữ liệu đã nhập).
4. **Chuẩn bị backend trước, nếu trang cần CPT/taxonomy/field mới**: nếu Content Audit ở bước 3 xác định trang có nội dung dạng lặp lại (Custom Post Type) hoặc field mới chưa tồn tại, đăng ký CPT/taxonomy, tạo meta box hoặc ACF field group, thêm option vào file `NN_opt_*.php` trước — chưa cần đẹp, chỉ cần đủ để có dữ liệu thật để code frontend gọi vào. Nếu trang chỉ toàn nội dung tĩnh/singleton dùng option đã có sẵn từ trước, bỏ qua bước này và code frontend trước như bình thường (không bắt buộc đảo thứ tự khi không cần thiết).
5. **Code frontend theo ảnh design** — xem phần "Quy tắc Frontend" bên dưới. Nếu bước 4 đã chuẩn bị dữ liệu, gọi trực tiếp từ CPT/option đó (`_opt()`, `the_field()`, `get_post_meta()`...), không hardcode tạm rồi tính sửa lại sau.
6. **Code backend còn lại (nếu chưa xong ở bước 4)** — xem phần "Quy tắc Backend" bên dưới.
7. **Nối frontend ↔ backend**: kiểm tra `_opt()`, `the_field()`, `get_post_meta()`... đã trỏ đúng key chưa, không còn dữ liệu hardcode tạm.
8. Sau khi code xong, đối chiếu nhanh lại với ảnh design: bố cục, khoảng cách, breakpoint responsive, và các quy tắc SEO bên dưới, rồi mới đưa cho người dùng xem.

## Quy tắc Frontend (HTML/CSS/JS thuần)

### Class bắt buộc (không tự đổi tên, không tự bỏ)

Mọi section/block full màn hình đều phải bọc theo đúng 2 lớp sau, không được gộp hoặc thay thế bằng class khác:

```css
.full-row {
  width: 100%;
}

.inner-container {
  max-width: 1240px;
  margin: 0 auto;
  box-sizing: border-box;
  padding: 0 15px;
  width: 100%;
}
```

Cấu trúc HTML mẫu cho một section:

```html
<section class="full-row [ten-section-rieng]">
  <div class="inner-container">
    <!-- nội dung section -->
  </div>
</section>
```

- `.full-row` luôn là class bọc ngoài cùng của section (để nền/màu section có thể full width).
- `.inner-container` luôn là class bọc trực tiếp bên trong `.full-row` để giới hạn nội dung ở 1240px và căn giữa.
- Class riêng của từng section (mô tả nội dung, vd `.hero-banner`, `.about-us`) được thêm kèm theo `.full-row`, không thay thế nó.
- Nếu design có section không cần full-width nền nhưng vẫn cần giới hạn 1240px, vẫn dùng `.inner-container` như bình thường, không tự sáng tạo class thay thế.

### Đặt tên class khác (ngoài 2 class bắt buộc trên)

Nếu người dùng chưa cung cấp quy tắc đặt tên riêng cho phần còn lại (BEM, tên theo section...), hỏi trước khi code hàng loạt. Trong lúc chờ xác nhận, có thể đề xuất đặt tên rõ nghĩa theo section (kebab-case, tiếng Anh, mô tả đúng nội dung, ví dụ `.hero-banner__title`).

### Độ chính xác so với design

Không cần pixel-perfect tuyệt đối, nhưng phải:

- Giữ đúng bố cục, tỷ lệ, khoảng cách tương đối, màu sắc, font-size theo design.
- Ưu tiên code responsive tốt (mobile, tablet, desktop) hơn là khớp chính xác từng pixel ở 1 breakpoint.
- Nếu design chỉ có 1 kích thước (thường là desktop), tự suy luận hợp lý cách responsive xuống mobile/tablet theo logic UX thông thường, và nói rõ với người dùng là mình đã tự suy luận phần responsive để họ review lại. Nếu design không ghi rõ breakpoint, mặc định dùng mobile < 768px, tablet 768–1024px, desktop > 1024px mà không cần hỏi lại (xem thêm mục "Khi nào được tự quyết").

### CSS

- Viết CSS thuần (hoặc SCSS/Sass nếu người dùng nói rõ dự án đó dùng Sass), không dùng Bootstrap/Tailwind/framework khác trừ khi người dùng yêu cầu rõ cho dự án cụ thể.
- Mobile-first hoặc desktop-first đều được, nhưng phải nhất quán trong cùng 1 file/dự án — kiểm tra file CSS hiện có của dự án trước khi thêm code mới để theo đúng cách đang dùng.

### JS

- Dùng jQuery (có sẵn trong WordPress core), không dùng vanilla JS thuần trừ khi người dùng yêu cầu riêng cho dự án đó.
- Không enqueue thêm jQuery riêng nếu theme/plugin đã enqueue sẵn (`jquery` là handle mặc định của WordPress) — kiểm tra `functions.php` trước.

## Quy tắc Backend (PHP thuần trong functions.php)

- Toàn bộ logic backend viết trong `functions.php` của theme, có thể kết hợp gọi hook/filter từ các plugin đã cài (không cần viết lại logic plugin, chỉ hook vào).
- Không dùng ACF, không dùng Timber/Twig, không dùng ORM hay framework PHP khác cho hệ thống option cấp site/trang (xem ngoại lệ cho field-cấp-bài-viết trong "Quy tắc phân loại nội dung").
- Trước khi thêm function mới vào `functions.php`, xem các function đã có trong file để:
  - Tránh trùng tên function (PHP sẽ fatal error nếu khai báo trùng tên).
  - Theo đúng quy ước đặt tên function/hook đang dùng trong dự án (ví dụ prefix theo tên công ty/theme).
- Escape output đúng chuẩn WordPress (`esc_html`, `esc_attr`, `esc_url`...) và sanitize input (`sanitize_text_field`...) khi xử lý form/AJAX, kể cả khi công ty không có quy chuẩn riêng — đây là chuẩn bảo mật tối thiểu của WordPress, luôn áp dụng.
- Khi xử lý AJAX, dùng `wp_ajax_` / `wp_ajax_nopriv_` hook chuẩn của WordPress kết hợp `wp_verify_nonce` để bảo mật.

### Hệ thống Custom Option riêng của công ty ("keyweb")

Dự án dùng một theme framework nội bộ tên **keyweb**, đi kèm 1 plugin bắt buộc `keyweb/keyweb.php` (theme sẽ die() nếu plugin này chưa active — không xóa đoạn code kiểm tra này). Plugin keyweb cung cấp hệ thống custom option riêng (không phải WordPress Customizer chuẩn, không phải ACF). Khi cần thêm option để khách hàng tùy chỉnh nội dung (text, ảnh...) trong admin, PHẢI theo đúng pattern này, không tự chế ra hệ thống option khác.

**Vị trí khai báo option**: `wp-content/themes/keyweb/option/`, mỗi file đặt tên `NN_opt_[ten-trang-hoac-khu-vuc].php` (ví dụ: `01_opt_header.php`, `02_opt_footer.php`, `03_opt_home.php`, `04_opt_product.php`). Khi thêm option mới:

- Nếu option thuộc khu vực/trang đã có file (header, footer, home, product...), mở đúng file đó để thêm vào, không tạo file mới trùng khu vực.
- Nếu là khu vực/trang hoàn toàn mới, tạo file mới theo đúng format tên `NN_opt_[ten].php` với số thứ tự tiếp theo.

**Cấu trúc mảng `$options` trong mỗi file** (giữ nguyên format, không đổi tên key):

```php
$options = array(
	array( "namePanel" => "Cấu hình Header"),          // Tên panel hiển thị trong admin — chỉ khai báo 1 lần đầu file/nhóm
	array( "nameSection" => "Header Top"),              // Tên nhóm con trong panel — khai báo mỗi khi bắt đầu nhóm field mới
	array(  "name"        => "header_left_text",        // Key duy nhất, dùng để lấy giá trị ra template
	        "label"       => "Văn bản bên trái",         // Tên field hiển thị trong admin
	        "description" => "Đoạn text văn bản bên trái header", // Mô tả hướng dẫn cho admin
	        "default"     => "Giá trị mặc định",
	        "partial"     => ".left-header-top",         // Selector CSS của phần tử ngoài frontend tương ứng field này (dùng cho live preview)
	        "type"        => "text"),                    // Loại field: text, textarea, image, color...
);
$arrOpt = array_merge($arrOpt, $options);               // Luôn merge vào biến global $arrOpt ở cuối file
```

- `namePanel` = tên nhóm lớn (thường theo trang: Header, Footer, Trang chủ, Sản phẩm...).
- `nameSection` = tên nhóm nhỏ bên trong panel, dùng để chia field thành từng khối trong admin UI.
- `name` = key duy nhất toàn hệ thống, không trùng với các option đã có trong toàn theme (kiểm tra file khác trong `option/` nếu không chắc).
- `partial` = CSS selector trỏ đúng tới phần tử HTML ngoài frontend sẽ hiển thị giá trị field này — khi code frontend, nếu phần tử đó đã có `partial` selector định nghĩa, phải gắn đúng class/selector đó vào HTML, không tự đổi class khác.
- `type` phổ biến: `text`, `textarea`, `image`. Nếu cần loại khác, kiểm tra các file option đã có xem plugin keyweb hỗ trợ loại nào trước khi dùng.

**Lấy giá trị option ra template**: dùng hàm helper có sẵn từ plugin keyweb, ví dụ `_opt('ten_option')` (đã thấy dùng trong `functions.php`, vd `_opt('product_contact')`). Không tự viết lại hàm `get_option`/`update_option` thay thế — dùng đúng hàm `_opt()` (hoặc hàm tương đương nếu dự án dùng tên khác, kiểm tra file `functions.php`/plugin keyweb nếu không chắc tên hàm).

**Xử lý option kiểu `type => image`**: trước khi dùng, kiểm tra file option mẫu hoặc plugin keyweb xem `_opt()` với field ảnh trả về **URL** hay **attachment ID** — hai dự án keyweb khác nhau có thể khác nhau, không giả định cố định một kiểu.

- Nếu trả về URL: dùng trực tiếp trong `src`/`background-image`, kèm `alt` lấy từ `label`/`description` của option nếu không có field alt riêng.
- Nếu trả về attachment ID: dùng `wp_get_attachment_image($id, 'large')` thay vì tự ghép thẻ `<img>` thủ công, để có `srcset`/`sizes` responsive và alt tự động từ Media Library.

**Lưu ý phạm vi**: hệ thống `keyweb option` chỉ dùng cho nội dung **cấp site/trang** (singleton) — xem "Quy tắc phân loại nội dung" bên dưới để biết khi nào KHÔNG dùng option mà phải dùng Custom Post Type.

## Quy tắc phân loại nội dung: Option vs Custom Post Type vs Field-cấp-bài-viết

Trước khi code một khối nội dung trong ảnh design, xác định nó thuộc loại nào:

### 1. Singleton (chỉ có 1 instance, thuộc về 1 trang cố định)

Ví dụ: banner trang chủ, đoạn giới thiệu ở trang About, thông tin liên hệ ở footer, số lượng ô cố định không đổi (3 ô USP cố định trên trang chủ mà khách chỉ sửa nội dung, không thêm/bớt ô).
→ Dùng hệ thống **keyweb option** hiện có (`option/NN_opt_[trang].php` + `_opt()`).
→ KHÔNG tạo Custom Post Type cho loại này dù nội dung "nhìn giống nhiều mục" nếu khách không cần tự thêm/bớt số lượng.

### 2. Repeatable / danh sách (có thể tăng giảm số lượng, có trang chi tiết riêng hoặc trang danh sách/lọc)

Ví dụ: sản phẩm, tin tức, dự án, đội ngũ, đối tác, dịch vụ, testimonial (nếu khách cần tự thêm bớt).
→ Dùng **Custom Post Type**, đăng ký qua `register_post_type()`. Đặt tên template theo chuẩn WordPress: `single-{post_type}.php`, `archive-{post_type}.php`.
→ Nếu cần lọc/phân nhóm (danh mục sản phẩm, chuyên mục tin) → thêm **Custom Taxonomy** qua `register_taxonomy()`.
→ Field riêng của từng bài (giá, thông số, ngày sinh, chức vụ...) → KHÔNG dùng hệ thống keyweb option (option đó là theo trang/site, không theo từng post, không thể có N giá trị cho N sản phẩm). Dùng `add_meta_box()` + lưu qua hook `save_post`, hoặc nếu dự án đã cài ACF riêng cho phần CPT thì dùng ACF field group gắn vào post type đó. Đây là ngoại lệ hợp lệ với quy tắc "không dùng ACF" ở phần backend chung — quy tắc đó áp dụng cho _site-level option_ (thay thế bằng keyweb), không áp dụng cho field-cấp-bài-viết của CPT. Nếu chưa rõ dự án hiện tại có cài ACF hay dùng meta box thuần, hỏi người dùng trước khi chọn.

### 3. Điều hướng (menu, breadcrumb)

→ Xem mục "Header Menu & Mega Menu" riêng bên dưới. Không xếp vào 2 loại trên.

### Cách nhận biết nhanh khi nhìn ảnh design

Tự hỏi: "Nếu khách muốn thêm cái thứ N+1 giống hệt khối này, họ có cần bấm 'Thêm mới' trong admin không, hay chỉ sửa nội dung ô có sẵn?"

- Cần "Thêm mới" không giới hạn số lượng → Custom Post Type.
- Chỉ sửa nội dung cố định trong số ô đã định sẵn → keyweb option.

Nếu không chắc, hỏi lại người dùng, không tự đoán — quyết định CPT sai rất tốn công sửa lại sau này (ảnh hưởng URL, SEO, dữ liệu đã nhập).

## Header Menu & Mega Menu

KHÔNG BAO GIỜ hardcode HTML của menu trong `header.php`. Menu (kể cả mega menu) luôn phải lấy từ WordPress Menus (Appearance > Menus) để khách tự sửa được, không cần sửa code.

- Đăng ký vị trí menu trong `functions.php` bằng `register_nav_menus()`, ví dụ:
  ```php
  register_nav_menus( array(
      'primary' => 'Menu chính (Header)',
  ) );
  ```
- Render bằng `wp_nav_menu()`, không tự viết vòng lặp `<ul><li>` thủ công:
  ```php
  wp_nav_menu( array(
      'theme_location' => 'primary',
      'container'      => false,
      'walker'         => new Keyweb_Mega_Menu_Walker(), // Walker riêng để render mega menu
  ) );
  ```
- Cấu trúc mega menu (cột, nhóm con) lấy từ **cấu trúc menu item lồng nhau** (parent/child trong Appearance > Menus), không phải từ 1 khối HTML tự viết. Item cấp 2 = cột trong mega menu, item cấp 3 = link trong cột.
- Nếu design cần thêm mô tả ngắn hoặc icon cho từng mục mega menu, dùng trường có sẵn của menu item trước khi nghĩ tới giải pháp phức tạp hơn:
  - "Description" (bật qua Screen Options trong Appearance > Menus) cho đoạn mô tả ngắn.
  - "CSS Classes" để đánh dấu biến thể (ví dụ `has-icon-service1`, `col-featured`) rồi CSS/Walker xử lý theo class đó.
  - Nếu cần field phức tạp hơn (ảnh minh hoạ riêng cho từng mục menu) → phải hỏi người dùng trước, vì cần viết thêm Walker + hook `wp_setup_nav_menu_item`/custom meta box cho menu item, không có sẵn trong WordPress core.
- Viết `Walker_Nav_Menu` riêng (ví dụ class `Keyweb_Mega_Menu_Walker`) để tự thêm class bao mega menu, không sửa core hay dùng plugin mega menu ngoài trừ khi người dùng yêu cầu.

## Quy tắc SEO (bắt buộc khi code template)

- Mỗi trang chỉ có **1 thẻ `<h1>`** — thường là tiêu đề chính của trang (không phải logo, không phải tên site).
- Cấu trúc heading tuần tự: `h1 → h2 → h3`, không nhảy cấp.
- `<title>` và `<meta description>`: nếu dự án dùng plugin SEO (Yoast/RankMath) thì để plugin xử lý, không tự echo trong `<head>`. Nếu chưa có plugin, dùng `add_theme_support('title-tag')` và để WordPress core render.
- Ảnh: luôn có `alt` (lấy từ custom field/option nếu có, xem thêm mục xử lý `type => image` ở phần Backend), `loading="lazy"` cho ảnh dưới màn hình đầu, `width`/`height` khai báo rõ để tránh CLS (Cumulative Layout Shift).
- Link nội bộ dùng `esc_url(home_url(...))` hoặc `get_permalink()`, không hardcode domain.
- Schema markup: nếu trang chi tiết CPT (sản phẩm, bài viết, sự kiện...) cần schema, dùng JSON-LD trong template, ưu tiên hook vào plugin SEO nếu có thay vì tự viết trùng lặp.
- `single-{post_type}.php` và `archive-{post_type}.php`: đảm bảo `the_title()`, `the_excerpt()`, `the_content()` được gọi trong đúng ngữ cảnh loop (`have_posts()`/`the_post()`), không echo thủ công dữ liệu ra ngoài loop.

### Cấu trúc thư mục theme "keyweb" (tham khảo, dùng nếu dự án hiện tại theo đúng theme này)

```
wp-content/themes/keyweb/
├── css/
│   ├── 1.woocommerce.css      ← số thứ tự = nhóm/độ ưu tiên load, giữ đúng quy ước đánh số đã có
│   ├── 2.addon.css
│   ├── 2.archive.css
│   ├── 2.footer.css
│   ├── 2.lienhe.css
│   └── 1.layout.css
├── js/
├── option/
│   ├── 01_opt_header.php
│   ├── 02_opt_footer.php
│   ├── 03_opt_home.php
│   ├── 04_opt_product.php
│   ├── 04_opt_sidebar.php
│   ├── 05_opt_lienhe.php
│   ├── 06_opt_addon.php
│   ├── 07_opt_css.php
│   └── opt_demo.php
├── woocommerce/
├── functions.php
├── header.php
├── footer.php
├── page.php
├── archive-event.php / archive.php
├── single-program.php / single-professor.php   ← template theo custom post type, đặt tên theo chuẩn WordPress (single-{post_type}.php, archive-{post_type}.php)
├── university-post-types.php                    ← đăng ký custom post type riêng, tách file khỏi functions.php khi post type phức tạp
├── 404.php
```

Đây là ví dụ cấu trúc thực tế của 1 dự án, cấu trúc chi tiết vẫn có thể khác nhau giữa các dự án — luôn xem thư mục thực tế của dự án đang làm trước khi tạo file mới, theo đúng "Quy trình tổng quát" bước 2 ở trên.

## Khi nào được tự quyết (không cần hỏi)

Để tránh hỏi quá nhiều làm chậm workflow, các trường hợp sau được tự quyết mà không cần dừng lại hỏi người dùng:

- Text ngắn, mang tính cố định theo chuẩn (copyright, "Đọc thêm", "Xem tất cả", "Gửi", "Quay lại").
- Cấu trúc HTML semantic (dùng `<article>`, `<nav>`, `<aside>`, `<header>`, `<footer>` đúng ngữ cảnh) miễn không đổi class bắt buộc `.full-row`/`.inner-container`.
- Alt text mặc định cho ảnh nếu option/field không có mô tả riêng — lấy từ tiêu đề section hoặc tiêu đề bài viết gần nhất.
- Breakpoint responsive khi design không ghi rõ: mobile < 768px, tablet 768–1024px, desktop > 1024px.

Các trường hợp còn lại (đặc biệt: chọn CPT hay option, cấu trúc thư mục, tên plugin/hệ thống riêng của công ty, ACF hay meta box thuần) vẫn phải hỏi theo mục "Khi thiếu thông tin" bên dưới.

## Khi thiếu thông tin

Luôn hỏi lại người dùng thay vì tự đoán khi:

- Chưa có ảnh design nhưng được yêu cầu code giao diện.
- Chưa biết cấu trúc thư mục/file của dự án hiện tại (đặc biệt khi tạo file mới).
- Chưa rõ tên plugin/option riêng của công ty đang được nhắc tới trong yêu cầu.
- Chưa rõ quy tắc đặt tên class ngoài `.full-row` / `.inner-container` cho một dự án cụ thể.
- Một khối nội dung có khả năng lặp lại (nên là Custom Post Type) nhưng chưa rõ khách có cần tự thêm/bớt số lượng hay không.
- Dự án hiện tại có dùng ACF cho field-cấp-bài-viết của Custom Post Type hay không.
- Chưa rõ `_opt()` với field ảnh trả về URL hay attachment ID trong dự án hiện tại.

Không tự tạo cấu trúc thư mục mới hoặc quy ước đặt tên mới song song với cấu trúc/quy ước đã có sẵn trong dự án.
