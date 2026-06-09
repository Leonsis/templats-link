<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'titulo',
        'autor',
        'slug',
        'imagem_apresentacao',
        'conteudo',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot do modelo para gerar slug automaticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->titulo);
            }
        });

        static::updating(function ($post) {
            if ($post->isDirty('titulo') && empty($post->slug)) {
                $post->slug = Str::slug($post->titulo);
            }
        });
    }

    /**
     * Scope para posts ativos
     */
    public function scopeAtivo($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Scope para posts inativos
     */
    public function scopeInativo($query)
    {
        return $query->where('ativo', false);
    }

    /**
     * Verificar se o post está ativo
     */
    public function isAtivo()
    {
        return $this->ativo;
    }

    /**
     * Obter URL da imagem de apresentação
     */
    public function getImagemUrlAttribute()
    {
        if ($this->imagem_apresentacao) {
            return route('blog.image', ['filename' => $this->imagem_apresentacao]);
        }
        return null;
    }

    /**
     * Obter resumo do conteúdo (primeiros 150 caracteres)
     */
    public function getResumoAttribute()
    {
        return Str::limit(strip_tags($this->conteudo), 150);
    }
}
