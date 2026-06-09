@extends('dashboard.layouts.admin')

@section('title', 'Criar Nova Postagem')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">                
                <h4 class="page-title">Criar Nova Postagem</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Formulário de Nova Postagem</h5>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <h6>Erro ao criar postagem:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('dashboard.blog.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <!-- Meta Tags -->
                            <div class="col-12">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Meta Tags (SEO)</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="meta_title" class="form-label">Meta Title</label>
                                                    <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                                           id="meta_title" name="meta_title" value="{{ old('meta_title', '') }}"
                                                           placeholder="Título para SEO (máximo 255 caracteres)">
                                                    @error('meta_title')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                                    <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                                                           id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', '') }}"
                                                           placeholder="Palavras-chave separadas por vírgula">
                                                    @error('meta_keywords')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="meta_description" class="form-label">Meta Description</label>
                                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                                      id="meta_description" name="meta_description" rows="3"
                                                      placeholder="Descrição para SEO (máximo 500 caracteres)">{{ old('meta_description', '') }}</textarea>
                                            @error('meta_description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Conteúdo Principal -->
                            <div class="col-12">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Conteúdo da Postagem</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="titulo" class="form-label">Título da Postagem <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('titulo') is-invalid @enderror" 
                                                   id="titulo" name="titulo" value="{{ old('titulo') }}" required
                                                   placeholder="Digite o título da postagem">
                                            @error('titulo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="autor" class="form-label">Autor da Postagem <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('autor') is-invalid @enderror" 
                                                   id="autor" name="autor" value="{{ old('autor') }}" required
                                                   placeholder="Digite o nome do autor">
                                            @error('autor')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="slug" class="form-label">URL Personalizada (Slug)</label>
                                            <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                                   id="slug" name="slug" value="{{ old('slug') }}"
                                                   placeholder="Ex: como-participar-de-licitacoes (deixe vazio para gerar automaticamente)">
                                            <div class="form-text">
                                                <strong>Dica:</strong> Deixe vazio para gerar automaticamente baseado no título, ou digite uma URL personalizada.<br>
                                                <strong>Exemplo:</strong> "Como Participar de Licitações" → "como-participar-de-licitacoes"
                                            </div>
                                            @error('slug')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="imagem_apresentacao" class="form-label">Imagem de Apresentação</label>
                                            <input type="file" class="form-control @error('imagem_apresentacao') is-invalid @enderror" 
                                                   id="imagem_apresentacao" name="imagem_apresentacao" 
                                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                                            <div class="form-text">Formatos aceitos: JPEG, PNG, JPG, GIF, WEBP. Tamanho máximo: 10MB</div>
                                            @error('imagem_apresentacao')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="conteudo" class="form-label">Conteúdo da Postagem <span class="text-danger">*</span></label>
                                            
                                            <!-- Textarea com formatação básica -->
                                            <textarea class="form-control @error('conteudo') is-invalid @enderror" 
                                                      id="conteudo" name="conteudo" rows="15" required
                                                      placeholder="Digite o conteúdo da postagem. Você pode usar HTML básico como: &lt;b&gt;negrito&lt;/b&gt;, &lt;i&gt;itálico&lt;/i&gt;, &lt;ul&gt;&lt;li&gt;listas&lt;/li&gt;&lt;/ul&gt;, &lt;h2&gt;subtítulos&lt;/h2&gt;">{{ old('conteudo') }}</textarea>
                                            
                                            <div class="form-text">
                                                <strong>Dicas de formatação:</strong><br>
                                                • <code>&lt;b&gt;texto&lt;/b&gt;</code> para <b>negrito</b><br>
                                                • <code>&lt;i&gt;texto&lt;/i&gt;</code> para <i>itálico</i><br>
                                                • <code>&lt;h2&gt;título&lt;/h2&gt;</code> para subtítulos<br>
                                                • <code>&lt;ul&gt;&lt;li&gt;item&lt;/li&gt;&lt;/ul&gt;</code> para listas<br>
                                                • <code>&lt;p&gt;parágrafo&lt;/p&gt;</code> para parágrafos
                                            </div>
                                            @error('conteudo')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="ativo" name="ativo" value="1"
                                                       {{ old('ativo', true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="ativo">
                                                    Postagem ativa (visível no site)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <a href="{{ route('dashboard.blog') }}" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Voltar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Criar Postagem
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Preview da Imagem -->
<script>
document.getElementById('imagem_apresentacao').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Remover preview anterior se existir
            const existingPreview = document.getElementById('image-preview');
            if (existingPreview) {
                existingPreview.remove();
            }
            
            // Criar novo preview
            const preview = document.createElement('div');
            preview.id = 'image-preview';
            preview.className = 'mt-2';
            preview.innerHTML = `
                <label class="form-label">Preview:</label>
                <div class="border rounded p-2">
                    <img src="${e.target.result}" alt="Preview" class="img-fluid" style="max-height: 200px;">
                </div>
            `;
            
            document.getElementById('imagem_apresentacao').parentNode.appendChild(preview);
        };
        reader.readAsDataURL(file);
    }
});

// Gerar slug automaticamente baseado no título
document.getElementById('titulo').addEventListener('input', function(e) {
    const titulo = e.target.value;
    const slugField = document.getElementById('slug');
    
    // Só gerar slug se o campo slug estiver vazio
    if (!slugField.value.trim()) {
        const slug = titulo
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '') // Remove acentos
            .replace(/[^a-z0-9\s-]/g, '') // Remove caracteres especiais
            .replace(/\s+/g, '-') // Substitui espaços por hífens
            .replace(/-+/g, '-') // Remove hífens duplicados
            .trim('-'); // Remove hífens do início e fim
        
        slugField.value = slug;
    }
});

// Validação do formulário
document.querySelector('form').addEventListener('submit', function(e) {
    const titulo = document.getElementById('titulo').value;
    const autor = document.getElementById('autor').value;
    const conteudo = document.getElementById('conteudo').value;
    
    if (!titulo.trim()) {
        alert('O título é obrigatório!');
        e.preventDefault();
        return false;
    }
    
    if (!autor.trim()) {
        alert('O autor é obrigatório!');
        e.preventDefault();
        return false;
    }
    
    if (!conteudo.trim()) {
        alert('O conteúdo é obrigatório!');
        e.preventDefault();
        return false;
    }
});

</script>
@endsection
