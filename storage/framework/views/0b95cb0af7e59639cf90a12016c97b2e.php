

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
        <div class="stat-card danger">
            <div class="stat-number"><?php echo e($dados['dataAtual']); ?></div>
            <div class="stat-label">
                <i class="fas fa-calendar me-2"></i>
                Data Atual
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
                <div class="row">
                    <div class="col-sm-4">
                        <div class="text-center mb-3">
                            <div class="user-avatar mx-auto mb-2" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                <?php echo e(strtoupper(substr($dados['usuario']->nome, 0, 1))); ?>

                            </div>
                            <h6 class="mb-0"><?php echo e($dados['usuario']->nome); ?></h6>
                            <small class="text-muted"><?php echo e(ucfirst($dados['usuario']->tipo)); ?></small>
                        </div>
                    </div>
                    <div class="col-sm-8">
                        <table class="table table-borderless table-sm">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Atividade Recente -->
<div class="row">
    <div class="col-12">
        <div class="admin-card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Atividade Recente
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info border-0">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle fa-2x text-info"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="alert-heading mb-1">Bem-vindo ao sistema!</h6>
                            <p class="mb-0">
                                Você fez login com sucesso em <?php echo e($dados['dataAtual']); ?>.
                                <?php if($dados['isAdmin']): ?>
                                    Como administrador, você tem acesso completo ao sistema e pode gerenciar usuários e configurações.
                                <?php else: ?>
                                    Explore as funcionalidades disponíveis para o seu tipo de usuário.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-chart-bar fa-2x text-primary mb-2"></i>
                            <h6>Estatísticas</h6>
                            <p class="text-muted small">Visualize estatísticas do sistema</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center p-3 bg-light rounded">
                            <i class="fas fa-home fa-2x text-success mb-2"></i>
                            <h6>Site Principal</h6>
                            <p class="text-muted small">Acesse o site principal</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('dashboard.layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\templats-link\resources\views/dashboard/index.blade.php ENDPATH**/ ?>