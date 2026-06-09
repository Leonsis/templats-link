-- Script para atualizar o favicon padrão de .webp para .svg
-- Execute este script no banco de dados para atualizar todas as referências

UPDATE `head_configs` 
SET `favicon` = 'uploads/favicons/favicon-main.svg',
    `updated_at` = NOW()
WHERE `favicon` = 'uploads/favicons/favicon-main.webp'
   OR `favicon` LIKE 'uploads/favicons/favicon-main.%';

-- Atualizar também o logo padrão se necessário
UPDATE `head_configs` 
SET `logo` = 'uploads/logos/logo-main.svg',
    `updated_at` = NOW()
WHERE `logo` = 'uploads/logos/logo-main.png'
   OR `logo` LIKE 'uploads/logos/logo-main.%';

