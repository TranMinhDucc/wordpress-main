<?php
/**
 * Reference implementation. Integrate deliberately; see page-custom-fields.md.
 * Configure Page IDs and the JS asset via filters. No Page is enabled by default.
 */
if (!defined('ABSPATH')) {
    exit;
}

function kwdtc_example_faq_enabled($post_id) {
    $ids = apply_filters('kwdtc_example_faq_page_ids', array());
    if (!is_array($ids)) {
        return false;
    }
    return in_array((int) $post_id, array_map('absint', $ids), true)
        && 'page' === get_post_type($post_id);
}

function kwdtc_example_faq_asset() {
    $asset = apply_filters('kwdtc_example_faq_asset', array('path' => '', 'url' => ''));
    if (!is_array($asset) || !isset($asset['path'], $asset['url'])
        || !is_string($asset['path']) || !is_string($asset['url'])
        || !is_file($asset['path']) || '' === esc_url_raw($asset['url'])) {
        return false;
    }
    return $asset;
}

function kwdtc_example_faq_limits() {
    $defaults = array('items' => 100, 'question' => 500, 'answer' => 5000, 'bytes' => 262144);
    $custom   = apply_filters('kwdtc_example_faq_limits', $defaults);
    foreach ($defaults as $key => $value) {
        if (is_array($custom) && isset($custom[$key])
            && is_int($custom[$key]) && $custom[$key] > 0) {
            $defaults[$key] = $custom[$key];
        }
    }
    return $defaults;
}

function kwdtc_example_faq_length($text) {
    if (function_exists('mb_strlen')) {
        return mb_strlen($text, 'UTF-8');
    }
    // Count Unicode code points, matching JS spread; strlen() counts UTF-8 bytes.
    $count = preg_match_all('/./us', $text, $matches);
    return false === $count ? PHP_INT_MAX : $count;
}

/** Validate without altering storage; returns a normalized array or WP_Error. */
function kwdtc_example_faq_validate_items($items) {
    if (!is_array($items) || (!empty($items) && array_keys($items) !== range(0, count($items) - 1))) {
        return new WP_Error('faq_shape', __('Danh sách FAQ không đúng định dạng.', 'keyweb'));
    }
    $limits = kwdtc_example_faq_limits();
    if (count($items) > $limits['items']) {
        return new WP_Error('faq_limit', __('Danh sách FAQ vượt giới hạn đã cấu hình.', 'keyweb'));
    }
    $clean = array();
    foreach ($items as $item) {
        if (!is_array($item) || !array_key_exists('question', $item) || !array_key_exists('answer', $item)
            || !is_string($item['question']) || !is_string($item['answer'])
            || array_diff(array_keys($item), array('question', 'answer'))) {
            return new WP_Error('faq_item', __('Mỗi FAQ cần câu hỏi và câu trả lời dạng văn bản.', 'keyweb'));
        }
        if (kwdtc_example_faq_length($item['question']) > $limits['question']
            || kwdtc_example_faq_length($item['answer']) > $limits['answer']) {
            return new WP_Error('faq_length', __('Nội dung FAQ vượt độ dài đã cấu hình.', 'keyweb'));
        }
        $question = sanitize_text_field($item['question']);
        $answer   = sanitize_textarea_field($item['answer']);
        if ('' === trim($question) && '' === trim($answer)) {
            continue;
        }
        if ('' === trim($question) || '' === trim($answer)) {
            return new WP_Error('faq_required', __('Hãy nhập đủ câu hỏi và câu trả lời hoặc xóa hàng trống.', 'keyweb'));
        }
        $clean[] = array('question' => $question, 'answer' => $answer);
    }
    return $clean;
}

function kwdtc_example_faq_decode($raw) {
    $limits = kwdtc_example_faq_limits();
    if (!is_string($raw) || strlen($raw) > $limits['bytes']) {
        return new WP_Error('faq_payload', __('Dữ liệu FAQ không hợp lệ hoặc quá lớn.', 'keyweb'));
    }
    // Decode objects as objects first: {} and [] must not become indistinguishable.
    $decoded = json_decode($raw);
    if (JSON_ERROR_NONE !== json_last_error() || !is_array($decoded)) {
        return new WP_Error('faq_json', __('Không đọc được danh sách FAQ; dữ liệu cũ được giữ nguyên.', 'keyweb'));
    }
    $items = array();
    foreach ($decoded as $item) {
        if (!is_object($item) || !($item instanceof stdClass)) {
            return new WP_Error('faq_item', __('Một mục FAQ không đúng định dạng.', 'keyweb'));
        }
        $items[] = get_object_vars($item);
    }
    return kwdtc_example_faq_validate_items($items);
}

function kwdtc_example_faq_notice_key($post_id) {
    return 'kwdtc_faq_' . get_current_user_id() . '_' . (int) $post_id;
}

function kwdtc_example_faq_error($post_id, $message) {
    set_transient(kwdtc_example_faq_notice_key($post_id), $message, 120);
}

function kwdtc_example_faq_register_metabox($post) {
    if ($post instanceof WP_Post && kwdtc_example_faq_enabled($post->ID)) {
        add_meta_box('kwdtc-example-faq', __('Danh sách FAQ', 'keyweb'),
            'kwdtc_example_faq_metabox', 'page', 'normal', 'default');
    }
}
add_action('add_meta_boxes_page', 'kwdtc_example_faq_register_metabox');

function kwdtc_example_faq_enqueue($hook) {
    if ('post.php' !== $hook) {
        return;
    }
    global $post;
    $asset = kwdtc_example_faq_asset();
    if (!$post instanceof WP_Post || !kwdtc_example_faq_enabled($post->ID) || !$asset) {
        return;
    }
    wp_enqueue_script('kwdtc-example-faq-editor', $asset['url'], array(),
        (string) filemtime($asset['path']), true);
}
add_action('admin_enqueue_scripts', 'kwdtc_example_faq_enqueue');

function kwdtc_example_faq_metabox($post) {
    $key   = '_kwdtc_example_faq_items';
    $items = metadata_exists('post', $post->ID, $key) ? get_post_meta($post->ID, $key, true) : array();
    $valid = kwdtc_example_faq_validate_items($items);
    if (is_wp_error($valid) || $valid !== $items) {
        echo '<p>' . esc_html__('Dữ liệu FAQ hiện có cần được kiểm tra trước khi chỉnh sửa. Chưa có dữ liệu nào bị thay đổi.', 'keyweb') . '</p>';
        return;
    }
    if (!kwdtc_example_faq_asset()) {
        echo '<p>' . esc_html__('Chưa cấu hình được tệp giao diện FAQ. Dữ liệu hiện có được giữ nguyên.', 'keyweb') . '</p>';
        return;
    }
    $json = wp_json_encode($items, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    if (false === $json) {
        echo '<p>' . esc_html__('Không đọc được dữ liệu FAQ để chỉnh sửa.', 'keyweb') . '</p>';
        return;
    }
    $limits = kwdtc_example_faq_limits();
    wp_nonce_field('kwdtc_example_faq_save_' . $post->ID, 'kwdtc_example_faq_nonce');
    ?>
    <div data-kwdtc-faq data-max-items="<?php echo esc_attr($limits['items']); ?>"
        data-max-question="<?php echo esc_attr($limits['question']); ?>"
        data-max-answer="<?php echo esc_attr($limits['answer']); ?>"
        data-max-bytes="<?php echo esc_attr($limits['bytes']); ?>"
        data-label-question="<?php echo esc_attr__('Câu hỏi', 'keyweb'); ?>"
        data-label-answer="<?php echo esc_attr__('Câu trả lời', 'keyweb'); ?>"
        data-label-up="<?php echo esc_attr__('Chuyển lên', 'keyweb'); ?>"
        data-label-down="<?php echo esc_attr__('Chuyển xuống', 'keyweb'); ?>"
        data-label-delete="<?php echo esc_attr__('Xóa mục', 'keyweb'); ?>"
        data-message-error="<?php echo esc_attr__('Chưa thể lưu FAQ: kiểm tra dữ liệu, độ dài và giới hạn danh sách.', 'keyweb'); ?>"
        data-message-ready="<?php echo esc_attr__('Bạn có thể thêm, xóa và sắp xếp FAQ. Nhấn Cập nhật trang để lưu.', 'keyweb'); ?>">
        <textarea data-faq-initial hidden disabled><?php echo esc_textarea($json); ?></textarea>
        <input data-faq-payload type="hidden" name="kwdtc_example_faq_json" disabled>
        <div data-faq-rows></div>
        <p><button type="button" class="button" data-faq-add disabled><?php esc_html_e('Thêm FAQ', 'keyweb'); ?></button></p>
        <p data-faq-status role="status" aria-live="polite"><?php esc_html_e('Đang mở trình chỉnh sửa FAQ. Nếu không mở được, dữ liệu cũ sẽ được giữ nguyên.', 'keyweb'); ?></p>
    </div>
    <?php
}

function kwdtc_example_faq_save($post_id) {
    if ((defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || wp_is_post_revision($post_id)
        || wp_is_post_autosave($post_id) || !kwdtc_example_faq_enabled($post_id)
        || !current_user_can('edit_post', $post_id)) {
        return;
    }
    // Missing payload is expected for Quick Edit, unavailable JS and unrelated saves.
    if (!array_key_exists('kwdtc_example_faq_json', $_POST)) {
        return;
    }
    $nonce = isset($_POST['kwdtc_example_faq_nonce']) && is_string($_POST['kwdtc_example_faq_nonce'])
        ? sanitize_text_field(wp_unslash($_POST['kwdtc_example_faq_nonce'])) : '';
    if (!wp_verify_nonce($nonce, 'kwdtc_example_faq_save_' . $post_id)) {
        kwdtc_example_faq_error($post_id, __('Phiên chỉnh sửa FAQ không hợp lệ. FAQ cũ chưa thay đổi.', 'keyweb'));
        return;
    }
    $payload = $_POST['kwdtc_example_faq_json'];
    $raw     = is_string($payload) ? wp_unslash($payload) : null;
    $clean   = kwdtc_example_faq_decode($raw);
    if (is_wp_error($clean)) {
        kwdtc_example_faq_error($post_id, $clean->get_error_message());
        return;
    }
    $key = '_kwdtc_example_faq_items';
    if (metadata_exists('post', $post_id, $key) && get_post_meta($post_id, $key, true) === $clean) {
        delete_transient(kwdtc_example_faq_notice_key($post_id));
        return;
    }
    // Metadata API removes slashes recursively. Preserve quotes/backslashes in array strings.
    $result = update_post_meta($post_id, $key, wp_slash($clean));
    if (false === $result && (!metadata_exists('post', $post_id, $key)
        || get_post_meta($post_id, $key, true) !== $clean)) {
        kwdtc_example_faq_error($post_id, __('Chưa lưu được FAQ. Hãy kiểm tra lại dữ liệu và thử lại.', 'keyweb'));
        return;
    }
    delete_transient(kwdtc_example_faq_notice_key($post_id));
}
add_action('save_post_page', 'kwdtc_example_faq_save');

function kwdtc_example_faq_admin_notice() {
    $screen = get_current_screen();
    if (!$screen || 'post' !== $screen->base || 'page' !== $screen->post_type) {
        return;
    }
    $post_id = isset($_GET['post']) && is_string($_GET['post']) ? absint($_GET['post']) : 0;
    if (!$post_id || !current_user_can('edit_post', $post_id)) {
        return;
    }
    $key     = kwdtc_example_faq_notice_key($post_id);
    $message = get_transient($key);
    if (is_string($message) && '' !== $message) {
        echo '<div class="notice notice-error"><p>' . esc_html($message) . '</p></div>';
        delete_transient($key);
    }
}
add_action('admin_notices', 'kwdtc_example_faq_admin_notice');

function kwdtc_example_render_faq($post_id) {
    $items = get_post_meta($post_id, '_kwdtc_example_faq_items', true);
    if (!is_array($items) || empty($items)) {
        return;
    }
    echo '<div class="page-faq-list">';
    foreach ($items as $item) {
        if (!is_array($item) || !isset($item['question'], $item['answer'])
            || !is_string($item['question']) || !is_string($item['answer'])
            || '' === trim($item['question']) || '' === trim($item['answer'])) {
            continue;
        }
        echo '<details class="page-faq-item"><summary>' . esc_html($item['question']) . '</summary>';
        echo '<p>' . nl2br(esc_html($item['answer'])) . '</p></details>';
    }
    echo '</div>';
}
