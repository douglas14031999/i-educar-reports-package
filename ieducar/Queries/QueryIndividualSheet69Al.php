<?php

class QueryIndividualSheet69Al extends QueryBridge
{
    protected function getDefaultData()
    {
        return [
            'instituicao' => 0,
            'escola' => 0,
            'ano' => 0,
            'curso' => 0,
            'serie' => 0,
            'turma' => 0,
            'matricula' => 0,
        ];
    }

    protected function query()
    {
        return <<<'SQL'
SELECT * FROM (
    SELECT DISTINCT ON (matricula.cod_matricula, componente_curricular.id)
        instituicao.cod_instituicao,
        upper(instituicao.nm_instituicao) AS nm_instituicao,
        upper(instituicao.nm_responsavel) AS nm_responsavel,
        instituicao.cidade AS cidade_instituicao,
        escola.cod_escola,
        COALESCE(
            (SELECT j.fantasia FROM cadastro.juridica j WHERE j.idpes = escola.ref_idpes LIMIT 1),
            (SELECT p.nome FROM cadastro.pessoa p WHERE p.idpes = escola.ref_idpes LIMIT 1),
            pessoa_escola.nome,
            'Não informado'
        ) AS nm_escola,
        curso.nm_curso,
        serie.nm_serie,
        turma.cod_turma,
        turma.nm_turma,
        COALESCE(turma_turno.nome, 'Não informado') AS periodo,
        aluno.cod_aluno,
        matricula.cod_matricula,
        upper(pessoa.nome) AS nome_aluno,
        to_char(fisica.data_nasc, 'DD/MM/YYYY') AS data_nasc,
        fisica.sexo,
        COALESCE(pai.nome, 'Não informado') AS nm_pai,
        COALESCE(mae.nome, 'Não informado') AS nm_mae,
        view_situacao.texto_situacao_simplificado AS situacao_aluno,
        componente_curricular.id AS cod_disciplina,
        componente_curricular.nome AS nm_disciplina,
        componente_curricular.abreviatura AS sigla_disciplina,
        COALESCE(componente_curricular.ordenamento, 999) AS ordenamento,
        COALESCE((
            SELECT replace(COALESCE(nota.nota_arredondada, nota.nota::text), '.', ',')
            FROM modules.nota_componente_curricular nota
            INNER JOIN modules.nota_aluno na ON na.id = nota.nota_aluno_id
            WHERE na.matricula_id = matricula.cod_matricula
              AND nota.componente_curricular_id = componente_curricular.id
              AND nota.etapa = '1'
            ORDER BY nota.id DESC LIMIT 1
        ), '-') AS nota1,
        COALESCE((
            SELECT replace(COALESCE(nota.nota_arredondada, nota.nota::text), '.', ',')
            FROM modules.nota_componente_curricular nota
            INNER JOIN modules.nota_aluno na ON na.id = nota.nota_aluno_id
            WHERE na.matricula_id = matricula.cod_matricula
              AND nota.componente_curricular_id = componente_curricular.id
              AND nota.etapa = '2'
            ORDER BY nota.id DESC LIMIT 1
        ), '-') AS nota2,
        COALESCE((
            SELECT replace(COALESCE(nota.nota_arredondada, nota.nota::text), '.', ',')
            FROM modules.nota_componente_curricular nota
            INNER JOIN modules.nota_aluno na ON na.id = nota.nota_aluno_id
            WHERE na.matricula_id = matricula.cod_matricula
              AND nota.componente_curricular_id = componente_curricular.id
              AND nota.etapa = '3'
            ORDER BY nota.id DESC LIMIT 1
        ), '-') AS nota3,
        COALESCE((
            SELECT replace(COALESCE(nota.nota_arredondada, nota.nota::text), '.', ',')
            FROM modules.nota_componente_curricular nota
            INNER JOIN modules.nota_aluno na ON na.id = nota.nota_aluno_id
            WHERE na.matricula_id = matricula.cod_matricula
              AND nota.componente_curricular_id = componente_curricular.id
              AND nota.etapa = '4'
            ORDER BY nota.id DESC LIMIT 1
        ), '-') AS nota4,
        COALESCE((
            SELECT replace(COALESCE(nccm.media_arredondada, nccm.media::text), '.', ',')
            FROM modules.nota_componente_curricular_media nccm
            INNER JOIN modules.nota_aluno na ON na.id = nccm.nota_aluno_id
            WHERE na.matricula_id = matricula.cod_matricula
              AND nccm.componente_curricular_id = componente_curricular.id
            ORDER BY nccm.etapa DESC LIMIT 1
        ), '-') AS media_anual,
        COALESCE((
            SELECT replace(COALESCE(nota.nota_arredondada, nota.nota::text), '.', ',')
            FROM modules.nota_componente_curricular nota
            INNER JOIN modules.nota_aluno na ON na.id = nota.nota_aluno_id
            WHERE na.matricula_id = matricula.cod_matricula
              AND nota.componente_curricular_id = componente_curricular.id
              AND nota.etapa = 'Rc'
            ORDER BY nota.id DESC LIMIT 1
        ), '-') AS nota_recuperacao,
        COALESCE((
            SELECT replace(COALESCE(nccm.media_arredondada, nccm.media::text), '.', ',')
            FROM modules.nota_componente_curricular_media nccm
            INNER JOIN modules.nota_aluno na ON na.id = nccm.nota_aluno_id
            WHERE na.matricula_id = matricula.cod_matricula
              AND nccm.componente_curricular_id = componente_curricular.id
            ORDER BY nccm.etapa DESC LIMIT 1
        ), '-') AS media_final,
        COALESCE((
            SELECT SUM(f.quantidade)
            FROM modules.falta_aluno fa
            INNER JOIN modules.falta_componente_curricular f ON (f.falta_aluno_id = fa.id)
            WHERE fa.matricula_id = matricula.cod_matricula
              AND f.componente_curricular_id = componente_curricular.id
        ), 0) AS total_faltas_disciplina,
        to_char(CURRENT_DATE, 'DD/MM/YYYY') AS data_atual,
        to_char(CURRENT_DATE, 'DD/MM/YYYY') AS data_extenso,
        COALESCE((SELECT upper(p.nome) FROM cadastro.pessoa p WHERE escola.ref_idpes_gestor = p.idpes LIMIT 1), 'Direção Escolar') AS gestor_escolar,
        COALESCE((SELECT upper(p.nome) FROM cadastro.pessoa p WHERE escola.ref_idpes_secretario_escolar = p.idpes LIMIT 1), 'Secretaria Escolar') AS secretario_escolar
    FROM pmieducar.instituicao
    INNER JOIN pmieducar.escola ON (escola.ref_cod_instituicao = instituicao.cod_instituicao)
    LEFT JOIN cadastro.pessoa pessoa_escola ON (pessoa_escola.idpes = escola.ref_idpes)
    INNER JOIN pmieducar.matricula ON (matricula.ref_ref_cod_escola = escola.cod_escola AND matricula.ano = $P{ano} AND matricula.ativo = 1)
    INNER JOIN pmieducar.aluno ON (aluno.cod_aluno = matricula.ref_cod_aluno AND aluno.ativo = 1)
    INNER JOIN cadastro.fisica ON (fisica.idpes = aluno.ref_idpes)
    INNER JOIN cadastro.pessoa ON (pessoa.idpes = fisica.idpes)
    INNER JOIN pmieducar.matricula_turma ON (matricula_turma.ref_cod_matricula = matricula.cod_matricula AND matricula_turma.ativo = 1)
    INNER JOIN pmieducar.turma ON (turma.cod_turma = matricula_turma.ref_cod_turma AND turma.ativo = 1)
    LEFT JOIN pmieducar.turma_turno ON (turma_turno.id = turma.turma_turno_id)
    LEFT JOIN pmieducar.curso ON (curso.cod_curso = matricula.ref_cod_curso)
    LEFT JOIN pmieducar.serie ON (serie.cod_serie = matricula.ref_ref_cod_serie)
    LEFT JOIN cadastro.pessoa pai ON (pai.idpes = fisica.idpes_pai)
    LEFT JOIN cadastro.pessoa mae ON (mae.idpes = fisica.idpes_mae)
    INNER JOIN relatorio.view_situacao ON (
        view_situacao.cod_matricula = matricula.cod_matricula 
        AND view_situacao.cod_turma = turma.cod_turma 
        AND view_situacao.sequencial = matricula_turma.sequencial
    )
    INNER JOIN relatorio.view_componente_curricular componente_curricular ON (
        componente_curricular.cod_turma = turma.cod_turma
        AND (
            componente_curricular.cod_serie = matricula.ref_ref_cod_serie 
            OR componente_curricular.cod_serie = serie.cod_serie
            OR componente_curricular.cod_serie IS NULL
        )
    )
    WHERE (CASE WHEN $P{instituicao} = 0 THEN TRUE ELSE instituicao.cod_instituicao = $P{instituicao} END)
      AND (CASE WHEN $P{escola} = 0 THEN TRUE ELSE escola.cod_escola = $P{escola} END)
      AND (CASE WHEN $P{curso} = 0 THEN TRUE ELSE curso.cod_curso = $P{curso} END)
      AND (CASE WHEN $P{serie} = 0 THEN TRUE ELSE serie.cod_serie = $P{serie} END)
      AND (CASE WHEN $P{turma} = 0 THEN TRUE ELSE turma.cod_turma = $P{turma} END)
      AND (CASE WHEN $P{matricula} = 0 THEN TRUE ELSE matricula.cod_matricula = $P{matricula} END)
      AND matricula_turma.sequencial = (
          SELECT MAX(mt.sequencial)
          FROM pmieducar.matricula_turma mt
          WHERE mt.ref_cod_matricula = matricula.cod_matricula
            AND mt.ref_cod_turma = turma.cod_turma
      )
    ORDER BY matricula.cod_matricula, componente_curricular.id, componente_curricular.ordenamento, componente_curricular.nome
) ficha
ORDER BY ficha.nome_aluno, ficha.ordenamento, ficha.nm_disciplina
SQL;
    }
}
