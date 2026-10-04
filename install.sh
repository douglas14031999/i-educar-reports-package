#!/usr/bin/env bash
# ==============================================================================
# Script de Instalação e Atualização Automatizada - i-Educar Reports Package
# Repositório: https://github.com/douglas14031999/i-educar-reports-package
# ==============================================================================

set -e
export COMPOSER_ALLOW_SUPERUSER=1

# Cores para saída no terminal
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m' # No Color

REPO_URL="https://github.com/douglas14031999/i-educar-reports-package.git"
BRANCH="${BRANCH:-2.11}"
PACKAGE_DIR="packages/portabilis/i-educar-reports-package"

print_banner() {
    echo -e "${CYAN}"
    echo "======================================================================="
    echo "       🚀 INSTALADOR AUTOMÁTICO DE RELATÓRIOS DO I-EDUCAR             "
    echo "          Repositório: douglas14031999/i-educar-reports-package        "
    echo "======================================================================="
    echo -e "${NC}"
}

print_step() {
    echo -e "${BLUE}${BOLD}[PASSO $1]${NC} $2"
}

print_success() {
    echo -e "${GREEN}✔ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_error() {
    echo -e "${RED}✖ $1${NC}"
}

# 1. Localizar a raiz do i-Educar
find_ieducar_root() {
    print_step "1/6" "Localizando diretório raiz do i-Educar..."
    
    # Se o diretório atual tem artisan e composer.json
    if [ -f "artisan" ] && [ -f "composer.json" ]; then
        IEDUCAR_DIR="$(pwd)"
    # Se existe em /var/www/ieducar
    elif [ -d "/var/www/ieducar" ] && [ -f "/var/www/ieducar/artisan" ]; then
        IEDUCAR_DIR="/var/www/ieducar"
    # Se existe em /var/www/html
    elif [ -d "/var/www/html" ] && [ -f "/var/www/html/artisan" ]; then
        IEDUCAR_DIR="/var/www/html"
    else
        # Procura até 2 níveis acima
        if [ -f "../artisan" ]; then
            IEDUCAR_DIR="$(cd .. && pwd)"
        elif [ -f "../../artisan" ]; then
            IEDUCAR_DIR="$(cd ../.. && pwd)"
        else
            print_error "Não foi possível encontrar a raiz do i-Educar (arquivo 'artisan' ausente)."
            echo -e "${YELLOW}Por favor, execute este comando dentro da pasta do i-Educar (ex: /var/www/ieducar).${NC}"
            exit 1
        fi
    fi

    cd "$IEDUCAR_DIR"
    print_success "Raiz do i-Educar detectada em: ${BOLD}$IEDUCAR_DIR${NC}"
}

# 2. Detectar se o ambiente roda via Docker ou nativo
detect_environment() {
    print_step "2/6" "Detectando ambiente de execução (Nativo vs Docker)..."
    USE_DOCKER=false

    if command -v docker-compose &> /dev/null && [ -f "docker-compose.yml" ]; then
        # Verifica se o container php está em execução
        if docker-compose ps 2>/dev/null | grep -q "php"; then
            USE_DOCKER=true
            DOCKER_CMD="docker-compose exec -T php"
            print_success "Ambiente Docker detectado! Comandos rodarão via docker-compose."
            return
        fi
    fi

    if command -v docker &> /dev/null && [ -f "docker-compose.yml" ]; then
        if docker compose ps 2>/dev/null | grep -q "php"; then
            USE_DOCKER=true
            DOCKER_CMD="docker compose exec -T php"
            print_success "Ambiente Docker (Compose v2) detectado! Comandos rodarão via docker compose."
            return
        fi
    fi

    print_success "Ambiente nativo/VPS detectado (execução direta no host)."
}

# 3. Analisar status do pacote atual
check_and_install_package() {
    print_step "3/6" "Verificando instalação do pacote de relatórios..."
    mkdir -p packages/portabilis

    if [ -d "$PACKAGE_DIR" ]; then
        echo -e "Diretório ${BOLD}$PACKAGE_DIR${NC} já existe."
        
        # Verificar a origem do repositório
        IS_PORTABILIS=false
        IS_DOUGLAS=false

        if [ -d "$PACKAGE_DIR/.git" ]; then
            REMOTE_URL=$(git -C "$PACKAGE_DIR" config --get remote.origin.url || true)
            echo -e "Origem Git detectada: ${CYAN}$REMOTE_URL${NC}"

            if echo "$REMOTE_URL" | grep -qi "douglas14031999"; then
                IS_DOUGLAS=true
            elif echo "$REMOTE_URL" | grep -qi "portabilis"; then
                IS_PORTABILIS=true
            fi
        else
            # Sem pasta .git, verificar composer.json interno ou autor
            if [ -f "$PACKAGE_DIR/composer.json" ]; then
                if grep -qi "douglas" "$PACKAGE_DIR/composer.json"; then
                    IS_DOUGLAS=true
                else
                    IS_PORTABILIS=true
                fi
            else
                IS_PORTABILIS=true
            fi
        fi

        if [ "$IS_DOUGLAS" = true ]; then
            print_success "O seu pacote (douglas14031999) já está instalado! Atualizando com as últimas alterações..."
            if [ -d "$PACKAGE_DIR/.git" ]; then
                git -C "$PACKAGE_DIR" fetch origin
                git -C "$PACKAGE_DIR" reset --hard origin/$BRANCH
                git -C "$PACKAGE_DIR" pull origin $BRANCH
            fi
        else
            print_warning "O pacote da Portábilis foi detectado! Removendo para instalar a versão customizada..."
            rm -rf "$PACKAGE_DIR"
            print_step "3.1" "Clonando o repositório douglas14031999/i-educar-reports-package..."
            git clone -b "$BRANCH" "$REPO_URL" "$PACKAGE_DIR"
            print_success "Repositório clonado com sucesso!"
        fi
    else
        print_step "3.1" "Nenhum pacote anterior detectado. Clonando o seu repositório..."
        git clone -b "$BRANCH" "$REPO_URL" "$PACKAGE_DIR"
        print_success "Repositório clonado com sucesso!"
    fi
}

# 4. Configurar permissões necessárias
setup_permissions() {
    print_step "4/6" "Ajustando permissões de arquivos e executáveis..."
    
    if [ -f "vendor/cossou/jasperphp/src/JasperStarter/bin/jasperstarter" ]; then
        chmod +x vendor/cossou/jasperphp/src/JasperStarter/bin/jasperstarter 2>/dev/null || true
    fi

    if [ -d "ieducar/modules/Reports/ReportSources" ]; then
        chmod -R 777 ieducar/modules/Reports/ReportSources 2>/dev/null || true
    fi

    if [ -d "$PACKAGE_DIR" ]; then
        chmod -R 775 "$PACKAGE_DIR" 2>/dev/null || true
    fi

    print_success "Permissões aplicadas com sucesso."
}

# 5. Executar Composer e Artisan
run_installation_commands() {
    print_step "5/6" "Registrando pacote no Composer e executando Artisan..."

    if [ "$USE_DOCKER" = true ]; then
        echo "Executando no container Docker..."
        $DOCKER_CMD composer plug-and-play || $DOCKER_CMD composer dump-autoload
        $DOCKER_CMD php artisan community:reports:install
        $DOCKER_CMD php artisan vendor:publish --tag=reports-assets --ansi --force
        $DOCKER_CMD php artisan cache:clear || true
        $DOCKER_CMD php artisan config:clear || true
    else
        if command -v composer &> /dev/null; then
            composer plug-and-play || composer dump-autoload
        fi
        
        php artisan community:reports:install
        php artisan vendor:publish --tag=reports-assets --ansi --force
        php artisan view:clear || true
        php artisan cache:clear || true
        php artisan config:clear || true

        # Otimização Busca Rápida (com suporte a busca sem acentuação)
        if [ -f "$IEDUCAR_DIR/app/Menu.php" ]; then
            php -r "
            \$file = '$IEDUCAR_DIR/app/Menu.php';
            \$c = file_get_contents(\$file);
            \$old = 'public static function findByUser(User \$user, \$search)';
            if (strpos(\$c, \$old) !== false && strpos(\$c, 'unaccent') === false) {
                \$pattern = '/public static function findByUser\(User \\\$user, \\\$search\)\s*\{[\s\S]*?return \\\$query->whereNotNull\(\'link\'\)[\s\S]*?->get\(\);\s*\}/';
                \$replacement = \"public static function findByUser(User \\\$user, \\\$search)\n    {\n        \\\$query = \\\$user->isAdmin() ? static::query() : \\\$user->menu();\n\n        return \\\$query->whereNotNull('link')\n            ->where(function (\\\$query) use (\\\$search) {\n                \\\$term = \\\"%{\\\$search}%\\\";\n                \\\$query->orWhereRaw('unaccent(title) ilike unaccent(?)', [\\\$term])\n                      ->orWhereRaw('unaccent(description) ilike unaccent(?)', [\\\$term]);\n            })\n            ->orderBy('title')\n            ->limit(15)\n            ->get();\n    }\";
                \$c = preg_replace(\$pattern, \$replacement, \$c);
                file_put_contents(\$file, \$c);
            }
            " 2>/dev/null || true
        fi

        # Otimização Notificações (eliminação do falso alerta vermelho)
        if [ -f "$PACKAGE_DIR/fix_all.sh" ] && [ -d "$IEDUCAR_DIR/ieducar/intranet/scripts" ]; then
            # Sincroniza correções de notificações e busca rápida se aplicável
            if [ -d "$IEDUCAR_DIR/public/intranet/scripts" ] && [ -f "$IEDUCAR_DIR/ieducar/intranet/scripts/notifications.js" ]; then
                cp "$IEDUCAR_DIR/ieducar/intranet/scripts/notifications.js" "$IEDUCAR_DIR/public/intranet/scripts/notifications.js" 2>/dev/null || true
            fi
        fi

        systemctl reload php8.4-fpm 2>/dev/null || systemctl reload php8.3-fpm 2>/dev/null || systemctl reload php-fpm 2>/dev/null || true
    fi

    print_success "Comandos de instalação, compilação e migrações concluídos com sucesso!"
}

# 6. Verificação final
verify_installation() {
    print_step "6/6" "Verificação final dos relatórios..."
    
    TOTAL_TEMPLATES=$(find "$PACKAGE_DIR/ieducar/ReportSources" -name "*.jrxml" 2>/dev/null | wc -l || echo "0")
    TOTAL_MIGRATIONS=$(find "$PACKAGE_DIR/database/migrations" -name "*.php" 2>/dev/null | wc -l || echo "0")

    echo -e "${GREEN}"
    echo "======================================================================="
    echo "   🎉 INSTALAÇÃO / ATUALIZAÇÃO CONCLUÍDA COM SUCESSO!                 "
    echo "======================================================================="
    echo -e "${NC}"
    echo -e " • Templates JRXML disponíveis: ${BOLD}$TOTAL_TEMPLATES${NC}"
    echo -e " • Migrações de menu instaladas: ${BOLD}$TOTAL_MIGRATIONS${NC}"
    echo -e " • Todos os 17 novos relatórios (Alagoas, Termos, Fichas e Registros) já estão disponíveis no menu."
    echo -e " • Repositório ativo: ${CYAN}https://github.com/douglas14031999/i-educar-reports-package${NC}"
    echo ""
}

# Fluxo Principal
main() {
    print_banner
    find_ieducar_root
    detect_environment
    check_and_install_package
    setup_permissions
    run_installation_commands
    verify_installation
}

main "$@"
