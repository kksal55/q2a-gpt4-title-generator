<?php
/*
    Plugin Name: GPT-4 Başlık Oluşturucu
    Plugin URI:
    Plugin Description: Soru başlıklarını GPT-4 ile otomatik olarak günceller ve kullanıcı onayı alır.
    Plugin Version: 1.0
    Plugin Date: 2023-09-20
    Plugin Author:
    Plugin Author URI:
    Plugin License: MIT
    Plugin Minimum Question2Answer Version: 1.8
    Plugin Update Check URI:
*/

if (!defined('QA_VERSION')) { // Doğrudan erişimi engelle
    header('Location: ../../');
    exit;
}

// Filter modülünü kaydediyoruz
qa_register_plugin_module(
    'filter', // Modül türü
    'qa-gpt4-title-generator.php', // PHP dosyası
    'qa_gpt4_title_generator_filter', // Sınıf adı
    'GPT-4 Başlık Oluşturucu' // Modül adı
);

// Page modülünü kaydediyoruz
qa_register_plugin_module(
    'page', // Modül türü
    'qa-gpt4-title-generator.php', // PHP dosyası
    'qa_gpt4_title_generator_page', // Sınıf adı
    'GPT-4 Başlık Oluşturucu Sayfası' // Modül adı
);

// Layer modülünü kaydediyoruz
qa_register_plugin_layer(
    'qa-gpt4-title-layer.php', // PHP dosyası
    'GPT-4 Başlık Oluşturucu Katmanı' // Modül adı
);


