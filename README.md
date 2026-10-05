# 🎓 i-Educar Reports Package (Community Edition - Douglas)

> **Pacote estendido de relatórios, correções de interface e otimizações arquiteturais para o [i-Educar](https://github.com/portabilis/i-educar).**  
> Inclui 136 templates JasperReports compilados, novos modelos estaduais (com padrão oficial Alagoas - AL), fichas de saúde/habitação, termos administrativos, declarações, além de correções críticas na **Busca Rápida** e no **Sistema de Notificações**.

---

## 🚀 Scripts de Instalação e Correção Rápida (Execução Direta)

O repositório disponibiliza scripts bash automatizados prontos para execução em servidores Linux / VPS:

| Script | Finalidade Principal | Tempo Médio | Comando Direto via Curl |
| :--- | :--- | :---: | :--- |
| **`fix_search_and_notifications.sh`** | **Busca sem acento (`unaccent`) + Notificações corrigidas + PMD (tela branca resolvida)** | **~5 segundos** | `curl -fsSL https://raw.githubusercontent.com/douglas14031999/i-educar-reports-package/2.11/fix_search_and_notifications.sh \| bash` |
| **`fix_all.sh`** | **Correção Geral Completa**: busca, notificações, 136 relatórios compilados, limpeza de menus 404, realocação de módulos e permissões | **~45 segundos** | `curl -fsSL https://raw.githubusercontent.com/douglas14031999/i-educar-reports-package/2.11/fix_all.sh \| bash` |
| **`install.sh`** | **Instalador Completo do Pacote**: substitui pacote padrão, roda composer, compila templates e publica assets | **~60 segundos** | `curl -fsSL https://raw.githubusercontent.com/douglas14031999/i-educar-reports-package/2.11/install.sh \| bash` |
| **`deploy_login_vps.sh`** | **Novo Design da Tela de Login/Autenticação do i-Diário (Lagoa da Canoa)** | **~10 segundos** | `curl -fsSL https://raw.githubusercontent.com/douglas14031999/i-educar-reports-package/2.11/deploy_login_vps.sh \| bash` |

---

## 🛠️ Detalhamento dos Scripts e suas Funções

### 1. `fix_search_and_notifications.sh` (Correção Focada e Ultrarrápida)
Desenvolvido para aplicar correções cirúrgicas de usabilidade sem tocar na estrutura de relatórios ou banco:
- **Busca Rápida Sem Acentos (`unaccent`)**:
  - Habilita a extensão `unaccent` no PostgreSQL (`CREATE EXTENSION IF NOT EXISTS unaccent;`).
  - Atualiza o método `App\Menu::findByUser()` para consultar com `unaccent(title) ilike unaccent(?)` e `unaccent(description) ilike unaccent(?)`.
  - Permite digitar termos como `relatorio`, `distribuicao`, `declaracao`, `modulo`, encontrando resultados acentuados instantaneamente.
  - Atualiza o template `resources/views/layout/vue.blade.php` com o componente Vue Multiselect sanitizado.
- **Sistema de Notificações Preciso**:
  - Atualiza `ieducar/intranet/scripts/notifications.js` e sincroniza com `public/intranet/scripts/notifications.js`.
  - O balão vermelho passa a considerar estritamente notificações não lidas reais (`read_at IS NULL`).
  - Corrige os eventos de "Marcar todas como lidas" e o clique em itens individuais.
- **Módulo Pré-Matrícula Digital (PMD - Resolução da Tela Branca)**:
  - Corrige a interceptação do Nginx para arquivos `.js` dinâmicos (`try_files $uri /index.php?$query_string;`), permitindo que a rota `/config/prematricula.js` responda normalmente.
  - Gera o arquivo estático de redundância `public/config/prematricula.js` com o payload `window.config = {...}`, eliminando o erro fatal de JavaScript (`Cannot read properties of undefined (reading 'map')`) e restaurando a interface completa do PMD.
- **Caches e Serviços**:
  - Limpa views, cache de aplicação e configurações (`view:clear`, `cache:clear`, `config:clear`).
  - Recarrega suavemente os serviços `php-fpm` e `nginx`.

### 2. `fix_all.sh` (Manutenção Global e Estabilização)
Executa a suíte completa de alinhamento e reparos do ecossistema:
1. **Detecção de Dependências do SO**: Verifica e instala automaticamente o Java JRE (`default-jre-headless`) e Google Chrome/Chromium caso não estejam presentes.
2. **Sincronização de Código**: Atualiza o repositório git do pacote na branch `2.11`.
3. **Permissões de Execução**: Concede permissão ao binário do `jasperstarter` e aos diretórios de relatórios gerados.
4. **Composer Autoload**: Executa `composer plug-and-play` ou `composer dump-autoload -o`.
5. **Banco de Dados e Migrations**: Habilita a extensão `unaccent` no PostgreSQL e executa `php artisan migrate --force`.
6. **Remoção de Menus Fantasma / Erro 404**: Remove registros órfãos que apontavam para `/relatorios/...` inexistentes e o antigo menu 564.
7. **Compilação de Relatórios**: Compila todos os arquivos `.jrxml` gerando 136 binários `.jasper`.
8. **Busca Rápida e Notificações**: Aplica todas as otimizações de busca sem acento e notificações em tempo real.
9. **Limpeza e Recarregamento de Serviços**: Reseta todos os caches e reinicia workers PHP-FPM e Nginx.

### 3. `install.sh` (Instalador Inicial e Bootstrap para Novas VPS)
Script completo de bootstrap para novos ambientes e novas VPS:
- **Detecção de Ambiente**: Detecta a raiz do i-Educar (`/var/www/ieducar` ou contêiner Docker).
- **Auto-instalação de Dependências**: Instala automaticamente o Java JRE e Google Chrome Headless no sistema operacional caso ausentes.
- **Instalação do Repositório**: Substitui a versão antiga da Portábilis pela versão estendida da comunidade.
- **Banco de Dados**: Habilita `unaccent` no PostgreSQL e roda todas as migrações (incluindo o menu de Diplomas).
- **Autoload & Assets**: Executa `composer plug-and-play` (ou `dump-autoload`), registra links simbólicos (`community:reports:link`), compila os 136 relatórios e publica assets (`reports-assets`).
- **Otimizações Prontas**: Já deixa a Busca Rápida e as Notificações configuradas e 100% funcionais no primeiro boot.

---

## 🔍 Problemas Resolvidos e Melhorias Implementadas

### 1. Busca Rápida: Pesquisa Imune a Acentos e Caixa Alta
- **Problema anterior**: A busca rápida exigia correspondência exata de caracteres. Digitar "relatorio" não encontrava "Relatório de Alunos".
- **Solução implementada**: Refatoração do `Menu::findByUser` com a função nativa `unaccent()` do PostgreSQL, tornando a digitação fluida e intuitiva.

### 2. Balão de Notificações: Falso Positivo e Contagem Presa
- **Problema anterior**: O balão vermelho de notificações permanecia visível com contagem incorreta mesmo após o usuário ler todas as mensagens.
- **Solução implementada**: O script `notifications.js` foi reestruturado para verificar `read_at == null` de forma consistente tanto na contagem global quanto no clique individual e no botão "Marcar todas como lidas".

### 3. Desaparecimento do Menu Superior e Marcação de Módulo (Regra do `_processoAp`)
- **Problema anterior**: Ao abrir determinados relatórios, o menu superior horizontal sumia e a barra lateral perdia o destaque do módulo.
- **Solução implementada**:
  - Alinhamento rigoroso entre a propriedade `protected $_processoAp = X;` de cada Controller e o campo `process` na tabela `menus`.
  - Inclusão de um View Composer no `ReportsServiceProvider` como camada de redundância por `link`.

### 4. Realocação dos Menus nos Módulos Canônicos do Sistema
- Relatórios de transporte alocados no módulo **Transporte** (`old: 388`).
- Relatórios de acervo e empréstimos no módulo **Biblioteca** (`old: 342`).
- Relatórios funcionais no módulo **Servidores** (`old: 71`).
- Relatórios pedagógicos e cadastrais no módulo **Escola** (`old: 555`).

---

## 📋 Lista dos Novos Relatórios Inclusos

| Processo | Nome do Relatório | Categoria | Descrição |
| :---: | :--- | :--- | :--- |
| **999701** | Declaração de Ciência e Anuência de Menor | Termos e Declarações | Declaração jurídica e administrativa de ciência dos pais/responsáveis legais. |
| **999702** | Termo de Falta / Notificação de Infrequência | Termos e Declarações | Notificação formal e termo de compromisso para acompanhamento de faltas reiteradas. |
| **999703** | Termo de Compromisso da Educação Infantil | Termos e Declarações | Termo de adesão, frequência e responsabilidade escolar para creche e pré-escola. |
| **999704** | Termo de Desistência e Renúncia de Vaga | Termos e Declarações | Instrumento formal de desistência voluntária de matrícula/vaga escolar. |
| **999705** | Autorização de Uso de Imagem e Voz do Aluno | Termos e Declarações | Termo de consentimento para projetos pedagógicos, murais e divulgação institucional. |
| **999706** | Certificado de Conclusão da Educação Infantil | Termos e Declarações | Certificado formal e decorativo de encerramento da etapa de Educação Infantil. |
| **999707** | Ficha Individual - Modelo Alagoas (1º ao 5º Ano) | Padrão Alagoas (AL) | Ficha oficial de notas e frequência bimestrais estruturada para os anos iniciais de AL. |
| **999708** | Ficha Individual - Modelo Alagoas (6º ao 9º Ano) | Padrão Alagoas (AL) | Ficha oficial completa com componentes curriculares, recuperação e situação final para AL. |
| **999709** | Ficha de Habitação e Perfil Socioeconômico | Fichas Cadastrais | Relatório detalhado das condições de moradia, infraestrutura e perfil socioeconômico. |
| **999710** | Ficha Médica e Ficha de Saúde do Aluno | Fichas Cadastrais | Registro de alergias, medicamentos, comorbidades, restrições e contatos de emergência. |
| **999711** | Carteira de Transporte Escolar | Identificação | Carteirinha de identificação do estudante para transporte escolar municipal/estadual. |
| **999712** | Verso da Capa do Diário de Classe | Diários e Registros | Folha regulamentar de instruções e encerramento pedagógico para capa do diário. |
| **999713** | Relação com Nota Necessária para Exame | Gestão de Notas | Listagem analítica por turma dos estudantes que necessitam de exame final e nota mínima. |
| **999714** | Canhoto de Devolução e Recebimento de Notas | Gestão Pedagógica | Comprovante destacável de entrega e devolução de notas e avaliações por componente. |
| **999715** | Ficha de Acompanhamento Individual do Aluno | Acompanhamento | Registro descritivo contínuo do desenvolvimento cognitivo, social e pedagógico. |
| **999716** | Ficha Individual - Modelo EJA | Modalidades | Ficha individual adaptada para períodos, módulos e etapas da EJA. |
| **999717** | Histórico Escolar em Conferência | Histórico Escolar | Histórico com marca d'água de segurança "EM CONFERÊNCIA" para auditoria prévia. |
| **999718** | Emissão de Diplomas e Certificados de Conclusão | Diplomas e Documentos | Emissão de diplomas ornamentais de alta resolução com suporte a 3 modelos visuais (Azul Tradicional, Azul Petróleo/Dourado e Verde Esmeralda com dados cadastrais completos), assinaturas e carga horária personalizáveis, renderização vetorial em PDF via Google Chrome Headless ou visualização HTML direta. Permite emissão individual ou em lote por turma. |

---

## 🎨 Arquitetura Híbrida: JasperReports + HTML/PDF Headless de Alta Fidelidade

O pacote suporta duas arquiteturas complementares de geração de documentos:
1. **JasperReports (`.jrxml` / `.jasper`)**: Ideal para relatórios tabulares, fichas de notas, mapas de rendimento e históricos escolares com totalizações e quebras complexas.
2. **HTML5 / CSS Paged Media + Headless Chrome**: Ideal para documentos nobres, diplomas, certificados e carteiras que demandam bordas guilhochê ornamentais, arabescos, selos em vetor/base64 de altíssima definição e flexibilidade total de diagramação tipográfica. Utiliza o comando `google-chrome --headless=new --print-to-pdf` para gerar PDFs A4 paisagem perfeitos e sem perdas vetoriais.

---

## 📖 Blueprint de Desenvolvimento: Guia Canônico

Para criar novos relatórios ou customizar relatórios existentes, consulte o guia oficial:  
👉 **[GUIA_BASE_CRIACAO_RELATORIOS.md](GUIA_BASE_CRIACAO_RELATORIOS.md)**

O guia conta com mais de **2.100 linhas** e inclui:
- **Arquitetura em 5 Camadas**: `Controller` → `Report` → `QueryBridge` → `JasperReports (.jrxml)` ou `HTML Template` → `Migration`.
- **Catálogo de Inputs do Formulário**: Uso de inputs dinâmicos (`inputsHelper()->dynamic(...)`) e customizados (`select`, `text`, `date`, `boolean`).
- **Padrões de Queries SQL**: Filtros opcionais com `$P{x} = 0`, busca textual com `fcn_upper_nrm`, tratamento de datas e a regra mandatória de desduplicação de enturmação ativa com `MAX(sequencial)`.
- **Dicionário de Expressões Java para JasperReports**: Sintaxe de operadores ternários, máscaras de CPF, formatação de datas e variáveis de totalização.
- **4 Modelos Práticos End-to-End Completos**:
  1. *Listagem Cadastral Retrato (A4 Portrait)*
  2. *Documento Individual com Assinaturas*
  3. *Mapa Quantitativo em Paisagem (A4 Landscape - 802pt)*
  4. *Relatório Master-Detail com Subreport e DataSources JSON Aninhados*

---

## ⌨️ Comandos Artisan Disponíveis

Ao registrar o pacote, os seguintes comandos do console ficam disponíveis:

```bash
# Compilar todos os arquivos .jrxml em binários .jasper
php artisan community:reports:compile

# Criar os links simbólicos necessários entre o pacote e a raiz do i-Educar
php artisan community:reports:link

# Instalação completa (executa link, permissões, compilação e migrações)
php artisan community:reports:install

# Publicar assets (CSS/JS)
php artisan vendor:publish --tag=reports-assets --ansi --force
```

---

## ⚙️ Dependências do Sistema

- **PHP**: 7.4 / 8.2 / 8.4+ com extensões padrão e funções `exec` e `passthru` habilitadas.
- **Java**: [OpenJDK](https://openjdk.java.net/) 8 ou superior (JRE headless: `default-jre-headless`) - *instalado automaticamente pelos scripts caso ausente*.
- **Google Chrome / Chromium**: Necessário para renderização headless de Diplomas em PDF vetorial A4 (`google-chrome-stable` ou `chromium-browser`) - *instalado automaticamente pelos scripts caso ausente*.
- **JasperStarter**: Intermediador de compilação dos arquivos JRXML em PDFs (incluído no pacote).
- **PostgreSQL**: Com extensão `unaccent` instalada (`CREATE EXTENSION IF NOT EXISTS unaccent;`) - *habilitada automaticamente pelos scripts*.

---

## 📄 Licença e Créditos

- Desenvolvido originalmente pela comunidade [i-Educar](https://github.com/portabilis/i-educar) e [Portábilis](https://portabilis.com.br/).
- Correções de compatibilidade, estabilidade de menus, busca rápida, notificações, novos relatórios e scripts de automação desenvolvidos por **Douglas** ([@douglas14031999](https://github.com/douglas14031999)).
