<?php

use iEducar\Reports\JsonDataSource;

class IndividualSheetEjaReport extends Portabilis_Report_ReportCore
{
    use JsonDataSource;

    public function templateName()
    {
        return 'individual-sheet-eja';
    }

    public function requiredArgs()
    {
        $this->addRequiredArg('ano');
        $this->addRequiredArg('instituicao');
        $this->addRequiredArg('escola');
    }

    public function getJsonData()
    {
        $query = new QueryIndividualSheetEja();

        return [
            'main' => $query->get($this->args),
            'header' => Portabilis_Utils_Database::fetchPreparedQuery($this->getSqlHeaderReport())
        ];
    }
}
