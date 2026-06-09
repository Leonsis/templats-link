<?php

namespace App\Helpers;

class ImageHelper
{
    /**
     * Gera srcset para imagens responsivas
     * 
     * @param string $imagePath
     * @param array $sizes
     * @return string
     */
    public static function generateSrcset($imagePath, $sizes = [320, 640, 768, 1024, 1280, 1920])
    {
        $basePath = public_path($imagePath);
        $baseDir = dirname($basePath);
        $baseName = pathinfo($basePath, PATHINFO_FILENAME);
        $extension = pathinfo($basePath, PATHINFO_EXTENSION);
        
        $srcset = [];
        
        foreach ($sizes as $size) {
            // Verificar se existe versão otimizada
            $optimizedPath = $baseDir . '/' . $baseName . '-' . $size . 'w.' . $extension;
            $optimizedUrl = str_replace(public_path(), '', $optimizedPath);
            
            if (file_exists($optimizedPath)) {
                $srcset[] = asset($optimizedUrl) . ' ' . $size . 'w';
            } else {
                // Se não existe versão otimizada, usar imagem original
                $srcset[] = asset($imagePath) . ' ' . $size . 'w';
            }
        }
        
        return implode(', ', $srcset);
    }
    
    /**
     * Gera atributo sizes para imagens responsivas
     * 
     * @param string $defaultSize
     * @return string
     */
    public static function generateSizes($defaultSize = '100vw')
    {
        return "(max-width: 640px) 100vw, (max-width: 1024px) 50vw, {$defaultSize}";
    }
    
    /**
     * Verifica se deve usar loading="lazy"
     * 
     * @param bool $aboveFold
     * @return string
     */
    public static function getLoadingAttribute($aboveFold = false)
    {
        return $aboveFold ? 'eager' : 'lazy';
    }
    
    /**
     * Gera atributos de imagem otimizada
     * 
     * @param string $imagePath
     * @param string $alt
     * @param bool $aboveFold
     * @param string $class
     * @param int|null $width
     * @param int|null $height
     * @return array
     */
    public static function getOptimizedImageAttributes($imagePath, $alt = '', $aboveFold = false, $class = '', $width = null, $height = null)
    {
        $attributes = [
            'src' => asset($imagePath),
            'alt' => $alt,
            'loading' => self::getLoadingAttribute($aboveFold),
            'class' => $class,
        ];
        
        // Adicionar srcset se disponível
        $srcset = self::generateSrcset($imagePath);
        if ($srcset) {
            $attributes['srcset'] = $srcset;
            $attributes['sizes'] = self::generateSizes();
        }
        
        // Adicionar dimensões se fornecidas
        if ($width) {
            $attributes['width'] = $width;
        }
        
        if ($height) {
            $attributes['height'] = $height;
        }
        
        return $attributes;
    }
    
    /**
     * Gera tag img otimizada
     * 
     * @param string $imagePath
     * @param string $alt
     * @param bool $aboveFold
     * @param string $class
     * @param int|null $width
     * @param int|null $height
     * @return string
     */
    public static function optimizedImage($imagePath, $alt = '', $aboveFold = false, $class = '', $width = null, $height = null)
    {
        $attributes = self::getOptimizedImageAttributes($imagePath, $alt, $aboveFold, $class, $width, $height);
        
        $html = '<img';
        foreach ($attributes as $key => $value) {
            if ($value !== null && $value !== '') {
                $html .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
            }
        }
        $html .= '>';
        
        return $html;
    }
    
    /**
     * Gera srcset para imagem com tamanhos específicos baseados nas dimensões exibidas
     * Usa densidades (1x, 1.5x, 2x) para dispositivos retina
     * 
     * @param string $imagePath
     * @param int $displayWidth Largura em que a imagem é exibida
     * @param int $displayHeight Altura em que a imagem é exibida
     * @return string
     */
    public static function generateSrcsetForDisplaySize($imagePath, $displayWidth, $displayHeight = null)
    {
        $basePath = public_path($imagePath);
        
        // Verificar se imagem existe
        if (!file_exists($basePath)) {
            return '';
        }
        
        // Obter dimensões reais da imagem
        $imageInfo = @getimagesize($basePath);
        if (!$imageInfo) {
            return '';
        }
        
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];
        
        // Se a imagem original é menor ou igual ao tamanho de exibição, não precisa de srcset
        if ($originalWidth <= $displayWidth) {
            return '';
        }
        
        // Calcular densidades baseadas no tamanho de exibição
        // Para imagens grandes, usar densidades menores para evitar download desnecessário
        $maxDensity = min(2, floor($originalWidth / $displayWidth));
        
        $srcset = [];
        $densities = [1];
        
        if ($maxDensity >= 1.5) {
            $densities[] = 1.5;
        }
        if ($maxDensity >= 2) {
            $densities[] = 2;
        }
        
        // Gerar srcset com densidades
        foreach ($densities as $density) {
            $srcset[] = asset($imagePath) . ' ' . $density . 'x';
        }
        
        return implode(', ', $srcset);
    }
    
    /**
     * Otimiza imagem WebP aumentando compactação
     * Requer extensão GD ou Imagick
     * 
     * @param string $sourcePath
     * @param string $destinationPath
     * @param int $quality Qualidade (0-100, menor = mais compactação)
     * @return bool
     */
    public static function optimizeWebP($sourcePath, $destinationPath = null, $quality = 75)
    {
        if (!file_exists($sourcePath)) {
            return false;
        }
        
        $destinationPath = $destinationPath ?? $sourcePath;
        $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
        
        // Verificar se é WebP
        if ($extension !== 'webp') {
            return false;
        }
        
        // Verificar se GD está disponível
        if (!function_exists('imagecreatefromwebp')) {
            return false;
        }
        
        try {
            // Ler imagem WebP
            $image = @imagecreatefromwebp($sourcePath);
            if (!$image) {
                return false;
            }
            
            // Criar diretório de destino se não existir
            $destDir = dirname($destinationPath);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            
            // Salvar com qualidade otimizada
            $result = imagewebp($image, $destinationPath, $quality);
            imagedestroy($image);
            
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    /**
     * Redimensiona imagem mantendo aspect ratio
     * 
     * @param string $sourcePath
     * @param string $destinationPath
     * @param int $maxWidth
     * @param int $maxHeight
     * @param int $quality
     * @return bool
     */
    public static function resizeImage($sourcePath, $destinationPath, $maxWidth, $maxHeight = null, $quality = 85)
    {
        if (!file_exists($sourcePath)) {
            return false;
        }
        
        if (!function_exists('imagecreatefromwebp')) {
            return false;
        }
        
        try {
            $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
            
            // Ler imagem baseado na extensão
            switch ($extension) {
                case 'webp':
                    $image = @imagecreatefromwebp($sourcePath);
                    break;
                case 'jpg':
                case 'jpeg':
                    $image = @imagecreatefromjpeg($sourcePath);
                    break;
                case 'png':
                    $image = @imagecreatefrompng($sourcePath);
                    break;
                default:
                    return false;
            }
            
            if (!$image) {
                return false;
            }
            
            $originalWidth = imagesx($image);
            $originalHeight = imagesy($image);
            
            // Calcular novas dimensões mantendo aspect ratio
            $maxHeight = $maxHeight ?? ($originalHeight * $maxWidth / $originalWidth);
            
            $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);
            $newWidth = (int)($originalWidth * $ratio);
            $newHeight = (int)($originalHeight * $ratio);
            
            // Criar nova imagem redimensionada
            $resized = imagecreatetruecolor($newWidth, $newHeight);
            
            // Preservar transparência para PNG
            if ($extension === 'png') {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }
            
            // Redimensionar
            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $originalWidth, $originalHeight);
            
            // Criar diretório de destino
            $destDir = dirname($destinationPath);
            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }
            
            // Salvar imagem
            $result = false;
            switch ($extension) {
                case 'webp':
                    $result = imagewebp($resized, $destinationPath, $quality);
                    break;
                case 'jpg':
                case 'jpeg':
                    $result = imagejpeg($resized, $destinationPath, $quality);
                    break;
                case 'png':
                    $result = imagepng($resized, $destinationPath, 9);
                    break;
            }
            
            imagedestroy($image);
            imagedestroy($resized);
            
            return $result;
        } catch (\Exception $e) {
            return false;
        }
    }
}

