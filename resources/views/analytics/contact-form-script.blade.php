{{-- Script para capturar eventos de formulários de contato --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Verificar se há dados de evento na sessão (retornados pelo backend)
    @if(session('event_data'))
        const eventData = @json(session('event_data'));
        console.log('Dados de evento capturados da sessão:', eventData);
        
        // Enviar evento customizado
        if (typeof sendCustomEvent === 'function') {
            sendCustomEvent(eventData);
        } else {
            console.warn('Função sendCustomEvent não encontrada');
        }
        
        // Limpar dados da sessão após uso
        @php
            session()->forget('event_data');
        @endphp
    @endif
    
    // Capturar eventos de formulários de contato em tempo real
    const contactForms = document.querySelectorAll('form[action*="contato"], form[action*="contact"]');
    
    contactForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const formData = new FormData(form);
            const contactData = {
                name: formData.get('nome') || formData.get('name') || '',
                surname: '',
                email: formData.get('email') || '',
                phone: formData.get('telefone') || formData.get('phone') || '',
                country: 'Brasil',
                cep: '',
                region: '',
                city: '',
                street: '',
                service: 'contact',
                source: 'footer_form'
            };
            
            // Separar nome e sobrenome
            const nameParts = contactData.name.split(' ');
            contactData.name = nameParts[0] || '';
            contactData.surname = nameParts.slice(1).join(' ') || '';
            
            console.log('Dados do formulário de contato capturados:', contactData);
            
            // Enviar evento após um pequeno delay para garantir que o formulário foi processado
            setTimeout(() => {
                if (typeof sendCustomEvent === 'function') {
                    sendCustomEvent(contactData);
                }
            }, 1000);
        });
    });
    
    // Capturar eventos de cliques em botões de contato
    const contactButtons = document.querySelectorAll('a[href*="tel:"], a[href*="mailto:"], button[onclick*="contato"]');
    
    contactButtons.forEach(button => {
        button.addEventListener('click', function() {
            const contactData = {
                name: '',
                surname: '',
                email: '',
                phone: '',
                country: 'Brasil',
                cep: '',
                region: '',
                city: '',
                street: '',
                service: 'contact',
                source: 'contact_button'
            };
            
            // Determinar tipo de contato baseado no elemento
            if (this.href && this.href.includes('tel:')) {
                contactData.phone = this.href.replace('tel:', '');
                contactData.service = 'phone';
            } else if (this.href && this.href.includes('mailto:')) {
                contactData.email = this.href.replace('mailto:', '');
                contactData.service = 'email';
            }
            
            console.log('Evento de contato capturado:', contactData);
            
            if (typeof sendCustomEvent === 'function') {
                sendCustomEvent(contactData);
            }
        });
    });
});
</script>
