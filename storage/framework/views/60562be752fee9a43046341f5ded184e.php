

<?php $__env->startSection('title', 'Preview do Tema: ' . $dadosTema['nome']); ?>

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-eye"></i> Preview do Tema: <?php echo e($dadosTema['nome']); ?></h1>
        <a href="<?php echo e(route('dashboard.temas')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <!-- Navegação entre páginas -->
    <div class="admin-card mb-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Páginas Disponíveis</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <?php $__currentLoopData = $dadosTema['paginas_disponiveis']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pagina): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <div class="card-body text-center">
                                <h6 class="card-title"><?php echo e(ucfirst($pagina)); ?></h6>
                                <a href="<?php echo e(route('dashboard.temas.preview.page', [$dadosTema['nome'], $pagina])); ?>" 
                                   class="btn btn-primary btn-sm" 
                                   target="_blank">
                                    <i class="fas fa-eye"></i> Visualizar
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>

    <!-- Preview da página principal -->
    <div class="admin-card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-desktop"></i> Preview: <?php echo e(ucfirst($dadosTema['pagina_principal'])); ?>

            </h5>
        </div>
        <div class="card-body p-0">
            <div class="preview-container">
                <iframe src="<?php echo e(route('dashboard.temas.preview.page', [$dadosTema['nome'], $dadosTema['pagina_principal']])); ?>" 
                        width="100%" 
                        height="800px" 
                        frameborder="0"
                        style="border: 1px solid #ddd; border-radius: 5px;">
                </iframe>
            </div>
        </div>
    </div>

    <!-- Informações do tema -->
    <div class="admin-card mt-4">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-info-circle"></i> Informações do Tema</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nome do Tema:</strong> <?php echo e($dadosTema['nome']); ?></p>
                    <p><strong>Páginas Disponíveis:</strong> <?php echo e(count($dadosTema['paginas_disponiveis'])); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Caminho dos Assets:</strong> <?php echo e($dadosTema['assets_path']); ?></p>
                    <p><strong>Página Principal:</strong> <?php echo e($dadosTema['pagina_principal']); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.preview-container {
    position: relative;
    background: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
}

.preview-container iframe {
    background: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .preview-container iframe {
        height: 600px;
    }
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\templats-link\resources\views/dashboard/temas/preview.blade.php ENDPATH**/ ?>