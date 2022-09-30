<?php

namespace App\Http\Controllers\EnsinoPesquisaExtensao;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Services\Basic\CertificateService;
use App\DAO\EnsinoPesquisaExtensao\CertificadoDAO;

class CertificateController extends Controller
{
    public function generateCertificateByStudent($incricaoid, $turmaId)
    {
        return $this->generateCertificate($incricaoid, $turmaId);
    }

    public function generateCertificateByClass($turmaId)
    {
        return $this->generateCertificate($turmaId);
    }

    public function download()
    {
        $certificate_service = new CertificateService();

        $certificate_service->generatePDF();
    }

    private function generateCertificate($turmaId, $incricaoid = null)
    {
        $certificadoDao = new CertificadoDAO();
        $cursoMatrizCurricular = $certificadoDao->getCursoMatrizCurricular(
            $turmaId
        );

        if (!is_null($incricaoid)) {
            $estudantes = $certificadoDao->getInscricoesDaTurma(
                $turmaId,
                $incricaoid
            );
        } else {
            $estudantes = $certificadoDao->getInscricoesDaTurma(
                $turmaId,
            );
        }

        return response()->json(
            [
                'sucesso' => true,
                'curso' => $cursoMatrizCurricular['curso'],
                'modulos' => $cursoMatrizCurricular['modulos'],
                'estudantes' => $estudantes,
            ],
            Response::HTTP_OK
        );
    }
}
