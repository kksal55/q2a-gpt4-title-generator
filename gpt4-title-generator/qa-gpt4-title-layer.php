<?php
class qa_html_theme_layer extends qa_html_theme_base {
    function body_suffix() {
        parent::body_suffix();

        if (qa_request() == 'ask') {
            $qa_root = qa_path_html('');
            $script = <<<EOT
<!-- Font Awesome CSS (İkonlar için) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

<!-- Modal ve Buton Stilleri -->
<style>
/* Modal Temel Yapısı */
.custom-modal {
    display: none; /* Başlangıçta gizli */
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: hidden;
    background-color: rgba(0, 0, 0, 0.5); /* Arkadaki koyu fon */
    justify-content: center;
    align-items: center;
}

/* Modal İçeriği Konumlandırma */
.custom-modal-dialog {
    position: relative;
    width: 95%;
    max-width: 600px;
    margin: auto;
}

/* Modal İçeriği */
.custom-modal-content {
    background-color: #fff;
    border-radius: 10px;
    padding: 0;
    box-shadow: 0 5px 15px rgba(0,0,0,0.3); /* Gölge efekti */
    overflow: hidden;
}

/* Modal Başlık */
.custom-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px;
    background-color: #4e73df;
    color: #fff;
}

/* Modal Başlık Metni */
.custom-modal-title {
    margin: 0;
    font-size: 1.0rem;
    font-weight: bold;
}

/* Kapatma Butonu */
.custom-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #fff;
}

/* Modal Gövde */
.custom-modal-body {
    padding: 20px;
    font-size: 1.1rem;
    color: #5a5c69;
    text-align: center;
}

/* Modal Alt Bilgi */
.custom-modal-footer {
    display: flex;
    justify-content: space-between;
    padding: 15px;
    background-color: #f1f1f1;
}

/* Buton Stilleri */
.custom-btn {
    padding: 8px 10px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    display: flex;
    align-items: center;
    transition: background-color 0.3s, border-color 0.3s;
}

/* Birincil Buton */
.custom-btn-primary {
    background-color: #4e73df;
    color: #fff;
}

.custom-btn-primary:hover {
    background-color: #2e59d9;
}

/* İkincil (Outline) Buton */
.custom-btn-outline-secondary {
    background-color: transparent;
    color: #6c757d;
    border: 1px solid #6c757d;
}

.custom-btn-outline-secondary:hover {
    background-color: #e2e6ea;
}

/* Başarı (Success) Buton */
.custom-btn-success {
    background-color: #1cc88a;
    color: #fff;
}

.custom-btn-success:hover {
    background-color: #17a673;
}

/* Form Kontrolü */
.custom-form-control {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 1rem;
}

/* Modal Gösterme */
.custom-modal.show {
    display: flex;
}
</style>

<script>
// Modal HTML yapısı
var modalHtml = `
<!-- GPT Modal -->
<div id="gptModal" class="custom-modal">
    <div class="custom-modal-dialog">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h5 class="custom-modal-title">
                    <i class="fas fa-magic"></i> &nbsp;Yapay Zeka Tarafından Oluşturulan Yeni Başlık
                </h5>
                <button type="button" class="custom-close" id="gptModalClose">&times;</button>
            </div>
            <div class="custom-modal-body">
                <p id="gptModalTitle" class="custom-lead text-center font-weight-bold text-dark"></p>
            </div>
            <div class="custom-modal-footer">
                <button id="gptEditButton" type="button" class="custom-btn custom-btn-outline-secondary">
                    <i class="fas fa-edit"></i> &nbsp;Düzenle
                </button>
                <button id="gptAcceptButton" type="button" class="custom-btn custom-btn-primary">
                    <i class="fas fa-check"></i> &nbsp;Kabul Et ve Devam Et
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Başlığı Düzenle Modal -->
<div id="gptEditModal" class="custom-modal">
    <div class="custom-modal-dialog">
        <div class="custom-modal-content">
            <div class="custom-modal-header" style="background-color: #1cc88a;">
                <h5 class="custom-modal-title">
                    <i class="fas fa-edit"></i> &nbsp;Başlığı Düzenleyin
                </h5>
                <button type="button" class="custom-close" id="gptEditModalClose">&times;</button>
            </div>
            <div class="custom-modal-body">
                <label for="gptEditInput" class="font-weight-bold">Yeni Başlık</label>
                <input type="text" id="gptEditInput" class="custom-form-control" />
            </div>
            <div class="custom-modal-footer">
                <button id="gptSaveButton" type="button" class="custom-btn custom-btn-success">
                    <i class="fas fa-save"></i>&nbsp;Kaydet ve Devam Et
                </button>
                <button type="button" class="custom-btn custom-btn-outline-secondary" id="gptCancelEditButton">&nbsp;İptal</button>
            </div>
        </div>
    </div>
</div>
`;

// Modal'ları sayfaya ekle
document.body.insertAdjacentHTML('beforeend', modalHtml);

// Modal açma ve kapama fonksiyonları
function showModal(modalId) {
    var modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('show');
    }
}

function closeModal(modalId) {
    var modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('show');
    }
}

// Kapatma butonlarına olay ekle
document.addEventListener('click', function(event) {
    if (event.target.matches('#gptModalClose')) {
        closeModal('gptModal');
    }
    if (event.target.matches('#gptEditModalClose') || event.target.matches('#gptCancelEditButton')) {
        closeModal('gptEditModal');
    }
});

// Form submit olayını ele al
function initGptTitleGenerator() {
    console.log("JavaScript kodu yüklendi.");
    var form = document.querySelector('form[name="ask"]');
    if (form) {
        console.log("Form bulundu.");
        form.addEventListener("submit", function(event) {
            console.log("Form submit olayı tetiklendi.");

            // Eğer kullanıcı başlığı zaten onayladıysa veya GPT API başarısız olduysa, yeniden işlem yapma
            if (document.querySelector("input[name='gpt_title_confirmed']") || document.querySelector("input[name='gpt_api_failed']")) {
                console.log("gpt_title_confirmed veya gpt_api_failed input bulundu, işlem devam ediyor.");
                return;
            }

            // Formu durduruyoruz
            event.preventDefault();
            console.log("Form gönderimi durduruldu.");

            // Başlık ve içeriği alıyoruz
            var titleInput = document.querySelector("input[name='title']");
            var contentInput = document.querySelector("textarea[name='content']");

            var title = titleInput ? titleInput.value : '';
            var content = contentInput ? contentInput.value : '';

            console.log("Başlık:", title);
            console.log("İçerik:", content);

            // AJAX isteği
            var xhr = new XMLHttpRequest();
            var ajaxUrl = "{$qa_root}index.php/gpt-title-generator";
            console.log("AJAX isteği URL'si:", ajaxUrl);

            xhr.open("POST", ajaxUrl, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=UTF-8");

            // Zaman aşımı ayarı (5000 milisaniye = 5 saniye)
            xhr.timeout = 5000;

            xhr.onreadystatechange = function() {
                console.log("AJAX readyState:", xhr.readyState, "status:", xhr.status);
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        console.log("AJAX isteği başarılı.");
                        try {
                            var response = JSON.parse(xhr.responseText);
                            var newTitle = response.new_title;

                            if (newTitle) {
                                // Modal ile kullanıcıya gösteriyoruz
                                showModal('gptModal');
                                document.getElementById('gptModalTitle').textContent = newTitle;

                                // Kabul Et ve Devam Et butonuna tıklama
                                var acceptButton = document.getElementById('gptAcceptButton');
                                acceptButton.addEventListener('click', function() {
                                    closeModal('gptModal');
                                    titleInput.value = newTitle;

                                    // `gpt_title` değerini gizli bir input olarak ekliyoruz
                                    var hiddenTitleInput = document.createElement("input");
                                    hiddenTitleInput.type = "hidden";
                                    hiddenTitleInput.name = "gpt_title";
                                    hiddenTitleInput.value = newTitle;
                                    form.appendChild(hiddenTitleInput);

                                    // `gpt_title_confirmed` değerini gizli bir input olarak ekliyoruz
                                    var hiddenConfirmInput = document.createElement("input");
                                    hiddenConfirmInput.type = "hidden";
                                    hiddenConfirmInput.name = "gpt_title_confirmed";
                                    hiddenConfirmInput.value = "1";
                                    form.appendChild(hiddenConfirmInput);

                                    console.log("Form yeniden gönderiliyor.");
                                    form.submit();
                                }, { once: true });

                                // Başlığı Düzenle butonuna tıklama
                                var editButton = document.getElementById('gptEditButton');
                                editButton.addEventListener('click', function() {
                                    closeModal('gptModal');
                                    showEditModal(newTitle);
                                }, { once: true });

                            } else {
                                console.log("Yeni başlık oluşturulamadı.");
                                // GPT API başarısız oldu, formu normal şekilde gönderiyoruz
                                var hiddenInput = document.createElement("input");
                                hiddenInput.type = "hidden";
                                hiddenInput.name = "gpt_api_failed";
                                hiddenInput.value = "1";
                                form.appendChild(hiddenInput);
                                form.submit();
                            }
                        } catch (e) {
                            console.log("JSON parse hatası:", e);
                            alert("Başlık oluşturulurken bir hata oluştu. Lütfen tekrar deneyin.");
                            // Hata durumunda formu normal şekilde gönderiyoruz
                            var hiddenInput = document.createElement("input");
                            hiddenInput.type = "hidden";
                            hiddenInput.name = "gpt_api_failed";
                            hiddenInput.value = "1";
                            form.appendChild(hiddenInput);
                            form.submit();
                        }
                    } else {
                        console.log("AJAX isteği başarısız. Status:", xhr.status);
                        // GPT API başarısız oldu, formu normal şekilde gönderiyoruz
                        var hiddenInput = document.createElement("input");
                        hiddenInput.type = "hidden";
                        hiddenInput.name = "gpt_api_failed";
                        hiddenInput.value = "1";
                        form.appendChild(hiddenInput);
                        form.submit();
                    }
                }
            };

            // Zaman aşımı olduğunda çalışır
            xhr.ontimeout = function() {
                console.log("AJAX isteği zaman aşımına uğradı.");
                // GPT API başarısız oldu, formu normal şekilde gönderiyoruz
                var hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = "gpt_api_failed";
                hiddenInput.value = "1";
                form.appendChild(hiddenInput);
                form.submit();
            };

            // Hata oluştuğunda çalışır
            xhr.onerror = function() {
                console.log("AJAX isteği sırasında bir hata oluştu.");
                // GPT API başarısız oldu, formu normal şekilde gönderiyoruz
                var hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = "gpt_api_failed";
                hiddenInput.value = "1";
                form.appendChild(hiddenInput);
                form.submit();
            };

            var postData = "title=" + encodeURIComponent(title) + "&content=" + encodeURIComponent(content);
            console.log("AJAX isteği gönderiliyor. Data:", postData);
            xhr.send(postData);
        });
    } else {
        console.log("Form bulunamadı.");
    }
}

// Edit Modal Fonksiyonu
function showEditModal(currentTitle) {
    var editModal = document.getElementById('gptEditModal');
    var editInput = document.getElementById('gptEditInput');
    var saveButton = document.getElementById('gptSaveButton');

    if (editInput) {
        editInput.value = currentTitle;
    }

    showModal('gptEditModal');

    // Kaydet ve Devam Et butonuna tıklama
    saveButton.addEventListener('click', function() {
        var userTitle = editInput.value;
        closeModal('gptEditModal');

        var form = document.querySelector('form[name="ask"]');
        if (form) {
            document.querySelector("input[name='title']").value = userTitle;

            // `gpt_title` değerini gizli bir input olarak ekliyoruz
            var hiddenTitleInput = document.createElement("input");
            hiddenTitleInput.type = "hidden";
            hiddenTitleInput.name = "gpt_title";
            hiddenTitleInput.value = userTitle;
            form.appendChild(hiddenTitleInput);

            // `gpt_title_confirmed` değerini gizli bir input olarak ekliyoruz
            var hiddenConfirmInput = document.createElement("input");
            hiddenConfirmInput.type = "hidden";
            hiddenConfirmInput.name = "gpt_title_confirmed";
            hiddenConfirmInput.value = "1";
            form.appendChild(hiddenConfirmInput);

            console.log("Form yeniden gönderiliyor.");
            form.submit();
        }
    }, { once: true });
}

// Font Awesome yüklenmesini bekleyip, init fonksiyonunu başlat
function loadScripts() {
    // Font Awesome'ın yüklendiğinden emin ol
    var faCheck = document.createElement('i');
    faCheck.className = 'fas fa-check';
    document.body.appendChild(faCheck);
    if (faCheck.offsetWidth === 0) {
        console.error("Font Awesome yüklenemedi.");
    }
    document.body.removeChild(faCheck);

    // Modal ve işlevleri başlat
    initGptTitleGenerator();
}

// Sayfa yüklendiğinde script'i başlat
window.addEventListener('load', loadScripts);
</script>
EOT;
            $this->output_raw($script);
        }
    }
}
?>
