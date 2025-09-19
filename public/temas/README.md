# Diretório de Temas

Este diretório é usado para armazenar os temas personalizados do sistema.

## Como usar:

1. Acesse o Admin Panel
2. Vá para a aba "Temas"
3. Preencha o nome do tema (apenas letras, números, hífens e underscores)
4. Faça upload de um arquivo ZIP contendo os arquivos do tema
5. O sistema irá:
   - Criar um diretório com o nome do tema
   - Descompactar o arquivo ZIP
   - Deletar o arquivo ZIP original

## Estrutura recomendada do ZIP:

```
meu-tema/
├── css/
│   ├── style.css
│   └── responsive.css
├── js/
│   └── theme.js
├── images/
│   ├── logo.png
│   └── background.jpg
└── README.md
```

## Limitações:

- Tamanho máximo do arquivo: 10MB
- Nome do tema: apenas letras, números, hífens e underscores
- Formato aceito: ZIP
