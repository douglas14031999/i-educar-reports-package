# Atualização da Tela de Login do i-Diário · Lagoa da Canoa

Este documento resume a alteração da identidade visual e telas de autenticação do **i-Diário 1.6** para o design moderno com animações e estética oficial de **Lagoa da Canoa**.

---

## 🎯 Resumo das Modificações Realizadas

1. **Estrutura das Telas Atualizadas**:
   - `app/views/layouts/devise.html.erb`: Layout mestre com fontes Google (*Atkinson Hyperlegible* e *Bitter*), variáveis CSS com suporte completo a tema escuro/claro (*Dark Mode*), reset moderno e componentes responsivos.
   - `app/views/layouts/_not_logged_header.html.erb`: Cabeçalho com logo dinâmica (`logo_url`) ou fallback de alta resolução de Lagoa da Canoa, com alternância inteligente de botões (*Criar conta* ou *Acessar*).
   - `app/views/devise/sessions/new.html.erb`: Tela de login com cenário SVG vetorial animado (nascer do sol animado `rise 1.4s`, lagoa, colinas e canoa), inputs modernos, botão de alternância de senha (*Mostrar/Ocultar*), persistência de bloqueio de força bruta (`@time`) e alertas dinâmicos de flash.
   - `app/views/devise/passwords/new.html.erb`: Tela "Esqueceu sua senha?" com o mesmo visual moderno e campo de e-mail estilizado.
   - `app/views/devise/unlocks/new.html.erb`: Tela "Reenviar instruções de desbloqueio".
   - `app/views/layouts/registration.html.erb`: Layout de registro integrado à identidade visual Lagoa da Canoa.
   - `app/views/registrations/new.html.erb`: Tela oficial de cadastro de usuários e servidores do i-Diário (`@signup`) com formulário responsivo, máscara de CPF automática, alternância de senha e seleção de servidor.
   - `app/views/devise/registrations/new.html.erb`: Tela de cadastro fallback caso o Devise padrão seja invocado.
   - `app/views/devise/shared/_links.erb`: Links auxiliares de navegação entre as telas de autenticação.

2. **Segurança e Backups**:
   - O projeto local em `C:\Users\Douglas\Downloads\i-diario-1.6 (1)\i-diario-1.6` já foi atualizado com sucesso.
   - Os arquivos originais foram preservados no diretório de backup: `app/views_backup_devise_original`.

---

## 🚀 Como Aplicar na VPS com Comando Único

Foi gerado o script [deploy_login_vps.sh](file:///c:/Users/Douglas/Downloads/analise%20repositorio%20-%20report/deploy_login_vps.sh) que faz todo o processo automaticamente:
- Localiza o diretório do i-Diário na VPS (ex: `/var/www/i-diario`).
- Cria um backup com timestamp de todos os arquivos originais.
- Substitui os templates pelas novas versões.
- Dispara o reload/restart da aplicação (`touch tmp/restart.txt` ou `systemctl restart puma/i-diario`).

### Opção 1: Via SCP direto para a VPS (Mais Simples)
No PowerShell do seu computador, envie o script e execute-o:
```bash
scp "c:\Users\Douglas\Downloads\analise repositorio - report\deploy_login_vps.sh" root@SEU_IP_VPS:/tmp/
ssh root@SEU_IP_VPS "bash /tmp/deploy_login_vps.sh"
```

### Opção 2: Via curl bash (Comando Direto Pronto)
Como o script está publicado no seu repositório GitHub, execute diretamente na VPS:
```bash
curl -fsSL https://raw.githubusercontent.com/douglas14031999/i-educar-reports-package/2.11/deploy_login_vps.sh | bash
```

---

## 🎨 Recursos Visuais e Diferenciais
- **Zero Dependências Extras**: O CSS foi incorporado diretamente no layout Devise, garantindo que a página funcione imediatamente, sem necessidade de recompilar assets pesados ou correr riscos de incompatibilidade do Node/Yarn na VPS.
- **Animações SVG Nativas**: Sol nascente dinâmico com suporte a `prefers-reduced-motion`.
- **Modo Escuro Integrado**: Suporte automático a tema claro e escuro respeitando as preferências do sistema operacional do usuário.
