

<?php $__env->startSection('title', 'Gerenciar Temas'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div class="flex-grow-1">
            <h1 class="mb-2"><i class="fas fa-palette text-primary"></i> Gerenciar Temas</h1>
            <p class="text-muted mb-0">Instale, configure e gerencie os temas do seu site</p>
        </div>
        <div class="d-flex gap-2 flex-shrink-0">
            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#helpModal">
                <i class="fas fa-question-circle me-2"></i><span class="d-none d-sm-inline">Ajuda</span>
            </button>
        </div>
    </div>

    <!-- Formulário de Upload -->
    <div class="admin-card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-upload text-primary"></i> Instalar Novo Tema</h5>
            <small class="text-muted">Faça upload dos arquivos do tema para instalá-lo no sistema</small>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('dashboard.temas.store')); ?>" method="POST" enctype="multipart/form-data" id="themeForm">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="nome_tema" class="form-label">
                                <i class="fas fa-tag me-1"></i>Nome do Tema
                            </label>
                            <input type="text" 
                                   class="form-control <?php $__errorArgs = ['nome_tema'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="nome_tema" 
                                   name="nome_tema" 
                                   value="<?php echo e(old('nome_tema')); ?>"
                                   placeholder="Ex: tema-azul, meu-tema, etc."
                                   required>
                            <?php $__errorArgs = ['nome_tema'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>Use apenas letras, números, hífens e underscores
                            </small>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="arquivo_zip" class="form-label">
                                <i class="fas fa-file-archive me-1"></i>Arquivo ZIP dos Assets
                                <i class="fas fa-info-circle text-info ms-2" 
                                   data-bs-toggle="tooltip" 
                                   data-bs-placement="top" 
                                   data-bs-html="true"
                                   title="<strong>Como preparar o ZIP dos Assets:</strong><br>
                                   1. Crie uma pasta chamada 'assets'<br>
                                   2. Dentro da pasta 'assets', coloque as pastas:<br>
                                   &nbsp;&nbsp;• css (arquivos CSS)<br>
                                   &nbsp;&nbsp;• js (arquivos JavaScript)<br>
                                   &nbsp;&nbsp;• images (imagens e ícones)<br>
                                   3. Verifique se todos os caminhos dos links e imagens estão corretos<br>
                                   4. Comprima tudo em um arquivo ZIP"></i>
                            </label>
                            <input type="file" 
                                   class="form-control <?php $__errorArgs = ['arquivo_zip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="arquivo_zip" 
                                   name="arquivo_zip" 
                                   accept=".zip"
                                   required>
                            <?php $__errorArgs = ['arquivo_zip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>CSS, JS, imagens, etc. (Máx: 10MB)
                            </small>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="mb-3">
                            <label for="arquivo_paginas" class="form-label">
                                <i class="fas fa-file-code me-1"></i>Arquivo ZIP das Páginas
                                <i class="fas fa-info-circle text-info ms-2" 
                                   data-bs-toggle="tooltip" 
                                   data-bs-placement="top" 
                                   data-bs-html="true"
                                   title="<strong>Como preparar o ZIP das Páginas:</strong><br>
                                   As páginas têm que ficar somente o conteúdo delas.<br>
                                   <br>
                                   <strong>Estrutura recomendada:</strong><br>
                                   • home.html<br>
                                   • sobre.html<br>
                                   • contato.html<br>
                                   • etc..."></i>
                            </label>
                            <input type="file" 
                                   class="form-control <?php $__errorArgs = ['arquivo_paginas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                   id="arquivo_paginas" 
                                   name="arquivo_paginas"
                                   accept=".zip">
                            <?php $__errorArgs = ['arquivo_paginas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                Páginas HTML/Blade (Máx: 10MB)
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Campos para código dos arquivos Blade -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="codigo_head" class="form-label">Código do head.blade.php</label>
                            <textarea class="form-control <?php $__errorArgs = ['codigo_head'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="codigo_head" 
                                      name="codigo_head" 
                                      rows="8" 
                                      placeholder="Cole aqui o código do arquivo head.blade.php"><?php echo e(old('codigo_head')); ?></textarea>
                            <?php $__errorArgs = ['codigo_head'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                Código HTML para o cabeçalho (meta tags, CSS, etc.)
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="codigo_nav" class="form-label">Código do nav.blade.php</label>
                            <textarea class="form-control <?php $__errorArgs = ['codigo_nav'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="codigo_nav" 
                                      name="codigo_nav" 
                                      rows="8" 
                                      placeholder="Cole aqui o código do arquivo nav.blade.php"><?php echo e(old('codigo_nav')); ?></textarea>
                            <?php $__errorArgs = ['codigo_nav'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                Código HTML para a navegação
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="codigo_footer" class="form-label">Código do footer.blade.php</label>
                            <textarea class="form-control <?php $__errorArgs = ['codigo_footer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="codigo_footer" 
                                      name="codigo_footer" 
                                      rows="8" 
                                      placeholder="Cole aqui o código do arquivo footer.blade.php"><?php echo e(old('codigo_footer')); ?></textarea>
                            <?php $__errorArgs = ['codigo_footer'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                Código HTML para o rodapé
                            </small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="codigo_scripts" class="form-label">Código do scripts.blade.php</label>
                            <textarea class="form-control <?php $__errorArgs = ['codigo_scripts'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      id="codigo_scripts" 
                                      name="codigo_scripts" 
                                      rows="8" 
                                      placeholder="Cole aqui o código do arquivo scripts.blade.php"><?php echo e(old('codigo_scripts')); ?></textarea>
                            <?php $__errorArgs = ['codigo_scripts'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text text-muted">
                                Código JavaScript e scripts
                            </small>
                        </div>
                    </div>
                </div>
                
                <!-- Nova seção para páginas com HTML diferente -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-primary">
                            <div class="card-header bg-primary">
                                <h6 class="mb-0">
                                    <i class="fas fa-code me-2"></i>Páginas com HTML Diferente
                                </h6>
                                <small>Configure páginas que possuem tags HTML específicas</small>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="tem_paginas_html_diferente" name="tem_paginas_html_diferente" value="1">
                                        <label class="form-check-label" for="tem_paginas_html_diferente">
                                            <i class="fas fa-check-square me-1"></i>Este tema possui páginas com tags HTML diferentes
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>Marque esta opção se algumas páginas do tema precisam de HTML específico
                                    </small>
                                </div>
                                
                                <!-- Campos dinâmicos (inicialmente ocultos) -->
                                <div id="campos_paginas_html" style="display: none;">
                                    <div class="mb-3">
                                        <label for="numero_paginas_html" class="form-label">
                                            <i class="fas fa-list-ol me-1"></i>Número de páginas com HTML diferente
                                        </label>
                                        <select class="form-control" id="numero_paginas_html" name="numero_paginas_html">
                                            <option value="">Selecione o número de páginas</option>
                                            <option value="1">1 página</option>
                                            <option value="2">2 páginas</option>
                                            <option value="3">3 páginas</option>
                                            <option value="4">4 páginas</option>
                                            <option value="5">5 páginas</option>
                                            <option value="6">6 páginas</option>
                                            <option value="7">7 páginas</option>
                                            <option value="8">8 páginas</option>
                                            <option value="9">9 páginas</option>
                                            <option value="10">10 páginas</option>
                                        </select>
                                    </div>
                                    
                                    <!-- Container para os campos dinâmicos -->
                                    <div id="container_paginas_html">
                                        <!-- Os campos serão inseridos aqui via JavaScript -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                        <i class="fas fa-undo me-2"></i>Limpar
                    </button>
                    <button type="submit" class="btn btn-primary btn-admin" id="submitBtn">
                        <i class="fas fa-upload me-2"></i>Instalar Tema
                    </button>
                </div>
            </form>
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
                        <p class="text-muted mb-4">Faça upload de um tema usando o formulário acima para começar.</p>
                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#helpModal">
                            <i class="fas fa-question-circle me-2"></i>Como instalar um tema?
                        </button>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal de Ajuda -->
<div class="modal fade" id="helpModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-question-circle text-primary me-2"></i>Como Instalar um Tema</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-file-archive text-info me-2"></i>Arquivo ZIP dos Assets</h6>
                        <p class="text-muted">Este arquivo deve conter:</p>
                        <ul class="text-muted">
                            <li>Arquivos CSS</li>
                            <li>Arquivos JavaScript</li>
                            <li>Imagens e ícones</li>
                            <li>Fontes</li>
                            <li>Outros recursos estáticos</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-file-code text-success me-2"></i>Arquivo ZIP das Páginas</h6>
                        <p class="text-muted">Este arquivo deve conter:</p>
                        <ul class="text-muted">
                            <li>Arquivos HTML/Blade</li>
                            <li>Páginas como home, sobre, contato</li>
                            <li>Estrutura de pastas organizada</li>
                        </ul>
                    </div>
                </div>
                <hr>
                <div class="alert alert-info">
                    <h6><i class="fas fa-lightbulb me-2"></i>Dica Importante</h6>
                    <p class="mb-0">Após a instalação, o sistema automaticamente linkará os formulários de configuração ao tema, permitindo personalizar logos, cores e textos diretamente do painel administrativo.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Entendi</button>
            </div>
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
// Função para resetar o formulário
function resetForm() {
    document.getElementById('themeForm').reset();
    // Limpar previews de arquivos se existirem
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.value = '';
    });
    // Resetar campos dinâmicos
    resetarCamposDinamicos();
}

// Função para resetar campos dinâmicos
function resetarCamposDinamicos() {
    document.getElementById('campos_paginas_html').style.display = 'none';
    document.getElementById('container_paginas_html').innerHTML = '';
    document.getElementById('numero_paginas_html').value = '';
}

// Função para mostrar/ocultar campos dinâmicos
function toggleCamposPaginasHtml() {
    const checkbox = document.getElementById('tem_paginas_html_diferente');
    const camposDiv = document.getElementById('campos_paginas_html');
    
    if (checkbox.checked) {
        camposDiv.style.display = 'block';
    } else {
        camposDiv.style.display = 'none';
        resetarCamposDinamicos();
    }
}

// Função para criar campos dinâmicos
function criarCamposPaginasHtml(numeroPaginas) {
    const container = document.getElementById('container_paginas_html');
    container.innerHTML = '';
    
    for (let i = 1; i <= numeroPaginas; i++) {
        const div = document.createElement('div');
        div.className = 'card mb-3 border-secondary';
        div.innerHTML = `
            <div class="card-header bg-light">
                <h6 class="mb-0">
                    <i class="fas fa-file-code me-2"></i>Página ${i}
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="nome_pagina_${i}" class="form-label">
                                <i class="fas fa-tag me-1"></i>Nome da Página
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="nome_pagina_${i}" 
                                   name="nome_pagina_${i}" 
                                   placeholder="Ex: home, sobre, contato"
                                   required>
                            <small class="form-text text-muted">
                                Nome da página (sem extensão)
                            </small>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="codigo_html_${i}" class="form-label">
                                <i class="fas fa-code me-1"></i>Código HTML da Página
                            </label>
                            <textarea class="form-control" 
                                      id="codigo_html_${i}" 
                                      name="codigo_html_${i}" 
                                      rows="8" 
                                      placeholder="Cole aqui o código HTML específico desta página"
                                      required></textarea>
                            <small class="form-text text-muted">
                                Código HTML completo da página
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(div);
    }
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
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (response.ok) {
            return response.text();
        }
        throw new Error('Erro na requisição: ' + response.status);
    })
    .then(data => {
        // Sucesso - recarregar a página para mostrar mudanças
        window.location.reload();
    })
    .catch(error => {
        console.error('Erro ao selecionar tema:', error);
        alert('Erro ao selecionar o tema. Verifique o console para mais detalhes.');
        
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
    
    // Event listeners para campos dinâmicos
    const checkboxHtmlDiferente = document.getElementById('tem_paginas_html_diferente');
    const selectNumeroPaginas = document.getElementById('numero_paginas_html');
    
    if (checkboxHtmlDiferente) {
        checkboxHtmlDiferente.addEventListener('change', toggleCamposPaginasHtml);
    }
    
    if (selectNumeroPaginas) {
        selectNumeroPaginas.addEventListener('change', function() {
            const numeroPaginas = parseInt(this.value);
            if (numeroPaginas > 0) {
                criarCamposPaginasHtml(numeroPaginas);
            } else {
                document.getElementById('container_paginas_html').innerHTML = '';
            }
        });
    }
    
    // Adicionar loading ao formulário
    const form = document.getElementById('themeForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Instalando...';
            submitBtn.disabled = true;
        });
    }
    
    // Adicionar drag and drop para arquivos
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const label = this.previousElementSibling;
                if (label && label.classList.contains('form-label')) {
                    const icon = label.querySelector('i');
                    if (icon) {
                        icon.className = 'fas fa-check-circle text-success me-1';
                    }
                }
            }
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

<?php echo $__env->make('dashboard.layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\templats-link\resources\views/dashboard/temas/index.blade.php ENDPATH**/ ?>