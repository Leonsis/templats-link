

<?php $__env->startSection('title', 'Blog - Gerenciar Posts'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">Gerenciar Blog</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="card-title mb-0">Posts do Blog</h5>
                        </div>
                        <div class="col-auto">
                            <a href="<?php echo e(route('dashboard.blog.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Criar Postagem
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if($posts->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table blog table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Imagem</th>
                                        <th>Título</th>
                                        <th>Autor</th>
                                        <th>Status</th>
                                        <th>Data de Criação</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($post->id); ?></td>
                                            <td>
                                                <?php if($post->imagem_apresentacao): ?>
                                                    <img src="<?php echo e($post->imagem_url); ?>" alt="<?php echo e($post->titulo); ?>" 
                                                         class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong><?php echo e($post->titulo); ?></strong>
                                                    <?php if($post->meta_title): ?>
                                                        <br><small class="text-muted"><?php echo e(\Illuminate\Support\Str::limit($post->meta_title, 50)); ?></small>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?php echo e($post->autor ?: 'Não informado'); ?></span>
                                            </td>
                                            <td>
                                                <?php if($post->ativo): ?>
                                                    <span class="badge bg-success">Ativo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Inativo</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo e($post->created_at->format('d/m/Y H:i')); ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?php echo e(route('dashboard.blog.edit', $post)); ?>" class="btn btn-sm btn-outline-primary" title="Editar" style="border-radius: var(--radius-md) !important;">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    
                                                    <form action="<?php echo e(route('dashboard.blog.toggle-status', $post)); ?>" 
                                                          method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PATCH'); ?>
                                                        <button type="submit" 
                                                                class="btn btn-sm <?php echo e($post->ativo ? 'btn-outline-warning' : 'btn-outline-success'); ?>"
                                                                title="<?php echo e($post->ativo ? 'Desativar' : 'Ativar'); ?>"
                                                                onclick="return confirm('Tem certeza que deseja <?php echo e($post->ativo ? 'desativar' : 'ativar'); ?> este post?')">
                                                            <i class="fas <?php echo e($post->ativo ? 'fa-eye-slash' : 'fa-eye'); ?>"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    <form action="<?php echo e(route('dashboard.blog.destroy', $post)); ?>" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Tem certeza que deseja deletar este post? Esta ação não pode ser desfeita.')">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('DELETE'); ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Deletar">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <?php if($posts->hasPages()): ?>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted">
                                    Mostrando <?php echo e($posts->firstItem()); ?> a <?php echo e($posts->lastItem()); ?> de <?php echo e($posts->total()); ?> resultados
                                </div>
                                <nav aria-label="Paginação de posts">
                                    <ul class="pagination pagination-sm mb-0">
                                        
                                        <?php if($posts->onFirstPage()): ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">Anterior</span>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?php echo e($posts->previousPageUrl()); ?>" rel="prev">Anterior</a>
                                            </li>
                                        <?php endif; ?>

                                        
                                        <?php $__currentLoopData = $posts->getUrlRange(1, $posts->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($page == $posts->currentPage()): ?>
                                                <li class="page-item active">
                                                    <span class="page-link"><?php echo e($page); ?></span>
                                                </li>
                                            <?php else: ?>
                                                <li class="page-item">
                                                    <a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                                                </li>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        
                                        <?php if($posts->hasMorePages()): ?>
                                            <li class="page-item">
                                                <a class="page-link" href="<?php echo e($posts->nextPageUrl()); ?>" rel="next">Próximo</a>
                                            </li>
                                        <?php else: ?>
                                            <li class="page-item disabled">
                                                <span class="page-link">Próximo</span>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </nav>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-blog fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhum post encontrado</h5>
                            <p class="text-muted">Comece criando sua primeira postagem!</p>
                            <a href="<?php echo e(route('dashboard.blog.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Criar Primeira Postagem
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('dashboard.layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\Templats-link-templats-link\resources\views/dashboard/blog/index.blade.php ENDPATH**/ ?>