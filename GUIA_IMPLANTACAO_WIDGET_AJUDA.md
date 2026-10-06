# Guia Oficial de Implantação: Widget de Ajuda Flutuante i-Educar

Este documento descreve a arquitetura, raspagem completa de dados, funcionamento e instruções passo a passo para colocar em produção o **Widget de Ajuda Flutuante com Documentação Oficial e Telas** no i-Educar.

---

## 📌 1. Visão Geral da Solução

O **Widget de Ajuda do i-Educar** foi construído para atender com precisão aos requisitos:
1. **Botão Flutuante Fiel à Captura de Tela**:
   - Pílula arredondada no canto inferior direito (`position: fixed; bottom: 24px; right: 24px;`).
   - Tom azul-ardósia oficial do i-Educar (`#2b5278`), ícone `(?)` e texto `Ajuda`.
2. **Balão Flutuante Acima do Botão de Ajuda**:
   - Posicionado diretamente acima do botão (`bottom: 76px; right: 24px; width: 440px;`).
   - Abre e fecha de forma suave, sem cobrir toda a tela e sem ser painel lateral.
   - Contém barra de pesquisa com foco automático, atalhos rápidos e passo a passo.
3. **Imagens 100% Funcionais (76 Telas Oficiais)**:
   - Baixadas localmente para a pasta `help_images/` (3 MB no total).
   - Carregadas diretamente do disco/servidor sem bloqueio de hotlink (CORS/Referer 403 resolvido).
   - Suporte a zoom lightbox em alta resolução com clique.
4. **Busca Instantânea Inteligente**:
   - Normalização automática de acentos (ex.: `matricula` encontra `Matrícula`, `transferencia` encontra `Transferência`).
   - Mapeamento de sinônimos automáticos (ex.: buscar `boletim` ou `falta` exibe regras de avaliação).
   - Filtros rápidos por botões: *Todos*, *Usuários*, *Administradores*, *Matrículas*, *Notas*, *Ano Letivo*, *Censo Escolar*.
5. **Resiliência e Tratamento de Imagens**:
   - As imagens são referenciadas diretamente dos servidores oficiais do i-Educar (`https://ieducar.org/img/...`), poupando espaço de armazenamento e largura de banda da VPS.
   - Zoom Lightbox ao clicar na imagem para visualização em alta resolução.
   - Se a internet oscilar, o widget exibe um aviso amigável com botão **"Tentar recarregar imagem"**, sem travar os passos textuais.

---

## 📂 2. Estrutura dos Arquivos Criados

| Arquivo | Descrição |
| :--- | :--- |
| [`ieducar-help-widget.js`](file:///c:/Users/Douglas/Downloads/analise%20repositorio%20-%20report/ieducar-help-widget.js) | **Bundle Standalone completo**. Contém CSS embutido, HTML, motor de busca e toda a base de conhecimento indexada. Basta incluir 1 linha no Blade. |
| [`ieducar_help_data.json`](file:///c:/Users/Douglas/Downloads/analise%20repositorio%20-%20report/ieducar_help_data.json) | Base de dados estruturada em JSON (67 tópicos, caminhos de menu, passos e 76 imagens). |
| [`demo_help_widget.html`](file:///c:/Users/Douglas/Downloads/analise%20repositorio%20-%20report/demo_help_widget.html) | Tela de demonstração local reproduzindo a interface exata do i-Educar enviada na captura de tela. |
| [`scrape_ieducar_docs.py`](file:///c:/Users/Douglas/Downloads/analise%20repositorio%20-%20report/scrape_ieducar_docs.py) | Script de webscraping para atualizar os dados sempre que a documentação oficial do i-Educar mudar. |
| [`build_help_widget.py`](file:///c:/Users/Douglas/Downloads/analise%20repositorio%20-%20report/build_help_widget.py) | Compilador que processa a base JSON e gera o arquivo JavaScript final e o demonstrativo. |
| [`deploy_help_widget.py`](file:///c:/Users/Douglas/Downloads/analise%20repositorio%20-%20report/deploy_help_widget.py) | Script de deploy para injetar automaticamente o widget no Blade do i-Educar na VPS ou ambiente de desenvolvimento. |

---

## 🚀 3. Como Testar Localmente Imediatamente

Você pode testar a interface e o funcionamento agora mesmo abrindo o arquivo de teste no navegador:

1. Dê um duplo clique no arquivo [`demo_help_widget.html`](file:///c:/Users/Douglas/Downloads/analise%20repositorio%20-%20report/demo_help_widget.html) no seu computador (ele abrirá no Chrome, Edge ou Firefox).
2. Veja a tela idêntica à captura de tela enviada com o botão **[ ? Ajuda ]** flutuando no canto inferior direito.
3. Clique em **Ajuda**:
   - O painel lateral deslizará pela direita.
   - Digite termos como `matrícula`, `bloqueio`, `professor`, `censo` ou `ano letivo`.
   - Veja o caminho exato no menu do i-Educar (`📍 Módulo Escola > ...`), o passo a passo numerado e as imagens oficiais carregadas.
   - Clique em qualquer imagem para abrir em tela cheia (Lightbox). Pressione `Esc` para fechar.

---

## 🛠️ 4. Instruções de Instalação no i-Educar (Produção / VPS)

### Opção A: Deploy Automatizado via Script Bash (Recomendado para Linux VPS)

No terminal da sua VPS Linux:

```bash
bash deploy_help_widget.sh /var/www/ieducar
```

O script executa de forma 100% autônoma:
1. Detecta o diretório e permissões do i-Educar.
2. Copia `ieducar-help-widget.js` para `public/js/`.
3. Copia a pasta `help_images/` com as 76 telas oficiais para `public/help_images/`.
4. Ajusta permissões de leitura para o servidor web (`www-data`).
5. Localiza automaticamente o arquivo de layout mestre do Blade (`layouts/default.blade.php`, `app.blade.php`, `master.blade.php` ou templates do AdminLTE).
6. Injeta a tag do script antes da tag `</body>`.
7. Executa `php artisan view:clear` e `php artisan cache:clear` (ou via Docker Compose).

---

### Opção B: Deploy Automatizado via Script Python

Se preferir executar via interpretador Python:

```bash
python deploy_help_widget.py /var/www/ieducar
```

---

### Opção C: Instalação Manual em 2 Minutos

1. Copie o arquivo [`ieducar-help-widget.js`](file:///c:/Users/Douglas/Downloads/analise%20repositorio%20-%20report/ieducar-help-widget.js) e a pasta `help_images/` para a pasta pública do seu i-Educar:
   ```bash
   cp ieducar-help-widget.js /var/www/ieducar/public/js/
   cp -r help_images /var/www/ieducar/public/
   chown -R www-data:www-data /var/www/ieducar/public/js/ieducar-help-widget.js /var/www/ieducar/public/help_images
   ```

2. Abra o arquivo de template mestre do Blade no i-Educar (normalmente `resources/views/layouts/default.blade.php` ou `resources/views/layouts/app.blade.php`):
   ```blade
   <!-- Adicione imediatamente antes de </body>: -->
   <script src="{{ asset('js/ieducar-help-widget.js') }}" defer></script>
   </body>
   </html>
   ```

3. Limpe os caches do Laravel para garantir a exibição imediata:
   ```bash
   php artisan view:clear && php artisan cache:clear
   ```

---

## 🔄 5. Como Atualizar os Dados no Futuro

Se o portal do i-Educar publicar novos capítulos ou alterar tutoriais:

1. Execute o scraper para baixar as alterações mais recentes:
   ```bash
   python scrape_ieducar_docs.py
   ```
2. Recompile o widget:
   ```bash
   python build_help_widget.py
   ```
3. O arquivo [`ieducar-help-widget.js`](file:///c:/Users/Douglas/Downloads/analise%20repositorio%20-%20report/ieducar-help-widget.js) será atualizado imediatamente!
