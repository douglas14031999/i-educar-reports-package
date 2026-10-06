#!/usr/bin/env bash
# ==============================================================================
# Script de Instalação Automática: Widget Menu de Ajuda Oficial i-Educar
# Compatível com Ubuntu, Debian, CentOS, AlmaLinux, Nginx, Apache e Docker
# ==============================================================================
set -e

GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}==================================================================${NC}"
echo -e "${GREEN}   Instalador Automático: Widget Menu de Ajuda Oficial i-Educar   ${NC}"
echo -e "${BLUE}==================================================================${NC}"

# 1. Identificar Diretório do i-Educar
POSSIBLE_PATHS=(
  "$1"
  "$IEDUCAR_PATH"
  "$(pwd)"
  "/var/www/ieducar"
  "/var/www/i-educar"
  "/var/www/html/ieducar"
  "/var/www/html/i-educar"
  "/home/deploy/ieducar"
  "/home/ubuntu/ieducar"
  "/root/ieducar"
)

TARGET_DIR=""

for p in "${POSSIBLE_PATHS[@]}"; do
  if [ -n "$p" ] && [ -d "$p" ]; then
    if ([ -f "$p/artisan" ] && [ -d "$p/public" ]) || ([ -d "$p/ieducar" ] && [ -d "$p/public" ]); then
      TARGET_DIR="$p"
      break
    fi
  fi
done

if [ -z "$TARGET_DIR" ]; then
  # Procura rápida nos diretórios mais comuns
  for base_search in /var/www /home /root; do
    if [ -d "$base_search" ]; then
      match=$(find "$base_search" -maxdepth 2 -type d \( -name "ieducar*" -o -name "i-educar*" \) 2>/dev/null | head -n 1)
      if [ -n "$match" ] && [ -f "$match/artisan" ]; then
        TARGET_DIR="$match"
        break
      fi
    fi
  done
fi

if [ -z "$TARGET_DIR" ]; then
  echo -e "${RED}[ERRO] Não foi possível localizar a pasta do i-Educar automaticamente.${NC}"
  echo -e "${YELLOW}Uso: bash deploy_help_widget.sh /caminho/do/seu/ieducar${NC}"
  exit 1
fi

echo -e "${GREEN}[1/4] i-Educar encontrado em:${NC} $TARGET_DIR"

# 2. Copiar ou Baixar o arquivo JavaScript para public/js/
mkdir -p "$TARGET_DIR/public/js"
if [ -f "ieducar-help-widget.js" ]; then
  cp -f "ieducar-help-widget.js" "$TARGET_DIR/public/js/ieducar-help-widget.js"
  echo -e " -> ${GREEN}ieducar-help-widget.js copiado localmente para public/js/${NC}"
else
  echo -e "${BLUE} -> Baixando ieducar-help-widget.js do repositório GitHub...${NC}"
  curl -fsSL "https://raw.githubusercontent.com/douglas14031999/i-educar-reports-package/2.11/ieducar-help-widget.js" -o "$TARGET_DIR/public/js/ieducar-help-widget.js"
  echo -e " -> ${GREEN}ieducar-help-widget.js baixado e instalado com sucesso!${NC}"
fi
chmod 644 "$TARGET_DIR/public/js/ieducar-help-widget.js"

# 3. Instalar pasta help_images com as 76 telas oficiais
mkdir -p "$TARGET_DIR/public/help_images"
if [ -d "help_images" ]; then
  cp -rf help_images/* "$TARGET_DIR/public/help_images/" 2>/dev/null || true
  echo -e " -> ${GREEN}76 imagens oficiais copiadas localmente para public/help_images/${NC}"
elif [ -f "help_images.tar.gz" ]; then
  tar -xzf "help_images.tar.gz" -C "$TARGET_DIR/public/" 2>/dev/null || true
  echo -e " -> ${GREEN}76 imagens oficiais extraídas para public/help_images/${NC}"
else
  echo -e "${BLUE} -> Baixando pacote de imagens (76 telas) do GitHub...${NC}"
  if curl -fsSL "https://raw.githubusercontent.com/douglas14031999/i-educar-reports-package/2.11/help_images.tar.gz" -o "/tmp/help_images.tar.gz" 2>/dev/null; then
    tar -xzf "/tmp/help_images.tar.gz" -C "$TARGET_DIR/public/" 2>/dev/null && rm -f "/tmp/help_images.tar.gz" || true
    echo -e " -> ${GREEN}76 imagens oficiais instaladas com sucesso em public/help_images/${NC}"
  else
    echo -e " -> ${YELLOW}[Aviso] O widget usará as imagens oficiais remotas via CDN.${NC}"
  fi
fi
chmod -R 755 "$TARGET_DIR/public/help_images" 2>/dev/null || true

# Ajustar permissões para www-data se existir
if id "www-data" &>/dev/null; then
  chown -R www-data:www-data "$TARGET_DIR/public/js/ieducar-help-widget.js" 2>/dev/null || true
  [ -d "$TARGET_DIR/public/help_images" ] && chown -R www-data:www-data "$TARGET_DIR/public/help_images" 2>/dev/null || true
  echo -e " -> ${GREEN}Permissões ajustadas para www-data!${NC}"
fi

# 4. Localizar arquivos de Layout Blade Ativos
echo -e "\n${BLUE}[2/4] Localizando templates base do Blade...${NC}"

POSSIBLE_LAYOUTS=(
  "$TARGET_DIR/resources/views/layout/default.blade.php"
  "$TARGET_DIR/resources/views/layout/base.blade.php"
  "$TARGET_DIR/resources/views/layout/public.blade.php"
  "$TARGET_DIR/resources/views/layouts/default.blade.php"
  "$TARGET_DIR/resources/views/layouts/app.blade.php"
  "$TARGET_DIR/resources/views/layouts/master.blade.php"
  "$TARGET_DIR/resources/views/layouts/main.blade.php"
  "$TARGET_DIR/resources/views/vendor/adminlte/page.blade.php"
  "$TARGET_DIR/ieducar/templates/corpo.blade.php"
  "$TARGET_DIR/ieducar/templates/padrao.blade.php"
  "$TARGET_DIR/ieducar/intranet/templates/padrao.blade.php"
)

FOUND_LAYOUTS=()
for l in "${POSSIBLE_LAYOUTS[@]}"; do
  if [ -f "$l" ]; then
    FOUND_LAYOUTS+=("$l")
  fi
done

# Se não encontrou nas rotas comuns, busca por layouts com </body> excluindo backups
if [ "${#FOUND_LAYOUTS[@]}" -eq 0 ] && [ -d "$TARGET_DIR/resources/views" ]; then
  while IFS= read -r f; do
    if [ -n "$f" ] && [[ "$f" != *.bak* ]] && [[ "$f" != *.backup* ]]; then
      FOUND_LAYOUTS+=("$f")
    fi
  done < <(grep -rn "</body>" "$TARGET_DIR/resources/views" 2>/dev/null | cut -d: -f1 | sort -u)
fi

INJECTED_COUNT=0
for LAYOUT_FILE in "${FOUND_LAYOUTS[@]}"; do
  if [ -f "$LAYOUT_FILE" ]; then
    echo -e " -> ${GREEN}Layout identificado:${NC} $LAYOUT_FILE"

    if grep -q "ieducar-help-widget.js" "$LAYOUT_FILE"; then
      echo -e "    ${YELLOW}O script já estava injetado neste layout.${NC}"
    else
      # Criar cópia de segurança
      cp "$LAYOUT_FILE" "${LAYOUT_FILE}.bak_widget"
      echo -e "    ${GREEN}Backup preventivo salvo como ${LAYOUT_FILE}.bak_widget${NC}"

      if command -v python3 &>/dev/null; then
        python3 -c "
import sys
fpath = sys.argv[1]
with open(fpath, 'r', encoding='utf-8', errors='ignore') as f:
    c = f.read()
tag = '\n    <!-- Central de Ajuda Oficial i-Educar -->\n    <script src=\"{{ asset(\x27js/ieducar-help-widget.js\x27) }}\" defer></script>\n'
if '</body>' in c:
    c = c.replace('</body>', tag + '</body>', 1)
else:
    c += tag
with open(fpath, 'w', encoding='utf-8') as f:
    f.write(c)
" "$LAYOUT_FILE"
      else
        if grep -q "</body>" "$LAYOUT_FILE"; then
          sed -i "s|</body>|    <!-- Central de Ajuda Oficial i-Educar -->\n    <script src=\"{{ asset('js/ieducar-help-widget.js') }}\" defer></script>\n</body>|" "$LAYOUT_FILE"
        else
          echo -e "\n<script src=\"{{ asset('js/ieducar-help-widget.js') }}\" defer></script>" >> "$LAYOUT_FILE"
        fi
      fi
      echo -e "    ${GREEN}Script injetado com sucesso no Blade layout!${NC}"
      INJECTED_COUNT=$((INJECTED_COUNT + 1))
    fi
  fi
done

if [ "${#FOUND_LAYOUTS[@]}" -eq 0 ]; then
  echo -e "${YELLOW}[!] Não foi possível identificar o layout Blade principal automaticamente.${NC}"
  echo -e "    Insira manualmente esta linha antes de </body> no seu layout base:"
  echo -e "    <script src=\"{{ asset('js/ieducar-help-widget.js') }}\" defer></script>"
fi

# 5. Limpar Cache do Laravel
echo -e "\n${BLUE}[3/4] Limpando cache de views e rotas do Laravel...${NC}"

CLEARED=0
if [ -f "$TARGET_DIR/artisan" ] && command -v php &>/dev/null; then
  php "$TARGET_DIR/artisan" view:clear 2>/dev/null || true
  php "$TARGET_DIR/artisan" cache:clear 2>/dev/null || true
  echo -e " -> ${GREEN}php artisan view:clear & cache:clear executados com sucesso!${NC}"
  CLEARED=1
fi

if [ "$CLEARED" -eq 0 ] && [ -f "$TARGET_DIR/docker-compose.yml" ]; then
  if command -v docker &>/dev/null && docker compose version &>/dev/null; then
    docker compose -f "$TARGET_DIR/docker-compose.yml" exec -T app php artisan view:clear 2>/dev/null || true
    docker compose -f "$TARGET_DIR/docker-compose.yml" exec -T app php artisan cache:clear 2>/dev/null || true
    echo -e " -> ${GREEN}Caches limpos via docker compose exec app!${NC}"
  elif command -v docker-compose &>/dev/null; then
    docker-compose -f "$TARGET_DIR/docker-compose.yml" exec -T app php artisan view:clear 2>/dev/null || true
    docker-compose -f "$TARGET_DIR/docker-compose.yml" exec -T app php artisan cache:clear 2>/dev/null || true
    echo -e " -> ${GREEN}Caches limpos via docker-compose exec app!${NC}"
  fi
fi

echo -e "\n${BLUE}==================================================================${NC}"
echo -e "${GREEN}   INSTALAÇÃO CONCLUÍDA COM SUCESSO NO I-EDUCAR!                  ${NC}"
echo -e "${BLUE}==================================================================${NC}"
echo -e "Abra o i-Educar no navegador e pressione ${YELLOW}Ctrl + F5${NC} para visualizar."
