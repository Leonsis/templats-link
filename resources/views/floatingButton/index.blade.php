<!-- Floating Action Buttons -->
@if(\App\Helpers\FloatingButtonHelper::isEnabled())
<div class="floating-buttons">
    @php
        $whatsapp = \App\Helpers\FloatingButtonHelper::getWhatsapp();
        $telefone = \App\Helpers\FloatingButtonHelper::getTelefone();
    @endphp
    
    @if($whatsapp)
    <!-- WhatsApp Button -->
    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}?text=Olá! Gostaria de mais informações sobre seus serviços." 
       target="_blank" 
       class="floating-btn whatsapp-btn" 
       title="Fale conosco no WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
    @endif
    
    @if($telefone)
    <!-- Phone Button -->
    <a href="tel:{{ $telefone }}" 
       class="floating-btn phone-btn" 
       title="Ligue para nós">
        <i class="fas fa-phone"></i>
    </a>
    @endif
</div>
@endif

<style>
.floating-buttons {
    position: fixed;
    bottom: 20px;
    right: 20px;
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
    animation: pulse 2s infinite;
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
