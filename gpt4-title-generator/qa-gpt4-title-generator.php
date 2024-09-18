<?php
if (!defined('QA_VERSION')) { // Doğrudan erişimi engelle
    header('Location: ../../');
    exit;
}

class qa_gpt4_title_generator_filter {
    function filter_question(&$question, &$errors, $oldquestion) {
        // Eğer hata yoksa ve eski soru yoksa (yeni bir soruysa) devam et
        if (empty($errors) && is_null($oldquestion)) {
            // Eğer kullanıcı başlığı henüz onaylamadıysa
            if (!isset($_POST['gpt_title_confirmed'])) {
                // Eğer GPT API başarısız olduysa, hiçbir hata ekleme ve devam et
                if (isset($_POST['gpt_api_failed'])) {
                    // Başlığı değiştirmeden devam ediyoruz
                } else {
                    // Hata ekliyoruz ki, form gönderimi durdurulsun
                    $errors['title'] = ' ';
                    $question['gpt_intercept'] = true;
                }
            } else {
                // Kullanıcı başlığı onayladı veya düzenledi
                // `gpt_title` değeri gönderildi, bunu kullanıyoruz
                if (isset($_POST['gpt_title'])) {
                    $question['title'] = $_POST['gpt_title'];
                }
                // Eğer `gpt_title` yoksa, mevcut başlığı kullanıyoruz
            }
        }
    }
    
}

class qa_gpt4_title_generator_page {
    function match_request($request) {
        return $request == 'gpt-title-generator';
    }

    function process_request($request) {
        // Başlık ve içeriği alıyoruz
        $title = qa_post_text('title');
        $content = qa_post_text('content');

        // GPT ile yeni başlığı oluşturuyoruz
        //sleep(6);
        $new_title = $this->generate_title_with_gpt4($content, $title);

        if ($new_title) {
            // JSON olarak yanıt veriyoruz
            header('Content-Type: application/json');
            echo json_encode(['new_title' => $new_title]);
        } else {
            // Hata durumunda
            header('HTTP/1.1 500 Internal Server Error');
            echo json_encode(['error' => 'Başlık oluşturulamadı.']);
        }
        exit;
    }

    function generate_title_with_gpt4($content, $title) {
        // OpenAI API anahtarınızı buradan alıyoruz
        //$api_key = 'OPENAI_API_KEY';
        $api_key = 'YOUR_OPENAI_API_KEY';

        if (empty($api_key)) {
            return null;
        }

        // OpenAI API'sine istek gönderiyoruz
        $postData = [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'Kullanıcının post içeriğine göre başlığı güncelle. Anlamlı ve aynı zamanda seo için uygun başlık oluştur. Oluşturulan başlık eğer soru ise soru işareti ekle. Başlık 14 kelimeyi geçmesin.'],
                ['role' => 'user', 'content' => $title . '-' . $content],
            ],
            'max_tokens' => 80,
            'temperature' => 0.7,
        ];

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key,
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode == 200) {
            $responseData = json_decode($response, true);
            $generated_title = $responseData['choices'][0]['message']['content'];
            return trim($generated_title);
        } else {
            // Hata durumunda null döndür
            return null;
        }
    }
}
