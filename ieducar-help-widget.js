/*
 * WIDGET DE AJUDA FLUTUANTE I-EDUCAR (DESIGN EXATO DA REFERÊNCIA - CORRIGIDO)
 * Layout em Bloco Imune ao Encolhimento Flexbox | Accordion Funcional com Telas Embutidas
 */

(function() {
  "use strict";

  if (document.getElementById("ieducar-help-root")) return;

  const IEDUCAR_HELP_DATA = [{"id": "guia-matricula", "is_featured": true, "title": "Como fazer uma nova matrícula", "category": "Usuários", "subtopic": "Ano letivo e matrículas", "path": "Escola > Cadastros > Aluno > [Selecionar] > Matrículas", "keywords": ["matricula", "matricular", "novo aluno", "aluno", "vaga", "ingresso"], "steps": [{"text": "Acesse Módulo Escola > Cadastros > Aluno, pesquise o aluno pelo nome e abra a aba 'Matrículas'.", "image": {"local_filename": "user-figura-46-historico-matriculas-alunos.png", "url": "https://ieducar.org/img/user-docs/user-figura-46-historico-matriculas-alunos.png", "alt": "Listagem de matrículas de alunos com histórico e botão Nova Matrícula"}}, {"text": "Clique no botão verde 'Nova Matrícula' e preencha Instituição, Escola, Curso, Série, Turma e Data da Matrícula.", "image": {"local_filename": "user-figura-47-insercao-matricula-aluno.png", "url": "https://ieducar.org/img/user-docs/user-figura-47-insercao-matricula-aluno.png", "alt": "Formulário para inserção da matrícula do aluno"}}, {"text": "Para gerenciar a matrícula ativa, consultar transferências ou emitir documentos, clique em 'Visualizar'.", "image": {"local_filename": "user-figura-48-gerenciar-matricula-aluno.png", "url": "https://ieducar.org/img/user-docs/user-figura-48-gerenciar-matricula-aluno.png", "alt": "Painel de gerenciamento da matrícula com opções e ocorrências"}}, {"text": "Caso necessite registrar saída por abandono ou transferência, informe a justificativa e confirme.", "image": {"local_filename": "user-figura-49-insercao-abandono-aluno.png", "url": "https://ieducar.org/img/user-docs/user-figura-49-insercao-abandono-aluno.png", "alt": "Formulário para inserção de abandono e justificativa"}}]}, {"id": "guia-enturmar", "is_featured": true, "title": "Como enturmar um aluno", "category": "Usuários", "subtopic": "Turmas e enturmação", "path": "Escola > Movimentações > Enturmações em lote", "keywords": ["enturmar", "enturmacao", "entrumacao", "turma", "lote", "alocar aluno", "sala"], "steps": [{"text": "Acesse Módulo Escola > Movimentações > Enturmações em lote (ou na matrícula do aluno clique em 'Enturmar').", "image": null}, {"text": "Selecione o Ano Letivo, Escola, Curso, Série e a Turma desejada.", "image": null}, {"text": "O sistema listará os alunos da turma e os matriculados ainda não enturmados. Marque a caixa de seleção ao lado de cada estudante que deseja vincular.", "image": {"local_filename": "user-figura-50-selecao-enturmacao.png", "url": "https://ieducar.org/img/user-docs/user-figura-50-selecao-enturmacao.png", "alt": "Formulário para escolha múltipla de alunos a serem enturmados na turma selecionada"}}, {"text": "Clique no botão 'Salvar' na barra inferior para confirmar e efetivar a enturmação.", "image": null}]}, {"id": "guia-transferir-turma", "is_featured": true, "title": "Como transferir um aluno de turma", "category": "Usuários", "subtopic": "Turmas e enturmação", "path": "Escola > Movimentações > Enturmações em lote", "keywords": ["transferir", "trocar turma", "mudanca de turma", "transferencia", "rematrícula"], "steps": [{"text": "Acesse o cadastro do aluno ou o menu de movimentações de turmas.", "image": null}, {"text": "Para alterar ou transferir um aluno já enturmado para uma nova turma, selecione a opção de transferência de turma.", "image": {"local_filename": "user-figura-51-transferindo-aluno-turma.png", "url": "https://ieducar.org/img/user-docs/user-figura-51-transferindo-aluno-turma.png", "alt": "Formulário para alteração de turma de aluno com histórico de enturmações anteriores"}}, {"text": "Escolha a nova turma de destino, a data da mudança e clique em 'Salvar' para atualizar o diário do professor.", "image": null}]}, {"id": "guia-ano-letivo", "is_featured": true, "title": "Como abrir o ano letivo", "category": "Usuários", "subtopic": "Ano letivo e matrículas", "path": "Escola > Cadastros > Escola > [Selecionar] > Definir Ano Letivo", "keywords": ["ano letivo", "abrir ano", "novo ano", "etapas", "bimestre", "trimestre", "calendario"], "steps": [{"text": "Acesse Módulo Escola > Cadastros > Escola, clique na escola e consulte os anos letivos cadastrados.", "image": {"local_filename": "user-figura-40-listagem-anos-letivos.png", "url": "https://ieducar.org/img/user-docs/user-figura-40-listagem-anos-letivos.png", "alt": "Listagem de anos letivos da escola com status de início e finalização"}}, {"text": "Clique no botão 'Definir ano letivo' (ou 'Novo'), informe o Ano (ex: 2026), o tipo de módulo (Bimestral/Trimestral) e as datas de início e fim de cada etapa.", "image": {"local_filename": "user-figura-41-modulos-cadastrados-ano-letivo.png", "url": "https://ieducar.org/img/user-docs/user-figura-41-modulos-cadastrados-ano-letivo.png", "alt": "Formulário para configurar módulos e etapas do ano letivo com total de dias e semanas"}}, {"text": "Para bloquear ou reabrir anos letivos anteriores na escola, utilize as opções de bloqueio disponíveis.", "image": {"local_filename": "user-figura-42-bloqueio-ano-letivo.png", "url": "https://ieducar.org/img/user-docs/user-figura-42-bloqueio-ano-letivo.png", "alt": "Tela de bloqueio do ano letivo impedindo movimentações em anos encerrados"}}]}, {"id": "guia-notas-faltas", "is_featured": true, "title": "Como lançar notas e faltas", "category": "Usuários", "subtopic": "Diário de classe", "path": "Escola > Movimentações > Notas e Faltas", "keywords": ["notas", "faltas", "lancar notas", "boletim", "diario", "media", "frequencia"], "steps": [{"text": "Acesse Módulo Escola > Movimentações > Notas e Faltas e use os filtros por Ano, Escola, Curso, Série, Turma e Componente Curricular.", "image": {"local_filename": "user-figura-61-busca-lancamento-faltas-notas.png", "url": "https://ieducar.org/img/user-docs/user-figura-61-busca-lancamento-faltas-notas.png", "alt": "Filtros para busca de diário de classe e lançamento de notas e faltas"}}, {"text": "Na tabela de estudantes carregada, digite as notas e a quantidade de faltas de cada aluno no período.", "image": {"local_filename": "user-figura-62-lancamento-faltas-notas.png", "url": "https://ieducar.org/img/user-docs/user-figura-62-lancamento-faltas-notas.png", "alt": "Grade de alunos para digitação direta de notas e faltas da turma"}}, {"text": "Para lançamentos individuais e verificação das médias da matrícula, consulte a tela de disciplinas.", "image": {"local_filename": "user-figura-63-disciplinas-matricula.png", "url": "https://ieducar.org/img/user-docs/user-figura-63-disciplinas-matricula.png", "alt": "Detalhamento de componentes curriculares e fórmulas de cálculo da média do aluno"}}, {"text": "Clique no botão 'Salvar' para atualizar os boletins e o histórico de toda a turma.", "image": null}]}, {"id": "guia-bloqueio", "is_featured": false, "title": "Como bloquear lançamento de notas e faltas", "category": "Usuários", "subtopic": "Geral", "path": "Escola > Ferramentas > Parâmetros > Bloqueio de lançamentos", "keywords": ["bloqueio", "travar notas", "prazo", "data limite", "fechar bimestre"], "steps": []}, {"id": "guia-regras-avaliacao", "is_featured": false, "title": "Como configurar regras de avaliação", "category": "Usuários", "subtopic": "Geral", "path": "Escola > Cadastros > Regras de Avaliação", "keywords": ["regra de avaliacao", "media", "arredondamento", "recuperacao", "aprovacao"], "steps": []}, {"id": "guia-censo", "is_featured": false, "title": "Como importar dados do Censo Escolar", "category": "Administradores", "subtopic": "Geral", "path": "EducaCenso > Importação", "keywords": ["censo", "educacenso", "inep", "carga inicial", "importar"], "steps": []}, {"id": "guide-2", "is_featured": false, "title": "Como configurar acesso ao sistema", "category": "Usuários", "subtopic": "Geral", "path": "", "keywords": ["abrir", "acessar", "acesso", "administrador", "basta", "conhecendo", "disponibilizado", "endereco", "endereço", "entao", "então", "foi", "fornecida", "informar", "internet", "matricula", "matrícula", "mesmo", "navegador", "senha"], "steps": []}, {"id": "guide-3", "is_featured": false, "title": "Como configurar tela inicial", "category": "Usuários", "subtopic": "Geral", "path": "", "keywords": ["acima", "alertas", "anteriormente", "aos", "basta", "busca", "campo", "conhecendo", "depois", "descritos", "desejado", "documento", "e-mail", "efetuar", "entrada", "enviara", "enviará", "exibidos", "existe", "forma", "funcionalidade", "informacao", "informação", "inicial", "inseridas", "inserir", "instrucoes", "instruções", "localizada", "login", "manutencao", "manutenção", "meio", "mensagens", "modulos", "módulos", "novidades", "opcao", "opção", "pagina", "periodica", "periódica", "pesquisar", "podem", "podera", "poderá", "página", "qualquer", "rapida", "recupera-la", "recuperar", "recuperá-la", "resultado", "rápida", "seguinte", "selecionar", "senha", "ser", "serao", "serão", "subdividem", "tambem", "também", "tela", "usuarios", "usuários", "utiliza-la", "utilizá-la", "voce", "você"], "steps": []}, {"id": "guide-4", "is_featured": false, "title": "Como configurar comportamento das listas e botões", "category": "Usuários", "subtopic": "Geral", "path": "", "keywords": ["aberta", "aberto", "alunos", "atualizar", "banco", "botao", "botoes", "botão", "botões", "cadastro", "cadastros", "cancelar", "caso", "clicar", "clique", "comporta", "comportamento", "comuns", "conforme", "conhecendo", "dados", "descartara", "descartará", "descrita", "destes", "distribuicao", "distribuição", "editar", "entre", "especificas", "especificos", "específicas", "específicos", "exibida", "exibidos", "funcao", "função", "gravara", "gravará", "habilitados", "historico", "histórico", "ilustrada", "ilustrados", "informados", "insercao", "inserção", "item", "listagem", "listas", "matricula", "matrícula", "nao", "navegacao", "navegação", "neste", "nova", "novo", "não", "opcoes", "opções", "pagina", "paginas", "podem", "pressionado", "pressionar", "página", "páginas", "realizado", "registro", "registros", "retornara", "retornará", "salvar", "sao", "seguir", "ser", "surgem", "são", "uniforme", "variar", "varias", "voltar", "várias"], "steps": []}, {"id": "guide-5", "is_featured": false, "title": "Como configurar filtros de busca e seleção", "category": "Usuários", "subtopic": "Geral", "path": "", "keywords": ["abaixo", "abertos", "alimentado", "alimentados", "alunos", "arquivos", "assim", "atestado", "basta", "botao", "botão", "busca", "buscar", "cadastros", "campo", "campos", "componente", "conhecendo", "contem", "contém", "curso", "cursos", "data", "depois", "descrita", "desta", "diferentes", "digitadas", "digitar", "disponiveis", "disponíveis", "documento", "emissao", "emissão", "enter", "escola", "escolas", "especifica", "específica", "esteja", "exemplo", "exibidos", "exibir", "exportacoes", "exportações", "filtrar", "filtro", "filtros", "foram", "imagem", "inferior", "informacoes", "informar", "informações", "instituicao", "instituição", "isto", "listados", "listagem", "medida", "mesmo", "modifica", "mostra", "mostrara", "mostrará", "nascimento", "neste", "nome", "normalmente", "outra", "pagina", "parte", "pode", "podem", "possivel", "possível", "pressionar", "processar", "página", "rapida", "referencia", "referência", "registros", "relatorio", "relatorios", "relatório", "relatórios", "rápida", "sao", "selecao", "selecionar", "seleção", "ser", "sera", "serao", "serve", "será", "serão", "sucessivamente", "são", "teclado", "usados", "usuario", "usuário", "utilizados", "vaga", "valores", "visualizado"], "steps": []}, {"id": "guide-7", "is_featured": false, "title": "Como configurar módulo endereçamento", "category": "Usuários", "subtopic": "Geral", "path": "", "keywords": ["acessar", "alunos", "apresentado", "bairros", "barra", "basta", "cadastro", "ceps", "clicar", "devem", "enderecamento", "endereçamento", "escolas", "informacoes", "informações", "inseridas", "instituicao", "instituição", "logradouros", "mesmo", "modulo", "modulos", "módulo", "módulos", "outros", "pessoas", "possam", "ser", "utilizados"], "steps": []}, {"id": "guide-8", "is_featured": false, "title": "Como configurar módulo pessoas", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Pessoas > Cadastros > Física / Jurídica", "keywords": ["ainda", "alem", "alunos", "além", "cadastramento", "cadastro", "censo", "coleta", "cor", "dados", "deficiencia", "deficiência", "dentro", "depois", "enderecamento", "endereçamento", "escolar", "escolas", "estas", "fase", "fazer", "fisicas", "físicas", "importantes", "incluem", "informacoes", "informações", "inserir", "juridicas", "jurídicas", "modulo", "módulo", "outros", "pais", "parte", "passa", "pessoas", "pode", "possivel", "possível", "professores", "raca", "raça", "registrados", "registrar", "serao", "serão", "tipos", "trabalhadas", "unico", "voce", "você", "único"], "steps": []}, {"id": "guide-9", "is_featured": false, "title": "Como configurar tipos de deficiência e cor ou raça", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Pessoas > Cadastros > Tipos > Tipos de cor ou raça", "keywords": ["alunos", "apresentado", "apresentem", "assim", "atualizados", "auditiva", "base", "cadastramento", "cadastrar", "cadastro", "cadastros", "censo", "coleta", "conforme", "cor", "correta", "correto", "deficiencia", "deficiencias", "deficiência", "deficiências", "definidos", "disponibilizado", "docentes", "durante", "enderecamento", "endereçamento", "escolar", "etc", "exemplos", "fisica", "física", "identificados", "importante", "importantes", "informacao", "informacoes", "informadas", "informação", "informações", "manter", "mesmos", "modulo", "módulo", "nacionais", "nacionalmente", "necessario", "necessário", "pessoas", "podem", "podera", "poderá", "portanto", "posteriormente", "pre-cadastrados", "professores", "pré-cadastrados", "raca", "raça", "relacionado", "responsavel", "responsável", "sao", "ser", "serao", "serão", "são", "tabela", "tambem", "também", "tipos", "utilizados", "vinculo", "visual", "voce", "você", "vínculo"], "steps": []}, {"id": "guide-11", "is_featured": false, "title": "Como configurar transferência, abandono ou saída de aluno", "category": "Usuários", "subtopic": "Geral", "path": "Escola > Cadastros > Aluno > Matrículas > Visualizar Matrícula", "keywords": ["abandono", "advertencia", "advertência", "algum", "aluno", "alunos", "aos", "atestado", "atingir", "aviso", "cadastrar", "cadastro", "cadastros", "desfazer", "desistencia", "desistência", "desrespeito", "disciplinares", "educacional", "emitido", "endereco", "endereço", "escola", "etc", "evasao", "exemplos", "falecimento", "fim", "informadas", "informar", "matriculas", "matrículas", "maximo", "modulo", "mudanca", "mudança", "máximo", "módulo", "neste", "numero", "número", "ocorrencias", "ocorrências", "podem", "podera", "poderá", "possivel", "possível", "posteriormente", "professores", "realizar", "registrar", "responsaveis", "responsáveis", "saida", "ser", "serao", "serão", "significa", "tambem", "também", "tipo", "tipos", "transferencia", "transferencias", "transferência", "transferências", "troca", "turma", "usadas", "utilizados", "voce", "você", "transferir", "mudanca de escola"], "steps": []}, {"id": "guide-12", "is_featured": false, "title": "Como configurar instituição", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Instituição", "keywords": ["abaixo", "acessar", "ainda", "alem", "além", "anexa-lo", "anexá-lo", "apresenta-lo", "apresentá-lo", "arquivo", "basta", "botao", "botão", "cadastrar", "cadastro", "cadastros", "carregar", "clicar", "conforme", "contemplados", "controle", "dados", "datas", "definir", "devera", "deverá", "dispoe", "dispõe", "documentacao", "documentacoes", "documentação", "documentações", "documento", "documentos", "educacional", "emitir", "ensino", "escola", "escolas", "escolha", "especificos", "específicos", "estes", "exclusao", "exclusão", "exemplo", "fim", "funcionalidade", "funcionalidades", "inserir", "instituicao", "instituicoes", "instituição", "instituições", "ira", "irá", "listagem", "meio", "mesma", "modulo", "municipio", "município", "módulo", "nao", "neste", "nova", "não", "opcoes", "opções", "padrao", "padroes", "padrão", "padrões", "parametros", "parâmetros", "permitirao", "permitirão", "podera", "poderá", "possa", "possivel", "possível", "proprios", "próprios", "quanto", "rede", "relatorios", "relatórios", "respectivo", "responsaveis", "responsáveis", "sao", "selecionar", "serie", "são", "série", "tanto", "tela", "titulo", "título", "usuario", "usuário", "visualizacao", "visualização", "voce", "você"], "steps": []}, {"id": "guide-13", "is_featured": false, "title": "Como configurar cursos", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Cursos", "keywords": ["antes", "cadastramento", "cadastrar", "cadastro", "cadastros", "curso", "cursos", "detalhadas", "educacional", "ensino", "entretanto", "escola", "estas", "habilitacoes", "habilitações", "importante", "informacoes", "informações", "iniciar", "instituicao", "instituição", "modulo", "módulo", "neste", "nivel", "nível", "oferecidos", "opcoes", "opções", "podera", "poderá", "preenchidas", "principais", "proprio", "próprio", "regime", "sao", "seguir", "sendo", "são", "tipo", "tipos", "voce", "você"], "steps": []}, {"id": "guide-14", "is_featured": false, "title": "Como configurar escolas", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Escolas", "keywords": ["ano", "antes", "assim", "cadastradas", "cadastramento", "cadastrar", "cadastro", "cadastros", "componentes", "copia", "cópia", "detalhadas", "duplicadas", "educacional", "ensino", "entretanto", "escola", "escolas", "estas", "farao", "farão", "foram", "importante", "informacoes", "informações", "iniciado", "iniciar", "letivo", "localizacao", "localização", "modulo", "municipal", "módulo", "necessario", "necessário", "neste", "novo", "opcoes", "opções", "parte", "podera", "poderá", "preenchidas", "principais", "realiza", "rede", "renomear", "sao", "seguir", "sendo", "sera", "serao", "será", "serão", "são", "tipo", "tipos", "turmas", "voce", "você"], "steps": []}, {"id": "guide-15", "is_featured": false, "title": "Como configurar alunos", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Alunos", "keywords": ["aberta", "alterado", "aluno", "alunos", "apos", "apresentada", "após", "basicos", "beneficios", "benefícios", "botao", "botão", "busca", "básicos", "cadastrar", "cadastro", "cadastros", "caso", "cep", "consiga", "controlar", "dados", "deficiencias", "deficiências", "devera", "deverá", "digitando", "diretamente", "disponiveis", "disponíveis", "editando", "editar", "educacional", "endereco", "endereço", "escola", "estiver", "exibida", "gravar", "informacoes", "informado", "informações", "isto", "janela", "localizar", "lupa", "modulo", "mostra", "módulo", "nao", "neste", "novamente", "novo", "novos", "não", "opcao", "opcoes", "opção", "opções", "outras", "pessoa", "podera", "poderá", "preencher", "pressione", "rapido", "responsaveis", "responsáveis", "rápido", "selecionar", "sera", "será", "similar", "tela", "ter", "usando", "voce", "você"], "steps": []}, {"id": "guide-16", "is_featured": false, "title": "Como configurar unificação de alunos", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Escola > Ferramentas > Unificações > Unificação de alunos", "keywords": ["acaba", "alguns", "aluno", "alunos", "antes", "autonomia", "botao", "botão", "busca", "cadastro", "cadastros", "campo", "campos", "casos", "clicar", "codigo", "conforme", "correto", "corrigir", "cpf", "criacao", "criação", "código", "dados", "definido", "demais", "devera", "deverá", "diversas", "duplicado", "duplicados", "duplicidade", "educacional", "efetuar", "embora", "entretanto", "escola", "estes", "evitar", "excluindo", "excluir", "existe", "ferramentas", "foram", "forma", "funcionalidade", "historicos", "históricos", "incorreta", "inep", "informacoes", "informados", "informações", "inserir", "mantido", "matriculas", "matrículas", "migradas", "modulo", "módulo", "nao", "nome", "não", "ocorrer", "pesquisando", "pode", "portanto", "possua", "preencher", "preenchidos", "principal", "registros", "reinserir", "respectivo", "resultar", "salvar", "sem", "sera", "serao", "será", "serão", "tabela", "tela", "ter", "unificacao", "unificacoes", "unificação", "unificações", "usuario", "usuário", "utilizados", "verificacao", "verificação"], "steps": []}, {"id": "guide-17", "is_featured": false, "title": "Como configurar componentes curriculares", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Tipos > Componentes curriculares > Tipos de dispensa", "keywords": ["aluno", "alunos", "antes", "areas", "basta", "breve", "cadastramento", "cadastrar", "cadastro", "cadastros", "capitulos", "capítulos", "componentes", "conhecimento", "curriculares", "descricao", "descrição", "destes", "determinada", "determinados", "disciplina", "disciplinas", "dispensa", "dispensados", "dispensar", "educacional", "ensino", "escola", "explicado", "importante", "informados", "informar", "iniciar", "lecionadas", "matricula", "matrícula", "modulo", "motivos", "módulo", "neste", "opcao", "opção", "permite", "podem", "podera", "poderao", "poderá", "poderão", "pre-cadastrados", "processo", "proximos", "pré-cadastrados", "próximos", "rede", "sendo", "ser", "sera", "serao", "será", "serão", "tipos", "utilizada", "visto", "voce", "você", "áreas"], "steps": []}, {"id": "guide-18", "is_featured": false, "title": "Como configurar séries e configuração dos anos escolares", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Séries da escola", "keywords": ["aberto", "acessar", "anos", "botao", "botão", "cadastradas", "cadastrar", "cadastro", "cadastros", "chamado", "configuracao", "configurados", "configurar", "configuração", "conforme", "curso", "definira", "definirá", "depois", "detalhadas", "determinada", "deverao", "deverão", "disciplina", "disponivel", "disponível", "edicao", "edição", "educacional", "escola", "escolares", "escolas", "especificas", "específicas", "estara", "estará", "exibira", "exibirá", "instituicao", "instituição", "listagem", "modo", "modulo", "módulo", "opcoes", "opções", "padroes", "padrões", "podera", "poderá", "pressiona-lo", "pressioná-lo", "principais", "procedimento", "sao", "seguida", "seguir", "ser", "sera", "serao", "series", "será", "serão", "são", "séries", "vistas", "voce", "você"], "steps": []}, {"id": "guide-19", "is_featured": false, "title": "Como configurar infraestrutura", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Infraestrutura", "keywords": ["ainda", "ambientes", "aos", "blocos", "cadastrar", "cadastro", "cadastros", "comodos", "cômodos", "detalhados", "dito", "educacional", "ensino", "escola", "escolas", "estes", "funcoes", "funções", "informacoes", "informações", "infraestrutura", "modulo", "módulo", "podera", "poderá", "possivel", "possível", "predio", "predios", "propriamente", "proprios", "prédio", "prédios", "próprios", "rede", "referentes", "sao", "seguir", "são", "tipos", "voce", "você"], "steps": []}, {"id": "guide-20", "is_featured": false, "title": "Como configurar turmas", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Turmas", "keywords": ["aba", "adicionais", "alfabeticamente", "alunos", "ano", "atualiza-los", "atualizá-los", "aulas", "botoes", "botões", "cadastro", "cadastros", "campos", "capacidade", "caso", "censo", "coleta", "colhidos", "contem", "contém", "corrente", "dados", "detalhadas", "dica", "diferentes", "dois", "editar", "educacenso", "educacional", "encontradas", "escola", "especificos", "específicos", "estes", "existem", "facilite", "fase", "gerais", "horarios", "horários", "importante", "inep", "informar", "inicial", "letivo", "listas", "localizacao", "localização", "mesmo", "modulo", "momento", "módulo", "nao", "neste", "não", "obrigatorios", "obrigatórios", "opcoes", "opções", "ordenacao", "ordenação", "periodos", "períodos", "podera", "poderá", "portanto", "presencas", "presenças", "principais", "processos", "professor", "reclassificar", "regente", "sao", "seguir", "sejam", "sendo", "sequencia", "sequência", "serao", "serie", "serão", "são", "série", "tambem", "também", "trabalhar", "tratando", "turma", "turmas", "visualizacao", "visualização", "voce", "você"], "steps": []}, {"id": "guide-21", "is_featured": false, "title": "Como configurar alocação e horários dos professores", "category": "Usuários", "subtopic": "Geral", "path": "Servidores > Cadastros > Servidor > Alocação", "keywords": ["acesso", "alocacao", "alocação", "apresentaremos", "avaliacao", "avaliação", "cadastrais", "cadastro", "caso", "componente", "configuracoes", "configurações", "consultas", "controle", "criado", "desempenho", "detalhes", "devera", "deverá", "disciplina", "docente", "entre", "escola", "estes", "externo", "fins", "fornecerao", "fornecerão", "geral", "gestao", "gestores", "gestão", "horario", "horarios", "horas", "horário", "horários", "i-educar", "informacoes", "informações", "insercao", "inserção", "interno", "modulo", "módulo", "nesta", "outros", "possibilitara", "possibilitará", "processos", "professores", "quadro", "registro", "secao", "secretaria", "seguir", "ser", "servidor", "servidores", "seção", "sobre", "tenha", "tera", "terá", "topicos", "turmas", "tópicos", "uso", "usuario", "usuarios", "usuário", "usuários", "visao", "visão", "professor", "alocar", "quadro de horario"], "steps": []}, {"id": "guide-22", "is_featured": false, "title": "Como configurar alocação e horários dos professores", "category": "Usuários", "subtopic": "Geral", "path": "Servidores > Cadastros > Servidor > Alocação", "keywords": ["acessar", "acesso", "alocacao", "apenas", "assim", "atrasos", "avaliacoes", "avaliações", "cadastrado", "cadastro", "cadastros", "carga", "caso", "centralizador", "componente", "configuracoes", "configurações", "controladas", "desempenho", "deve-se", "diario", "disciplina", "diário", "docente", "entao", "então", "escolar", "faltas", "feito", "fisica", "funcionarios", "funcionários", "física", "gerenciados", "gestao", "gestão", "horaria", "horario", "horária", "i-educar", "importante", "informacoes", "informações", "internamente", "lembrar", "membros", "mesmo", "modulo", "municipal", "municipio", "município", "módulo", "necessario", "necessário", "neste", "nota", "obrigatorio", "obrigatório", "online", "outras", "permissoes", "permissões", "pertinentes", "pessoa", "previamente", "professores", "proprio", "próprio", "quadro", "rede", "registrara", "registrará", "secretaria", "sejam", "sendo", "sera", "serao", "servidor", "servidores", "será", "serão", "sido", "somente", "tenha", "ter", "unico", "unidade", "usuario", "usuarios", "usuário", "usuários", "utilizarao", "utilizarão", "vincular", "vinculo", "vínculo", "único", "professor", "alocar", "quadro de horario"], "steps": []}, {"id": "guide-23", "is_featured": false, "title": "Como configurar funções e categorias ou níveis", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Servidores > Cadastros > Tipos > Categoria ou níveis", "keywords": ["cadastrar", "cadastro", "cadastros", "carreira", "categoria", "categorias", "diretores", "entre", "estas", "estatuto", "faixas", "funcoes", "funções", "gerenciados", "informacoes", "informações", "inserir", "modulo", "momento", "módulo", "niveis", "níveis", "oriundas", "outros", "plano", "podera", "poderá", "professores", "profissionais", "progressao", "progressão", "relacao", "relação", "salariais", "secretarios", "secretários", "seguir", "sera", "serao", "servidor", "servidores", "será", "serão", "subniveis", "subníveis", "tabelas", "tais", "tipos", "usuario", "usuário", "utilizadas", "visto"], "steps": []}, {"id": "guide-24", "is_featured": false, "title": "Como configurar escolaridade e motivos de afastamento", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Servidores > Cadastros > Tipos > Motivos de afastamento", "keywords": ["afastados", "afastamento", "afastamentos", "auxilio-maternidade", "auxílio-maternidade", "cadastrar", "cadastro", "cadastros", "completo", "escolaridade", "especifica", "específica", "etc", "executar", "exemplos", "existe", "funcao", "funcoes", "função", "funções", "informado", "informar", "licenca", "licença", "modulo", "motivo", "motivos", "módulo", "neste", "niveis", "nivel", "níveis", "nível", "obrigatorio", "obrigatório", "pagina", "podera", "poderá", "pos-graduacao", "posteriormente", "premio", "previamente", "professores", "prêmio", "página", "pós-graduação", "sao", "sera", "serao", "servidor", "servidores", "será", "serão", "superior", "são", "tipos", "usuario", "usuário", "utilizadas"], "steps": []}, {"id": "guide-25", "is_featured": false, "title": "Como configurar quadro de horários", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Servidores > Cadastros > Quadro de horários", "keywords": ["aberta", "aberto", "acessar", "alocacao", "alocação", "aula", "buscar", "cadastro", "cadastros", "calendario", "calendário", "carga", "caso", "clicar", "componente", "controle", "criar", "curricular", "deve", "deverao", "deverão", "dia", "dias", "disciplina", "disciplinas", "disponivel", "disponível", "efetuar", "eficiente", "ensino", "estas", "existente", "feito", "final", "gestao", "gestão", "horaria", "horario", "horarios", "horas", "horária", "horário", "horários", "informacoes", "informados", "informações", "inicial", "janela", "mesmos", "modulo", "municipal", "módulo", "nova", "novo", "numero", "número", "opcao", "opção", "pode", "pois", "porem", "porém", "possivel", "possível", "procedimento", "professor", "professores", "quadro", "rede", "relacionada", "seja", "selecionada", "sem", "semana", "ser", "sera", "servidores", "será", "similar", "turma", "usuario", "usuário"], "steps": []}, {"id": "guide-27", "is_featured": false, "title": "Como configurar permissões (tipos de usuário/usuários)", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Configurações > Permissões > Usuários", "keywords": ["acessarao", "acessarão", "acesso", "ainda", "atribuicoes", "atribuições", "atualizacoes", "atualizações", "atualmente", "biblioteca", "cadastro", "categorias", "configuracoes", "configurações", "define", "demais", "descontinuado", "descritas", "determinadas", "dito", "documentado", "escola", "estas", "existentes", "foi", "funcionalidades", "garantem", "hierarquico", "hierárquico", "i-educar", "incluem", "instituicao", "instituição", "manutencao", "manutenção", "modulo", "módulo", "nao", "nem", "nivel", "nota", "não", "nível", "permissao", "permissoes", "permissão", "permissões", "permite", "propriamente", "recebe", "redes", "respeitando", "sao", "segue", "seguir", "sera", "será", "são", "tipo", "tipos", "usam", "usuario", "usuarios", "usuário", "usuários", "versoes", "versões", "vinculado"], "steps": []}, {"id": "guide-29", "is_featured": false, "title": "Como configurar auditoria e backups", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Configurações > Ferramentas > Auditoria geral / Backups", "keywords": ["alteracoes", "alterações", "analisar", "assim", "auditoria", "automatica", "automática", "backup", "backups", "baixar", "campo", "configuracoes", "configurações", "dados", "datas", "desejado", "determinado", "dia", "disponibilizado", "download", "durante", "efetuadas", "fazer", "ferramentas", "forma", "geral", "gerencial", "meia", "modulo", "momento", "módulo", "nivel", "noite", "nível", "partir", "periodo", "período", "podendo", "possibilidade", "possivel", "possível", "preenchidos", "referida", "sempre", "tanto", "tela", "tera", "terá", "usuario", "usuário", "verificacao", "verificar", "verificação"], "steps": []}, {"id": "guide-30", "is_featured": false, "title": "Como configurar exportação de usuários", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Configurações > Ferramentas > Exportação de usuários", "keywords": ["assim", "ativos", "auxiliar", "cadastrados", "configuracoes", "configurações", "desenvolvida", "efetuado", "escolar", "exportacao", "exportação", "exporte", "ferramenta", "ferramentas", "foi", "gestor", "mesmo", "modulo", "módulo", "permite", "somente", "tipo", "trabalho", "usuario", "usuarios", "usuário", "usuários"], "steps": []}, {"id": "guide-31", "is_featured": false, "title": "Como Realizar uma Nova Matrícula", "category": "Usuários", "subtopic": "Geral", "path": "Escola > Cadastros > Aluno > [Selecionar] > Matrículas", "keywords": ["abertura", "abrir ano", "aluno", "alunos", "ano", "anos", "apresentado", "automatica", "automática", "bem", "bimestre", "cadastro", "calendario", "calendário", "configurados", "efetuar", "enturmacao", "enturmar", "enturmação", "escolar", "escolares", "escolas", "estudante", "etapas", "fechar ano", "geral", "gerenciados", "gerenciamento", "historicos", "históricos", "horarios", "horários", "letivo", "letivos", "matricula", "matriculas", "matrícula", "matrículas", "nesta", "procedimento", "quadro", "rematricula", "rematrícula", "reserva", "sao", "secao", "sera", "serao", "será", "serão", "seção", "são", "tambem", "também", "trimestre", "turma", "vaga", "visao", "vistos", "visão", "matricular", "novo aluno", "ingresso", "cadastro aluno"], "steps": []}, {"id": "guide-32", "is_featured": false, "title": "Como configurar módulos", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Tipos > Escolas > Tipos de Módulos", "keywords": ["ano", "bimestral", "cadastro", "cadastros", "comuns", "definem", "ensino", "escola", "escolar", "escolas", "estes", "etapas", "existentes", "informar", "instituicao", "instituição", "letivo", "listagem", "matriculas", "matrículas", "meses", "modulo", "modulos", "módulo", "módulos", "neste", "periodo", "período", "pode", "podera", "poderá", "semanas", "semestral", "ser", "serie", "série", "tipos", "tres", "trimestral", "três", "visualizada", "voce", "você"], "steps": []}, {"id": "guide-33", "is_featured": false, "title": "Como configurar abertura e configuração do ano letivo", "category": "Usuários", "subtopic": "Geral", "path": "Escola > Cadastros > Escola > [Selecionar] > Definir Ano Letivo", "keywords": ["abrir", "abrir ano", "adicionar", "ainda", "aluno", "andamento", "ano", "anos", "apresentada", "basta", "bimestre", "botao", "botão", "cadastrar", "cadastro", "cadastros", "calendario", "caso", "concluir", "conforme", "conste", "data", "datas", "definir", "deseja", "desejado", "desejar", "desta", "deve", "dias", "dois", "duas", "editar", "escola", "escolar", "escolas", "etapas", "exemplo", "exibidos", "fechar ano", "fim", "final", "finalizados", "foram", "forma", "gerenciar", "igual", "inferior", "informada", "informados", "informar", "iniciados", "inicial", "inicio", "inserir", "início", "letivo", "letivos", "listagem", "matriculas", "matrículas", "modulo", "modulos", "módulo", "módulos", "nao", "nesse", "neste", "nota", "notas", "nova", "novo", "não", "obter", "opcao", "opcoes", "operacao", "operação", "opção", "opções", "outras", "pagina", "parte", "periodos", "períodos", "pode", "possivel", "possível", "precisa", "precisaria", "pressionar", "página", "quantidade", "questao", "questão", "salvar", "selecionar", "semestrais", "ser", "sera", "serao", "será", "serão", "trimestre", "usuario", "usuário", "utilizado", "ano letivo", "novo ano", "dias letivos"], "steps": []}, {"id": "guide-34", "is_featured": false, "title": "Como configurar bloqueio de prazos de notas e faltas", "category": "Usuários", "subtopic": "Geral", "path": "Escola > Ferramentas > Parâmetros > Bloqueio de lançamentos", "keywords": ["ano", "apresentadas", "bloqueio", "bloqueios", "caso", "completo", "data limite", "duas", "efetuar", "escolar", "escolas", "especificas", "específicas", "etapas", "existentes", "faltas", "fechar lancamento", "formas", "lancamento", "lançamento", "letivo", "limite", "matriculas", "matrículas", "neste", "notas", "possivel", "possível", "prazo", "sendo", "serao", "serão", "somente", "topico", "travar", "tópico", "travar notas", "fechar bimestre", "bloquear notas"], "steps": []}, {"id": "guide-35", "is_featured": false, "title": "Como configurar abertura e configuração do ano letivo", "category": "Usuários", "subtopic": "Geral", "path": "Escola > Cadastros > Escola > [Selecionar] > Definir Ano Letivo", "keywords": ["abaixo", "abrir ano", "alteradas", "alterados", "alunos", "ano", "apos", "apresentara", "apresentará", "após", "basta", "bimestre", "bloqueado", "bloqueio", "botao", "botão", "cadastro", "calendario", "campos", "caso", "controle", "dados", "data", "data limite", "dias", "entre", "erroneamente", "escola", "escolar", "esta", "estabelecidas", "está", "etapas", "faltas", "fechar ano", "fechar lancamento", "ferramentas", "final", "fora", "funcionalidade", "habilitar", "impedindo", "inicial", "insercao", "inseridas", "inseridos", "inserção", "instituicao", "instituição", "lancamento", "lancar", "lançamento", "lançar", "letivo", "limite", "listagem", "matriculas", "matrículas", "mensagem", "modulo", "municipio", "município", "módulo", "nessa", "nesta", "notas", "novo", "obrigatorios", "obrigatórios", "parametros", "parâmetros", "periodo", "período", "podemos", "poderao", "poderão", "possibilitara", "possibilitará", "prazo", "preencher", "salvar", "seguinte", "sejam", "selecionar", "ser", "somente", "tente", "travar", "trimestre", "usuario", "usuário", "ver", "voce", "você", "ano letivo", "novo ano", "dias letivos"], "steps": []}, {"id": "guide-36", "is_featured": false, "title": "Como configurar bloqueio de prazos de notas e faltas", "category": "Usuários", "subtopic": "Geral", "path": "Escola > Ferramentas > Parâmetros > Bloqueio de lançamentos", "keywords": ["abaixo", "acima", "alteradas", "alunos", "ano", "apos", "apresentara", "apresentará", "após", "assim", "basta", "bloqueado", "bloqueia", "bloqueio", "boletim", "botao", "botão", "cadastro", "campos", "caso", "citado", "conceito", "data", "data limite", "datas", "determinada", "diario", "dias", "diferenca", "diferentemente", "diferença", "efetuar", "enquanto", "entre", "escola", "escolar", "escolas", "especificas", "específicas", "esta", "estabelecidas", "está", "etapa", "falta", "faltas", "fechar lancamento", "ferramentas", "final", "fora", "forma", "frequencia", "habilitar", "implemente", "individual", "inicial", "inseridas", "instituicao", "instituição", "lancamento", "lancamentos", "lancar", "lançamento", "lançamentos", "lançar", "letivo", "limite", "listagem", "matriculas", "matrículas", "media", "mensagem", "modulo", "municipio", "município", "módulo", "nessa", "nesta", "nota", "notas", "novo", "obrigatorios", "obrigatórios", "opcao", "opção", "parametros", "parâmetros", "periodo", "permite", "período", "podemos", "poderao", "poderão", "possibilita", "prazo", "preencher", "primeira", "recuperacao", "restricao", "restrição", "salvar", "sao", "seguinte", "segunda", "selecionar", "semelhantes", "ser", "somente", "são", "tente", "travar", "unica", "usuario", "usuário", "ver", "voce", "você", "única", "travar notas", "fechar bimestre", "bloquear notas"], "steps": []}, {"id": "guide-37", "is_featured": false, "title": "Como configurar calendário letivo", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Tipos > Calendários > Tipos de evento do calendário", "keywords": ["aberto", "acessar", "ano", "anos", "azul", "botao", "botão", "branca", "buscar", "cadastrar", "cadastro", "cadastros", "calendario", "calendarios", "calendário", "calendários", "caso", "comemorativas", "consideradas", "contam", "cor", "cores", "datas", "definido", "dia", "dias", "diferenciadas", "domingos", "entre", "escola", "escolar", "etc", "evento", "exibe", "exibidas", "exista", "existente", "extra", "extras", "for", "informacao", "informacoes", "informar", "informação", "informações", "laranja", "letivo", "letivos", "marcada", "matriculas", "matrículas", "meio", "menu", "meses", "modulo", "módulo", "nao", "nao-letivo", "navegar", "nenhum", "novo", "não", "não-letivo", "opcao", "opção", "outra", "parte", "pertinente", "pode-se", "possivel", "possível", "qualquer", "referente", "registrar", "representa", "rosa", "sabados", "sao", "superior", "sábados", "são", "tais", "tipo", "tipos", "visualizar"], "steps": []}, {"id": "guide-38", "is_featured": false, "title": "Como Realizar uma Nova Matrícula", "category": "Usuários", "subtopic": "Geral", "path": "Escola > Cadastros > Aluno > [Selecionar] > Matrículas", "keywords": ["acesso", "aluno", "ano", "apresentado", "ato", "botao", "botão", "cadastro", "cadastros", "campo", "curso", "dados", "data", "dentre", "desejada", "desta", "destino", "detalhe", "diretamente", "efetuar", "entrada", "enturmacao", "enturmar", "enturmação", "escola", "escolar", "esta", "estudante", "está", "exibido", "existem", "feita", "historico", "histórico", "individualmente", "informando", "letivo", "localizado", "logo", "matricula", "matriculas", "matrícula", "matrículas", "modulo", "mostra", "módulo", "nesta", "nova", "opcao", "opcoes", "opção", "opções", "pagina", "permite", "pode", "possivel", "possível", "preencha", "pressione", "processo", "processos", "página", "saida", "saída", "secao", "selecionar", "ser", "serie", "seção", "situacao", "situação", "série", "tambem", "também", "tratar", "turma", "vaga", "varias", "ver", "visualizar", "várias", "matricular", "novo aluno", "ingresso", "cadastro aluno"], "steps": []}, {"id": "guide-39", "is_featured": false, "title": "Como configurar enturmação de alunos (individual e em lote)", "category": "Usuários", "subtopic": "Geral", "path": "Escola > Movimentações > Enturmações em lote", "keywords": ["acessar", "ainda", "alocar", "aluno", "alunos", "ano", "apresentados", "apto", "botao", "botão", "cadastradas", "cadastro", "cadastros", "caso", "clicar", "configurada", "conforme", "copia", "copiar", "copiar enturmacao", "correspondente", "criando", "curso", "cópia", "daquela", "desta", "determinado", "diferentes", "diretamente", "efetuar", "enturmacao", "enturmacoes", "enturmados", "enturmar", "enturmação", "enturmações", "escola", "escolar", "esta", "esteja", "estes", "estiverem", "está", "exibida", "exibidas", "exibido", "exibidos", "faltas", "fizer", "inclusive", "informando", "letivo", "listagem", "lote", "marcar", "matricula", "matriculados", "matriculas", "matrícula", "matrículas", "meio", "mensagem", "menu", "mesma", "modulo", "momento", "movimentacoes", "movimentações", "módulo", "nao", "necessario", "necessário", "nesta", "notas", "nova", "não", "opcao", "opção", "outra", "pertencem", "pode", "porem", "porém", "possivel", "possível", "procedimento", "processo", "quanto", "realizado", "realizar", "receber", "registro", "relaciona", "relatorios", "relatórios", "sala", "salvar", "seja", "selecionados", "selecionar", "sendo", "ser", "sera", "serao", "serie", "será", "serão", "situacao", "situação", "série", "tambem", "também", "tanto", "turma", "turmas", "usuario", "usuário", "visualizado", "visualizar", "entrumacao", "alocar aluno", "distribuir alunos", "vincular turma", "distribuir", "vincular"], "steps": []}, {"id": "guide-40", "is_featured": false, "title": "Como configurar enturmação de alunos (individual e em lote)", "category": "Usuários", "subtopic": "Geral", "path": "Escola > Movimentações > Enturmações em lote", "keywords": ["alocar", "aluno", "alunos", "ano", "anterior", "automatica", "automaticamente", "automática", "basta", "botao", "botão", "cadastro", "cadastros", "configurado", "conforme", "copiar enturmacao", "corrente", "curso", "data", "definem", "destino", "determinada", "enturmacao", "enturmacoes", "enturmar", "enturmação", "enturmações", "escola", "escolar", "esta", "estudante", "está", "executado", "fim", "letivo", "localizado", "lote", "matricula", "matriculas", "matrícula", "matrículas", "menu", "modulo", "mostra", "movimentacoes", "movimentações", "módulo", "necessario", "necessário", "neste", "origem", "pode", "possa", "pressionar", "procedimento", "processo", "proximo", "próximo", "rematricula", "rematriculara", "rematriculará", "rematrícula", "sala", "salvar", "selecionar", "sequencia", "sequencias", "sequência", "sequências", "ser", "serie", "série", "tipos", "turma", "usuario", "usuário", "vaga", "visualizado", "entrumacao", "alocar aluno", "distribuir alunos", "vincular turma", "distribuir", "vincular"], "steps": []}, {"id": "guide-41", "is_featured": false, "title": "Como configurar histórico escolar", "category": "Usuários", "subtopic": "Geral", "path": "Nota: Existem observações em cada ano escolar que podem ser fixadas, não sendo necessário redigitar a cada vez que processar o histórico. Neste caso, o texto do campo Observação deve ser informado no campo Observação histórico do cadastro de Séries (Cadastros > Séries).", "keywords": ["alguns", "alunos", "ano", "apenas", "apresentado", "asterisco", "base", "cadastro", "cadastros", "campo", "campos", "caso", "configuracao", "configuração", "conforme", "considera", "consiste", "dados", "definidos", "definir", "deve", "durante", "efetue", "escola", "escolar", "escolares", "executar", "existem", "explicadas", "faltas", "fases", "filtragem", "fixadas", "flexivel", "flexível", "formulario", "formulário", "funcao", "função", "geracao", "gerados", "geração", "historico", "historicos", "histórico", "históricos", "informacoes", "informado", "informações", "instituicao", "instituição", "lancadas", "lançadas", "letivo", "liberdade", "manualmente", "marcadas", "mas", "matriculas", "matrículas", "momento", "nao", "necessario", "necessário", "neste", "nota", "notas", "não", "obrigatorias", "obrigatorios", "obrigatórias", "obrigatórios", "observacao", "observacoes", "observação", "observações", "opcoes", "opções", "parametrizacao", "parametrização", "parametros", "parâmetros", "permite", "podem", "pois", "processadas", "processamento", "processar", "processo", "quantidade", "redigitar", "referentes", "registradas", "sao", "seguir", "sejam", "sendo", "ser", "serao", "serem", "series", "serão", "sugere-se", "são", "séries", "tem", "texto", "turma", "usuario", "usuário", "utiliza", "vez"], "steps": []}, {"id": "guide-42", "is_featured": false, "title": "Como configurar histórico escola avulso", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Alunos > Selecionar Aluno > Selecionar Atualizar histórico", "keywords": ["acessar", "aluno", "alunos", "ano", "apresentada", "atualizar", "avulso", "botao", "botão", "cadastrar", "cadastro", "cadastros", "campo", "caso", "conforme", "consiste", "criar", "demonstrado", "devera", "deverá", "disciplina", "disciplinas", "dispensando", "escola", "escolar", "escolares", "esta", "está", "exibido", "faltas", "forma", "globalizadas", "historico", "historicos", "histórico", "históricos", "informacoes", "informatizados", "informações", "insercao", "inserção", "instituicoes", "instituições", "lancadas", "lançadas", "letivo", "lista", "listagem", "localizada", "manual", "marcada", "matriculas", "matrículas", "meio", "modulo", "módulo", "nesta", "nota", "novo", "numero", "nunca", "número", "observar", "opcao", "opção", "outras", "pode", "registros", "seja", "selecionar", "sem", "ser", "sera", "será", "tabela", "tem", "tiveram", "total", "usuario", "usuário", "utilizado", "utilizar", "vieram", "visualizada", "visualizar"], "steps": []}, {"id": "guide-43", "is_featured": false, "title": "Como configurar cópia de histórico escolar", "category": "Usuários", "subtopic": "Geral", "path": "", "keywords": ["aberta", "acesse", "alterar", "aluno", "alunos", "ano", "atualizar", "botao", "botão", "cadastrar", "cadastro", "concluir", "copia", "copiar", "copias", "curso", "cópia", "cópias", "dados", "desejadas", "desejado", "desta", "devera", "deverá", "diretor", "disciplina", "diz", "editar", "entre", "escolar", "escolhido", "especificacoes", "especificações", "facilitando", "fazer", "final", "funcionalidade", "historico", "historicos", "histórico", "históricos", "informacoes", "informações", "letivo", "listagem", "manualmente", "mas", "matriculas", "matrículas", "mostrada", "nao", "necessarios", "necessários", "nome", "nota", "notas", "nova", "não", "outras", "pagina", "podera", "poderá", "precisara", "precisará", "processamento", "proximo", "próximo", "página", "registrar", "repetido", "salvar", "secretario", "secretário", "seguida", "seja", "selecionar", "selecione", "sera", "serve", "será", "sim", "somente", "usuario", "usuário", "voce", "você"], "steps": []}, {"id": "guide-44", "is_featured": false, "title": "Como configurar regras de avaliação e fórmulas de média", "category": "Usuários", "subtopic": "Geral", "path": "Escola > Cadastros > Regras de Avaliação", "keywords": ["administrador", "alunos", "aplicam", "apresentaremos", "avaliacao", "avaliacoes", "avaliação", "avaliações", "boletim", "cadastros", "conceito", "conceitos", "configuracao", "configuradas", "configurar", "configuração", "diario", "efetua", "ensino", "escolas", "estas", "falta", "formas", "frequencia", "geral", "instituicao", "instituição", "lancamento", "lançamento", "media", "necessitam", "nesta", "nivel", "notas", "nível", "permissao", "permissão", "pois", "possiveis", "possíveis", "prefeitura", "recuperacao", "rede", "regras", "sao", "secao", "seja", "seção", "são", "tipos", "visao", "visão", "regra de avaliacao", "formula", "arredondamento", "aprovacao", "reprovacao"], "steps": []}, {"id": "guide-45", "is_featured": false, "title": "Como configurar tabelas de arredondamento de notas", "category": "Usuários", "subtopic": "Geral", "path": "Escola > Cadastros > Tabelas de Arredondamento", "keywords": ["acao", "ainda", "alfanumerico", "alfanumérico", "alunos", "arredondado", "arredondamento", "avaliacao", "avaliação", "ação", "cadastro", "cadastros", "campo", "campos", "casa", "caso", "chamado", "completou", "conceitual", "configuracao", "configuração", "correspondente", "decimal", "definido", "descricao", "descrição", "deve", "devera", "deverá", "efetuado", "escola", "especifica", "especifico", "específica", "específico", "exata", "exemplo", "exibido", "faixa", "habilitado", "inferior", "informacao", "informada", "informado", "informar", "informação", "lancamento", "lançamento", "linha", "maxima", "maximo", "minima", "minimo", "modulo", "máxima", "máximo", "mínima", "mínimo", "módulo", "nao", "nota", "notas", "numerica", "numericas", "numérica", "numéricas", "não", "pode", "podendo", "possa", "preenchido", "regras", "relacionada", "rotulo", "rótulo", "seja", "ser", "sera", "serve", "será", "somente", "superior", "tabela", "tabelas", "tipo", "tipos", "valor", "valores", "vira", "virá", "visualizado", "fracao", "nota quebrada", "regra"], "steps": []}, {"id": "guide-46", "is_featured": false, "title": "Como configurar fórmulas de cálculo de média", "category": "Usuários", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Tipos > Regras de avaliação > Fórmulas de cálculo da média", "keywords": ["administrador", "aluno", "aritmetica", "aritmeticos", "aritmética", "aritméticos", "avaliacao", "avaliação", "cadastro", "cadastros", "calcular", "calculo", "configuradas", "configurar", "criar", "cálculo", "definicao", "definição", "devem", "diferentes", "editar", "escola", "final", "forma", "formula", "formulas", "fórmula", "fórmulas", "instituicao", "instituição", "media", "modulo", "média", "módulo", "nao", "notas", "não", "permissao", "permissão", "podera", "poderao", "poderá", "poderão", "ponderada", "portanto", "possuem", "prefeitura", "preve", "prevê", "recuperacao", "recuperação", "regras", "reutilizaveis", "reutilizáveis", "sao", "seguintes", "ser", "serao", "serão", "simbolos", "simples", "são", "símbolos", "tal", "tipos", "usadas", "usuario", "usuarios", "usuário", "usuários", "utilizados", "variaveis", "variáveis"], "steps": []}, {"id": "guide-47", "is_featured": false, "title": "Como configurar regras de avaliação e fórmulas de média", "category": "Usuários", "subtopic": "Geral", "path": "Escola > Cadastros > Regras de Avaliação", "keywords": ["aluno", "anteriormente", "arredondamento", "avaliacao", "avaliado", "avaliação", "baseado", "boletim", "cadastrada", "cadastro", "cadastros", "calculo", "campo", "combinacao", "combinação", "componente", "conceito", "configuracao", "configuração", "consta", "curricular", "cursos", "cálculo", "decimal", "demonstrado", "depende", "desejada", "dessas", "detalhamento", "diario", "disciplinas", "diversas", "entre", "escola", "especificada", "especificados", "especificar", "exemplo", "falta", "faltas", "formula", "frequencia", "fórmula", "hora", "horas", "informar", "matricula", "matrícula", "media", "minutos", "modulo", "média", "módulo", "necessario", "necessário", "nesse", "nota", "notas", "opcao", "opcoes", "opção", "opções", "outros", "parametros", "parâmetros", "permite", "porcentagem", "possui", "pre-requisitos", "precisa", "presenca", "presença", "promocao", "promoção", "pré-requisitos", "quadro", "quanto", "recuperacao", "regra", "regras", "representa", "resultado", "seguir", "seja", "sera", "será", "tabela", "tipo", "unitaria", "unitária", "usa", "valor", "valores", "vistos", "regra de avaliacao", "aprovacao", "reprovacao"], "steps": []}, {"id": "guide-48", "is_featured": false, "title": "Como configurar lançamento de notas, faltas e médias", "category": "Usuários", "subtopic": "Geral", "path": "Escola > Movimentações > Notas e Faltas", "keywords": ["abrir", "alteracao", "alteração", "aluno", "apos", "apresentada", "após", "assim", "avaliacao", "avaliação", "baseadas", "boletim", "campos", "canto", "carregar", "componente", "conceito", "conceituais", "configurado", "consiste", "console", "definicoes", "definições", "descritivos", "deseja", "detalhe", "deve", "diario", "digitacao", "digitação", "disciplina", "durante", "efetuar", "escola", "esquerdo", "esta", "estiver", "está", "exibidas", "falta", "faltas", "feito", "frequencia", "inferior", "informado", "lancamento", "lancamentos", "lançamento", "lançamentos", "listadas", "mantendo", "matriculado", "matriculas", "matrículas", "media", "mensagens", "menu", "modulo", "módulo", "necessario", "necessário", "notas", "numericos", "numéricos", "opcoes", "opções", "pagina", "pareceres", "possivel", "possível", "preencher", "processo", "página", "real", "recuperacao", "referentes", "regras", "sao", "selecao", "selecionado", "selecionar", "seleção", "ser", "serao", "serie", "serão", "status", "são", "série", "tempo", "texto", "turma", "usuario", "usuário", "valores", "vinculadas", "visualizar", "nota", "lancar nota", "lancar"], "steps": []}, {"id": "guide-50", "is_featured": false, "title": "Como configurar configurar a instituição", "category": "Administradores", "subtopic": "Geral", "path": "Para que o usuário possa emitir estes documentos, basta ele acessar o Módulo Escola > Documentos > Documentação padrão.", "keywords": ["acessar", "acordo", "ainda", "alguns", "apresentaremos", "armazenado", "assim", "basta", "botao", "botão", "cadastro", "campo", "cep", "clicar", "configuracao", "configurar", "configuração", "contemplados", "controle", "dados", "datas", "definir", "devem", "dispoe", "dispõe", "documentacao", "documentacoes", "documentação", "documentações", "documentos", "educacao", "educação", "emitir", "endereco", "endereço", "ensino", "escola", "escolas", "estao", "estes", "estão", "exibido", "funcionalidade", "funcionalidades", "i-educar", "inicial", "iniciar", "inserir", "instituicao", "instituicoes", "instituição", "instituições", "listagem", "marcados", "modulo", "municipio", "município", "módulo", "nao", "nesse", "nesta", "nome", "não", "padrao", "padroes", "padrão", "padrões", "parametros", "parâmetros", "permitirao", "permitirão", "pois", "possa", "possivel", "possível", "primeira", "principalmente", "proprios", "próprios", "quanto", "realizada", "rede", "regras", "relatorios", "relatórios", "responsavel", "responsável", "sao", "secao", "secretaria", "selecionar", "ser", "sera", "serie", "será", "setup", "seção", "são", "série", "tambem", "também", "tanto", "telefone", "usuario", "usuário", "utilizacao", "utilização"], "steps": []}, {"id": "guide-51", "is_featured": false, "title": "Como cadastrar um novo usuário", "category": "Administradores", "subtopic": "Geral", "path": "", "keywords": ["acessar", "apresentaremos", "atribuidas", "atribuídas", "bloqueios", "cadastrar", "configuracoes", "configurados", "configurações", "demais", "inicial", "nesta", "novo", "permissoes", "permissões", "podem", "rede", "sao", "secao", "seguranca", "segurança", "ser", "setup", "seção", "são", "usuario", "usuarios", "usuário", "usuários"], "steps": []}, {"id": "guide-52", "is_featured": false, "title": "Como configurar permissões (tipos de usuário/usuários)", "category": "Administradores", "subtopic": "Geral", "path": "Módulo Configurações > Permissões > Usuários", "keywords": ["acessarao", "acessarão", "acesso", "ainda", "atribuicoes", "atribuições", "atualizacoes", "atualizações", "atualmente", "biblioteca", "cadastro", "categorias", "configuracoes", "configurações", "define", "demais", "descontinuado", "descritas", "determinadas", "dito", "documentado", "escola", "estas", "existentes", "foi", "funcionalidades", "garantem", "hierarquico", "hierárquico", "i-educar", "incluem", "inicial", "instituicao", "instituição", "manutencao", "manutenção", "modulo", "módulo", "nao", "nem", "nivel", "nota", "não", "nível", "permissao", "permissoes", "permissão", "permissões", "permite", "propriamente", "recebe", "rede", "redes", "respeitando", "sao", "segue", "seguir", "sera", "será", "setup", "são", "tipo", "tipos", "usam", "usuario", "usuarios", "usuário", "usuários", "versoes", "versões", "vinculado"], "steps": []}, {"id": "guide-53", "is_featured": false, "title": "Como configurar cursos", "category": "Administradores", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Cursos", "keywords": ["apresentaremos", "cadastro", "cadastros", "configurar", "cursos", "dados", "detalhadas", "ensino", "escola", "escolas", "inicial", "modulo", "módulo", "nesta", "oferecidos", "opcoes", "opções", "principais", "rede", "sao", "secao", "seguir", "setup", "seção", "são"], "steps": []}, {"id": "guide-54", "is_featured": false, "title": "Como configurar etapas", "category": "Administradores", "subtopic": "Geral", "path": "Módulo Configurações > Usuários > Permissões", "keywords": ["anos", "apresentado", "configurar", "escolares", "escolas", "etapas", "gerenciados", "inicial", "letivos", "nesta", "rede", "sao", "secao", "sera", "será", "setup", "seção", "são"], "steps": []}, {"id": "guide-55", "is_featured": false, "title": "Como configurar etapas", "category": "Administradores", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Tipos > Escolas > Tipos de Etapas", "keywords": ["acima", "ano", "bimestral", "cadastro", "cadastros", "comuns", "definem", "ensino", "escola", "escolas", "estas", "etapas", "existentes", "imagem", "informar", "inicial", "instituicao", "instituição", "listagem", "meses", "modulo", "módulo", "neste", "periodo", "período", "pode", "podera", "poderá", "rede", "semanas", "semestral", "ser", "serie", "setup", "série", "tipos", "tres", "trimestral", "três", "visualizada", "voce", "você"], "steps": []}, {"id": "guide-56", "is_featured": false, "title": "Como configurar abertura e configuração do ano letivo", "category": "Administradores", "subtopic": "Geral", "path": "Escola > Cadastros > Escola > [Selecionar] > Definir Ano Letivo", "keywords": ["abaixo", "abrir", "abrir ano", "adicionar", "ainda", "aluno", "andamento", "ano", "anos", "apresentada", "basta", "bimestre", "botao", "botão", "cadastrar", "cadastro", "cadastros", "calendario", "caso", "concluir", "conforme", "conste", "data", "datas", "definir", "deseja", "desejado", "desejar", "desta", "deve", "dias", "dois", "duas", "editar", "escola", "escolar", "escolas", "etapas", "exemplo", "exibidos", "fechar ano", "fim", "final", "finalizados", "foram", "forma", "gerenciar", "igual", "inferior", "informada", "informados", "informar", "iniciados", "inicial", "inicio", "inserir", "início", "letivo", "letivos", "listagem", "modulo", "modulos", "módulo", "módulos", "nao", "nesse", "neste", "nota", "notas", "nova", "novo", "não", "obter", "opcao", "opcoes", "operacao", "operação", "opção", "opções", "outras", "pagina", "parte", "periodos", "períodos", "pode", "possivel", "possível", "precisa", "precisaria", "pressionar", "página", "quantidade", "questao", "questão", "rede", "salvar", "selecionar", "semestrais", "ser", "sera", "serao", "será", "serão", "setup", "trimestre", "usuario", "usuário", "utilizado", "ano letivo", "novo ano", "dias letivos"], "steps": []}, {"id": "guide-57", "is_featured": false, "title": "Como configurar escolas", "category": "Administradores", "subtopic": "Geral", "path": "Módulo Configurações > Usuários > Permissões", "keywords": ["apresentado", "configurar", "ensino", "escolas", "gerenciadas", "inicial", "nesta", "rede", "sao", "secao", "sera", "será", "setup", "seção", "são"], "steps": []}, {"id": "guide-58", "is_featured": false, "title": "Como configurar escolas", "category": "Administradores", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Escolas", "keywords": ["ano", "assim", "cadastradas", "cadastro", "cadastros", "componentes", "copia", "cópia", "detalhadas", "duplicadas", "escola", "escolas", "farao", "farão", "foram", "iniciado", "inicial", "letivo", "modulo", "módulo", "necessario", "necessário", "novo", "opcoes", "opções", "parte", "principais", "realiza", "rede", "renomear", "sao", "seguir", "sendo", "sera", "serao", "será", "serão", "setup", "são", "turmas"], "steps": []}, {"id": "guide-59", "is_featured": false, "title": "Como configurar regras de avaliação e fórmulas de média", "category": "Administradores", "subtopic": "Geral", "path": "Escola > Cadastros > Regras de Avaliação", "keywords": ["administrador", "alunos", "aplicam", "apresentaremos", "avaliacao", "avaliacoes", "avaliação", "avaliações", "boletim", "cadastros", "conceito", "configuradas", "configurar", "diario", "efetua", "ensino", "escolas", "estas", "falta", "formas", "frequencia", "inicial", "instituicao", "instituição", "media", "necessitam", "nesta", "nivel", "nível", "permissao", "permissão", "pois", "possiveis", "possíveis", "prefeitura", "recuperacao", "rede", "regras", "sao", "secao", "seja", "setup", "seção", "são", "tipos", "regra de avaliacao", "formula", "arredondamento", "aprovacao", "reprovacao"], "steps": []}, {"id": "guide-60", "is_featured": false, "title": "Como configurar tabelas de arredondamento de notas", "category": "Administradores", "subtopic": "Geral", "path": "Escola > Cadastros > Tabelas de Arredondamento", "keywords": ["abaixo", "acao", "ainda", "alfanumerico", "alfanumérico", "alunos", "arredondado", "arredondamento", "avaliacao", "avaliação", "ação", "cadastro", "cadastros", "campo", "campos", "casa", "caso", "chamado", "completou", "conceitual", "configuracao", "configuração", "correspondente", "decimal", "definido", "descricao", "descrição", "deve", "devera", "deverá", "efetuado", "escola", "especifica", "especifico", "específica", "específico", "exata", "exemplo", "exibido", "faixa", "habilitado", "inferior", "informacao", "informada", "informado", "informar", "informação", "inicial", "lancamento", "lançamento", "linha", "maxima", "maximo", "minima", "minimo", "modulo", "máxima", "máximo", "mínima", "mínimo", "módulo", "nao", "nota", "notas", "numerica", "numericas", "numérica", "numéricas", "não", "pode", "podendo", "possa", "preenchido", "rede", "regras", "relacionada", "rotulo", "rótulo", "seja", "ser", "sera", "serve", "será", "setup", "somente", "superior", "tabela", "tabelas", "tipo", "tipos", "valor", "valores", "vira", "virá", "visualizado", "fracao", "nota quebrada", "regra"], "steps": []}, {"id": "guide-61", "is_featured": false, "title": "Como configurar fórmulas de cálculo de média", "category": "Administradores", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Tipos > Regras de avaliação > Fórmulas de cálculo da média", "keywords": ["administrador", "aluno", "aritmetica", "aritmeticos", "aritmética", "aritméticos", "avaliacao", "avaliação", "cadastro", "cadastros", "calcular", "calculo", "configuradas", "configurar", "criar", "cálculo", "definicao", "definição", "devem", "diferentes", "editar", "escola", "final", "forma", "formula", "formulas", "fórmula", "fórmulas", "inicial", "instituicao", "instituição", "media", "modulo", "média", "módulo", "nao", "não", "permissao", "permissão", "podera", "poderao", "poderá", "poderão", "ponderada", "portanto", "possuem", "prefeitura", "preve", "prevê", "recuperacao", "recuperação", "rede", "regras", "reutilizaveis", "reutilizáveis", "sao", "seguintes", "ser", "serao", "serão", "setup", "simbolos", "simples", "são", "símbolos", "tal", "tipos", "usadas", "usuario", "usuarios", "usuário", "usuários", "utilizados", "variaveis", "variáveis"], "steps": []}, {"id": "guide-62", "is_featured": false, "title": "Como configurar regras de avaliação e fórmulas de média", "category": "Administradores", "subtopic": "Geral", "path": "Escola > Cadastros > Regras de Avaliação", "keywords": ["abaixo", "aluno", "anteriormente", "arredondamento", "avaliacao", "avaliado", "avaliação", "baseado", "boletim", "cadastrada", "cadastro", "cadastros", "calculo", "campo", "combinacao", "combinação", "componente", "conceito", "configuracao", "configuração", "consta", "curricular", "cursos", "cálculo", "decimal", "demonstrado", "depende", "desejada", "dessas", "detalhamento", "diario", "disciplinas", "diversas", "entre", "escola", "especificada", "especificados", "especificar", "exemplo", "falta", "faltas", "formula", "frequencia", "fórmula", "hora", "horas", "informar", "inicial", "matricula", "matrícula", "media", "minutos", "modulo", "média", "módulo", "necessario", "necessário", "nesse", "nota", "notas", "opcao", "opcoes", "opção", "opções", "outros", "parametros", "parâmetros", "permite", "porcentagem", "possui", "pre-requisitos", "precisa", "presenca", "presença", "promocao", "promoção", "pré-requisitos", "quadro", "quanto", "recuperacao", "rede", "regra", "regras", "representa", "resultado", "seguir", "seja", "sera", "será", "setup", "tabela", "tipo", "unitaria", "unitária", "usa", "valor", "valores", "vistos", "regra de avaliacao", "aprovacao", "reprovacao"], "steps": []}, {"id": "guide-63", "is_featured": false, "title": "Como configurar as séries", "category": "Administradores", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Séries", "keywords": ["anos", "cadastrar", "cadastro", "cadastros", "configurar", "curso", "detalhadas", "escola", "escolares", "escolas", "especificas", "específicas", "inicial", "instituicao", "instituição", "modulo", "módulo", "opcoes", "opções", "podera", "poderá", "principais", "rede", "sao", "seguida", "seguir", "serao", "series", "serão", "setup", "são", "séries", "vistas", "voce", "você"], "steps": []}, {"id": "guide-64", "is_featured": false, "title": "Como configurar os Componentes Curriculares", "category": "Administradores", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Componentes curriculares", "keywords": ["aluno", "alunos", "antes", "areas", "cadastramento", "cadastrar", "cadastro", "cadastros", "capitulos", "capítulos", "componentes", "configurar", "conhecimento", "curriculares", "disciplina", "disciplinas", "dispensa", "ensino", "escola", "importante", "informados", "inicial", "iniciar", "lecionadas", "matricula", "matrícula", "modulo", "módulo", "neste", "podera", "poderao", "poderá", "poderão", "processo", "proximos", "próximos", "rede", "sendo", "ser", "sera", "serao", "será", "serão", "setup", "tipos", "visto", "voce", "você", "áreas"], "steps": []}, {"id": "guide-65", "is_featured": false, "title": "Como configurar as séries da escola", "category": "Administradores", "subtopic": "Geral", "path": "Módulo Escola > Cadastros > Séries da escola", "keywords": ["anos", "cadastrar", "cadastro", "cadastros", "configuracoes", "configurar", "configurações", "definir", "detalhadas", "encontradas", "escola", "especificas", "específicas", "estao", "estão", "herda", "informacoes", "informações", "inicial", "instituicao", "instituição", "mas", "modulo", "módulo", "opcoes", "opções", "padroes", "padrões", "podendo", "podera", "poderá", "principais", "rede", "seguir", "series", "setup", "séries", "tambem", "também", "voce", "você"], "steps": []}, {"id": "guide-66", "is_featured": false, "title": "Como configurar enturmação de alunos (individual e em lote)", "category": "Administradores", "subtopic": "Geral", "path": "Escola > Movimentações > Enturmações em lote", "keywords": ["abaixo", "alocar", "alterado", "alunos", "ano", "anos", "assim", "cadastro", "cadastros", "configurar", "continua", "copiar enturmacao", "curso", "destino", "deve", "diante", "ensino", "enturmacao", "enturmação", "escola", "exemplo", "finais", "fund", "informado", "iniciais", "inicial", "irao", "irão", "lote", "modulo", "módulo", "neste", "origem", "outra", "passado", "pertence", "podera", "poderá", "progredir", "rede", "sala", "segue", "sequencia", "sequência", "ser", "serie", "series", "setup", "somente", "série", "séries", "tipos", "turma", "voce", "você", "enturmar", "entrumacao", "enturmacoes", "alocar aluno", "distribuir alunos", "vincular turma", "distribuir", "vincular"], "steps": []}, {"id": "guide-68", "is_featured": false, "title": "Como configurar importação de dados do censo escolar (educacenso)", "category": "Administradores", "subtopic": "Geral", "path": "EducaCenso > Importação", "keywords": ["2017", "2018", "acessar", "acesso", "administrador", "ambiente", "ano", "anterior", "apos", "após", "atual", "buscar", "carga inicial", "censo", "dados", "devera", "deverá", "diferente", "download", "educacenso", "entao", "então", "escolar", "exemplo", "exportacao", "exportação", "extracao", "extração", "importacao", "importar", "importação", "inep", "inicial", "login", "migracao", "nivel", "nome", "nível", "opcao", "opção", "pagina", "perfil", "pode", "poder", "precisa", "preencher", "producao", "produção", "página", "realizar", "referente", "sempre", "ser", "site", "superusuario", "superusuário", "tem", "ter", "usuario", "usuário", "visualizar", "voce", "você"], "steps": []}, {"id": "guide-69", "is_featured": false, "title": "Como configurar importação no i-educar", "category": "Administradores", "subtopic": "Geral", "path": "Módulo Educacenso > Importações > Importação Educacenso", "keywords": ["acessar", "acima", "ano", "anterior", "apos", "após", "arquivo", "atual", "basta", "campo", "censo", "deve", "download", "educacenso", "escolar", "fizemos", "i-educar", "importacao", "importacoes", "importação", "importações", "indicado", "informado", "informar", "ira", "irá", "menu", "modulo", "módulo", "obter", "selecionar", "ser", "voce", "você"], "steps": []}];

  const widgetStyles = `
    /* Reset & Tipografia */
    #ieducar-help-root {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      font-size: 13px;
      line-height: 1.5;
      color: #1e293b;
      box-sizing: border-box;
      -webkit-font-smoothing: antialiased;
    }
    #ieducar-help-root * {
      box-sizing: border-box;
    }

    /* Botão Flutuante (Formato Exato da Captura de Referência) */
    #ieducar-help-btn {
      position: fixed;
      bottom: 52px;
      right: 24px;
      display: inline-flex;
      align-items: center;
      gap: 7px;
      height: 36px;
      background: #47728f; /* Tom exato da captura oficial (#47728f) */
      color: #ffffff;
      border: none;
      /* Formato assimétrico idêntico à captura: lado esquerdo arredondado, topo direito quase reto e canto inferior direito suave */
      border-radius: 20px 3px 14px 20px;
      padding: 0 16px 0 11px;
      font-size: 14px;
      font-weight: 700;
      letter-spacing: 0.2px;
      cursor: pointer;
      box-shadow: 0 3px 10px rgba(71, 114, 143, 0.35);
      z-index: 999999;
      transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
      outline: none;
      user-select: none;
    }
    #ieducar-help-btn:hover {
      background: #395c74;
      transform: translateY(-1px);
      box-shadow: 0 5px 14px rgba(71, 114, 143, 0.45);
    }
    #ieducar-help-btn.active {
      background: #2e4b5f;
      box-shadow: 0 2px 6px rgba(46, 75, 95, 0.5);
    }
    #ieducar-help-btn svg {
      width: 17px;
      height: 17px;
      flex-shrink: 0;
    }
    #ieducar-help-btn span {
      line-height: 1;
      font-weight: 700;
    }

    /* Balão Flutuante Posicionado Acima do Botão */
    #ieducar-help-balloon {
      position: fixed;
      bottom: 98px;
      right: 24px;
      width: 420px;
      max-width: calc(100vw - 32px);
      height: 590px;
      max-height: calc(100vh - 118px);
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 16px 40px rgba(71, 114, 143, 0.22), 0 4px 16px rgba(0, 0, 0, 0.08);
      border: 1px solid #c9ddec;
      display: flex;
      flex-direction: column;
      overflow: hidden;
      z-index: 999999;
      transform-origin: bottom right;
      opacity: 0;
      visibility: hidden;
      transform: scale(0.96) translateY(8px);
      pointer-events: none;
      transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.18s ease, visibility 0.18s ease;
    }
    #ieducar-help-balloon.open {
      opacity: 1;
      visibility: visible;
      transform: scale(1) translateY(0);
      pointer-events: auto;
    }

    /* Cabeçalho Limpo: Menu de ajuda */
    .ih-balloon-header {
      background: #47728f;
      color: #ffffff;
      padding: 13px 16px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-shrink: 0;
    }
    .ih-balloon-header h3 {
      margin: 0;
      font-size: 15px;
      font-weight: 700;
      letter-spacing: -0.2px;
      color: #ffffff;
    }
    .ih-balloon-close {
      background: transparent;
      border: none;
      color: #e9f0f8;
      width: 28px;
      height: 28px;
      border-radius: 6px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      transition: all 0.2s;
    }
    .ih-balloon-close:hover {
      color: #ffffff;
      background: rgba(255, 255, 255, 0.2);
    }

    /* Seção de Busca e Chips */
    .ih-search-section {
      padding: 12px 14px 8px;
      background: #ffffff;
      border-bottom: 1px solid #e9f0f8;
      flex-shrink: 0;
    }
    .ih-input-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }
    .ih-search-icon {
      position: absolute;
      left: 12px;
      color: #47728f;
      pointer-events: none;
    }
    .ih-input {
      width: 100%;
      padding: 10px 32px 10px 36px;
      border: 1px solid #c9ddec;
      border-radius: 8px;
      font-size: 13px;
      background: #ffffff;
      color: #1e293b;
      outline: none;
      transition: border-color 0.15s, box-shadow 0.15s;
    }
    .ih-input:focus {
      border-color: #47728f;
      box-shadow: 0 0 0 2px rgba(71, 114, 143, 0.2);
    }
    .ih-clear-btn {
      position: absolute;
      right: 10px;
      background: none;
      border: none;
      color: #94a3b8;
      font-size: 16px;
      cursor: pointer;
      display: none;
      padding: 2px;
    }
    .ih-clear-btn.visible {
      display: block;
    }

    /* Chips de Filtro */
    .ih-chips-row {
      display: flex;
      gap: 6px;
      overflow-x: auto;
      padding: 10px 0 4px;
      scrollbar-width: none;
    }
    .ih-chips-row::-webkit-scrollbar {
      display: none;
    }
    .ih-chip {
      padding: 4px 10px;
      border-radius: 9999px;
      font-size: 11.5px;
      font-weight: 500;
      border: 1px solid #d0deec;
      background: #ffffff;
      color: #334e68;
      cursor: pointer;
      white-space: nowrap;
      transition: all 0.15s;
      display: inline-flex;
      align-items: center;
      gap: 5px;
    }
    .ih-chip:hover {
      background: #e9f0f8;
      border-color: #47728f;
      color: #47728f;
    }
    .ih-chip.active {
      background: #47728f;
      color: #ffffff;
      border-color: #47728f;
      font-weight: 700;
    }
    .ih-chip .ih-count {
      font-size: 10px;
      background: #e9f0f8;
      color: #47728f;
      border-radius: 9999px;
      padding: 1px 5px;
      font-weight: 700;
    }
    .ih-chip.active .ih-count {
      background: rgba(255, 255, 255, 0.25);
      color: #ffffff;
    }

    /* Resumo abaixo dos chips */
    .ih-summary-text {
      font-size: 11.5px;
      color: #5c7891;
      margin-top: 6px;
      padding-left: 2px;
    }

    /* Lista de Guias em Accordion (Layout em Bloco com Rolagem Natural) */
    .ih-body-scroll {
      flex: 1 1 auto !important;
      min-height: 0 !important;
      overflow-y: auto !important;
      overflow-x: hidden !important;
      padding: 12px 14px !important;
      background: #f4f8fc !important;
      display: block !important;
      scrollbar-width: thin;
      scrollbar-color: #b8cde2 transparent;
    }
    .ih-body-scroll::-webkit-scrollbar {
      width: 6px;
    }
    .ih-body-scroll::-webkit-scrollbar-track {
      background: transparent;
    }
    .ih-body-scroll::-webkit-scrollbar-thumb {
      background: #b8cde2;
      border-radius: 9999px;
    }
    .ih-body-scroll::-webkit-scrollbar-thumb:hover {
      background: #8faec9;
    }

    /* Card de Accordion Totalmente Visível e Robusto */
    .ih-accordion-card {
      display: block !important;
      width: 100% !important;
      min-height: 56px !important;
      background: #ffffff !important;
      border: 1px solid #dce7f3 !important;
      border-radius: 10px !important;
      margin-bottom: 8px !important;
      overflow: hidden !important;
      box-shadow: 0 1px 3px rgba(71, 114, 143, 0.06) !important;
      transition: border-color 0.2s, box-shadow 0.2s !important;
      box-sizing: border-box !important;
      flex-shrink: 0 !important;
    }
    .ih-accordion-card:last-child {
      margin-bottom: 4px !important;
    }
    .ih-accordion-card:hover {
      border-color: #47728f !important;
      box-shadow: 0 3px 8px rgba(71, 114, 143, 0.12) !important;
    }
    .ih-accordion-card.open {
      border-color: #47728f !important;
      box-shadow: 0 4px 14px rgba(71, 114, 143, 0.18) !important;
    }

    /* Cabeçalho do Card Clicável */
    .ih-card-header {
      padding: 12px 14px !important;
      cursor: pointer !important;
      display: flex !important;
      align-items: center !important;
      justify-content: space-between !important;
      user-select: none !important;
      min-height: 54px !important;
      box-sizing: border-box !important;
      gap: 8px !important;
    }
    .ih-card-left {
      display: flex !important;
      flex-direction: column !important;
      gap: 4px !important;
      padding-right: 8px !important;
      flex: 1 1 auto !important;
      min-width: 0 !important;
    }
    .ih-card-title {
      margin: 0 !important;
      font-size: 13.5px !important;
      font-weight: 700 !important;
      color: #173650 !important;
      line-height: 1.35 !important;
      word-break: break-word !important;
    }
    .ih-card-tags {
      display: flex !important;
      align-items: center !important;
      gap: 6px !important;
      flex-wrap: wrap !important;
    }
    .ih-tag-user {
      display: inline-block !important;
      border: 1px solid #47728f !important;
      color: #274b63 !important;
      background: #e9f0f8 !important;
      font-size: 10.5px !important;
      font-weight: 700 !important;
      padding: 1px 6px !important;
      border-radius: 4px !important;
      line-height: 1.2 !important;
      white-space: nowrap !important;
    }
    .ih-tag-admin {
      display: inline-block !important;
      border: 1px solid #2563eb !important;
      color: #1d4ed8 !important;
      background: #eff6ff !important;
      font-size: 10.5px !important;
      font-weight: 700 !important;
      padding: 1px 6px !important;
      border-radius: 4px !important;
      line-height: 1.2 !important;
      white-space: nowrap !important;
    }
    .ih-subtopic-text {
      font-size: 11.5px !important;
      color: #627d96 !important;
      line-height: 1.2 !important;
      white-space: nowrap !important;
      overflow: hidden !important;
      text-overflow: ellipsis !important;
      max-width: 220px !important;
    }
    .ih-chevron {
      color: #7994ab !important;
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
      transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1) !important;
      flex-shrink: 0 !important;
      width: 22px !important;
      height: 22px !important;
    }
    .ih-accordion-card.open .ih-chevron {
      transform: rotate(180deg) !important;
      color: #47728f !important;
    }

    /* Conteúdo Retrátil do Accordion */
    .ih-card-body {
      display: none;
      padding: 0 14px 14px !important;
      border-top: 1px solid #e9f0f8;
      background: #ffffff;
    }
    .ih-accordion-card.open .ih-card-body {
      display: block !important;
    }

    .ih-path-bar {
      background: #e9f0f8 !important;
      border: 1px solid #d2e2f0;
      border-radius: 6px;
      padding: 7px 10px;
      font-size: 11.5px;
      font-weight: 600;
      color: #20415a !important;
      margin: 10px 0 12px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    /* Passos com Imagens Embutidas Logo Abaixo */
    .ih-walkthrough-list {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }
    .ih-step-row {
      display: flex;
      align-items: flex-start;
      gap: 9px;
      background: #f8fafc;
      border: 1px solid #dce7f3;
      border-radius: 6px;
      padding: 10px;
      transition: background 0.15s, border-color 0.15s;
    }
    .ih-step-row:hover {
      background: #e9f0f8;
      border-color: #c9ddec;
    }
    .ih-step-num {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      width: 22px;
      height: 22px;
      border-radius: 5px;
      background: #47728f;
      color: #ffffff;
      font-size: 11.5px;
      font-weight: 800;
      flex-shrink: 0;
      margin-top: 1px;
    }
    .ih-step-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .ih-step-text {
      font-size: 12.5px;
      color: #27435b;
      line-height: 1.45;
    }

    /* Imagem Oficial Logo Abaixo do Passo */
    .ih-step-img-box {
      border: 1px solid #c9ddec;
      border-radius: 6px;
      overflow: hidden;
      background: #ffffff;
      box-shadow: 0 1px 4px rgba(71, 114, 143, 0.08);
    }
    .ih-img {
      width: 100%;
      height: auto;
      max-height: 190px;
      object-fit: cover;
      display: block;
      cursor: zoom-in;
      transition: opacity 0.2s;
    }
    .ih-img:hover {
      opacity: 0.95;
    }
    .ih-caption {
      padding: 6px 9px;
      background: #e9f0f8;
      border-top: 1px solid #dce7f3;
      font-size: 10.5px;
      color: #355874;
      line-height: 1.35;
      font-style: italic;
    }

    .ih-highlight {
      background-color: #dbe8f6;
      color: #173852;
      padding: 0 3px;
      border-radius: 3px;
      font-weight: 700;
    }

    /* Botão de Expansão para Todos os Guias */
    .ih-show-all-btn {
      display: block;
      width: 100%;
      padding: 10px 14px;
      margin: 8px 0 16px;
      text-align: center;
      background: #f1f5f9;
      border: 1px dashed #cbd5e1;
      border-radius: 8px;
      color: #1e3a5c;
      font-size: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
    }
    .ih-show-all-btn:hover {
      background: #e2e8f0;
      border-color: #0d2238;
      color: #0d2238;
    }

    /* Modal Lightbox */
    #ih-zoom-modal {
      position: fixed;
      inset: 0;
      background: rgba(15, 23, 42, 0.88);
      backdrop-filter: blur(4px);
      z-index: 1000000;
      display: none;
      align-items: center;
      justify-content: center;
      padding: 20px;
      cursor: zoom-out;
    }
    #ih-zoom-modal.open {
      display: flex;
    }
    #ih-zoom-img {
      max-width: 95vw;
      max-height: 90vh;
      border-radius: 6px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
      cursor: default;
    }
    #ih-zoom-close {
      position: absolute;
      top: 16px;
      right: 20px;
      color: #ffffff;
      font-size: 24px;
      background: rgba(255, 255, 255, 0.15);
      border: none;
      width: 36px;
      height: 36px;
      border-radius: 4px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .ih-empty {
      text-align: center;
      padding: 30px 14px;
      color: #64748b;
    }
  `;

  const styleEl = document.createElement("style");
  styleEl.type = "text/css";
  styleEl.id = "ieducar-help-styles";
  styleEl.innerHTML = widgetStyles;
  document.head.appendChild(styleEl);

  const root = document.createElement("div");
  root.id = "ieducar-help-root";
  root.innerHTML = `
    <!-- Botão Flutuante (Fiel à captura oficial) -->
    <button id="ieducar-help-btn" type="button" aria-label="Ajuda do i-Educar">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"></circle>
        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
        <line x1="12" y1="17" x2="12.01" y2="17"></line>
      </svg>
      <span>Ajuda</span>
    </button>

    <!-- Balão Flutuante Acima do Botão -->
    <div id="ieducar-help-balloon" role="dialog" aria-modal="true" aria-labelledby="ih-balloon-title">
      <!-- Cabeçalho -->
      <div class="ih-balloon-header">
        <h3 id="ih-balloon-title">Menu de ajuda</h3>
        <button id="ih-balloon-close" class="ih-balloon-close" title="Fechar (Esc)">&times;</button>
      </div>

      <!-- Barra de Busca e Chips -->
      <div class="ih-search-section">
        <div class="ih-input-wrap">
          <svg class="ih-search-icon" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
          </svg>
          <input
            type="text"
            id="ih-input"
            class="ih-input"
            placeholder="O que você quer fazer? Ex.: enturmar, matricular"
            autocomplete="off"
          />
          <button id="ih-clear-btn" class="ih-clear-btn" title="Limpar">&times;</button>
        </div>

        <div class="ih-chips-row">
          <button type="button" class="ih-chip active" data-filter="all">Todos <span class="ih-count" id="cnt-all">70</span></button>
          <button type="button" class="ih-chip" data-filter="matricula">Matrícula <span class="ih-count" id="cnt-mat">25</span></button>
          <button type="button" class="ih-chip" data-filter="enturma">Enturmação <span class="ih-count" id="cnt-ent">7</span></button>
          <button type="button" class="ih-chip" data-filter="ano letivo">Ano letivo <span class="ih-count" id="cnt-ano">5</span></button>
          <button type="button" class="ih-chip" data-filter="nota">Notas e faltas <span class="ih-count" id="cnt-not">22</span></button>
          <button type="button" class="ih-chip" data-filter="censo">Censo <span class="ih-count" id="cnt-cen">6</span></button>
        </div>

        <div id="ih-summary-text" class="ih-summary-text">70 guias em todos os temas</div>
      </div>

      <!-- Lista de Guias em Accordion -->
      <div id="ih-body-scroll" class="ih-body-scroll"></div>
    </div>

    <!-- Lightbox Zoom -->
    <div id="ih-zoom-modal">
      <button id="ih-zoom-close" title="Fechar">&times;</button>
      <img id="ih-zoom-img" src="" alt="Tela Ampliada" />
    </div>
  `;
  document.body.appendChild(root);

  const btn = document.getElementById("ieducar-help-btn");
  const balloon = document.getElementById("ieducar-help-balloon");
  const closeBtn = document.getElementById("ih-balloon-close");
  const input = document.getElementById("ih-input");
  const clearBtn = document.getElementById("ih-clear-btn");
  const bodyScroll = document.getElementById("ih-body-scroll");
  const summaryText = document.getElementById("ih-summary-text");
  const chips = document.querySelectorAll(".ih-chip");
  const zoomModal = document.getElementById("ih-zoom-modal");
  const zoomImg = document.getElementById("ih-zoom-img");
  const zoomClose = document.getElementById("ih-zoom-close");

  let activeFilter = "all";

  function normalize(str) {
    return (str || "")
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "")
      .toLowerCase()
      .trim();
  }

  // Contadores dinâmicos dos chips
  function updateChipCounts() {
    const allCount = IEDUCAR_HELP_DATA.length;
    const matCount = IEDUCAR_HELP_DATA.filter(d => normalize(d.title + " " + d.subtopic + " " + (d.keywords||[]).join(" ")).includes("matric")).length;
    const entCount = IEDUCAR_HELP_DATA.filter(d => normalize(d.title + " " + d.subtopic + " " + (d.keywords||[]).join(" ")).includes("enturm")).length;
    const anoCount = IEDUCAR_HELP_DATA.filter(d => normalize(d.title + " " + d.subtopic + " " + (d.keywords||[]).join(" ")).includes("ano letiv")).length;
    const notCount = IEDUCAR_HELP_DATA.filter(d => {
      const s = normalize(d.title + " " + d.subtopic + " " + (d.keywords||[]).join(" "));
      return s.includes("nota") || s.includes("falta") || s.includes("diario");
    }).length;
    const cenCount = IEDUCAR_HELP_DATA.filter(d => normalize(d.title + " " + d.subtopic + " " + (d.keywords||[]).join(" ")).includes("censo")).length;

    const elAll = document.getElementById("cnt-all");
    const elMat = document.getElementById("cnt-mat");
    const elEnt = document.getElementById("cnt-ent");
    const elAno = document.getElementById("cnt-ano");
    const elNot = document.getElementById("cnt-not");
    const elCen = document.getElementById("cnt-cen");

    if (elAll) elAll.innerText = allCount;
    if (elMat) elMat.innerText = matCount;
    if (elEnt) elEnt.innerText = entCount;
    if (elAno) elAno.innerText = anoCount;
    if (elNot) elNot.innerText = notCount;
    if (elCen) elCen.innerText = cenCount;
  }

  const ROOT_SYNONYMS = [
    { test: /(entrum|etrum|enturm)/, root: "enturm", terms: ["enturmacao", "enturmar", "turma"] },
    { test: /(matric|matrik|matriu)/, root: "matric", terms: ["matricula", "matricular", "aluno"] },
    { test: /(profes|docent|servid)/, root: "profess", terms: ["professor", "alocacao", "docente"] },
    { test: /(bloq|trava|prazo)/, root: "bloquei", terms: ["bloqueio", "travar", "data limite"] },
    { test: /(nota|falta|boletim|diario|arredon)/, root: "nota", terms: ["notas", "faltas", "avaliacao"] },
    { test: /(cens|inep|educacen)/, root: "censo", terms: ["censo escolar", "educacenso", "importar"] },
    { test: /(transf|saida|aband)/, root: "transfer", terms: ["transferencia", "abandono", "saida"] },
    { test: /(ano letiv|etap|bimestr|trimestr)/, root: "ano letiv", terms: ["ano letivo", "modulo", "etapas"] }
  ];

  function extractSemanticRoots(rawStr) {
    const norm = normalize(rawStr);
    const roots = [];
    ROOT_SYNONYMS.forEach(s => {
      if (s.test.test(norm)) {
        roots.push(s.root);
        roots.push(...s.terms);
      }
    });
    return [...new Set(roots)];
  }

  function highlightText(text, tokens) {
    if (!tokens || tokens.length === 0 || !text) return text;
    let res = text;
    tokens.forEach(tok => {
      if (tok.length >= 3) {
        const regex = new RegExp("(" + tok + ")", "gi");
        res = res.replace(regex, "<mark class='ih-highlight'>$1</mark>");
      }
    });
    return res;
  }

  function toggleBalloon() {
    const isOpen = balloon.classList.contains("open");
    if (isOpen) {
      balloon.classList.remove("open");
      btn.classList.remove("active");
    } else {
      balloon.classList.add("open");
      btn.classList.add("active");
      setTimeout(() => input.focus(), 150);
    }
  }

  btn.addEventListener("click", toggleBalloon);
  closeBtn.addEventListener("click", toggleBalloon);

  window.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      if (zoomModal.classList.contains("open")) zoomModal.classList.remove("open");
      else if (balloon.classList.contains("open")) toggleBalloon();
    }
  });

  clearBtn.addEventListener("click", () => {
    input.value = "";
    clearBtn.classList.remove("visible");
    render();
    input.focus();
  });

  chips.forEach((c) => {
    c.addEventListener("click", () => {
      chips.forEach((ch) => ch.classList.remove("active"));
      c.classList.add("active");
      activeFilter = c.getAttribute("data-filter");
      render();
    });
  });

  input.addEventListener("input", () => {
    if (input.value.trim().length > 0) clearBtn.classList.add("visible");
    else clearBtn.classList.remove("visible");
    render();
  });

  window.__ihZoom = function(src) {
    zoomImg.src = src;
    zoomModal.classList.add("open");
  };
  zoomModal.addEventListener("click", (e) => {
    if (e.target !== zoomImg) zoomModal.classList.remove("open");
  });
  zoomClose.addEventListener("click", () => zoomModal.classList.remove("open"));

  function resolveImg(imgObj) {
    if (!imgObj) return { localSrc: "", remoteSrc: "" };
    const filename = imgObj.local_filename || (imgObj.url ? imgObj.url.split("/").pop() : "");
    const localSrc = "/help_images/" + filename;
    const remoteSrc = imgObj.url;
    return { localSrc, remoteSrc };
  }

  // Alternar Accordion (Abre/Fecha)
  window.__ihToggleCard = function(headerEl) {
    const card = headerEl.closest(".ih-accordion-card");
    if (!card) return;
    const isOpen = card.classList.contains("open");
    card.classList.toggle("open", !isOpen);
  };

  // Pontuação de Relevância
  function scoreItem(item, queryNorm, roots, rawTokens) {
    let score = 0;
    const titleNorm = normalize(item.title);
    const subtopicNorm = normalize(item.subtopic);
    const pathNorm = normalize(item.path);
    const keywordsNorm = (item.keywords || []).map(normalize);
    const stepsNorm = (item.steps || []).map(s => normalize(s.text)).join(" ");

    roots.forEach(r => {
      if (titleNorm.includes(r)) score += 350;
      if (subtopicNorm.includes(r)) score += 200;
      if (keywordsNorm.some(k => k.includes(r))) score += 180;
      if (pathNorm.includes(r)) score += 100;
      if (stepsNorm.includes(r)) score += 60;
    });

    if (queryNorm.length >= 3) {
      if (titleNorm.includes(queryNorm)) score += 400;
      if (subtopicNorm.includes(queryNorm)) score += 250;
      if (pathNorm.includes(queryNorm)) score += 150;
    }

    rawTokens.forEach(t => {
      if (t.length < 2) return;
      if (titleNorm.includes(t)) score += 120;
      if (subtopicNorm.includes(t)) score += 80;
      if (keywordsNorm.some(k => k.includes(t))) score += 60;
      if (stepsNorm.includes(t)) score += 30;
    });

    return score;
  }

  function render() {
    const raw = input.value.trim();
    const q = normalize(raw);
    const roots = extractSemanticRoots(raw);
    const tokens = q.split(/\s+/).filter(Boolean);

    let scoredList = [];

    IEDUCAR_HELP_DATA.forEach((item) => {
      if (activeFilter !== "all") {
        const nTitle = normalize(item.title);
        const nSub = normalize(item.subtopic);
        const nKeys = (item.keywords || []).map(normalize);
        const allText = nTitle + " " + nSub + " " + nKeys.join(" ");
        const matchChip = (activeFilter === "nota")
          ? (allText.includes("nota") || allText.includes("falta") || allText.includes("diario"))
          : allText.includes(activeFilter);
        if (!matchChip) return;
      }

      if (!q) {
        scoredList.push({ item, score: 1 });
        return;
      }

      const score = scoreItem(item, q, roots, tokens);
      if (score > 0) {
        scoredList.push({ item, score });
      }
    });

    if (q) {
      scoredList.sort((a, b) => b.score - a.score);
    }

    // Atualizar contadores dos chips
    updateChipCounts();

    // Atualizar texto de resumo
    if (!q) {
      if (activeFilter === "all") {
        summaryText.innerText = "70 guias em todos os temas";
      } else {
        summaryText.innerText = `${scoredList.length} guias nesta categoria`;
      }
    } else {
      summaryText.innerText = `${scoredList.length} ${scoredList.length === 1 ? "guia encontrado" : "guias encontrados"} para "${raw}"`;
    }

    if (scoredList.length === 0) {
      bodyScroll.innerHTML = `
        <div class="ih-empty">
          <div style="font-size: 26px; margin-bottom: 8px;">🔍</div>
          <h4 style="margin: 0 0 6px; font-size: 14px; font-weight: 700; color: #1e293b;">Nenhum guia encontrado</h4>
          <p style="margin: 0; font-size: 12px; color: #64748b;">Tente buscar por termos como <em>matrícula</em>, <em>enturmar</em>, <em>ano letivo</em> ou <em>notas</em>.</p>
        </div>
      `;
      return;
    }

    const cardsHtml = scoredList.map((entry, index) => {
      const doc = entry.item;
      // Se houver busca ativa, o melhor resultado abre automaticamente
      const isOpen = Boolean(q && index === 0);
      const isUser = doc.category === "Usuários";
      const tagCls = isUser ? "ih-tag-user" : "ih-tag-admin";

      const highlightedTitle = highlightText(doc.title, tokens);

      const stepsList = doc.steps || [];
      const stepsHtml = (stepsList.length > 0)
        ? `
          <div class="ih-walkthrough-list">
            ${stepsList.map((st, i) => {
              const hasImg = st.image && (st.image.local_filename || st.image.url);
              const imgPaths = hasImg ? resolveImg(st.image) : null;

              return `
                <div class="ih-step-row">
                  <span class="ih-step-num">${i + 1}</span>
                  <div class="ih-step-content">
                    <div class="ih-step-text">${highlightText(st.text, tokens)}</div>
                    ${hasImg ? `
                      <div class="ih-step-img-box">
                        <img
                          class="ih-img"
                          src="${imgPaths.localSrc}"
                          data-fallback="${imgPaths.remoteSrc}"
                          alt="${st.image.alt || doc.title}"
                          referrerpolicy="no-referrer"
                          loading="lazy"
                          onclick="event.stopPropagation(); window.__ihZoom(this.src);"
                          onerror="if (this.src !== this.dataset.fallback && this.dataset.fallback) { this.src = this.dataset.fallback; } else { this.style.display='none'; }"
                        />
                        ${st.image.alt ? `<div class="ih-caption">📸 ${st.image.alt}</div>` : ""}
                      </div>
                    ` : ""}
                  </div>
                </div>
              `;
            }).join("")}
          </div>
        `
        : "";

      return `
        <div class="ih-accordion-card ${isOpen ? "open" : ""}">
          <div class="ih-card-header" onclick="window.__ihToggleCard(this)">
            <div class="ih-card-left">
              <h4 class="ih-card-title">${highlightedTitle}</h4>
              <div class="ih-card-tags">
                <span class="${tagCls}">${doc.category}</span>
                <span class="ih-subtopic-text">${doc.subtopic}</span>
              </div>
            </div>
            <div class="ih-chevron">
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 12 15 18 9"></polyline>
              </svg>
            </div>
          </div>

          <div class="ih-card-body">
            ${doc.path ? `
              <div class="ih-path-bar">
                <span>📍</span>
                <span><strong>Onde Clicar:</strong> ${doc.path}</span>
              </div>
            ` : ""}

            ${stepsHtml}
          </div>
        </div>
      `;
    }).join("");

    bodyScroll.innerHTML = cardsHtml;
  }

  render();
})();
