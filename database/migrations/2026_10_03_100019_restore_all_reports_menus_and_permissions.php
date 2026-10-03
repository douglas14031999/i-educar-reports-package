<?php

use App\Menu;
use App\Process;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RestoreAllReportsMenusAndPermissions extends Migration
{
    /**
     * Disable transaction so that individual errors do not cancel the whole migration.
     */
    public $withinTransaction = false;

    public function up()
    {
        // 1. Limpeza de menus legados duplicados (IDs 457 a 482)
        try {
            $duplicateIds = [457, 458, 459, 460, 461, 462, 464, 465, 466, 467, 468, 469, 470, 472, 473, 474, 475, 476, 477, 478, 479, 480, 481, 482];
            DB::table('menus')->whereIn('id', $duplicateIds)->orWhereIn('old', $duplicateIds)->delete();

            if (Schema::hasTable('menu_tipo_usuario')) {
                DB::table('menu_tipo_usuario')->whereIn('menu_id', $duplicateIds)->delete();
            }
            if (Schema::hasTable('pmieducar.menu_tipo_usuario')) {
                DB::table('pmieducar.menu_tipo_usuario')->whereIn('menu_id', $duplicateIds)->delete();
            }
        } catch (\Throwable $e) {
            // Ignora se não existir
        }

        // 2. Garante a função PostgreSQL verifica_existe_matricula_posterior_mesma_turma
        try {
            DB::statement("
                CREATE OR REPLACE FUNCTION public.verifica_existe_matricula_posterior_mesma_turma(p_cod_matricula integer, p_cod_turma integer)
                RETURNS boolean AS \$\$
                BEGIN
                    RETURN EXISTS (
                        SELECT 1
                        FROM pmieducar.matricula m
                        JOIN pmieducar.matricula_turma mt ON (mt.ref_cod_matricula = m.cod_matricula)
                        JOIN pmieducar.matricula m_origem ON (m_origem.ref_cod_aluno = m.ref_cod_aluno)
                        WHERE m_origem.cod_matricula = p_cod_matricula
                          AND mt.ref_cod_turma = p_cod_turma
                          AND m.cod_matricula > p_cod_matricula
                          AND m.ativo = 1
                    );
                END;
                \$\$ LANGUAGE plpgsql;
            ");
        } catch (\Throwable $e) {
            // Ignora
        }

        $categories = [
            ['id' => 21126, 'old' => 21126, 'parent_old' => 15, 'title' => 'Relatórios', 'description' => 'Módulo de Relatórios', 'link' => null, 'order' => 5, 'process' => 999827],
            ['id' => 21127, 'old' => 21127, 'parent_old' => 15, 'title' => 'Documentos', 'description' => 'Módulo de Documentos', 'link' => null, 'order' => 6, 'process' => 999861],
            ['id' => 999827, 'old' => 999827, 'parent_old' => 21126, 'title' => 'Gerenciais', 'description' => 'Relatórios Gerenciais', 'link' => null, 'order' => 1, 'process' => 999827],
            ['id' => 999301, 'old' => 999301, 'parent_old' => 21126, 'title' => 'Movimentações', 'description' => 'Relatórios de Movimentações', 'link' => null, 'order' => 2, 'process' => 999301],
            ['id' => 999922, 'old' => 999922, 'parent_old' => 21126, 'title' => 'Lançamentos', 'description' => 'Relatórios de Lançamentos', 'link' => null, 'order' => 3, 'process' => 999922],
            ['id' => 999300, 'old' => 999300, 'parent_old' => 21126, 'title' => 'Cadastrais', 'description' => 'Relatórios Cadastrais', 'link' => null, 'order' => 4, 'process' => 999300],
            ['id' => 999923, 'old' => 999923, 'parent_old' => 21126, 'title' => 'Matrículas', 'description' => 'Relatórios de Matrículas', 'link' => null, 'order' => 5, 'process' => 999923],
            ['id' => 999303, 'old' => 999303, 'parent_old' => 21126, 'title' => 'Indicadores', 'description' => 'Relatórios de Indicadores', 'link' => null, 'order' => 6, 'process' => 999303],
            ['id' => 999924, 'old' => 999924, 'parent_old' => 21126, 'title' => 'Auditoria', 'description' => 'Relatórios de Auditoria', 'link' => null, 'order' => 7, 'process' => 999924],
            ['id' => 999400, 'old' => 999400, 'parent_old' => 21127, 'title' => 'Atestados', 'description' => 'Atestados e Declarações', 'link' => null, 'order' => 1, 'process' => 999400],
            ['id' => 999450, 'old' => 999450, 'parent_old' => 21127, 'title' => 'Boletins', 'description' => 'Boletins Escolares', 'link' => null, 'order' => 2, 'process' => 999450],
            ['id' => 999861, 'old' => 999861, 'parent_old' => 21127, 'title' => 'Fichas', 'description' => 'Fichas do Aluno e Servidor', 'link' => null, 'order' => 3, 'process' => 999861],
            ['id' => 999460, 'old' => 999460, 'parent_old' => 21127, 'title' => 'Históricos', 'description' => 'Históricos Escolares', 'link' => null, 'order' => 4, 'process' => 999460],
            ['id' => 999925, 'old' => 999925, 'parent_old' => 21127, 'title' => 'Resultados', 'description' => 'Mapas e Atas de Resultados', 'link' => null, 'order' => 5, 'process' => 999925],
            ['id' => 999500, 'old' => 999500, 'parent_old' => 21127, 'title' => 'Registros', 'description' => 'Diários de Classe e Registros', 'link' => null, 'order' => 6, 'process' => 999500],
            ['id' => 999600, 'old' => 999600, 'parent_old' => 21127, 'title' => 'Carteiras', 'description' => 'Carteiras Estudantis e Transporte', 'link' => null, 'order' => 7, 'process' => 999600],
            ['id' => 999913, 'old' => 999913, 'parent_old' => 71, 'title' => 'Relatórios', 'description' => 'Relatórios de Servidores', 'link' => null, 'order' => 2, 'process' => 999913],
            ['id' => 999914, 'old' => 999914, 'parent_old' => 999913, 'title' => 'Cadastrais', 'description' => 'Cadastrais de Servidores', 'link' => null, 'order' => 1, 'process' => 999914],
            ['id' => 999915, 'old' => 999915, 'parent_old' => 999913, 'title' => 'Indicadores', 'description' => 'Indicadores de Servidores', 'link' => null, 'order' => 2, 'process' => 999915],
            ['id' => 999916, 'old' => 999916, 'parent_old' => 71, 'title' => 'Documentos', 'description' => 'Documentos de Servidores', 'link' => null, 'order' => 3, 'process' => 999916],
        ];

        $items = [
            ['id' => 999224, 'old' => 999224, 'parent_old' => 999300, 'title' => 'Distribuição de uniforme por aluno', 'description' => null, 'link' => '/module/Reports/DistributionOfUniformPerStudent', 'order' => 1, 'process' => 999224],
            ['id' => 999227, 'old' => 999227, 'parent_old' => 999300, 'title' => 'Relatório de alunos com deficiência', 'description' => null, 'link' => '/module/Reports/StudentsWithDisabilities', 'order' => 2, 'process' => 999227],
            ['id' => 999226, 'old' => 999226, 'parent_old' => 999300, 'title' => 'Relatório de alunos que recebem benefícios', 'description' => null, 'link' => '/module/Reports/StudentsWithBenefits', 'order' => 3, 'process' => 999226],
            ['id' => 999228, 'old' => 999228, 'parent_old' => 999300, 'title' => 'Relatório de alunos participantes de projetos', 'description' => null, 'link' => '/module/Reports/StudentsPerProjects', 'order' => 4, 'process' => 999228],
            ['id' => 999868, 'old' => 999868, 'parent_old' => 999300, 'title' => 'Relatório de ocorrências disciplinares por aluno', 'description' => null, 'link' => '/module/Reports/StudentDisciplinaryOccurrence', 'order' => 5, 'process' => 999868],
            ['id' => 999862, 'old' => 999862, 'parent_old' => 999300, 'title' => 'Relatório geral de escolas', 'description' => null, 'link' => '/module/Reports/GeneralSchools', 'order' => 6, 'process' => 999862],
            ['id' => 999235, 'old' => 999235, 'parent_old' => 999300, 'title' => 'Relatório de etiquetas para mala direta', 'description' => null, 'link' => '/module/Reports/Tags', 'order' => 7, 'process' => 999235],
            ['id' => 999870, 'old' => 999870, 'parent_old' => 999300, 'title' => 'Lista de alunos para assinatura dos pais', 'description' => null, 'link' => '/module/Reports/ParentSignature', 'order' => 8, 'process' => 999870],
            ['id' => 999807, 'old' => 999807, 'parent_old' => 999300, 'title' => 'Relação de aniversariantes do mês', 'description' => null, 'link' => '/module/Reports/Birthdays', 'order' => 9, 'process' => 999807],
            ['id' => 999864, 'old' => 999864, 'parent_old' => 999300, 'title' => 'Relatório cadastral de servidores', 'description' => null, 'link' => '/module/Reports/Servants', 'order' => 10, 'process' => 999864],
            ['id' => 999863, 'old' => 999863, 'parent_old' => 999300, 'title' => 'Ficha do Servidor', 'description' => null, 'link' => '/module/Reports/ServantSheet', 'order' => 11, 'process' => 999863],
            ['id' => 999201, 'old' => 999201, 'parent_old' => 999300, 'title' => 'Usuários do transporte escolar', 'description' => null, 'link' => '/module/Reports/TransportationUsers', 'order' => 12, 'process' => 999201],
            ['id' => 999202, 'old' => 999202, 'parent_old' => 999300, 'title' => 'Motoristas do transporte escolar', 'description' => null, 'link' => '/module/Reports/Drivers', 'order' => 13, 'process' => 999202],
            ['id' => 999208, 'old' => 999208, 'parent_old' => 999300, 'title' => 'Obras da biblioteca', 'description' => null, 'link' => '/module/Reports/LibraryWorks', 'order' => 14, 'process' => 999208],
            ['id' => 999209, 'old' => 999209, 'parent_old' => 999300, 'title' => 'Autores da biblioteca', 'description' => null, 'link' => '/module/Reports/LibraryAuthors', 'order' => 15, 'process' => 999209],
            ['id' => 999210, 'old' => 999210, 'parent_old' => 999300, 'title' => 'Editoras da biblioteca', 'description' => null, 'link' => '/module/Reports/LibraryPublishers', 'order' => 16, 'process' => 999210],
            ['id' => 999211, 'old' => 999211, 'parent_old' => 999300, 'title' => 'Clientes da biblioteca', 'description' => null, 'link' => '/module/Reports/LibraryClients', 'order' => 17, 'process' => 999211],
            ['id' => 999225, 'old' => 999225, 'parent_old' => 999301, 'title' => 'Relatório de alunos transferidos/abandono', 'description' => null, 'link' => '/module/Reports/StudentsTransferredAbandonment', 'order' => 1, 'process' => 999225],
            ['id' => 9998868, 'old' => 9998868, 'parent_old' => 999301, 'title' => 'Movimento geral', 'description' => null, 'link' => '/module/Reports/GeneralMovement', 'order' => 2, 'process' => 9998868],
            ['id' => 9998862, 'old' => 9998862, 'parent_old' => 999301, 'title' => 'Relatório de Movimento Mensal', 'description' => null, 'link' => '/module/Reports/MonthlyMovement', 'order' => 3, 'process' => 9998862],
            ['id' => 999882, 'old' => 999882, 'parent_old' => 999301, 'title' => 'Quadro de Situação Final', 'description' => null, 'link' => '/module/Reports/FinalSituation', 'order' => 4, 'process' => 999882],
            ['id' => 999212, 'old' => 999212, 'parent_old' => 999301, 'title' => 'Empréstimos da biblioteca', 'description' => null, 'link' => '/module/Reports/LibraryLoans', 'order' => 5, 'process' => 999212],
            ['id' => 999213, 'old' => 999213, 'parent_old' => 999301, 'title' => 'Devoluções da biblioteca', 'description' => null, 'link' => '/module/Reports/LibraryDevolutions', 'order' => 6, 'process' => 999213],
            ['id' => 999214, 'old' => 999214, 'parent_old' => 999301, 'title' => 'Comprovante de empréstimo da biblioteca', 'description' => null, 'link' => '/module/Reports/LibraryLoanReceipt', 'order' => 7, 'process' => 999214],
            ['id' => 999215, 'old' => 999215, 'parent_old' => 999301, 'title' => 'Comprovante de devolução da biblioteca', 'description' => null, 'link' => '/module/Reports/LibraryDevolutionReceipt', 'order' => 8, 'process' => 999215],
            ['id' => 999805, 'old' => 999805, 'parent_old' => 999922, 'title' => 'Relatório de conferência de notas e faltas', 'description' => null, 'link' => '/module/Reports/ConferenceEvaluationsFaults', 'order' => 1, 'process' => 999805],
            ['id' => 999231, 'old' => 999231, 'parent_old' => 999922, 'title' => 'Relatório de notas e faltas lançadas', 'description' => null, 'link' => '/module/Reports/ScoreAbsenceRelease', 'order' => 2, 'process' => 999231],
            ['id' => 999713, 'old' => 999713, 'parent_old' => 999922, 'title' => 'Nota necessária para exame', 'description' => null, 'link' => '/module/Reports/ScoreRequiredForExam', 'order' => 3, 'process' => 999713],
            ['id' => 999101, 'old' => 999101, 'parent_old' => 999923, 'title' => 'Relatório de alunos por turma', 'description' => null, 'link' => '/module/Reports/StudentsPerClass', 'order' => 1, 'process' => 999101],
            ['id' => 999220, 'old' => 999220, 'parent_old' => 999923, 'title' => 'Relatório de alunos por data de entrada e enturmação', 'description' => null, 'link' => '/module/Reports/StudentsEntranceAndAllocation', 'order' => 2, 'process' => 999220],
            ['id' => 999221, 'old' => 999221, 'parent_old' => 999923, 'title' => 'Movimento de alunos e enturmações', 'description' => null, 'link' => '/module/Reports/StudentsMovement', 'order' => 3, 'process' => 999221],
            ['id' => 999105, 'old' => 999105, 'parent_old' => 999923, 'title' => 'Relatório de matrículas de alunos por escola', 'description' => null, 'link' => '/module/Reports/RegistrationSchool', 'order' => 4, 'process' => 999105],
            ['id' => 999108, 'old' => 999108, 'parent_old' => 999923, 'title' => 'Relatório de alunos não enturmados por escola', 'description' => null, 'link' => '/module/Reports/NotEnrollment', 'order' => 5, 'process' => 999108],
            ['id' => 999218, 'old' => 999218, 'parent_old' => 999923, 'title' => 'Mapa quantitativo de matrículas enturmadas', 'description' => null, 'link' => '/module/Reports/EnrollmentQuantitativeMap', 'order' => 6, 'process' => 999218],
            ['id' => 999804, 'old' => 999804, 'parent_old' => 999303, 'title' => 'Gráfico de distorção idade/série', 'description' => null, 'link' => '/module/Reports/AgeDistortionInSerie', 'order' => 1, 'process' => 999804],
            ['id' => 999808, 'old' => 999808, 'parent_old' => 999303, 'title' => 'Comparativo de média da turma', 'description' => null, 'link' => '/module/Reports/ClassAverageComparative', 'order' => 2, 'process' => 999808],
            ['id' => 999219, 'old' => 999219, 'parent_old' => 999303, 'title' => 'Relatório de alunos com o melhor desempenho', 'description' => null, 'link' => '/module/Reports/StudentsAverage', 'order' => 3, 'process' => 999219],
            ['id' => 999883, 'old' => 999883, 'parent_old' => 999303, 'title' => 'Quantitativo de alunos sem nota', 'description' => null, 'link' => '/module/Reports/PendingStudents', 'order' => 4, 'process' => 999883],
            ['id' => 999884, 'old' => 999884, 'parent_old' => 999303, 'title' => 'Média dos alunos', 'description' => null, 'link' => '/module/Reports/StudentsAverage', 'order' => 5, 'process' => 999884],
            ['id' => 999885, 'old' => 999885, 'parent_old' => 999303, 'title' => 'Acompanhamento pedagógico e procedimentos', 'description' => null, 'link' => '/module/Reports/EducationalProgressAndProcedures', 'order' => 6, 'process' => 999885],
            ['id' => 999223, 'old' => 999223, 'parent_old' => 999827, 'title' => 'Relatório de usuários e acessos', 'description' => null, 'link' => '/module/Reports/UserAccess', 'order' => 1, 'process' => 999223],
            ['id' => 999244, 'old' => 999244, 'parent_old' => 999827, 'title' => 'Gráfico de usuários e acessos', 'description' => null, 'link' => '/module/Reports/UserAccessGraphic', 'order' => 2, 'process' => 999244],
            ['id' => 999828, 'old' => 999828, 'parent_old' => 999827, 'title' => 'Auditoria', 'description' => null, 'link' => '/module/Reports/AuditEvaluationsFaults', 'order' => 3, 'process' => 999828],
            ['id' => 999860, 'old' => 999860, 'parent_old' => 999914, 'title' => 'Relatório de docentes e disciplinas lecionadas por turma', 'description' => null, 'link' => '/module/Reports/TeachersAndCoursesTaughtByClass', 'order' => 1, 'process' => 999860],
            ['id' => 999107, 'old' => 999107, 'parent_old' => 999914, 'title' => 'Horas alocadas por servidor', 'description' => null, 'link' => '/module/Reports/EmployeeAllocatedTime', 'order' => 2, 'process' => 999107],
            ['id' => 999859, 'old' => 999859, 'parent_old' => 999915, 'title' => 'Quantitativo de docentes por turma', 'description' => null, 'link' => '/module/Reports/TeachersPerSchoolClass', 'order' => 1, 'process' => 999859],
            ['id' => 999100, 'old' => 999100, 'parent_old' => 999400, 'title' => 'Atestado de vaga', 'description' => null, 'link' => '/module/Reports/VacancyCertificate', 'order' => 1, 'process' => 999100],
            ['id' => 999801, 'old' => 999801, 'parent_old' => 999400, 'title' => 'Atestado de matrícula', 'description' => null, 'link' => '/module/Reports/RegistrationCertificate', 'order' => 2, 'process' => 999801],
            ['id' => 999802, 'old' => 999802, 'parent_old' => 999400, 'title' => 'Atestado de frequência', 'description' => null, 'link' => '/module/Reports/FrequencyCertificate', 'order' => 3, 'process' => 999802],
            ['id' => 999810, 'old' => 999810, 'parent_old' => 999400, 'title' => 'Atestado de escolaridade', 'description' => null, 'link' => '/module/Reports/SchoolingCertificate', 'order' => 4, 'process' => 999810],
            ['id' => 999216, 'old' => 999216, 'parent_old' => 999400, 'title' => 'Atestado de transferência', 'description' => null, 'link' => '/module/Reports/TransferenceCertificate', 'order' => 5, 'process' => 999216],
            ['id' => 999806, 'old' => 999806, 'parent_old' => 999400, 'title' => 'Atestado de abandono', 'description' => null, 'link' => '/module/Reports/AbandonmentCertificate', 'order' => 6, 'process' => 999806],
            ['id' => 999803, 'old' => 999803, 'parent_old' => 999400, 'title' => 'Declaração de conclusão de curso', 'description' => null, 'link' => '/module/Reports/ConclusionCertificate', 'order' => 7, 'process' => 999803],
            ['id' => 999701, 'old' => 999701, 'parent_old' => 999400, 'title' => 'Declaração de Anuência para Menor', 'description' => null, 'link' => '/module/Reports/MinorConsentDeclaration', 'order' => 8, 'process' => 999701],
            ['id' => 999702, 'old' => 999702, 'parent_old' => 999400, 'title' => 'Termo de Ausência', 'description' => null, 'link' => '/module/Reports/AbsenceTerm', 'order' => 9, 'process' => 999702],
            ['id' => 999703, 'old' => 999703, 'parent_old' => 999400, 'title' => 'Termos de Compromisso da Educação Infantil', 'description' => null, 'link' => '/module/Reports/EarlyChildhoodCommitmentTerm', 'order' => 10, 'process' => 999703],
            ['id' => 999704, 'old' => 999704, 'parent_old' => 999400, 'title' => 'Termos de Desistência de Vaga', 'description' => null, 'link' => '/module/Reports/VacancyWaiverTerm', 'order' => 11, 'process' => 999704],
            ['id' => 999705, 'old' => 999705, 'parent_old' => 999400, 'title' => 'Utilização da Imagem do Aluno', 'description' => null, 'link' => '/module/Reports/StudentImageUseAuthorization', 'order' => 12, 'process' => 999705],
            ['id' => 999706, 'old' => 999706, 'parent_old' => 999400, 'title' => 'Certificado de Conclusão da Educação Infantil', 'description' => null, 'link' => '/module/Reports/EarlyChildhoodCertificate', 'order' => 13, 'process' => 999706],
            ['id' => 999103, 'old' => 999103, 'parent_old' => 999450, 'title' => 'Boletim escolar', 'description' => null, 'link' => '/module/Reports/ReportCard', 'order' => 1, 'process' => 999103],
            ['id' => 999205, 'old' => 999205, 'parent_old' => 999450, 'title' => 'Boletim do professor', 'description' => null, 'link' => '/module/Reports/TeacherReportCard', 'order' => 2, 'process' => 999205],
            ['id' => 999881, 'old' => 999881, 'parent_old' => 999450, 'title' => 'Boletim de transferência', 'description' => null, 'link' => '/module/Reports/ReportCardTransference', 'order' => 3, 'process' => 999881],
            ['id' => 999714, 'old' => 999714, 'parent_old' => 999450, 'title' => 'Canhoto do professor', 'description' => null, 'link' => '/module/Reports/TeacherReceiptStub', 'order' => 4, 'process' => 999714],
            ['id' => 999203, 'old' => 999203, 'parent_old' => 999861, 'title' => 'Ficha do aluno', 'description' => null, 'link' => '/module/Reports/StudentSheet', 'order' => 1, 'process' => 999203],
            ['id' => 999204, 'old' => 999204, 'parent_old' => 999861, 'title' => 'Ficha do aluno em branco', 'description' => null, 'link' => '/module/Reports/WhiteStudentForm', 'order' => 2, 'process' => 999204],
            ['id' => 999707, 'old' => 999707, 'parent_old' => 999861, 'title' => 'Ficha Individual - AL', 'description' => null, 'link' => '/module/Reports/IndividualSheetAl', 'order' => 3, 'process' => 999707],
            ['id' => 999708, 'old' => 999708, 'parent_old' => 999861, 'title' => 'Ficha Individual (6º ao 9º ano) - AL', 'description' => null, 'link' => '/module/Reports/IndividualSheet69Al', 'order' => 4, 'process' => 999708],
            ['id' => 999716, 'old' => 999716, 'parent_old' => 999861, 'title' => 'Ficha individual - EJA', 'description' => null, 'link' => '/module/Reports/IndividualSheetEja', 'order' => 5, 'process' => 999716],
            ['id' => 999715, 'old' => 999715, 'parent_old' => 999861, 'title' => 'Ficha de acompanhamento do aluno', 'description' => null, 'link' => '/module/Reports/StudentTrackingSheet', 'order' => 6, 'process' => 999715],
            ['id' => 999709, 'old' => 999709, 'parent_old' => 999861, 'title' => 'Ficha de Moradia do Aluno', 'description' => null, 'link' => '/module/Reports/StudentHousingForm', 'order' => 7, 'process' => 999709],
            ['id' => 999710, 'old' => 999710, 'parent_old' => 999861, 'title' => 'Ficha Médica do Aluno', 'description' => null, 'link' => '/module/Reports/StudentMedicalForm', 'order' => 8, 'process' => 999710],
            ['id' => 999800, 'old' => 999800, 'parent_old' => 999460, 'title' => 'Histórico escolar', 'description' => null, 'link' => '/module/Reports/SchoolHistory', 'order' => 1, 'process' => 999800],
            ['id' => 999717, 'old' => 999717, 'parent_old' => 999460, 'title' => 'Histórico escolar - conferência', 'description' => null, 'link' => '/module/Reports/SchoolHistoryConference', 'order' => 2, 'process' => 999717],
            ['id' => 999890, 'old' => 999890, 'parent_old' => 999925, 'title' => 'Rendimento e movimento escolar', 'description' => null, 'link' => '/module/Reports/SchoolMovementAndPerformance', 'order' => 1, 'process' => 999890],
            ['id' => 999891, 'old' => 999891, 'parent_old' => 999925, 'title' => 'Resultado final', 'description' => null, 'link' => '/module/Reports/FinalResult', 'order' => 2, 'process' => 999891],
            ['id' => 9998911, 'old' => 9998911, 'parent_old' => 999925, 'title' => 'Ata Resultado final', 'description' => null, 'link' => '/module/Reports/MinutesFinalResult', 'order' => 3, 'process' => 9998911],
            ['id' => 999609, 'old' => 999609, 'parent_old' => 999925, 'title' => 'Mapa do conselho de classe', 'description' => null, 'link' => '/module/Reports/ClassBoardMap', 'order' => 4, 'process' => 999609],
            ['id' => 999886, 'old' => 999886, 'parent_old' => 999925, 'title' => 'Mapa final por disciplina', 'description' => null, 'link' => '/module/Reports/FinalMapByDiscipline', 'order' => 5, 'process' => 999886],
            ['id' => 999899, 'old' => 999899, 'parent_old' => 999500, 'title' => 'Diário de classe', 'description' => null, 'link' => '/module/Reports/ClassRecordBook', 'order' => 1, 'process' => 999899],
            ['id' => 999712, 'old' => 999712, 'parent_old' => 999500, 'title' => 'Diário de classe - contracapa', 'description' => null, 'link' => '/module/Reports/ClassRecordBackCover', 'order' => 2, 'process' => 999712],
            ['id' => 999602, 'old' => 999602, 'parent_old' => 999600, 'title' => 'Carteira de estudante', 'description' => null, 'link' => '/module/Reports/StudentCard', 'order' => 1, 'process' => 999602],
            ['id' => 999711, 'old' => 999711, 'parent_old' => 999600, 'title' => 'Carteira de Transporte', 'description' => null, 'link' => '/module/Reports/TransportationCard', 'order' => 2, 'process' => 999711],
        ];

        $menuTable = 'menus';
        $menuColumns = Schema::getColumnListing($menuTable);
        $hasOld = in_array('old', $menuColumns);
        $hasParentOld = in_array('parent_old', $menuColumns);
        $hasProcess = in_array('process', $menuColumns);

        // 3. Insere/Atualiza Categorias
        $resolvedParentIds = [];

        $schoolMenu = Menu::query()->where('old', 15)->orWhere('old', Process::MENU_SCHOOL)->first();
        $schoolId = $schoolMenu ? $schoolMenu->getKey() : 15;

        $employeesMenu = Menu::query()->where('old', 71)->orWhere('old', Process::MENU_EMPLOYEES)->first();
        $employeesId = $employeesMenu ? $employeesMenu->getKey() : 71;

        $resolvedParentIds[15] = $schoolId;
        $resolvedParentIds[71] = $employeesId;

        foreach ($categories as $cat) {
            try {
                $parentId = null;
                if ($cat['parent_old'] === 15) {
                    $parentId = $schoolId;
                } elseif ($cat['parent_old'] === 71) {
                    $parentId = $employeesId;
                } elseif (isset($resolvedParentIds[$cat['parent_old']])) {
                    $parentId = $resolvedParentIds[$cat['parent_old']];
                } else {
                    $parentModel = Menu::query()->where('old', $cat['parent_old'])->first();
                    $parentId = $parentModel ? $parentModel->getKey() : $cat['parent_old'];
                }

                $payload = [
                    'title' => $cat['title'],
                    'order' => $cat['order'],
                ];
                if ($parentId !== null) {
                    $payload['parent_id'] = $parentId;
                }
                if ($hasParentOld && $cat['parent_old'] !== null) {
                    $payload['parent_old'] = $cat['parent_old'];
                }
                if ($hasProcess && $cat['process'] !== null) {
                    $payload['process'] = $cat['process'];
                }
                if (in_array('link', $menuColumns) && $cat['link'] !== null) {
                    $payload['link'] = $cat['link'];
                }

                $query = Menu::query();
                if ($hasOld) {
                    $model = $query->updateOrCreate(['old' => $cat['old']], $payload);
                } else {
                    $model = $query->updateOrCreate(['id' => $cat['id']], $payload);
                }
                $resolvedParentIds[$cat['old']] = $model->getKey();
                $resolvedParentIds[$cat['id']] = $model->getKey();
            } catch (\Throwable $e) {
                // Prossegue
            }
        }

        // 4. Insere/Atualiza Itens Canônicos de Relatórios e Documentos
        foreach ($items as $it) {
            try {
                $parentId = null;
                if (isset($resolvedParentIds[$it['parent_old']])) {
                    $parentId = $resolvedParentIds[$it['parent_old']];
                } else {
                    $parentModel = Menu::query()->where('old', $it['parent_old'])->first();
                    $parentId = $parentModel ? $parentModel->getKey() : $it['parent_old'];
                }

                $payload = [
                    'title' => $it['title'],
                    'order' => $it['order'],
                    'parent_id' => $parentId,
                ];
                if ($hasParentOld && $it['parent_old'] !== null) {
                    $payload['parent_old'] = $it['parent_old'];
                }
                if ($hasProcess && $it['process'] !== null) {
                    $payload['process'] = $it['process'];
                }
                if (in_array('link', $menuColumns) && $it['link'] !== null) {
                    $payload['link'] = $it['link'];
                }

                $query = Menu::query();
                if ($hasOld) {
                    $model = $query->updateOrCreate(['old' => $it['old']], $payload);
                } else {
                    $model = $query->updateOrCreate(['id' => $it['id']], $payload);
                }
                $resolvedParentIds[$it['old']] = $model->getKey();
                $resolvedParentIds[$it['id']] = $model->getKey();
            } catch (\Throwable $e) {
                // Prossegue
            }
        }

        // 5. Deduplicação inteligente de menus com mesmo link ou mesmo title sob o mesmo parent_id
        try {
            $allMenus = Menu::query()->whereNotNull('link')->get();
            $seen = [];
            foreach ($allMenus as $m) {
                $key = $m->parent_id . '|' . $m->link;
                if (isset($seen[$key])) {
                    // Deleta o duplicado mais antigo ou com menor id
                    $toDelete = ($m->getKey() < $seen[$key]->getKey()) ? $m : $seen[$key];
                    $toKeep = ($m->getKey() < $seen[$key]->getKey()) ? $seen[$key] : $m;

                    $deleteId = $toDelete->getKey();
                    if (Schema::hasTable('menu_tipo_usuario')) {
                        DB::table('menu_tipo_usuario')->where('menu_id', $deleteId)->delete();
                    }
                    if (Schema::hasTable('pmieducar.menu_tipo_usuario')) {
                        DB::table('pmieducar.menu_tipo_usuario')->where('menu_id', $deleteId)->delete();
                    }
                    $toDelete->delete();
                    $seen[$key] = $toKeep;
                } else {
                    $seen[$key] = $m;
                }
            }
        } catch (\Throwable $e) {
            // Ignora
        }

        // 6. Garante permissões em menu_tipo_usuario para TODOS os menus válidos
        try {
            $permTable = null;
            if (Schema::hasTable('menu_tipo_usuario')) {
                $permTable = 'menu_tipo_usuario';
            } elseif (Schema::hasTable('pmieducar.menu_tipo_usuario')) {
                $permTable = 'pmieducar.menu_tipo_usuario';
            }

            if ($permTable) {
                $columns = Schema::getColumnListing($permTable);

                $tipoUsuarioTable = null;
                foreach (['tipo_usuario', 'pmieducar.tipo_usuario'] as $tut) {
                    if (Schema::hasTable($tut)) {
                        $tipoUsuarioTable = $tut;
                        break;
                    }
                }

                $tipos = [];
                if ($tipoUsuarioTable) {
                    $tutCols = Schema::getColumnListing($tipoUsuarioTable);
                    $userTypeCol = in_array('cod_tipo_usuario', $tutCols) ? 'cod_tipo_usuario' : (in_array('id', $tutCols) ? 'id' : null);
                    if ($userTypeCol) {
                        $tipos = DB::table($tipoUsuarioTable)->pluck($userTypeCol)->all();
                    }
                }

                $userTypeFkCol = in_array('ref_cod_tipo_usuario', $columns) ? 'ref_cod_tipo_usuario' : (in_array('tipo_usuario_id', $columns) ? 'tipo_usuario_id' : null);
                if (empty($tipos) && $userTypeFkCol) {
                    $tipos = DB::table($permTable)->distinct()->pluck($userTypeFkCol)->filter()->all();
                }

                $hasMenuId = in_array('menu_id', $columns);
                $hasProcessCol = in_array('ref_processo_ap', $columns);

                if ($userTypeFkCol && ($hasMenuId || $hasProcessCol) && !empty($tipos)) {
                    $allTargetMenus = Menu::query()->get();

                    foreach ($allTargetMenus as $targetMenu) {
                        $mId = (int) $targetMenu->getKey();
                        $proc = $targetMenu->process ? (int) $targetMenu->process : null;

                        foreach ($tipos as $tipoId) {
                            try {
                                $q = DB::table($permTable)->where($userTypeFkCol, $tipoId);
                                if ($hasMenuId) {
                                    $q->where('menu_id', $mId);
                                } elseif ($hasProcessCol && $proc) {
                                    $q->where('ref_processo_ap', $proc);
                                } else {
                                    continue;
                                }

                                if (!$q->exists()) {
                                    $row = [
                                        $userTypeFkCol => $tipoId,
                                    ];
                                    if ($hasMenuId) {
                                        $row['menu_id'] = $mId;
                                    }
                                    if ($hasProcessCol && $proc) {
                                        $row['ref_processo_ap'] = $proc;
                                    }
                                    if (in_array('visualiza', $columns)) {
                                        $row['visualiza'] = 1;
                                    }
                                    if (in_array('cadastra', $columns)) {
                                        $row['cadastra'] = 1;
                                    }
                                    if (in_array('exclui', $columns)) {
                                        $row['exclui'] = 1;
                                    }
                                    DB::table($permTable)->insert($row);
                                } else {
                                    $updateData = [];
                                    if (in_array('visualiza', $columns)) {
                                        $updateData['visualiza'] = 1;
                                    }
                                    if (in_array('cadastra', $columns)) {
                                        $updateData['cadastra'] = 1;
                                    }
                                    if (in_array('exclui', $columns)) {
                                        $updateData['exclui'] = 1;
                                    }
                                    if (!empty($updateData)) {
                                        $q->update($updateData);
                                    }
                                }
                            } catch (\Throwable $e) {
                                // Ignora
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // Ignora
        }
    }

    public function down()
    {
    }
}
