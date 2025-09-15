

<?php $__env->startSection('title', 'Configurações da Navbar/Footer'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-bars me-2"></i>
                    Configurações da Navbar/Footer
                </h1>
            </div>

            <!-- Informações do Tema Ativo -->
            <div class="alert alert-info mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-palette me-2"></i>
                    <div>
                        <strong>Tema Ativo:</strong> <?php echo e($temaAtivo); ?>

                        <small class="text-muted ms-2">As configurações serão aplicadas especificamente para este tema.</small>
                    </div>
                </div>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('dashboard.navbar.update')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>
                
                <!-- Seção Logo -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow border-primary">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-image me-2"></i>
                                    Logo Navbar
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Importante:</strong> Este logo será aplicado no navbar do site. 
                                    Apenas arquivos WebP, máximo 2MB.
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="logo_upload" class="form-label">
                                                <i class="fas fa-upload me-1"></i>
                                                Upload do Logo Navbar
                                            </label>
                                            <div class="input-group">
                                                <input type="file" 
                                                       class="form-control" 
                                                       id="logo_upload" 
                                                       name="logo" 
                                                       accept=".webp"
                                                       onchange="previewLogo(this)">
                                                <button type="button" 
                                                        class="btn btn-outline-secondary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#imageGalleryModal"
                                                        onclick="loadImageGallery()">
                                                    <i class="fas fa-images me-1"></i>
                                                    Ver Imagens
                                                </button>
                                            </div>
                                            <div class="form-text">
                                                <i class="fas fa-image me-1"></i>
                                                Apenas arquivos WebP, máximo 2MB
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                <i class="fas fa-eye me-1"></i>
                                                Preview do Logo Navbar
                                            </label>
                                            <div class="logo-preview border rounded p-3 text-center" style="min-height: 120px;">
                                                <?php if($navbarConfigs->logo): ?>
                                                    <img src="<?php echo e(\App\Helpers\HeadHelper::getLogo()); ?>" 
                                                         alt="Logo atual" 
                                                         class="logo-current" 
                                                         style="max-height: 80px;">
                                                    <div class="mt-2">
                                                        <small class="text-muted">Logo atual</small>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="text-muted">
                                                        <i class="fas fa-image fa-3x mb-2"></i>
                                                        <div>Nenhum logo configurado</div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botão Salvar Logo -->
                                <div class="d-grid mt-3">
                                    <button type="submit" class="btn btn-primary btn-lg" name="save_logo">
                                        <i class="fas fa-save me-2"></i>
                                        Salvar Logo Navbar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seção Logo Footer -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow border-success">
                            <div class="card-header bg-success text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-image me-2"></i>
                                    Logo Footer
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Importante:</strong> Este logo será aplicado no footer do site. 
                                    Apenas arquivos WebP, máximo 2MB.
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="logo_footer_upload" class="form-label">
                                                <i class="fas fa-upload me-1"></i>
                                                Upload do Logo Footer
                                            </label>
                                            <div class="input-group">
                                                <input type="file" 
                                                       class="form-control" 
                                                       id="logo_footer_upload" 
                                                       name="logo_footer" 
                                                       accept=".webp"
                                                       onchange="previewLogoFooter(this)">
                                                <button type="button" 
                                                        class="btn btn-outline-secondary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#imageGalleryModal"
                                                        onclick="loadImageGallery()">
                                                    <i class="fas fa-images me-1"></i>
                                                    Ver Imagens
                                                </button>
                                            </div>
                                            <div class="form-text">
                                                <i class="fas fa-image me-1"></i>
                                                Apenas arquivos WebP, máximo 2MB
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                <i class="fas fa-eye me-1"></i>
                                                Preview do Logo Footer
                                            </label>
                                            <div class="logo-footer-preview border rounded p-3 text-center" style="min-height: 120px;">
                                                <?php if($navbarConfigs->logo_footer): ?>
                                                    <img src="<?php echo e(\App\Helpers\HeadHelper::getLogoFooter()); ?>" 
                                                         alt="Logo Footer atual" 
                                                         class="logo-footer-current" 
                                                         style="max-height: 80px;">
                                                    <div class="mt-2">
                                                        <small class="text-muted">Logo Footer atual</small>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="text-muted">
                                                        <i class="fas fa-image fa-3x mb-2"></i>
                                                        <div>Nenhum logo footer configurado</div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botão Salvar Logo Footer -->
                                <div class="d-grid mt-3">
                                    <button type="submit" class="btn btn-success btn-lg" name="save_logo_footer">
                                        <i class="fas fa-save me-2"></i>
                                        Salvar Logo Footer
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seção Dados do Footer -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow border-secondary">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-align-left me-2"></i>
                                    Dados do Footer
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Importante:</strong> Estes dados aparecerão no footer do site, abaixo do logo footer.
                                </div>
                                
                                <div class="mb-3">
                                    <label for="descricao_footer" class="form-label">
                                        <i class="fas fa-align-left me-1"></i>
                                        Descrição do Footer
                                    </label>
                                    <textarea class="form-control" 
                                              id="descricao_footer" 
                                              name="descricao_footer" 
                                              rows="4"
                                              placeholder="Digite uma descrição sobre sua empresa, serviços ou missão que aparecerá no footer do site..."><?php echo e($navbarConfigs->descricao_footer ?? ''); ?></textarea>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Esta descrição será exibida no footer do site. Use para destacar informações importantes sobre sua empresa.
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="copyright_footer" class="form-label">
                                        <i class="fas fa-copyright me-1"></i>
                                        Texto de Copyright
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="copyright_footer" 
                                           name="copyright_footer" 
                                           value="<?php echo e($navbarConfigs->copyright_footer ?? ''); ?>"
                                           placeholder="© 2025 Templats-link. Todos os direitos reservados.">
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Este texto aparecerá no final do footer. Use {ano} para o ano atual ser inserido automaticamente.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seção Informações de Contato -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow border-info">
                            <div class="card-header bg-info text-white">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-address-book me-2"></i>
                                    Informações de Contato
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email_contato" class="form-label">
                                                <i class="fas fa-envelope me-1"></i>
                                                Email de Contato
                                            </label>
                                            <input type="email" 
                                                   class="form-control" 
                                                   id="email_contato" 
                                                   name="email_contato" 
                                                   value="<?php echo e($navbarConfigs->email_contato ?? ''); ?>"
                                                   placeholder="contato@empresa.com">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="telefone" class="form-label">
                                                <i class="fas fa-phone me-1"></i>
                                                Telefone
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="telefone" 
                                                   name="telefone" 
                                                   value="<?php echo e($navbarConfigs->telefone ?? ''); ?>"
                                                   placeholder="(11) 99999-9999">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="whatsapp" class="form-label">
                                                <i class="fab fa-whatsapp me-1"></i>
                                                WhatsApp
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="whatsapp" 
                                                   name="whatsapp" 
                                                   value="<?php echo e($navbarConfigs->whatsapp ?? ''); ?>"
                                                   placeholder="(11) 99999-9999">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="horario_atendimento" class="form-label">
                                                <i class="fas fa-clock me-1"></i>
                                                Horário de Atendimento
                                            </label>
                                            <input type="text" 
                                                   class="form-control" 
                                                   id="horario_atendimento" 
                                                   name="horario_atendimento" 
                                                   value="<?php echo e($navbarConfigs->horario_atendimento ?? ''); ?>"
                                                   placeholder="Segunda a Sexta: 8h às 18h">
                                        </div>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="endereco" class="form-label">
                                                <i class="fas fa-map-marker-alt me-1"></i>
                                                Endereço
                                            </label>
                                            <textarea class="form-control" 
                                                      id="endereco" 
                                                      name="endereco" 
                                                      rows="3"
                                                      placeholder="Rua, Número, Bairro, Cidade - Estado, CEP"><?php echo e($navbarConfigs->endereco ?? ''); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Seção Redes Sociais -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-share-alt me-2"></i>
                                    Redes Sociais
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="facebook" class="form-label">
                                                <i class="fab fa-facebook me-1"></i>
                                                Facebook
                                            </label>
                                            <input type="url" 
                                                   class="form-control" 
                                                   id="facebook" 
                                                   name="facebook" 
                                                   value="<?php echo e($navbarConfigs->facebook ?? ''); ?>"
                                                   placeholder="https://facebook.com/empresa">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="instagram" class="form-label">
                                                <i class="fab fa-instagram me-1"></i>
                                                Instagram
                                            </label>
                                            <input type="url" 
                                                   class="form-control" 
                                                   id="instagram" 
                                                   name="instagram" 
                                                   value="<?php echo e($navbarConfigs->instagram ?? ''); ?>"
                                                   placeholder="https://instagram.com/empresa">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="twitter" class="form-label">
                                                <i class="fab fa-twitter me-1"></i>
                                                Twitter
                                            </label>
                                            <input type="url" 
                                                   class="form-control" 
                                                   id="twitter" 
                                                   name="twitter" 
                                                   value="<?php echo e($navbarConfigs->twitter ?? ''); ?>"
                                                   placeholder="https://twitter.com/empresa">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="linkedin" class="form-label">
                                                <i class="fab fa-linkedin me-1"></i>
                                                LinkedIn
                                            </label>
                                            <input type="url" 
                                                   class="form-control" 
                                                   id="linkedin" 
                                                   name="linkedin" 
                                                   value="<?php echo e($navbarConfigs->linkedin ?? ''); ?>"
                                                   placeholder="https://linkedin.com/company/empresa">
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="youtube" class="form-label">
                                                <i class="fab fa-youtube me-1"></i>
                                                YouTube
                                            </label>
                                            <input type="url" 
                                                   class="form-control" 
                                                   id="youtube" 
                                                   name="youtube" 
                                                   value="<?php echo e($navbarConfigs->youtube ?? ''); ?>"
                                                   placeholder="https://youtube.com/c/empresa">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botão Salvar -->
                <div class="row">
                    <div class="col-12">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-2"></i>
                                Salvar Configurações da Navbar/Footer
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Informações Adicionais -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-success">
                        <div class="card-header bg-success text-white">
                            <h6 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                Informações Importantes
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6><i class="fas fa-image text-primary me-2"></i>Logo:</h6>
                                    <ul class="list-unstyled">
                                        <li>• Formatos aceitos: WebP, PNG, JPG, JPEG</li>
                                        <li>• Tamanho máximo: 2MB</li>
                                        <li>• Recomendado: PNG com fundo transparente</li>
                                        <li>• Dimensões ideais: 200x80px</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6><i class="fas fa-share-alt text-warning me-2"></i>Redes Sociais:</h6>
                                    <ul class="list-unstyled">
                                        <li>• Use URLs completas (https://)</li>
                                        <li>• Campos opcionais</li>
                                        <li>• Aparecerão na navbar do site</li>
                                        <li>• Ícones automáticos</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal da Galeria de Imagens -->
<div class="modal fade" id="imageGalleryModal" tabindex="-1" aria-labelledby="imageGalleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageGalleryModalLabel">
                    <i class="fas fa-images me-2"></i>
                    Galeria de Imagens
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   id="imageSearch" 
                                   placeholder="Buscar imagens..."
                                   onkeyup="filterImages()">
                        </div>
                    </div>
                </div>
                
                <div id="imageGallery" class="row">
                    <!-- Imagens serão carregadas aqui -->
                </div>
                
                <div id="imageGalleryLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                    <p class="mt-2">Carregando imagens...</p>
                </div>
                
                <div id="imageGalleryEmpty" class="text-center py-4" style="display: none;">
                    <i class="fas fa-images fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhuma imagem encontrada</h5>
                    <p class="text-muted">Faça upload de imagens para vê-las aqui.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let allImages = [];

// Função para preview do logo
function previewLogo(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.querySelector('.logo-preview');
            preview.innerHTML = `
                <img src="${e.target.result}" 
                     alt="Preview do logo" 
                     style="max-height: 80px;">
                <div class="mt-2">
                    <small class="text-success">Preview do novo logo</small>
                </div>
            `;
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Função para preview do logo footer
function previewLogoFooter(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.querySelector('.logo-footer-preview');
            preview.innerHTML = `
                <img src="${e.target.result}" 
                     alt="Preview do logo footer" 
                     style="max-height: 80px;">
                <div class="mt-2">
                    <small class="text-success">Preview do novo logo footer</small>
                </div>
            `;
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Função para carregar a galeria de imagens
function loadImageGallery() {
    const gallery = document.getElementById('imageGallery');
    const loading = document.getElementById('imageGalleryLoading');
    const empty = document.getElementById('imageGalleryEmpty');
    
    loading.style.display = 'block';
    gallery.innerHTML = '';
    empty.style.display = 'none';
    
    fetch('<?php echo e(route("dashboard.navbar.images")); ?>')
        .then(response => response.json())
        .then(data => {
            loading.style.display = 'none';
            allImages = data;
            
            if (data.length === 0) {
                empty.style.display = 'block';
            } else {
                displayImages(data);
            }
        })
        .catch(error => {
            loading.style.display = 'none';
            gallery.innerHTML = `
                <div class="col-12">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Erro ao carregar imagens: ${error.message}
                    </div>
                </div>
            `;
        });
}

// Função para exibir as imagens
function displayImages(images) {
    const gallery = document.getElementById('imageGallery');
    
    if (images.length === 0) {
        document.getElementById('imageGalleryEmpty').style.display = 'block';
        return;
    }
    
    gallery.innerHTML = images.map(image => `
        <div class="col-md-3 col-sm-4 col-6 mb-3 image-item" data-type="${image.type}" data-filename="${image.filename}">
            <div class="card h-100">
                <div class="card-img-top" style="height: 150px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                    <img src="${image.url}" 
                         alt="${image.filename}" 
                         style="max-width: 100%; max-height: 100%; object-fit: contain;"
                         onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwIiBoZWlnaHQ9IjEwMCIgZmlsbD0iI2Y4ZjlmYSIvPjx0ZXh0IHg9IjUwIiB5PSI1MCIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjEyIiBmaWxsPSIjNmM3NTdkIiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+RXJybyBubyBjYXJyZWdhbWVudG88L3RleHQ+PC9zdmc+'">
                </div>
                <div class="card-body p-2">
                    <h6 class="card-title text-truncate" title="${image.filename}">${image.filename}</h6>
                    <p class="card-text small text-muted">
                        ${formatFileSize(image.size)}
                        <br>
                        ${formatDate(image.modified)}
                    </p>
                    <button type="button" 
                            class="btn btn-sm btn-outline-primary w-100" 
                            onclick="selectImage('${image.filename}', '${image.url}')">
                        <i class="fas fa-check me-1"></i>
                        Selecionar
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Função para filtrar imagens
function filterImages() {
    const searchTerm = document.getElementById('imageSearch').value.toLowerCase();
    
    let filteredImages = allImages;
    
    if (searchTerm) {
        filteredImages = filteredImages.filter(image => 
            image.filename.toLowerCase().includes(searchTerm)
        );
    }
    
    displayImages(filteredImages);
}

// Função para selecionar uma imagem
function selectImage(filename, url) {
    // Atualizar o preview
    const preview = document.querySelector('.logo-preview');
    preview.innerHTML = `
        <img src="${url}" 
             alt="Logo selecionado" 
             style="max-height: 80px;">
        <div class="mt-2">
            <small class="text-success">Logo selecionado: ${filename}</small>
        </div>
    `;
    
    // Limpar o input de upload
    const fileInput = document.getElementById('logo_upload');
    fileInput.value = '';
    
    // Adicionar campo hidden para o filename
    let hiddenInput = document.getElementById('logo_filename_hidden');
    if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'logo_filename';
        hiddenInput.id = 'logo_filename_hidden';
        fileInput.parentNode.appendChild(hiddenInput);
    }
    hiddenInput.value = filename;
    
    // Fechar o modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('imageGalleryModal'));
    modal.hide();
    
    // Mostrar mensagem de sucesso
    showAlert('success', `Logo "${filename}" selecionado com sucesso!`);
}

// Função para formatar tamanho do arquivo
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// Função para formatar data
function formatDate(timestamp) {
    const date = new Date(timestamp * 1000);
    return date.toLocaleDateString('pt-BR') + ' ' + date.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
}

// Função para mostrar alertas
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.container-fluid');
    container.insertBefore(alertDiv, container.firstChild);
    
    // Auto-remover após 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

document.addEventListener('DOMContentLoaded', function() {
    // Máscara para telefone
    const telefoneInputs = document.querySelectorAll('input[name="telefone"], input[name="whatsapp"]');
    telefoneInputs.forEach(input => {
        input.addEventListener('input', function(e) {
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
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\templats-link\resources\views/dashboard/navbar/index.blade.php ENDPATH**/ ?>