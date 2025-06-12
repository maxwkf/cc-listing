<?php
/*
   Block Name: Chat Form Block
   Block Description: Chat Form Block
   Internal: chat-form-block
   Post Types: post, page, custom-type
*/

    // Block options
    $whatsapp_image = get_template_directory_uri() . '/images/icons/whatsapp-image.svg';
    $phone_icon = file_get_contents( get_template_directory_uri() . '/images/icons/phone-icon-white.svg' );

    $whatsapp_icon = get_field('whatsapp_icon') ?? [];
    $is_show_whatsapp = $whatsapp_icon['show_whatsapp_icon'] ?? false;
    $qr_code = $whatsapp_icon['whatsapp_qr_code'] ?? '';
    $mobile_number = $whatsapp_icon['mobile_number'] ?? '';
    $whatsapp_link = $mobile_number ? 'https://wa.me/' . $mobile_number : '';
?>
<?php if($is_show_whatsapp): ?>
    <section class="block chat-form" >
        <?php if($whatsapp_image): ?>
            <button id="fabchat" class="chat-icon dt" type="button" aria-label="Show Whatsapp QR Code">
                <img src="<?= $whatsapp_image; ?>" alt="">
            </button>

            <a href="<?= $whatsapp_link; ?>" class="chat-icon mob">
                <img src="<?= $whatsapp_image; ?>" alt="">
            </a>
        <?php endif; ?>

        <div class="console" >
            <div class="pop-up">
                <?php if($whatsapp_icon['whatsapp_text']): ?>
                    <span class="intro"><?= $whatsapp_icon['whatsapp_text']; ?></span>
                <?php endif; ?>
                <?php if($qr_code): ?>
                    <div class="qr-wrap">
                        <a href="<?= isset($mobile_number) ? $whatsapp_link : ''; ?>">
                            <img src="<?= $qr_code['url']; ?>" alt="<?= $qr_code['title']; ?>" width="151" height="153">
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <?php if($mobile_number): ?>
                <div class="footer">
                    <?php if($phone_icon): ?>
                        <span class="icon">
                            <?= $phone_icon; ?>
                        </span>
                    <?php endif; ?>
                    <a href="tel:+<?= str_replace( ' ', '', $mobile_number ); ?>"><?= $mobile_number; ?></a>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>