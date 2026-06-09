

<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Estatísticas -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-number"><?php echo e($dados['totalUsuarios']); ?></div>
            <div class="stat-label">
                <i class="fas fa-users me-2"></i>
                Total de Usuários
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card success">
            <div class="stat-number"><?php echo e($dados['usuariosAtivos']); ?></div>
            <div class="stat-label">
                <i class="fas fa-user-check me-2"></i>
                Usuários Ativos
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card warning">
            <div class="stat-number"><?php echo e($dados['admins']); ?></div>
            <div class="stat-label">
                <i class="fas fa-user-shield me-2"></i>
                Administradores
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card info">
            <div class="stat-number"><?php echo e($dados['totalLeads']); ?></div>
            <div class="stat-label">
                <i class="fas fa-users me-2"></i>
                Total de Leads
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Ações Rápidas -->
    <div class="col-lg-6 mb-4">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Ações Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo e(route('home')); ?>" class="btn btn-primary btn-admin">
                        <i class="fas fa-home me-2"></i>
                        Voltar ao Site
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Informações do Usuário -->
    <div class="col-lg-6 mb-4">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Informações da Conta
                </h5>
            </div>
            <div class="card-body">
                <!-- <div class="row"> -->
                    <!-- <div class="col-sm-4"> -->
                        <div class="text-center mb-3">
                            <div class="row align-items-start">
                                <div class="col-3">
                                    <div class="user-avatar mx-auto mb-2" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                        <?php echo e(strtoupper(substr($dados['usuario']->nome, 0, 1))); ?>

                                    </div>
                                </div>
                                <div class="col-9 align-self-center">
                                    <h6 class="mb-0"><?php echo e($dados['usuario']->nome); ?></h6>
                                    <small class="text-muted"><?php echo e(ucfirst($dados['usuario']->tipo)); ?></small>
                                </div>                            
                            </div>
                        </div>
                    <!-- </div> -->
                    <!-- <div class="col-sm-8"> -->
                        <table class="table table-borderless table-sm account-info">
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td><?php echo e($dados['usuario']->email); ?></td>
                            </tr>
                            <tr>
                                <td><strong>Tipo:</strong></td>
                                <td>
                                    <span class="badge bg-<?php echo e($dados['isAdmin'] ? 'danger' : 'primary'); ?>">
                                        <?php echo e(ucfirst($dados['usuario']->tipo)); ?>

                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge bg-<?php echo e($dados['usuario']->ativo ? 'success' : 'danger'); ?>">
                                        <?php echo e($dados['usuario']->ativo ? 'Ativo' : 'Inativo'); ?>

                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Último Acesso:</strong></td>
                                <td><?php echo e($dados['dataAtual']); ?></td>
                            </tr>
                        </table>
                    <!-- </div> -->
                <!-- </div> -->
            </div>
        </div>
    </div>
</div>

<!-- Relatório de Leads -->
<div class="row">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-chart-line me-2"></i>
                    Relatório de Leads
                </h5>
                
                <!-- Botão Accordion de Configurações -->
                <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#configAccordion" aria-expanded="false" aria-controls="configAccordion">
                    <i class="fas fa-cog me-1"></i>
                    Configurações de Contato
                    <i class="fas fa-chevron-down ms-1" id="configChevron"></i>
                </button>
            </div>
            
            <!-- Accordion de Configurações -->
            <div class="collapse" id="configAccordion">
                <div class="config-accordion-content">
                    <div class="accordion-header">
                        <h6 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Como Configurar a Página de Contato
                        </h6>
                    </div>
                    
                    <div class="accordion-body">
                        <div class="step-guide">
                            <div class="step-item mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="step-number me-3">1</div>
                                    <div>
                                        <h6 class="mb-1">Acesse a Página de Contato</h6>
                                        <p class="mb-0 small text-muted">
                                            Vá para <code>/contato</code> no seu site para ver o formulário atual.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="step-item mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="step-number me-3">2</div>
                                    <div>
                                        <h6 class="mb-1">Modifique o Formulário</h6>
                                        <p class="mb-0 small text-muted">
                                            Altere o <code>action</code> do formulário para <code><?php echo e(route('leads.store')); ?></code> e adicione <code>method="POST"</code>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="step-item mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="step-number me-3">3</div>
                                    <div>
                                        <h6 class="mb-1">Adicione os Campos</h6>
                                        <p class="mb-0 small text-muted">
                                            Certifique-se de que os inputs tenham os atributos <code>name="nome"</code>, <code>name="email"</code> e <code>name="telefone"</code>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="step-item mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="step-number me-3">4</div>
                                    <div>
                                        <h6 class="mb-1">Adicione o Token CSRF</h6>
                                        <p class="mb-0 small text-muted">
                                            Inclua <code> @csrf </code> dentro do formulário para segurança.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="step-item mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="step-number me-3">5</div>
                                    <div>
                                        <h6 class="mb-1">Teste o Formulário</h6>
                                        <p class="mb-0 small text-muted">
                                            Preencha e envie o formulário. Os dados aparecerão aqui no relatório.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info mt-3 mb-0">
                            <small>
                                <i class="fas fa-lightbulb me-1"></i>
                                <strong>Dica:</strong> Os leads da página de contato aparecerão com origem "contato" no relatório.
                            </small>
                        </div>
                        
                        <div class="alert alert-warning mt-2 mb-0">
                            <small>
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <strong>Importante:</strong> Substitua "SEU-DOMINIO.com" pelo domínio real do seu site (ex: meusite.com.br).
                            </small>
                        </div>
                        
                        <div class="mt-3">
                            <h6 class="mb-2">
                                <i class="fas fa-code me-1"></i>
                                Exemplo de Formulário:
                            </h6>
                            <pre class="bg-light p-3 rounded small"><code>
 @if(session('success'))
    &lt;div class="alert alert-success alert-dismissible fade show" role="alert"&gt;
        &lt;i class="fas fa-check-circle me-2"&gt;&lt;/i&gt;
        {{ session('success') }}
    &lt;/div&gt;
 @endif 

 @if(session('error')) 
    &lt;div class="alert alert-danger alert-dismissible fade show" role="alert"&gt;
        &lt;i class="fas fa-exclamation-circle me-2"&gt;&lt;/i&gt;
        {{ session('error') }} 
    &lt;/div&gt;
 @endif 

&lt;form method="POST" action="{{ route('leads.store') }}"&gt;
     @csrf 
    &lt;input type="text" name="nome" placeholder="Nome" required&gt;
    &lt;input type="email" name="email" placeholder="Email" required&gt;
    &lt;input type="tel" name="tel" placeholder="Telefone" required&gt;
    &lt;input type="text" name="faturamento" placeholder="Ex: EPP, ME, LTDA, S.A" required&gt;
    &lt;button type="submit"&gt;Enviar&lt;/button&gt;
    &lt;div>
        &lt;label for="area_atuacao">Área de atuação&lt;/label>
        &lt;select id="area_atuacao" name="area_atuacao" required&gt;                                
            &lt;option value="engenharia">Engenharia&lt;/option&gt;
        &lt;/select&gt;
    &lt;/div>
&lt;/form&gt;</code></pre>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php if($dados['leadsRecentes']->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="fas fa-user me-1"></i> Nome</th>
                                    <th><i class="fas fa-envelope me-1"></i> Email</th>
                                    <th><i class="fas fa-phone me-1"></i> Telefone</th>
                                    <th><i class="fas fa-calendar me-1"></i> Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $dados['leadsRecentes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lead): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="user-avatar me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                <?php echo e(strtoupper(substr($lead->nome, 0, 1))); ?>

                                            </div>
                                            <strong><?php echo e($lead->nome); ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="mailto:<?php echo e($lead->email); ?>" class="text-decoration-none">
                                            <?php echo e($lead->email); ?>

                                        </a>
                                    </td>
                                    <td>
                                        <a href="tel:<?php echo e($lead->telefone); ?>" class="text-decoration-none">
                                            <?php echo e($lead->telefone); ?>

                                        </a>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            <?php echo e($lead->created_at->format('d/m/Y H:i')); ?>

                                        </small>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if($dados['totalLeads'] > 10): ?>
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                Mostrando os 10 leads mais recentes de <?php echo e($dados['totalLeads']); ?> total
                            </small>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-users fa-3x text-muted"></i>
                        </div>
                        <h6 class="text-muted">Nenhum lead encontrado</h6>
                        <p class="text-muted small">
                            Os leads capturados através dos botões flutuantes aparecerão aqui.
                        </p>
                    </div>
                <?php endif; ?>
                
                <!-- Estatísticas dos Leads -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-line fa-2x text-primary mb-2"></i>
                                <h5 class="mb-1"><?php echo e($dados['totalLeads']); ?></h5>
                                <small class="text-muted">Total de Leads</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos para o accordion de configuração */
.config-accordion-content {
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

.accordion-header {
    background: #e9ecef;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #dee2e6;
}

.accordion-header h6 {
    color: #495057;
    font-weight: 600;
    margin: 0;
}

.accordion-body {
    padding: 1.5rem;
}

/* Estilos para o guia de configuração */

/* Estilos para a tabela de leads */
.table-responsive {
    max-height: 500px;
    overflow-y: auto;
}

.user-avatar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}
.step-number {
    width: 24px;
    height: 24px;
    background: linear-gradient(135deg, #007bff, #0056b3);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: bold;
    flex-shrink: 0;
}

.step-item h6 {
    color: #495057;
    font-size: 0.9rem;
}

.step-item p {
    font-size: 0.8rem;
    line-height: 1.4;
}

.step-item code {
    background: #ffffff;
    color: #e83e8c;
    padding: 2px 4px;
    border-radius: 3px;
    font-size: 0.75rem;
    border: 1px solid #e9ecef;
}

pre code {
    background: transparent !important;
    color: #495057 !important;
    border: none !important;
    padding: 0 !important;
    font-size: 0.8rem;
    line-height: 1.4;
}

/* Animação da seta */
#configChevron {
    transition: transform 0.3s ease;
}

#configChevron.rotated {
    transform: rotate(180deg);
}

/* Responsivo */
@media (max-width: 768px) {
    .accordion-body {
        padding: 1rem;
    }
    
    .step-item {
        margin-bottom: 1rem !important;
    }
    
    .step-number {
        width: 20px;
        height: 20px;
        font-size: 0.7rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const configButton = document.querySelector('[data-bs-target="#configAccordion"]');
    const chevron = document.getElementById('configChevron');
    
    if (configButton && chevron) {
        configButton.addEventListener('click', function() {
            // Alternar rotação da seta
            chevron.classList.toggle('rotated');
        });
        
        // Verificar estado inicial do accordion
        const accordion = document.getElementById('configAccordion');
        if (accordion) {
            accordion.addEventListener('show.bs.collapse', function() {
                chevron.classList.add('rotated');
            });
            
            accordion.addEventListener('hide.bs.collapse', function() {
                chevron.classList.remove('rotated');
            });
        }
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('dashboard.layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Templats-link-templats-link\resources\views/dashboard/index.blade.php ENDPATH**/ ?>