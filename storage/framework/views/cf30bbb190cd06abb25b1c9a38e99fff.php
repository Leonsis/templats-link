

<?php $__env->startSection('title', 'Páginas do Tema'); ?>

<?php $__env->startSection('content'); ?>

<?php
    $allowedEmails = ['dev@templats-link.com'];
    $userCanAccessThemes = in_array(auth()->user()->email, $allowedEmails);
?>

<style>
    .page-card {
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .page-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12) !important;
    }
    
    .page-icon-wrapper {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        color: white !important;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    
    .page-card .card-header {
        background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
        border-radius: 12px 12px 0 0;
    }
    
    .config-status-list {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 0.75rem;
    }
    
    .config-item {
        padding: 0.25rem 0;
    }
    
    .page-card .btn-group .btn {
        border-radius: 0;
    }
    
    .page-card .btn-group .btn:first-child {
        border-top-left-radius: 6px;
        border-bottom-left-radius: 6px;
    }
    
    .page-card .btn-group .btn:last-child {
        border-top-right-radius: 6px;
        border-bottom-right-radius: 6px;
    }
    
    @media (max-width: 576px) {
        .page-card .btn-group .btn span {
            display: none;
        }
    }
    
    .badge {
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    
    code {
        background-color: rgba(13, 110, 253, 0.1);
        padding: 0.2rem 0.4rem;
        border-radius: 4px;
        font-size: 0.85em;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-alt me-2"></i>
                            Páginas do Tema: <?php echo e($temaAtivo); ?>

                        </h5>
                         <?php if($userCanAccessThemes): ?>
                             <?php if($temaAtivo !== 'main-Thema'): ?>
                                 <button type="button" 
                                         class="btn btn-warning btn-sm me-2" 
                                         onclick="openRenameModal('<?php echo e($temaAtivo); ?>')"
                                         title="Editar nome do tema">
                                     <i class="fas fa-edit me-2"></i>
                                     Editar Nome do Tema
                                 </button>
                             <?php endif; ?>
                             <button type="button" 
                                     class="btn btn-info btn-sm" 
                                     onclick="generateSitemap('<?php echo e($temaAtivo); ?>')"
                                     title="Gerar sitemap.xml do tema">
                                 <i class="fas fa-sitemap me-2"></i>
                                 Gerar Sitemap
                             </button>
                             <button type="button" 
                                     class="btn btn-success btn-sm" 
                                     id="robotsBtn"
                                     onclick="toggleRobots()"
                                     title="Habilitar/Desabilitar indexação de robôs">
                                 <i class="fas fa-robot me-2"></i>
                                 <span id="robotsBtnText">Indexar Robôs</span>
                             </button>
                             <button type="button" 
                                     class="btn btn-primary btn-sm" 
                                     onclick="generateLlms('<?php echo e($temaAtivo); ?>')"
                                     title="Gerar arquivo llms.txt do tema">
                                 <i class="fas fa-file-alt me-2"></i>
                                 Gerar LLMS.txt
                             </button>
                         <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php if(empty($paginas)): ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Nenhuma página encontrada neste tema.
                        </div>
                    <?php else: ?>
                        <div class="row g-4">
                            <?php $__currentLoopData = $paginas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pagina): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $configuracao = $configuracoes->where('pagina', $pagina)->first();
                                    $temConfiguracao = $configuracao && ($configuracao->meta_title || $configuracao->meta_description || $configuracao->meta_keywords);
                                    $configCount = 0;
                                    if ($configuracao) {
                                        $configCount = ($configuracao->meta_title ? 1 : 0) + 
                                                      ($configuracao->meta_description ? 1 : 0) + 
                                                      ($configuracao->meta_keywords ? 1 : 0);
                                    }
                                ?>
                                
                                <div class="col-12 col-sm-6 col-lg-4 ">
                                    <div class="card h-100 border-0 shadow-sm page-card" style="transition: all 0.3s ease;">
                                        <!-- Card Header -->
                                        <div class="card-header bg-white border-bottom pb-2">
                                            <div class="d-flex align-items-start justify-content-between mb-2">
                                                <div class="d-flex align-items-center flex-grow-1">
                                                    <div class="page-icon-wrapper me-3">
                                                        <i class="fas fa-file-alt text-primary"></i>
                                                    </div>
                                                    <div class="flex-grow-1 min-w-0">
                                                        <h6 class="card-title mb-0 text-truncate" style="font-weight: 600; font-size: 0.95rem;">
                                                            <?php echo e(ucfirst(str_replace('-', ' ', $pagina))); ?>

                                                        </h6>                                                        
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-start justify-content-between mb-2">                                                                                                    
                                                <div class="flex-grow-1 min-w-0">                                                        
                                                    <small class="text-muted d-block text-truncate" style="font-size: 0.75rem;">
                                                        <i class="fas fa-code me-1"></i><?php echo e($pagina); ?>.blade.php
                                                    </small>
                                                </div>                                                
                                            </div>
                                            
                                            <!-- Status Badge -->
                                            <div class="d-flex align-items-center justify-content-between">
                                                <?php if($temConfiguracao): ?>
                                                    <span class="badge bg-success" style="font-size: 0.7rem;">
                                                        <i class="fas fa-check-circle me-1"></i>
                                                        <?php echo e($configCount); ?>/3 Configurado<?php echo e($configCount > 1 ? 's' : ''); ?>

                                                    </span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">
                                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                                        Não configurado
                                                    </span>
                                                <?php endif; ?>
                                                
                                                <?php if(isset($rotasPaginas[$pagina])): ?>
                                                    <?php
                                                        $infoRota = $rotasPaginas[$pagina];
                                                    ?>
                                                    <span class="badge bg-info" style="font-size: 0.65rem;" title="Rota ativa">
                                                        <i class="fas fa-route me-1"></i>
                                                        Rota
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <!-- Card Body -->
                                        <div class="card-body flex-grow-1 d-flex flex-column">
                                            <!-- Rota Info -->
                                            <?php if(isset($rotasPaginas[$pagina])): ?>
                                                <?php
                                                    $infoRota = $rotasPaginas[$pagina];
                                                ?>
                                                <div class="mb-3 p-2 bg-light rounded" style="font-size: 0.8rem;">
                                                    <div class="d-flex align-items-center text-info mb-1">
                                                        <i class="fas fa-link me-2" style="font-size: 0.75rem;"></i>
                                                        <strong>Rota:</strong>
                                                    </div>
                                                    <code class="text-info d-block text-truncate" style="font-size: 0.75rem;">
                                                        <?php if($infoRota['tipo'] === 'nomeada' && $infoRota['rota']): ?>
                                                            <?php echo e(str_replace(['route(', ')', "'"], '', $infoRota['rota'])); ?>

                                                        <?php elseif($infoRota['url']): ?>
                                                            <?php echo e($infoRota['url']); ?>

                                                        <?php endif; ?>
                                                    </code>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Configurações Status -->
                                            <?php if($temConfiguracao): ?>
                                                <div class="mb-3 flex-grow-1">
                                                    <div class="config-status-list">
                                                        <?php if($configuracao->meta_title): ?>
                                                            <div class="config-item mb-2">
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas fa-check-circle text-success me-2" style="font-size: 0.8rem;"></i>
                                                                    <span class="text-muted" style="font-size: 0.8rem;">Meta Title</span>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if($configuracao->meta_description): ?>
                                                            <div class="config-item mb-2">
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas fa-check-circle text-success me-2" style="font-size: 0.8rem;"></i>
                                                                    <span class="text-muted" style="font-size: 0.8rem;">Meta Description</span>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if($configuracao->meta_keywords): ?>
                                                            <div class="config-item mb-2">
                                                                <div class="d-flex align-items-center">
                                                                    <i class="fas fa-check-circle text-success me-2" style="font-size: 0.8rem;"></i>
                                                                    <span class="text-muted" style="font-size: 0.8rem;">Meta Keywords</span>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <div class="mb-3 flex-grow-1 d-flex align-items-center">
                                                    <div class="alert alert-warning py-2 px-3 mb-0 w-100" style="font-size: 0.8rem;">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Configure as meta tags para melhorar o SEO
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            
                                            <!-- Action Buttons -->
                                            <div class="mt-auto">
                                                <div class="d-grid gap-2 mb-2">
                                                    <a href="<?php echo e(route('dashboard.theme-pages.show', $pagina)); ?>" 
                                                       class="btn btn-primary btn-sm">
                                                        <i class="fas fa-cog me-2"></i>
                                                        Configurar
                                                    </a>
                                                </div>
                                                
                                                <?php if($userCanAccessThemes): ?>
                                                    <div class="btn-group w-100" role="group">
                                                        <button type="button" 
                                                                class="btn btn-outline-info btn-sm" 
                                                                onclick="openRenamePageModal('<?php echo e($pagina); ?>')"
                                                                title="Editar nome da página"
                                                                style="font-size: 0.85rem;">
                                                            <i class="fas fa-pencil-alt"></i>
                                                            <span class="d-none d-sm-inline ms-1">Editar</span>
                                                        </button>
                                                        <button type="button" 
                                                                class="btn btn-outline-secondary btn-sm" 
                                                                onclick="openDuplicateModal('<?php echo e($pagina); ?>')"
                                                                title="Duplicar página"
                                                                style="font-size: 0.85rem;">
                                                            <i class="fas fa-copy"></i>
                                                            <span class="d-none d-sm-inline ms-1">Duplicar</span>
                                                        </button>
                                                        <?php if(!in_array($pagina, ['home', 'sobre', 'contato', 'blog', 'blogs', 'politicas-de-privacidade'])): ?>
                                                            <button type="button" 
                                                                    class="btn btn-outline-danger btn-sm" 
                                                                    onclick="openDeleteModal('<?php echo e($pagina); ?>')"
                                                                    title="Excluir página"
                                                                    style="font-size: 0.85rem;">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Duplicação -->
<div class="modal fade" id="duplicateModal" tabindex="-1" aria-labelledby="duplicateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="duplicateModalLabel">
                    <i class="fas fa-copy me-2"></i>
                    Duplicar Página
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="duplicateForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="originalPage" class="form-label">Página Original:</label>
                        <input type="text" class="form-control" id="originalPage" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="newPageName" class="form-label">Nome da Nova Página:</label>
                        <input type="text" class="form-control" id="newPageName" name="new_page_name" 
                               placeholder="Digite o nome da nova página" required>
                        <div class="form-text">
                            <div>A nova página será criada como: <span id="newPagePreview" class="text-muted"></span></div>
                            <div>Rota dinâmica: <code id="routePreview" class="text-muted"></code></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-copy me-2"></i>
                        Duplicar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Renomeação de Página -->
<div class="modal fade" id="renamePageModal" tabindex="-1" aria-labelledby="renamePageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="renamePageModalLabel">
                    <i class="fas fa-pencil-alt me-2"></i>
                    Editar Nome da Página
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="renamePageForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Importante!</strong> Esta ação irá renomear a página e atualizar todas as referências no tema.
                    </div>
                    <div class="mb-3">
                        <label for="paginaAtual" class="form-label">Página Atual:</label>
                        <input type="text" class="form-control" id="paginaAtual" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="novoNomePagina" class="form-label">Novo Nome da Página:</label>
                        <input type="text" 
                               class="form-control <?php $__errorArgs = ['novo_nome_pagina'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="novoNomePagina" 
                               name="novo_nome_pagina" 
                               placeholder="Digite o novo nome da página"
                               required>
                        <?php $__errorArgs = ['novo_nome_pagina'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">
                            <div>O novo arquivo será: <span id="novoNomePaginaPreview" class="text-muted"></span></div>
                            <div>Nova rota: <code id="novaRotaPreview" class="text-muted"></code></div>
                            <div><small class="text-muted">Use apenas letras, números e hífen (-). Não use espaços ou caracteres especiais.</small></div>
                        </div>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Esta ação irá:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Renomear o arquivo .blade.php</li>
                            <li>Atualizar a rota dinâmica no banco de dados</li>
                            <li>Atualizar todas as referências nas outras páginas do tema</li>
                            <li>Atualizar as configurações (meta tags, content forms) no banco de dados</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-pencil-alt me-2"></i>
                        Renomear Página
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirmar Exclusão
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="deleteForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Atenção!</strong> Esta ação não pode ser desfeita.
                    </div>
                    <p>Você está prestes a excluir a página:</p>
                    <div class="bg-light p-3 rounded">
                        <strong id="pageToDelete"></strong>
                    </div>
                    <p class="mt-3 mb-0">Esta ação irá:</p>
                    <ul class="list-unstyled mt-2">
                        <li><i class="fas fa-trash text-danger me-2"></i> Excluir o arquivo da página</li>
                        <li><i class="fas fa-cog text-danger me-2"></i> Remover todas as configurações</li>
                        <li><i class="fas fa-route text-danger me-2"></i> Deletar a rota dinâmica</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-2"></i>
                        Excluir Página
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.icon-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.card {
    transition: transform 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
}
</style>

<script>
function openDuplicateModal(originalPage) {
    document.getElementById('originalPage').value = originalPage;
    document.getElementById('newPageName').value = '';
    document.getElementById('newPagePreview').textContent = '';
    document.getElementById('duplicateForm').action = '<?php echo e(route("dashboard.theme-pages.duplicate", ":page")); ?>'.replace(':page', originalPage);
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('duplicateModal'));
    modal.show();
}

function openRenamePageModal(pageName) {
    document.getElementById('paginaAtual').value = pageName;
    document.getElementById('novoNomePagina').value = '';
    document.getElementById('novoNomePaginaPreview').textContent = '';
    document.getElementById('novaRotaPreview').textContent = '';
    document.getElementById('renamePageForm').action = '<?php echo e(route("dashboard.theme-pages.rename", ":page")); ?>'.replace(':page', pageName);
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('renamePageModal'));
    modal.show();
}

function openDeleteModal(pageName) {
    document.getElementById('pageToDelete').textContent = pageName + '.blade.php';
    document.getElementById('deleteForm').action = '<?php echo e(route("dashboard.theme-pages.destroy", ":page")); ?>'.replace(':page', pageName);
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

function openRenameModal(nomeTema) {
    document.getElementById('temaAtual').value = nomeTema;
    document.getElementById('novoNome').value = '';
    document.getElementById('novoNomePreview').textContent = '';
    document.getElementById('renameForm').action = '<?php echo e(route("dashboard.temas.rename", ":nomeTema")); ?>'.replace(':nomeTema', nomeTema);
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('renameModal'));
    modal.show();
}

function generateSitemap(nomeTema) {
    // O tema será detectado automaticamente pelo sistema baseado no domínio
    console.log('Iniciando geração de sitemap para o tema ativo do sistema');
    
    if (confirm('Deseja gerar o sitemap.xml para o tema ativo do sistema?\n\nIsso irá analisar todas as páginas do tema e criar um arquivo sitemap.xml na raiz do site.')) {
        console.log('Usuário confirmou a geração do sitemap');
        
        // Mostrar loading
        const button = event.target;
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Gerando...';
        button.disabled = true;
        
        // Criar formulário dinâmico
        const form = document.createElement('form');
        form.method = 'POST';
        // Usar o tema ativo (pode ser qualquer valor, o sistema vai ignorar e usar o tema ativo)
        const actionUrl = '<?php echo e(route("dashboard.temas.generate-sitemap", ":nomeTema")); ?>'.replace(':nomeTema', '<?php echo e($temaAtivo); ?>');
        form.action = actionUrl;
        form.style.display = 'none';
        
        console.log('URL da ação:', actionUrl);
        
        // Adicionar token CSRF
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '<?php echo e(csrf_token()); ?>';
        form.appendChild(csrfToken);
        
        console.log('Token CSRF:', csrfToken.value);
        
        // Adicionar ao body e submeter
        document.body.appendChild(form);
        console.log('Formulário criado e adicionado ao DOM');
        console.log('Submetendo formulário...');
        form.submit();
        
        // Restaurar botão após 5 segundos (fallback)
        setTimeout(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        }, 5000);
    } else {
        console.log('Usuário cancelou a geração do sitemap');
    }
}

let currentTemaForLlms = null;

function generateLlms(nomeTema) {
    console.log('Abrindo modal para gerar llms.txt para tema:', nomeTema);
    currentTemaForLlms = nomeTema;
    
    // Limpar conteúdo anterior
    document.getElementById('llmsContent').value = '';
    
    // Abrir modal
    const modal = new bootstrap.Modal(document.getElementById('llmsModal'));
    modal.show();
}

// Submissão do formulário LLMS
document.addEventListener('DOMContentLoaded', function() {
    const llmsForm = document.getElementById('llmsForm');
    if (llmsForm) {
        llmsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const content = document.getElementById('llmsContent').value.trim();
            
            if (!content) {
                alert('Por favor, insira o conteúdo do arquivo LLMS.txt.');
                return;
            }
            
            if (!currentTemaForLlms) {
                alert('Erro: Tema não identificado.');
                return;
            }
            
            // Mostrar loading
            const submitBtn = document.getElementById('llmsSubmitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Gerando...';
            submitBtn.disabled = true;
            
            // Fazer requisição AJAX
            const url = '<?php echo e(route("dashboard.temas.generate-llms", ":nomeTema")); ?>'.replace(':nomeTema', currentTemaForLlms);
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    content: content
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Erro na requisição');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message || 'Arquivo LLMS.txt gerado com sucesso!');
                    // Fechar modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('llmsModal'));
                    if (modal) {
                        modal.hide();
                    }
                    // Limpar formulário
                    document.getElementById('llmsContent').value = '';
                } else {
                    alert('Erro: ' + (data.message || 'Erro ao gerar o arquivo.'));
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao processar solicitação: ' + error.message);
            })
            .finally(() => {
                // Restaurar botão
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });
    }
});

// Atualizar preview do nome da nova página
document.getElementById('newPageName').addEventListener('input', function() {
    const newName = this.value.trim();
    const preview = document.getElementById('newPagePreview');
    const routePreview = document.getElementById('routePreview');
    
    if (newName) {
        // Converter para formato de nome de arquivo (lowercase, sem espaços, etc.)
        const fileName = newName.toLowerCase()
            .replace(/[^a-z0-9]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
        
        preview.textContent = fileName + '.blade.php';
        preview.className = 'text-success';
        
        // Mostrar rota dinâmica
        const routeName = fileName.replace(/-/g, '-');
        const temaAtivo = '<?php echo e(\App\Helpers\ThemeHelper::getActiveTheme()); ?>';
        // Gerar nome da rota dinâmica baseado no tema ativo
        const routeNamePattern = 'tema.' + temaAtivo + '.' + routeName;
        routePreview.textContent = '<?php echo e(url("/")); ?>' + '/' + routeName;
        routePreview.className = 'text-success';
    } else {
        preview.textContent = '';
        preview.className = 'text-muted';
        routePreview.textContent = '';
        routePreview.className = 'text-muted';
    }
});

// Validação do formulário
document.getElementById('duplicateForm').addEventListener('submit', function(e) {
    const newName = document.getElementById('newPageName').value.trim();
    
    if (!newName) {
        e.preventDefault();
        alert('Por favor, digite um nome para a nova página.');
        return;
    }
    
    // Validar nome (apenas letras, números e hífen)
    if (!/^[a-zA-Z0-9-]+$/.test(newName)) {
        e.preventDefault();
        alert('O nome da página deve conter apenas letras, números e hífen (-).');
        return;
    }
    
    // Mostrar loading no botão
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Duplicando...';
    submitBtn.disabled = true;
    
    // Restaurar botão em caso de erro (será feito pelo servidor)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 10000);
});

// Preview do novo nome da página
const novoNomePaginaInput = document.getElementById('novoNomePagina');
if (novoNomePaginaInput) {
    novoNomePaginaInput.addEventListener('input', function() {
        const novoNome = this.value.trim();
        const preview = document.getElementById('novoNomePaginaPreview');
        const rotaPreview = document.getElementById('novaRotaPreview');
        
        if (novoNome) {
            // Converter para formato de nome de arquivo (lowercase, sem espaços, etc.)
            const fileName = novoNome.toLowerCase()
                .replace(/[^a-z0-9]/g, '-')
                .replace(/-+/g, '-')
                .replace(/^-|-$/g, '');
            
            if (fileName && /^[a-z0-9-]+$/.test(fileName)) {
                preview.textContent = fileName + '.blade.php';
                preview.className = 'text-success';
                
                // Mostrar nova rota
                const temaAtivo = '<?php echo e(\App\Helpers\ThemeHelper::getActiveTheme()); ?>';
                const routeName = fileName.replace(/-/g, '-');
                rotaPreview.textContent = '/' + routeName;
                rotaPreview.className = 'text-success';
            } else {
                preview.textContent = 'Nome inválido';
                preview.className = 'text-danger';
                rotaPreview.textContent = '';
                rotaPreview.className = 'text-muted';
            }
        } else {
            preview.textContent = '';
            preview.className = 'text-muted';
            rotaPreview.textContent = '';
            rotaPreview.className = 'text-muted';
        }
    });
}

// Validação do formulário de renomeação de página
const renamePageForm = document.getElementById('renamePageForm');
if (renamePageForm) {
    renamePageForm.addEventListener('submit', function(e) {
        const novoNome = document.getElementById('novoNomePagina').value.trim();
        
        if (!novoNome) {
            e.preventDefault();
            alert('Por favor, digite um novo nome para a página.');
            return;
        }
        
        // Validar nome (apenas letras, números e hífen)
        const fileName = novoNome.toLowerCase()
            .replace(/[^a-z0-9]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
        
        if (!fileName || !/^[a-z0-9-]+$/.test(fileName)) {
            e.preventDefault();
            alert('O nome da página deve conter apenas letras, números e hífen (-).');
            return;
        }
        
        // Mostrar loading no botão
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Renomeando...';
        submitBtn.disabled = true;
        
        // Restaurar botão em caso de erro (será feito pelo servidor)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 10000);
    });
}

// Validação do formulário de exclusão
document.getElementById('deleteForm').addEventListener('submit', function(e) {
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    
    // Mostrar loading no botão
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Excluindo...';
    submitBtn.disabled = true;
    
    // Restaurar botão em caso de erro (será feito pelo servidor)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 10000);
});

// Adicionar preview do novo nome do tema
const novoNomeInput = document.getElementById('novoNome');
if (novoNomeInput) {
    novoNomeInput.addEventListener('input', function() {
        const novoNome = this.value.trim();
        const preview = document.getElementById('novoNomePreview');
        
        if (novoNome) {
            // Validar nome (apenas letras, números, hífen e underscore)
            if (/^[a-zA-Z0-9-_]+$/.test(novoNome)) {
                preview.textContent = novoNome;
                preview.className = 'text-success';
            } else {
                preview.textContent = 'Nome inválido - use apenas letras, números, hífen (-) e underscore (_)';
                preview.className = 'text-danger';
            }
        } else {
            preview.textContent = '';
            preview.className = 'text-muted';
        }
    });
}

// Validação do formulário de renomeação
const renameForm = document.getElementById('renameForm');
if (renameForm) {
    renameForm.addEventListener('submit', function(e) {
        const novoNome = document.getElementById('novoNome').value.trim();
        
        if (!novoNome) {
            e.preventDefault();
            alert('Por favor, digite um novo nome para o tema.');
            return;
        }
        
        // Validar nome (apenas letras, números, hífen e underscore)
        if (!/^[a-zA-Z0-9-_]+$/.test(novoNome)) {
            e.preventDefault();
            alert('O nome do tema deve conter apenas letras, números, hífen (-) e underscore (_).');
            return;
        }
        
        // Mostrar loading no botão
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Renomeando...';
        submitBtn.disabled = true;
        
        // Restaurar botão em caso de erro (será feito pelo servidor)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 10000);
    });
}
</script>

<!-- Modal de Renomeação do Tema -->
<div class="modal fade" id="renameModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>
                    Editar Nome do Tema
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="renameForm" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Importante!</strong> Esta ação irá renomear o tema em todo o sistema.
                    </div>
                    <div class="mb-3">
                        <label for="temaAtual" class="form-label">Tema Atual:</label>
                        <input type="text" class="form-control" id="temaAtual" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="novoNome" class="form-label">Novo Nome do Tema:</label>
                        <input type="text" 
                               class="form-control <?php $__errorArgs = ['novo_nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="novoNome" 
                               name="novo_nome" 
                               placeholder="Digite o novo nome do tema"
                               required>
                        <?php $__errorArgs = ['novo_nome'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <div class="form-text">
                            <div>O novo nome será: <span id="novoNomePreview" class="text-muted"></span></div>
                            <div><small class="text-muted">Use apenas letras, números, hífen (-) e underscore (_)</small></div>
                        </div>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Esta ação irá:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Renomear as pastas de assets e views</li>
                            <li>Atualizar todas as configurações no banco de dados</li>
                            <li>Atualizar as rotas dinâmicas</li>
                            <li>Se for o tema ativo, atualizar a configuração principal</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-edit me-2"></i>
                        Renomear Tema
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Gerar LLMS.txt -->
<div class="modal fade" id="llmsModal" tabindex="-1" aria-labelledby="llmsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="llmsModalLabel">
                    <i class="fas fa-file-alt me-2"></i>
                    Gerar LLMS.txt
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="llmsForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="llmsContent" class="form-label">Conteúdo do arquivo LLMS.txt:</label>
                        <textarea class="form-control" 
                                  id="llmsContent" 
                                  name="content" 
                                  rows="15" 
                                  placeholder="Digite o conteúdo do arquivo LLMS.txt aqui..."
                                  required></textarea>
                        <div class="form-text">
                            O arquivo será salvo como <code>llms.txt</code> na raiz do projeto.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" id="llmsSubmitBtn">
                        <i class="fas fa-save me-2"></i>
                        Gerar Arquivo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Função para controlar o botão de robots
let robotsStatus = null;

// Verificar status inicial do robots
document.addEventListener('DOMContentLoaded', function() {
    checkRobotsStatus();
});

function checkRobotsStatus() {
    fetch('<?php echo e(route("dashboard.robots.status")); ?>')
        .then(response => response.json())
        .then(data => {
            robotsStatus = data;
            updateRobotsButton();
        })
        .catch(error => {
            console.error('Erro ao verificar status do robots:', error);
        });
}

function updateRobotsButton() {
    const btn = document.getElementById('robotsBtn');
    const btnText = document.getElementById('robotsBtnText');
    
    if (robotsStatus && robotsStatus.robots_exists) {
        // Se já está habilitado, mostrar opção para desabilitar
        btn.className = 'btn btn-danger btn-sm';
        btnText.textContent = 'Desindexar Robôs';
        btn.title = 'Desabilitar indexação de robôs';
    } else {
        // Se não está habilitado, mostrar opção para habilitar
        btn.className = 'btn btn-success btn-sm';
        btnText.textContent = 'Indexar Robôs';
        btn.title = 'Habilitar indexação de robôs';
    }
}

function toggleRobots() {
    const isEnabled = robotsStatus && robotsStatus.robots_exists;
    const action = isEnabled ? 'disable' : 'enable';
    const actionText = isEnabled ? 'desabilitar' : 'habilitar';
    
    if (confirm(`Deseja ${actionText} a indexação de robôs?\n\nIsso irá ${actionText === 'habilitar' ? 'criar o arquivo robots.txt na raiz do projeto' : 'remover o arquivo robots.txt'}.`)) {
        // Mostrar loading
        const button = document.getElementById('robotsBtn');
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processando...';
        button.disabled = true;
        
        // Fazer requisição
        const url = action === 'enable' ? '<?php echo e(route("dashboard.robots.enable")); ?>' : '<?php echo e(route("dashboard.robots.disable")); ?>';
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Mostrar mensagem de sucesso
                alert(data.message);
                
                // Atualizar status
                checkRobotsStatus();
            } else {
                alert('Erro: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao processar solicitação');
        })
        .finally(() => {
            // Restaurar botão
            button.innerHTML = originalText;
            button.disabled = false;
        });
    }
}
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Templats-link-templats-link\resources\views/dashboard/theme-pages/index.blade.php ENDPATH**/ ?>