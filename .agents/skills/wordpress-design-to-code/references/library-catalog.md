# Kho thư viện Keyweb

## Quy tắc lựa chọn — nguồn chính

1. Kiểm tra tính năng có thể dùng code hiện có/JS/CSS đơn giản hay cần thư viện.
2. Khi cần thư viện, kiểm tra kho Keyweb và asset đã tải trong dự án.
3. **Keyweb có thư viện đáp ứng chức năng: ưu tiên bắt buộc thư viện Keyweb.** Không chọn bên ngoài vì quen API hoặc thấy triển khai tiện hơn. Nếu cần slider mà Keyweb đã có thư viện đáp ứng, dùng thư viện đó dù thư viện ưa thích của AI không có trong kho.
4. **Chỉ dùng thư viện ngoài khi kho Keyweb không có thư viện đáp ứng chức năng cần làm.** Ghi rõ chức năng còn thiếu, dependency và nguồn ngoài đã chọn. Tuân thủ quy trình báo quản lý để hỗ trợ tích hợp vào kho khi dự án yêu cầu; không biến bước này thành lệnh cấm tuyệt đối mọi nguồn ngoài.
5. Kiểm tra phiên bản/API/dependency của bản thực tế. Nếu bản nội bộ có vấn đề tương thích hoặc chưa tải được, tìm nguyên nhân và báo rõ; lỗi kết nối không tự chứng minh kho không có thư viện. Không ngầm chuyển CDN.

Danh sách dưới đây do người dùng cung cấp. Nó là catalog tên/đường dẫn, không phải chứng nhận mọi phiên bản đều phù hợp. Không tự đổi slug, sửa chính tả tên lạ hoặc giả định API mới nhất.

## Catalog

| Thư viện                                                                | Dùng cho                              |
| ----------------------------------------------------------------------- | ------------------------------------- |
| [animate](https://lib.keyweb.vn/lib/animate/)                           | Animation CSS (animate.css)           |
| [aos](https://lib.keyweb.vn/lib/aos/)                                   | Animate on scroll                     |
| [bxslider](https://lib.keyweb.vn/lib/bxslider/)                         | Slider/carousel                       |
| [carousel](https://lib.keyweb.vn/lib/carousel/)                         | Carousel                              |
| [chart](https://lib.keyweb.vn/lib/chart/)                               | Biểu đồ                               |
| [chosen](https://lib.keyweb.vn/lib/chosen/)                             | Select nâng cao                       |
| [circletype](https://lib.keyweb.vn/lib/circletype/)                     | Text theo hình tròn                   |
| [circliful](https://lib.keyweb.vn/lib/circliful/)                       | Circular progress/chart               |
| [countdown](https://lib.keyweb.vn/lib/countdown/)                       | Đếm ngược                             |
| [countto](https://lib.keyweb.vn/lib/countto/)                           | Đếm số tăng dần                       |
| [elevatezoom](https://lib.keyweb.vn/lib/elevatezoom/)                   | Zoom ảnh sản phẩm                     |
| [fancybox](https://lib.keyweb.vn/lib/fancybox/)                         | Lightbox/popup ảnh, video             |
| [flexslider](https://lib.keyweb.vn/lib/flexslider/)                     | Slider                                |
| [font-awesome-4.7.0](https://lib.keyweb.vn/lib/font-awesome-4.7.0/)     | Icon font (bản 4.7.0)                 |
| [font-awesome-5](https://lib.keyweb.vn/lib/font-awesome-5/)             | Icon font (bản 5)                     |
| [font-awesome-6](https://lib.keyweb.vn/lib/font-awesome-6/)             | Icon font (bản 6)                     |
| [fullcalendar](https://lib.keyweb.vn/lib/fullcalendar/)                 | Lịch/calendar                         |
| [google-translate](https://lib.keyweb.vn/lib/google-translate/)         | Dịch trang tự động                    |
| [invew](https://lib.keyweb.vn/lib/invew/)                               | Theo tài liệu nội bộ công ty          |
| [ionicons](https://lib.keyweb.vn/lib/ionicons/)                         | Icon font                             |
| [isotope](https://lib.keyweb.vn/lib/isotope/)                           | Filter/sort layout dạng lưới          |
| [jquery-cookie](https://lib.keyweb.vn/lib/jquery-cookie/)               | Đọc/ghi cookie qua jQuery             |
| [jquery-ezplus](https://lib.keyweb.vn/lib/jquery-ezplus/)               | Zoom ảnh                              |
| [jquery-lettering](https://lib.keyweb.vn/lib/jquery-lettering/)         | Hiệu ứng tách chữ                     |
| [jquery-parallax](https://lib.keyweb.vn/lib/jquery-parallax/)           | Parallax scroll                       |
| [jquery-rd-parallax](https://lib.keyweb.vn/lib/jquery-rd-parallax/)     | Parallax scroll (bản khác)            |
| [jquery-scrollbar](https://lib.keyweb.vn/lib/jquery-scrollbar/)         | Custom scrollbar                      |
| [jquery-simpleweather](https://lib.keyweb.vn/lib/jquery-simpleweather/) | Widget thời tiết                      |
| [jquery-slimscroll](https://lib.keyweb.vn/lib/jquery-slimscroll/)       | Custom scrollbar                      |
| [jquery-stellar](https://lib.keyweb.vn/lib/jquery-stellar/)             | Parallax scroll                       |
| [jquery-textillate](https://lib.keyweb.vn/lib/jquery-textillate/)       | Hiệu ứng text                         |
| [jquery-ui](https://lib.keyweb.vn/lib/jquery-ui/)                       | UI widget (datepicker, drag/drop...)  |
| [jquery-zoom](https://lib.keyweb.vn/lib/jquery-zoom/)                   | Zoom ảnh                              |
| [jquery183](https://lib.keyweb.vn/lib/jquery183/)                       | jQuery bản 1.8.3                      |
| [mapmultipoint](https://lib.keyweb.vn/lib/mapmultipoint/)               | Bản đồ nhiều điểm                     |
| [masonry](https://lib.keyweb.vn/lib/masonry/)                           | Layout dạng lưới xếp chồng            |
| [matchheight](https://lib.keyweb.vn/lib/matchheight/)                   | Đồng bộ chiều cao phần tử             |
| [mixitup](https://lib.keyweb.vn/lib/mixitup/)                           | Filter/sort layout                    |
| [moment](https://lib.keyweb.vn/lib/moment/)                             | Xử lý ngày giờ                        |
| [mousewheel](https://lib.keyweb.vn/lib/mousewheel/)                     | Sự kiện cuộn chuột                    |
| [nicescroll](https://lib.keyweb.vn/lib/nicescroll/)                     | Custom scrollbar                      |
| [pannellum](https://lib.keyweb.vn/lib/pannellum/)                       | Ảnh 360°/panorama                     |
| [particles](https://lib.keyweb.vn/lib/particles/)                       | Hiệu ứng particle nền                 |
| [prettyphoto](https://lib.keyweb.vn/lib/prettyphoto/)                   | Lightbox                              |
| [prettyphotowoo](https://lib.keyweb.vn/lib/prettyphotowoo/)             | Lightbox cho WooCommerce              |
| [slick](https://lib.keyweb.vn/lib/slick/)                               | Slider/carousel                       |
| [slicknav](https://lib.keyweb.vn/lib/slicknav/)                         | Menu mobile responsive                |
| [snow2](https://lib.keyweb.vn/lib/snow2/)                               | Hiệu ứng tuyết rơi                    |
| [spritespin](https://lib.keyweb.vn/lib/spritespin/)                     | Xoay ảnh 360° dạng sprite             |
| [sticky](https://lib.keyweb.vn/lib/sticky/)                             | Sticky element khi cuộn               |
| [swiper](https://lib.keyweb.vn/lib/swiper/)                             | Slider/carousel                       |
| [tilt](https://lib.keyweb.vn/lib/tilt/)                                 | Hiệu ứng nghiêng theo chuột (tilt/3D) |
| [uikit](https://lib.keyweb.vn/lib/uikit/)                               | UI framework UIkit                    |
| [wow](https://lib.keyweb.vn/lib/wow/)                                   | Animate on scroll (WOW.js)            |

## Cách gọi và thứ tự thực thi

```php
_kw_get_lib('font-awesome-4.7.0');
_kw_get_lib('swiper');
```

`_kw_get_lib()` là hàm PHP do Keyweb cung cấp. Đọc định nghĩa, dependency, cơ chế chống nạp trùng và cách các template đang gọi trước khi thêm lời gọi mới; không tự tạo helper nhái.

- Tài nguyên cần ở header: khai báo tích hợp trong `functions.php` hoặc file PHP được include từ đó, tại hook/thời điểm phù hợp với implementation. Nếu helper enqueue CSS, lời gọi phải đủ sớm để CSS được in ở head; không đợi đến sau lúc WordPress đã in styles.
- Tài nguyên không cần ở header: dùng điểm tập trung nạp thư viện trong quá trình dựng footer của Keyweb, **trước `wp_footer()`**. Giữ thứ tự dependency và init code sau khi thư viện sẵn sàng.
- File template/section xác định nhu cầu tài nguyên và dùng cơ chế đăng ký/hàng đợi sẵn có; không gọi PHP trực tiếp trong file `.css` hoặc `.js`. Quy tắc “sau khi khởi tạo footer” nói về loader PHP thực thi, không phải vị trí viết mã PHP trong asset tĩnh.
- Đọc helper để biết nó enqueue hay xuất thẻ trực tiếp. Không giả định đặt ở đâu trước `wp_footer()` cũng đúng. CSS cần vẽ ban đầu, đặc biệt icon/font/layout của slider, phải có đủ sớm để tránh nháy giao diện.
- Không nạp cùng thư viện hai lần qua `_kw_get_lib()` và CDN/npm. Dùng handle/dependency đang có khi helper hỗ trợ; không sửa helper toàn dự án chỉ để đáp ứng một section.

## Phiên bản, WordPress và ngữ cảnh admin

Giữ một bản jQuery phù hợp WordPress; slug `jquery183` là dữ liệu catalog legacy, không phải lựa chọn mặc định để thay jQuery core. Các slug như `fancybox`, `chart`, `swiper` không xác định phiên bản: đọc file hoặc manifest trước khi dùng API.

Kiểm tra helper có hỗ trợ admin khi tính năng nằm trong metabox. Asset admin riêng dùng `admin_enqueue_scripts`; tài nguyên WordPress có sẵn như Media Library dùng API core và không cần tải thêm bản ngoài.

Không đọc toàn bộ tài liệu của tất cả thư viện. Chỉ tra slug cần dùng; dùng tài liệu chính thức khớp phiên bản khi cần tham số/API.
