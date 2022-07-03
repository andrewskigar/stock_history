<?php

namespace App\Controller;

use App\Contracts\StockHistoryFetcherInterface;
use App\Entity\FormDto;
use App\Form\StockViewerFormType;
use App\Services\StockHistoryFormatter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class StockViewerController extends AbstractController
{

    #[Route('/', name: 'app_stock_viewer')]
    public function index(Request $request, StockHistoryFetcherInterface $stockHistoryFetcher, StockHistoryFormatter $stockHistoryFormatter, MailerInterface $mailer): Response
    {
        $formDto = new FormDto();
        $stockHistory = [];
        $stockPoints = [];

        $form = $this->createForm(StockViewerFormType::class, $formDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $stockHistory = $stockHistoryFetcher->fetchStockHistory($formDto->getCompanySymbol(), $formDto->getStartDate(), $formDto->getEndDate());
            $stockPoints = $stockHistoryFormatter->formatToCandlestickData($stockHistory);

            $email = (new Email())
                ->from($formDto->getEmail())
                ->to($formDto->getEmail())
                ->subject($formDto->getCompanySymbol() . ' => ' . $formDto->getCompanyNameBySymbol($formDto->getCompanySymbol()))
                ->text('From ' . $formDto->getFormattedStartDate() . ' to ' . $formDto->getFormattedEndDate());

            $mailer->send($email);
        }

        return $this->render('stock_viewer/index.html.twig', [
            'stock_form'    => $form->createView(),
            'stock_history' => $stockHistory,
            'stock_points'  => $stockPoints,
            'current_stock' => $formDto,
        ]);
    }
}
