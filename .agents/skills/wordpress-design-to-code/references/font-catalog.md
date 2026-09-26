# Kho font Keyweb

## Quy tắc lựa chọn và nạp

- Kiểm tra font design và font đang được tải; **font cần dùng có trong kho Keyweb thì dùng nguồn Keyweb**. Chỉ dùng nguồn ngoài khi font cần dùng không có trong kho. Không tải Google Fonts/CDN khác cho font đã có nguồn công ty.
- Giữ nguyên tên trong catalog. Danh sách này do người dùng cung cấp; chưa xác định URL file font, tên `font-family` CSS, weight/style, API nạp hoặc giấy phép của từng file. Đọc loader, CSS `@font-face`, network/asset manifest của dự án để lấy dữ liệu thật. Không bịa `_kw_get_font()` hoặc suy URL từ slug.
- Kiểm tra weight/style thật và ký tự tiếng Việt khi nội dung cần. Không tự coi font tương tự là cùng font hoặc bản medium là đầy đủ mọi weight. Nếu bản nội bộ thiếu biến thể quan trọng, báo rõ và xử lý theo quy trình tài nguyên; không âm thầm lấy một bản trùng từ ngoài.
- Một số tên có thể là icon font; xác định từ CSS/glyph mapping trước khi dùng. Không dùng icon font làm font nội dung hoặc hiển thị icon sai bộ.
- Chỉ tải các family/weight dùng trong design. Chọn fallback hợp lý; cấu hình `font-display` và preload khi thực sự có ích theo cơ chế dự án. Không preload mọi font trong catalog.
- Nếu design chưa xác định font, chọn font có sẵn trong Keyweb phù hợp phong cách/ngôn ngữ và ghi giả định. Không thay font đã chốt chỉ vì sở thích.

## Danh sách tên được cung cấp

| Tên trong catalog |
| --- |
| `alegreya` |
| `alegreya sans` |
| `anybody` |
| `archaic` |
| `avertademo` |
| `bali` |
| `barlow condensed` |
| `baskerville` |
| `be vietnam pro` |
| `brandontext` |
| `comfortaa` |
| `cormorant garamond` |
| `cuprum` |
| `dancing script` |
| `daniel` |
| `dengxian` |
| `encode sans expanded` |
| `exo2` |
| `frank ruhl libre` |
| `gilroy` |
| `glober` |
| `globerbold` |
| `icielbrandontext` |
| `icielpacifico` |
| `icons` |
| `itim` |
| `josefin sans` |
| `lato` |
| `libre baskerville` |
| `linearicons` |
| `lobster` |
| `lobster-regular` |
| `lora` |
| `markazi text` |
| `mincho` |
| `montserrat` |
| `muli` |
| `nunito` |
| `nunito sans` |
| `old standard tt` |
| `open sans` |
| `open sans condensed` |
| `opensan-semibold` |
| `oswald` |
| `pacifico` |
| `pattaya` |
| `philosopher` |
| `playfair display` |
| `poppins` |
| `prompt` |
| `quicksand` |
| `raleway` |
| `roboto` |
| `roboto-regular` |
| `roboto condensed` |
| `roboto slab` |
| `seasons` |
| `segoeui` |
| `sfprodisplay-medium` |
| `sfueurostilecondensed` |
| `sfufutura` |
| `signika` |
| `source sans pro` |
| `srisakdi` |
| `svn-newton` |
| `svn-veneer` |
| `tangerine` |
| `techmarket-icons` |
| `themify` |
| `tinos` |
| `trirong` |
| `unisectvnu` |
| `utm-cookies` |
| `utm-keyweb` |
| `utm edwardiankt` |
| `utmavo` |
| `utmduepuntozero` |
| `utmfacebook` |
| `utmhelve` |
| `uvf-breathepro` |
| `vcoronet` |
| `viber` |
| `vnf-oswald` |
| `yanone kaffeesatz` |
| `yesevaone` |
