

<?php $__env->startSection('title', 'Gerenciar Temas'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div class="flex-grow-1">
            <h1 class="mb-2"><i class="fas fa-palette text-primary"></i> Gerenciar Temas</h1>
            <p class="text-muted mb-0">Configure e gerencie os temas do seu site</p>
        </div>
        <div class="d-flex gap-2 flex-shrink-0">
        </div>
    </div>

    <!-- Lista de Temas Instalados -->
    <div class="admin-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list text-primary"></i> Temas Instalados</h5>
            <small class="text-muted">Gerencie os temas instalados no sistema</small>
        </div>
        <div class="card-body">
            <?php if(count($temas) > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Assets</th>
                                <th>Páginas</th>
                                <th>Tamanho</th>
                                <th>Instalado em</th>
                                <th>Preview</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $temas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tema): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($tema['nome']); ?></strong>
                                        <?php if($tema['is_main']): ?>
                                            <span class="badge bg-primary ms-2">
                                                <i class="fas fa-home"></i> Padrão
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?php echo e($tema['arquivos']); ?> arquivo(s)</span>
                                    </td>
                                    <td>
                                        <?php if($tema['tem_paginas']): ?>
                                            <span class="badge bg-success"><?php echo e($tema['arquivos_paginas']); ?> página(s)</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Sem páginas</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($tema['tamanho']); ?></td>
                                    <td><?php echo e($tema['criado_em']); ?></td>
                                    <td>
                                        <?php if($tema['tem_paginas']): ?>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-info preview-btn" 
                                                    data-bs-toggle="collapse" 
                                                    data-bs-target="#pagesAccordion<?php echo e($loop->index); ?>"
                                                    aria-expanded="false" 
                                                    aria-controls="pagesAccordion<?php echo e($loop->index); ?>"
                                                    title="Ver páginas do tema">
                                                <i class="fas fa-eye"></i>
                                                <span class="btn-text">Ver Páginas</span>
                                            </button>
                                        <?php else: ?>
                                            <span class="text-muted no-pages">Sem páginas</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1 align-items-center">
                                            <?php if($tema['ativo']): ?>
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check-circle"></i> Ativo
                                                </span>
                                            <?php else: ?>
                                                <form action="<?php echo e(route('dashboard.temas.select', $tema['nome'])); ?>" method="POST" class="d-inline" id="selectForm<?php echo e($loop->index); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-success select-theme-btn" 
                                                            data-tema="<?php echo e($tema['nome']); ?>"
                                                            data-index="<?php echo e($loop->index); ?>"
                                                            onclick="return selectTheme('<?php echo e($tema['nome']); ?>', <?php echo e($loop->index); ?>)">
                                                        <i class="fas fa-star"></i> Selecionar
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                            <?php if(!$tema['is_main']): ?>
                                                <button type="button" 
                                                        class="btn btn-sm btn-info" 
                                                        onclick="abrirModalDuplicar('<?php echo e($tema['nome']); ?>')"
                                                        title="Duplicar tema">
                                                    <i class="fas fa-copy"></i> Duplicar
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger" 
                                                        onclick="confirmarRemocao('<?php echo e($tema['nome']); ?>')">
                                                    <i class="fas fa-trash"></i> Remover
                                                </button>
                                            <?php else: ?>
                                                <span class="badge bg-info">
                                                    <i class="fas fa-shield-alt"></i> Sistema
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php if($tema['tem_paginas']): ?>
                                    <tr class="accordion-row">
                                        <td colspan="7" class="p-0">
                                            <div class="collapse" id="pagesAccordion<?php echo e($loop->index); ?>">
                                                <div class="card card-body border-0 bg-light">
                                                    <div class="pages-buttons">
                                                        <?php $__currentLoopData = $tema['paginas_disponiveis']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pagina): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <a href="<?php echo e(route('dashboard.temas.preview.page', [$tema['nome'], $pagina])); ?>" 
                                                               target="_blank"
                                                               class="btn btn-outline-primary btn-sm d-flex align-items-center">
                                                                <i class="fas fa-file-alt me-2"></i>
                                                                <?php echo e(ucfirst($pagina)); ?>

                                                            </a>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-palette fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted mb-2">Nenhum tema instalado</h5>
                        <p class="text-muted mb-4">Nenhum tema foi instalado no sistema ainda.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de Duplicar Tema -->
<div class="modal fade" id="duplicateModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-copy text-info me-2"></i>Duplicar Tema</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="duplicateForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <p>Informe o nome para o novo tema duplicado de <strong id="temaOriginalNome"></strong>:</p>
                    <div class="mb-3">
                        <label for="novo_nome_tema" class="form-label">Nome do Novo Tema</label>
                        <input type="text" 
                               class="form-control <?php $__errorArgs = ['novo_nome_tema'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="novo_nome_tema" 
                               name="novo_nome_tema" 
                               placeholder="Ex: tema-copia, meu-tema-v2"
                               pattern="[a-zA-Z0-9_-]+"
                               required>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle me-1"></i>Use apenas letras, números, hífens e underscores
                        </small>
                        <?php $__errorArgs = ['novo_nome_tema'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <small>O novo tema será uma cópia completa incluindo assets, páginas, rotas e configurações.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info" id="duplicateSubmitBtn">
                        <i class="fas fa-copy me-2"></i>Duplicar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Remoção</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Tem certeza que deseja remover o tema <strong id="temaNome"></strong>?</p>
                <p class="text-danger"><small>Esta ação não pode ser desfeita.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger">Remover</button>
                </form>
            </div>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Função para abrir modal de duplicar
function abrirModalDuplicar(nomeTema) {
    document.getElementById('temaOriginalNome').textContent = nomeTema;
    document.getElementById('duplicateForm').action = '<?php echo e(route("dashboard.temas.duplicate", ":nomeTema")); ?>'.replace(':nomeTema', nomeTema);
    document.getElementById('novo_nome_tema').value = '';
    
    var modal = new bootstrap.Modal(document.getElementById('duplicateModal'));
    modal.show();
    
    // Focar no campo de input
    setTimeout(function() {
        document.getElementById('novo_nome_tema').focus();
    }, 500);
}

// Função para confirmar remoção
function confirmarRemocao(nomeTema) {
    document.getElementById('temaNome').textContent = nomeTema;
    document.getElementById('deleteForm').action = '<?php echo e(route("dashboard.temas.destroy", ":nomeTema")); ?>'.replace(':nomeTema', nomeTema);
    
    var modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    modal.show();
}


// Função para selecionar tema com melhor tratamento de erros
function selectTheme(nomeTema, index) {
    if (!confirm('Deseja selecionar o tema "' + nomeTema + '" como tema principal?')) {
        return false;
    }
    
    const form = document.getElementById('selectForm' + index);
    const button = form.querySelector('.select-theme-btn');
    const originalText = button.innerHTML;
    
    // Mostrar loading
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Selecionando...';
    button.disabled = true;
    
    // Enviar formulário
    fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        // Verificar se a resposta é JSON
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json().then(data => {
                if (!response.ok) {
                    throw new Error(data.message || 'Erro na requisição');
                }
                return data;
            });
        } else {
            // Se não for JSON, tratar como texto (fallback)
            if (response.ok) {
                return { success: true, message: 'Tema selecionado com sucesso!' };
            }
            throw new Error('Erro na requisição: ' + response.status);
        }
    })
    .then(data => {
        // Sucesso - mostrar mensagem e recarregar a página
        if (data.success) {
            // Mostrar mensagem de sucesso se houver
            if (data.message) {
                alert(data.message);
            }
            // Recarregar a página para mostrar mudanças
            window.location.reload();
        } else {
            throw new Error(data.message || 'Erro desconhecido');
        }
    })
    .catch(error => {
        console.error('Erro ao selecionar tema:', error);
        alert('Erro ao selecionar o tema: ' + error.message);
        
        // Restaurar botão
        button.innerHTML = originalText;
        button.disabled = false;
    });
    
    return false; // Prevenir envio padrão do formulário
}

function linkarPaginas(nomeTema) {
    // Definir URLs das páginas de edição
    const paginas = {
        'home': '<?php echo e(route("dashboard.temas.home.edit")); ?>',
        'about': '<?php echo e(route("dashboard.temas.about.edit")); ?>',
        'contact': '<?php echo e(route("dashboard.temas.contact.edit")); ?>',
        'servico': '<?php echo e(route("dashboard.servico.index")); ?>'
    };
    
    // Abrir cada página em uma nova aba
    Object.keys(paginas).forEach(function(pagina) {
        window.open(paginas[pagina], '_blank');
    });
    
    // Mostrar mensagem de sucesso
    alert('Páginas abertas em novas abas para edição!');
}

// Inicializar funcionalidades
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Garantir que todos os accordions funcionem
    var collapseElementList = [].slice.call(document.querySelectorAll('.collapse'));
    var collapseList = collapseElementList.map(function (collapseEl) {
        return new bootstrap.Collapse(collapseEl, {
            toggle: false
        });
    });
    
    // Auto-hide alerts após 5 segundos
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
    
    // Adicionar loading ao formulário de duplicar
    const duplicateForm = document.getElementById('duplicateForm');
    const duplicateSubmitBtn = document.getElementById('duplicateSubmitBtn');
    
    if (duplicateForm && duplicateSubmitBtn) {
        duplicateForm.addEventListener('submit', function() {
            duplicateSubmitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Duplicando...';
            duplicateSubmitBtn.disabled = true;
        });
    }
    
    // Melhorar botões de preview
    const previewButtons = document.querySelectorAll('.preview-btn');
    previewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const target = this.getAttribute('data-bs-target');
            const collapse = document.querySelector(target);
            
            if (collapse) {
                // Adicionar loading state
                const icon = this.querySelector('i');
                const originalClass = icon.className;
                icon.className = 'fas fa-spinner fa-spin';
                
                // Restaurar ícone após animação
                setTimeout(() => {
                    icon.className = originalClass;
                }, 500);
            }
        });
    });
    
    
    console.log('Admin Panel inicializado com sucesso!');
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Templats-link-templats-link\resources\views/dashboard/temas/index.blade.php ENDPATH**/ ?>