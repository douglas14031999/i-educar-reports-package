# i-Educar Relatórios (Community Edition - Douglas)

Pacote estendido de relatórios para o [i-Educar](https://github.com/portabilis/i-educar), contendo correções de estabilidade multiplataforma, novos modelos estaduais (incluindo modelos padrão Alagoas - AL), fichas de saúde/habitação, termos administrativos e declarações prontas para uso.

---

## ⚡ Instalação com Comando Único (VPS / Linux / Docker)

Para instalar ou atualizar automaticamente na sua VPS (com detecção inteligente do ambiente, substituição do pacote padrão da Portábilis e compilação automática dos relatórios), acesse a raiz do seu i-Educar (ex: `/var/www/ieducar`) e execute:

```bash
curl -fsSL https://raw.githubusercontent.com/douglas14031999/i-educar-reports-package/main/install.sh | bash
```

### O que o script faz automaticamente:
1. **Localiza a raiz do i-Educar** (busca por `artisan` no diretório atual, `/var/www/ieducar` ou `/var/www/html`).
2. **Detecta o ambiente**: identifica se está rodando diretamente no Linux (Host) ou em contêineres Docker (`docker compose exec php`).
3. **Verifica o repositório instalado**:
   - Se for o da **Portábilis**, remove com segurança e clona a versão customizada.
   - Se já for a sua versão (**douglas14031999**), atualiza via `git pull` com as últimas melhorias.
   - Se ainda não estiver instalado, realiza a clonagem direta em `packages/portabilis/i-educar-reports-package`.
4. **Configura permissões de execução** no `jasperstarter` e pastas de relatórios compilados.
5. **Executa os comandos necessários**:
   - `composer plug-and-play` (ou `dump-autoload`)
   - `php artisan community:reports:install`
   - `php artisan vendor:publish --tag=reports-assets --ansi --force`
   - Limpeza de caches do Laravel.

---

## 📋 Lista de Relatórios Adicionados (17 Novos Relatórios)

| Processo | Nome do Relatório | Categoria | Descrição |
| :---: | :--- | :--- | :--- |
| **999701** | Declaração de Ciência e Anuência de Menor | Termos e Declarações | Declaração jurídica e administrativa de ciência dos pais/responsáveis legais. |
| **999702** | Termo de Falta / Notificação de Infrequência | Termos e Declarações | Notificação formal e termo de compromisso para acompanhamento de faltas reiteradas. |
| **999703** | Termo de Compromisso da Educação Infantil | Termos e Declarações | Termo de adesão, frequência e responsabilidade escolar para creche e pré-escola. |
| **999704** | Termo de Desistência e Renúncia de Vaga | Termos e Declarações | Instrumento formal de desistência voluntária de matrícula/vaga escolar. |
| **999705** | Autorização de Uso de Imagem e Voz do Aluno | Termos e Declarações | Termo de consentimento para projetos pedagógicos, murais e divulgação institucional. |
| **999706** | Certificado de Conclusão da Educação Infantil | Termos e Declarações | Certificado formal e decorativo de encerramento da etapa de Educação Infantil. |
| **999707** | Ficha Individual - Modelo Alagoas (1º ao 5º Ano) | Padrão Alagoas (AL) | Ficha oficial de notas e frequência bimestrais estruturada para os anos iniciais de AL. |
| **999708** | Ficha Individual - Modelo Alagoas (6º ao 9º Ano) | Padrão Alagoas (AL) | Ficha oficial completa com componentes curriculares, recuperação e situação final para os anos finais de AL. |
| **999709** | Ficha de Habitação e Perfil Socioeconômico | Fichas Cadastrais | Relatório detalhado das condições de moradia, infraestrutura domiciliar e perfil familiar. |
| **999710** | Ficha Médica e Ficha de Saúde do Aluno | Fichas Cadastrais | Registro de alergias, medicamentos, comorbidades, restrições e contatos de emergência. |
| **999711** | Carteira de Transporte Escolar | Identificação | Carteirinha de identificação do estudante para transporte escolar municipal/estadual. |
| **999712** | Verso da Capa do Diário de Classe | Diários e Registros | Folha regulamentar de instruções e encerramento pedagógico para capa do diário. |
| **999713** | Relação com Nota Necessária para Exame | Gestão de Notas | Listagem analítica por turma dos estudantes que necessitam de exame final e nota mínima. |
| **999714** | Canhoto de Devolução e Recebimento de Notas | Gestão Pedagógica | Comprovante destacável de entrega e devolução de notas e avaliações por componente. |
| **999715** | Ficha de Acompanhamento Individual do Aluno | Acompanhamento | Registro descritivo contínuo do desenvolvimento cognitivo, social e pedagógico. |
| **999716** | Ficha Individual - Modelo EJA | Modalidades | Ficha individual adaptada para períodos, módulos e etapas da Educação de Jovens e Adultos. |
| **999717** | Histórico Escolar em Conferência | Histórico Escolar | Histórico com marca d'água de segurança "EM CONFERÊNCIA" para auditoria prévia. |

---

## 🛠️ Instalação Manual Passo a Passo

Caso prefira executar manualmente os passos em seu servidor:

```bash
# 1. Acesse a raiz do seu i-Educar
cd /var/www/ieducar

# 2. Clone o repositório dentro de packages
git clone https://github.com/douglas14031999/i-educar-reports-package.git packages/portabilis/i-educar-reports-package

# 3. Registre o pacote no composer
composer plug-and-play

# 4. Instale o pacote (executa link, permissões, compilação e migrações)
php artisan community:reports:install

# 5. Publique os assets
php artisan vendor:publish --tag=reports-assets --ansi --force
```

---

## ☕ Dependências do Sistema

- **PHP**: 7.4 ou superior com suporte a `exec` e `passthru`.
- **Java**: [OpenJDK](https://openjdk.java.net/) 8 ou superior (JRE/JDK).
- **JasperStarter**: Utilizado para intermediar a geração entre PHP e os templates JasperReports.
- **Banco de Dados**: PostgreSQL configurado com o esquema padrão do i-Educar.

---

## 📖 Guia de Criação de Novos Relatórios

Foi desenvolvido um manual de arquitetura e desenvolvimento rápido no arquivo:
👉 [GUIA_BASE_CRIACAO_RELATORIOS.md](GUIA_BASE_CRIACAO_RELATORIOS.md)

Neste guia você encontra:
- Estrutura de cabeçalhos padrão do sistema (`header-portrait.jasper` e `header-landscape.jasper`).
- Padrões de queries SQL no PostgreSQL utilizando `QueryBridge` e `Portabilis_Utils_Database`.
- Criação dos Controllers (`ReportsController`) e menus de permissão no banco (`Add...MenuMigration`).
- 5 modelos boilerplates completos para novos relatórios.

---

## 📄 Licença e Créditos

- Desenvolvido e mantido pela comunidade [i-Educar](https://github.com/portabilis/i-educar) e [Portábilis](https://portabilis.com.br/).
- Customizações, novos relatórios estaduais e instalador desenvolvidos por **Douglas** ([@douglas14031999](https://github.com/douglas14031999)).
