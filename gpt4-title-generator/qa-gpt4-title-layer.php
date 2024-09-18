<?php
class qa_html_theme_layer extends qa_html_theme_base {
    function body_suffix() {
        parent::body_suffix();

        if (qa_request() == 'ask') {
            $qa_root = qa_path_html('');
            $script = <<<EOT
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

<script>
// jQuery ve Bootstrap JS dosyalarını ekliyoruz
var scriptJquery = document.createElement('script');
scriptJquery.src = 'https://code.jquery.com/jquery-3.5.1.min.js';
document.head.appendChild(scriptJquery);

var scriptBootstrap = document.createElement('script');
scriptBootstrap.src = 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js';
document.head.appendChild(scriptBootstrap);

scriptBootstrap.onload = function() {
    // Bootstrap JS yüklendikten sonra kodumuzu çalıştırıyoruz
    console.log("Bootstrap JS yüklendi.");
    initGptTitleGenerator();
};

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

            // AJAX ile sunucuya istekte bulunuyoruz
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
                        var response = JSON.parse(xhr.responseText);
                        var newTitle = response.new_title;

                        if (newTitle) {
                            // Modal ile kullanıcıya gösteriyoruz
                            showGptModal(newTitle);
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

    // Modal fonksiyonları
    function showGptModal(newTitle) {
        var modal = $('#gptModal');
        var modalTitle = $('#gptModalTitle');
        var acceptButton = $('#gptAcceptButton');
        var editButton = $('#gptEditButton');

        modalTitle.text(newTitle);
        modal.modal('show');

        // Kabul Et ve Devam Et butonu
        acceptButton.off('click').on('click', function() {
            modal.modal('hide');
            document.querySelector("input[name='title']").value = newTitle;

            // `gpt_title` değerini gizli bir input olarak ekliyoruz
            var hiddenTitleInput = document.createElement("input");
            hiddenTitleInput.type = "hidden";
            hiddenTitleInput.name = "gpt_title";
            hiddenTitleInput.value = newTitle;
            form.appendChild(hiddenTitleInput);

            // Formu yeniden gönderiyoruz
            var hiddenInput = document.createElement("input");
            hiddenInput.type = "hidden";
            hiddenInput.name = "gpt_title_confirmed";
            hiddenInput.value = "1";
            form.appendChild(hiddenInput);
            console.log("Form yeniden gönderiliyor.");
            form.submit();
        });

        // Başlığı Düzenle butonu
        editButton.off('click').on('click', function() {
            modal.modal('hide');
            showEditModal(newTitle);
        });
    }

    function showEditModal(currentTitle) {
        var editModal = $('#gptEditModal');
        var editInput = $('#gptEditInput');
        var saveButton = $('#gptSaveButton');

        editInput.val(currentTitle);
        editModal.modal('show');

        // Kaydet ve Devam Et butonu
        saveButton.off('click').on('click', function() {
            var userTitle = editInput.val();
            editModal.modal('hide');

            document.querySelector("input[name='title']").value = userTitle;

            // `gpt_title` değerini gizli bir input olarak ekliyoruz
            var hiddenTitleInput = document.createElement("input");
            hiddenTitleInput.type = "hidden";
            hiddenTitleInput.name = "gpt_title";
            hiddenTitleInput.value = userTitle;
            form.appendChild(hiddenTitleInput);

            // Formu yeniden gönderiyoruz
            var hiddenInput = document.createElement("input");
            hiddenInput.type = "hidden";
            hiddenInput.name = "gpt_title_confirmed";
            hiddenInput.value = "1";
            form.appendChild(hiddenInput);
            console.log("Form yeniden gönderiliyor.");
            form.submit();
        });
    }

    // Modal HTML yapısı
    var modalHtml = `
<!-- GPT Modal -->
<div id="gptModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content border-0">
            <div class="modal-header" style="background-color: #4e73df; color: #fff;">
                <h5 id="gptModalLabel" class="modal-title">
                    <i class="fas fa-magic"></i> Yapay Zeka Tarafından Oluşturulan Yeni Başlık
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Kapat">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="gptModalTitle" class="lead text-center font-weight-bold text-dark"></p>
            </div>
            <div class="modal-footer justify-content-between">
                <button id="gptEditButton" type="button" class="btn btn-outline-secondary">
                    <i class="fas fa-edit"></i> Düzenle
                </button>
                <button id="gptAcceptButton" type="button" class="btn btn-primary">
                    <i class="fas fa-check"></i> Kabul Et ve Devam Et
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Başlığı Düzenle Modal -->
<div id="gptEditModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="gptEditModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0">
            <div class="modal-header" style="background-color: #1cc88a; color: #fff;">
                <h5 id="gptEditModalLabel" class="modal-title">
                    <i class="fas fa-edit"></i> Başlığı Düzenleyin
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Kapat">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="gptEditInput" class="font-weight-bold">Yeni Başlık</label>
                    <input type="text" id="gptEditInput" class="form-control form-control-lg" />
                </div>
            </div>
            <div class="modal-footer">
                <button id="gptSaveButton" type="button" class="btn btn-success">
                    <i class="fas fa-save"></i> Kaydet ve Devam Et
                </button>
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">İptal</button>
            </div>
        </div>
    </div>
</div>
`;
    document.body.insertAdjacentHTML('beforeend', modalHtml);

}
</script>

<style>
/* Modal İçerik Özelleştirmeleri */
.modal-content {
    border-radius: 10px; /* Köşeleri yuvarlatma */
    box-shadow: 0 5px 15px rgba(0,0,0,0.3); /* Gölge efekti */
}

/* Başlık Metni */
.modal-title {
    font-size: 1.0rem;
    font-weight: bold;
}

/* Modal Body Metni */
#gptModalTitle {
    font-size: 1.2rem;
    color: #5a5c69;
}

/* Form Etiketleri */
.modal-body label {
    font-size: 1rem;
    color: #4e73df;
}

/* Buton İkonları Arası Boşluk */
.btn i {
    margin-right: 5px;
}

/* Buton Hover Efektleri */
.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}

.btn-success:hover {
    background-color: #17a673;
    border-color: #138f65;
}

.btn-outline-secondary:hover {
    background-color: #e2e6ea;
    border-color: #d3d9df;
}

/* Close Butonu Rengi */
.close {
    opacity: 0.8;
}

.close:hover {
    opacity: 1;
}
</style>

EOT;
            $this->output_raw($script);
        }
    }
}
