<!-- Floating Action Buttons -->
@if(\App\Helpers\FloatingButtonHelper::isEnabled())
<div class="floating-buttons">
    @php
        $whatsapp = \App\Helpers\FloatingButtonHelper::getWhatsapp();
        $telefone = \App\Helpers\FloatingButtonHelper::getTelefone();
    @endphp
    
    @if($whatsapp)
    <!-- WhatsApp Button -->
    <button type="button"  class="floating-btn whatsapp-btn"  title="Fale conosco no WhatsApp" onclick="openWhatsAppModal()">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
        </svg>
    </button>
    @endif
    
    @if($telefone)
    <!-- Phone Button -->
    <a href="tel:+55{{ preg_replace('/[^0-9]/', '', $telefone) }}"  class="floating-btn phone-btn"  title="Ligue para nós">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
        </svg>
    </a>
    @endif
</div>
@endif

<style>
.floating-buttons {
    position: fixed;
    bottom: 90px;
    right: 25px;
    z-index: 1000;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.floating-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    color: white;
    font-size: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
    /* animation: pulse 2s infinite; */
}

.floating-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.25);
    color: white;
    text-decoration: none;
}

.whatsapp-btn {
    background: linear-gradient(135deg, #25D366, #128C7E);
}

.whatsapp-btn:hover {
    background: linear-gradient(135deg, #128C7E, #25D366);
}

.phone-btn {
    background: linear-gradient(135deg, #007bff, #0056b3);
}

.phone-btn:hover {
    background: linear-gradient(135deg, #0056b3, #007bff);
}

@keyframes pulse {
    0% {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    50% {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15), 0 0 0 10px rgba(37, 211, 102, 0.1);
    }
    100% {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
}

/* Modal Styles */
.floating-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
    background-color: rgba(0, 0, 0, 0.5);
}

.floating-modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}


.floating-modal-dialog {
    position: relative;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    z-index: 10001;
    background: white;
    border-radius: 8px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

.floating-modal-content {
    overflow: hidden;
}

.floating-modal-header {
    background: linear-gradient(135deg, #25D366, #128C7E);
    color: white;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.floating-modal-title {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.floating-modal-close {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.floating-modal-close:hover {
    background-color: rgba(255, 255, 255, 0.2);
}

.floating-modal-body {
    padding: 20px;
}

.floating-alert {
    background-color: #e3f2fd;
    border: 1px solid #bbdefb;
    color: #1976d2;
    padding: 12px;
    border-radius: 4px;
    margin-bottom: 20px;
    /* display: flex; */
    /* align-items: center; */
    font-size: 14px;
}

.floating-form-group {
    margin-bottom: 20px;
}

.floating-form-label {
    display: block;
    margin-bottom: 6px;
    font-weight: 500;
    color: #333;
    font-size: 14px;
    display: flex;
    align-items: center;
}

.floating-form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.2s, box-shadow 0.2s;
    box-sizing: border-box;
}

.floating-form-control:focus {
    outline: none;
    border-color: #25D366;
    box-shadow: 0 0 0 2px rgba(37, 211, 102, 0.2);
}

.floating-form-control.is-invalid {
    border-color: #dc3545;
}

.floating-form-control.is-valid {
    border-color: #28a745;
}

.floating-invalid-feedback {
    color: #dc3545;
    font-size: 12px;
    margin-top: 4px;
    display: none;
}

.floating-form-control.is-invalid + .floating-invalid-feedback {
    display: block;
}

.floating-modal-footer {
    padding: 20px;
    background-color: #f8f9fa;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
}

.floating-btn-secondary {
    background-color: #6c757d;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    transition: background-color 0.2s;
}

.floating-btn-secondary:hover {
    background-color: #5a6268;
}

.floating-btn-success {
    background-color: #25D366;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    transition: background-color 0.2s;
}

.floating-btn-success:hover {
    background-color: #128C7E;
}

.floating-btn-success:disabled {
    background-color: #6c757d;
    cursor: not-allowed;
}

/* Responsive */
@media (max-width: 768px) {
    .floating-buttons {
        bottom: 15px;
        right: 15px;
    }
    
    .floating-btn {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .floating-modal-dialog {
        width: 95%;
        margin: 10px;
    }
    
    .floating-modal-header,
    .floating-modal-body,
    .floating-modal-footer {
        padding: 15px;
    }
    
    .floating-modal-footer {
        flex-direction: column;
    }
    
    .floating-btn-secondary,
    .floating-btn-success {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .floating-buttons {
        bottom: 10px;
        right: 10px;
    }
    
    .floating-btn {
        width: 45px;
        height: 45px;
        font-size: 18px;
    }
}
</style>

<!-- WhatsApp Modal -->
<div id="whatsappModal" class="floating-modal" onclick="closeWhatsAppModal()">
    <div class="floating-modal-dialog" onclick="event.stopPropagation()">
        <div class="floating-modal-content">
            <div class="floating-modal-header">
                <h5 class="floating-modal-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 8px;">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                    </svg>
                    Fale conosco no WhatsApp
                </h5>
            </div>
            <div class="floating-modal-body">
                <div class="floating-alert">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 8px;">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <strong>Quase la!</strong><br>
                    Preencha seus dados para continuarmos no WhatsApp.
                </div>
                
                <form id="whatsappForm">
                    @csrf
                    <input type="hidden" name="from_whatsapp" value="1">
                    <div class="floating-form-group">
                        <label for="leadNome" class="floating-form-label">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 4px;">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                            </svg>
                            Nome *
                        </label>
                        <input type="text" 
                               class="floating-form-control" 
                               id="leadNome" 
                               name="nome" 
                               required
                               placeholder="Digite seu nome completo">
                        <div class="floating-invalid-feedback"></div>
                    </div>
                    
                    <div class="floating-form-group">
                        <label for="leadEmail" class="floating-form-label">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 4px;">
                                <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                            </svg>
                            E-mail *
                        </label>
                        <input type="email" 
                               class="floating-form-control" 
                               id="leadEmail" 
                               name="email" 
                               required
                               placeholder="Digite seu e-mail">
                        <div class="floating-invalid-feedback"></div>
                    </div>
                    
                    <div class="floating-form-group">
                        <label for="leadTelefone" class="floating-form-label">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 4px;">
                                <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                            </svg>
                            Telefone/WhatsApp *
                        </label>
                        <input type="tel" 
                               class="floating-form-control" 
                               id="leadTelefone" 
                               name="tel" 
                               required
                               placeholder="(11) 99999-9999">
                        <div class="floating-invalid-feedback"></div>
                    </div>
                </form>
            </div>
            <div class="floating-modal-footer">
                <button type="button" class="floating-btn-success" id="enviarWhatsapp">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 4px;">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                    </svg>
                    Continuar no WhatsApp
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Funções globais para controle do modal
function openWhatsAppModal() {
    const modal = document.getElementById('whatsappModal');
    if (modal) {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden'; // Previne scroll do body
    }
}

function closeWhatsAppModal() {
    const modal = document.getElementById('whatsappModal');
    if (modal) {
        modal.classList.remove('show');
        document.body.style.overflow = ''; // Restaura scroll do body
    }
}

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeWhatsAppModal();
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const whatsappForm = document.getElementById('whatsappForm');
    const enviarBtn = document.getElementById('enviarWhatsapp');
    
    if (!whatsappForm || !enviarBtn) return;
    
    // Máscara para telefone
    const telefoneInput = document.getElementById('leadTelefone');
    if (telefoneInput) {
        telefoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length >= 11) {
                value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
            } else if (value.length >= 7) {
                value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
            } else if (value.length >= 3) {
                value = value.replace(/(\d{2})(\d{0,5})/, '($1) $2');
            }
            e.target.value = value;
        });
    }
    
    // Enviar formulário
    enviarBtn.addEventListener('click', function() {
        console.log('=== FLOATING BUTTON CLICKED ===');
        const formData = new FormData(whatsappForm);
        
        // Debug: mostrar dados que serão enviados
        console.log('Dados do formulário:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }
        
        // Validação básica
        let isValid = true;
        const inputs = whatsappForm.querySelectorAll('input[required]');
        
        inputs.forEach(input => {
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                const feedback = input.nextElementSibling;
                if (feedback) {
                    feedback.textContent = 'Este campo e obrigatorio';
                }
                isValid = false;
            } else {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
            }
        });
        
        // Validação de email
        const emailInput = document.getElementById('leadEmail');
        if (emailInput && emailInput.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailInput.value)) {
                emailInput.classList.add('is-invalid');
                const feedback = emailInput.nextElementSibling;
                if (feedback) {
                    feedback.textContent = 'Digite um e-mail valido';
                }
                isValid = false;
            }
        }
        
        if (!isValid) return;
        
        // Desabilitar botão e mostrar loading
        enviarBtn.disabled = true;
        enviarBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 4px; animation: spin 1s linear infinite;"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46C19.54 15.03 20 13.57 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74C4.46 8.97 4 10.43 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg>Enviando...';
        
        // Obter CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value || '';
        
        // Adicionar CSRF token ao FormData
        formData.append('_token', csrfToken);
        
        // Enviar dados para o servidor
        fetch('{{ route("leads.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Erro ao fazer parse do JSON:', text);
                    throw new Error('Resposta nao e JSON valido: ' + text.substring(0, 100));
                }
            });
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (data.success) {
                // Fechar modal
                closeWhatsAppModal();
                
                // Redirecionar para WhatsApp
                // Limpar o número removendo todos os caracteres não numéricos (incluindo espaços)
                @php
                    $whatsappRaw = \App\Helpers\FloatingButtonHelper::getWhatsapp();
                    // Remover todos os caracteres não numéricos, incluindo espaços
                    $whatsappClean = preg_replace('/[^0-9]/', '', $whatsappRaw);
                    // Remover espaços extras que podem ter sido inseridos
                    $whatsappClean = str_replace(' ', '', $whatsappClean);
                    // Garantir que não há espaços ou caracteres invisíveis
                    $whatsappClean = trim($whatsappClean);
                @endphp
                let whatsappNumber = '{{ $whatsappClean }}';
                // Remover espaços extras que podem ter sido inseridos no JavaScript também
                whatsappNumber = whatsappNumber.replace(/\s+/g, '').trim();
                // Garantir que comece com 55 (código do Brasil) se não começar
                if (whatsappNumber && !whatsappNumber.startsWith('55')) {
                    // Se começar com 0, remover o 0
                    if (whatsappNumber.startsWith('0')) {
                        whatsappNumber = whatsappNumber.substring(1);
                    }
                    // Adicionar código do país se necessário
                    if (whatsappNumber.length >= 10) {
                        whatsappNumber = '55' + whatsappNumber;
                    }
                }
                const nome = formData.get('nome');
                const telefone = formData.get('tel');
                const email = formData.get('email');
                
                const message = `Ola! Meu nome e ${nome}, meu telefone e ${telefone} e meu e-mail e ${email}. Gostaria de mais informacoes sobre seus servicos.`;
                const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;
                
                // Enviar evento customizado para analytics
                if (data.event_data) {
                    sendFloatingButtonEvent(data.event_data);
                } else {
                    // Fallback: criar dados básicos do evento
                    const eventData = {
                        name: nome,
                        surname: '',
                        email: email,
                        phone: telefone,
                        country: 'Brasil',
                        cep: '',
                        region: '',
                        city: '',
                        street: '',
                        service: 'whatsapp',
                        source: 'floating_button'
                    };
                    sendFloatingButtonEvent(eventData);
                }
                
                // Abrir WhatsApp em nova guia
                window.open(whatsappUrl, '_blank');
                
                // Limpar formulário
                whatsappForm.reset();
                inputs.forEach(input => {
                    input.classList.remove('is-valid', 'is-invalid');
                });
                
            } else {
                // Mostrar erro com detalhes
                let errorMessage = 'Erro ao enviar dados: ' + (data.message || 'Erro desconhecido');
                if (data.errors) {
                    errorMessage += '\n\nDetalhes dos erros:';
                    for (let field in data.errors) {
                        errorMessage += '\n• ' + field + ': ' + data.errors[field].join(', ');
                    }
                }
                alert(errorMessage);
            }
        })
        .catch(error => {
            console.error('Erro completo:', error);
            alert('Erro ao enviar dados: ' + error.message);
        })
        .finally(() => {
            // Reabilitar botão
            enviarBtn.disabled = false;
            enviarBtn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right: 4px;"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/></svg>Continuar no WhatsApp';
        });
    });
    
    // Limpar validação ao digitar
    whatsappForm.querySelectorAll('input').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid', 'is-valid');
        });
    });
});

// Função para enviar eventos customizados do botão flutuante WhatsApp
function sendFloatingButtonEvent(userData) {
    if (typeof dataLayer !== 'undefined') {
        // Enviar evento customizado específico para botão flutuante
        dataLayer.push({
            'event': 'Floating Button WhatsApp',
            'event_category': 'WhatsApp',
            'event_action': 'Formulario Preenchido',
            'event_label': 'Botao Flutuante',
            'email': userData.email,
            'telefone': userData.phone,
            'nome': userData.name,
            'sobrenome': userData.surname,
            'pais': userData.country,
            'cep': userData.cep,
            'regiao': userData.region,
            'cidade': userData.city,
            'rua': userData.street,
            'service': userData.service,
            'source': 'floating_button',
            'timestamp': new Date().toISOString()
        });
        
        console.log('Evento do botão flutuante enviado:', userData);
    } else {
        console.warn('Google Tag Manager nao encontrado');
    }
    
    if (typeof gtag !== 'undefined') {
        gtag('event', 'floating_button_whatsapp', {
            'event_category': 'WhatsApp',
            'event_action': 'Formulario Preenchido',
            'event_label': 'Botao Flutuante',
            'email': userData.email,
            'telefone': userData.phone,
            'nome': userData.name,
            'sobrenome': userData.surname,
            'pais': userData.country,
            'cep': userData.cep,
            'regiao': userData.region,
            'cidade': userData.city,
            'rua': userData.street,
            'service': userData.service,
            'source': 'floating_button'
        });
    }
}

// Função para enviar eventos customizados dos formulários da página
function sendPageFormEvent(userData) {
    if (typeof dataLayer !== 'undefined') {
        // Enviar evento customizado específico para formulários da página
        dataLayer.push({
            'event': 'Page Form Lead',
            'event_category': 'Formulario',
            'event_action': 'Formulario Preenchido',
            'event_label': 'Formulario da Pagina',
            'email': userData.email,
            'telefone': userData.phone,
            'nome': userData.name,
            'sobrenome': userData.surname,
            'pais': userData.country,
            'cep': userData.cep,
            'regiao': userData.region,
            'cidade': userData.city,
            'rua': userData.street,
            'service': userData.service,
            'source': 'page_form',
            'timestamp': new Date().toISOString()
        });
        
        console.log('Evento do formulário da página enviado:', userData);
    } else {
        console.warn('Google Tag Manager nao encontrado');
    }
    
    if (typeof gtag !== 'undefined') {
        gtag('event', 'page_form_lead', {
            'event_category': 'Formulario',
            'event_action': 'Formulario Preenchido',
            'event_label': 'Formulario da Pagina',
            'email': userData.email,
            'telefone': userData.phone,
            'nome': userData.name,
            'sobrenome': userData.surname,
            'pais': userData.country,
            'cep': userData.cep,
            'regiao': userData.region,
            'cidade': userData.city,
            'rua': userData.street,
            'service': userData.service,
            'source': 'page_form'
        });
    }
}

// Função genérica para enviar eventos customizados (mantida para compatibilidade)
function sendCustomEvent(userData) {
    // Determinar o tipo de evento baseado na fonte
    if (userData.source === 'floating_button') {
        sendFloatingButtonEvent(userData);
    } else if (userData.source === 'page_form') {
        sendPageFormEvent(userData);
    } else {
        // Fallback para evento genérico
        if (typeof dataLayer !== 'undefined') {
            dataLayer.push({
                'event': 'Lead Footer Formulario',
                'email': userData.email,
                'telefone': userData.phone,
                'nome': userData.name,
                'sobrenome': userData.surname,
                'pais': userData.country,
                'cep': userData.cep,
                'regiao': userData.region,
                'cidade': userData.city,
                'rua': userData.street,
                'service': userData.service,
                'timestamp': new Date().toISOString()
            });
            
            console.log('Evento customizado genérico enviado:', userData);
        } else {
            console.warn('Google Tag Manager nao encontrado');
        }
        
        if (typeof gtag !== 'undefined') {
            gtag('event', 'user_provided_data', {
                'email': userData.email,
                'telefone': userData.phone,
                'nome': userData.name,
                'sobrenome': userData.surname,
                'pais': userData.country,
                'cep': userData.cep,
                'regiao': userData.region,
                'cidade': userData.city,
                'rua': userData.street,
                'service': userData.service
            });
        }
    }
}

// Animação de loading para o spinner
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>

