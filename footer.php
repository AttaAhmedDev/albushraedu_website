<?php

try {
    $stmt = $pdo->prepare("SELECT setting_key, setting_value FROM site_settings WHERE setting_key IN ('footer_instagram', 'footer_email', 'footer_phone')");
    $stmt->execute();
    $settings = [];
    while ($row = $stmt->fetch()) {
        $settings[$row['setting_key']] = $row['setting_value'];
    }

    // قيم افتراضية في حال كانت فارغة
    $instagram = $settings['footer_instagram'] ?? 'https://instagram.com/albushra.kids';
    $email     = $settings['footer_email'] ?? 'Albushra.ayesh@gmail.com';
    $phone     = $settings['footer_phone'] ?? '+201002345678';

    // استخراج اسم المستخدم من رابط انستقرام إذا كان رابط كامل
    $insta_handle = '@albushra.kids';
    if (!empty($instagram)) {
        $parsed = parse_url($instagram);
        if (isset($parsed['path'])) {
            $insta_handle = '@' . trim($parsed['path'], '/');
        } else {
            $insta_handle = $instagram; // في حال أدخل admin اسم المستخدم مباشرة
        }
    }
} catch (PDOException $e) {
    // في حال فشل الاتصال، استخدم قيم افتراضية
    $instagram = 'https://instagram.com/albushra.kids';
    $email = 'Albushra.ayesh@gmail.com';
    $phone = '+201002345678';
    $insta_handle = '@albushra.kids';
}
?>

<footer class="footer">
    <div class="footer-container">
        <div class="footer-logo">
            <span class="star">🌟</span>
            <div>
                <h4>Albushra's World</h4>
                <p>❤️ kindergarten adventure</p>
            </div>
        </div>
        <div class="footer-social">
            <a href="<?= htmlspecialchars($instagram) ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                <i class="fab fa-instagram"></i>
                <span><?= htmlspecialchars($insta_handle) ?></span>
            </a>
            <a href="mailto:<?= htmlspecialchars($email) ?>">
                <i class="fas fa-envelope"></i>
                <span><?= htmlspecialchars($email) ?></span>
            </a>
            <a href="tel:<?= htmlspecialchars(preg_replace('/[^0-9+]/', '', $phone)) ?>">
                <i class="fas fa-phone-alt"></i>
                <span><?= htmlspecialchars($phone) ?></span>
            </a>
        </div>
    </div>
    <div class="footer-copyright">
        <p>© <?= date('Y') ?> Ms. Albushra's World — where little hands create big dreams ✨</p>
    </div>
</footer>