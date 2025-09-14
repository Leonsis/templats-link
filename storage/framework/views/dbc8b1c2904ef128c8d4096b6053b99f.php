

<?php $__env->startSection('title', 'Gerenciar Temas'); ?>

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-palette"></i> Gerenciar Temas</h1>
    </div>

    <!-- Formulário de Upload -->
    <div class="admin-card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-upload"></i> Instalar Novo Tema</h5>
        </div>
        <div class="card-body">
            <form action="<?php echo e(route('dashboard.temas.store')); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="nome_tema" class="form-label">Nome do Tema</label>
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
                                Use apenas letras, números, hífens e underscores
                            </small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="arquivo_zip" class="form-label">Arquivo ZIP dos Assets</label>
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
                                CSS, JS, imagens, etc. (Máx: 10MB)
                            </small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="arquivo_paginas" class="form-label">Arquivo ZIP das Páginas</label>
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
                                   required>
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
                                Templates Blade (Máx: 10MB)
                            </small>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-admin">
                    <i class="fas fa-upload"></i> Instalar Tema
                </button>
            </form>
        </div>
    </div>

    <!-- Lista de Temas Instalados -->
    <div class="admin-card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Temas Instalados</h5>
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
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $temas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tema): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <strong><?php echo e($tema['nome']); ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-info"><?php echo e($tema['arquivos']); ?> arquivo(s)</span>
                                    </td>
                                    <td>
                                        <?php if($tema['tem_paginas']): ?>
                                            <span class="badge bg-success"><?php echo e($tema['arquivos_paginas']); ?> página(s)</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Sem páginas</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($tema['tamanho']); ?></td>
                                    <td><?php echo e($tema['criado_em']); ?></td>
                                    <td>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger" 
                                                onclick="confirmarRemocao('<?php echo e($tema['nome']); ?>')">
                                            <i class="fas fa-trash"></i> Remover
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhum tema instalado</h5>
                    <p class="text-muted">Faça upload de um tema usando o formulário acima.</p>
                </div>
            <?php endif; ?>
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
function confirmarRemocao(nomeTema) {
    document.getElementById('temaNome').textContent = nomeTema;
    document.getElementById('deleteForm').action = '<?php echo e(route("dashboard.temas.destroy", ":nomeTema")); ?>'.replace(':nomeTema', nomeTema);
    
    var modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    modal.show();
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('dashboard.layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\templats-link\resources\views/dashboard/temas/index.blade.php ENDPATH**/ ?>