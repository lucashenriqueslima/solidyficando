<?php

namespace App\Livewire;

use App\Enums\BillingType;
use App\Services\Asaas\AsaasApiService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateDonation extends Component
{
    #[Validate('required|in:cpf,cnpj')]
    public $documentType = 'cpf';

    #[Validate('required|min:3|max:255')]
    public $name = '';

    #[Validate('required|string')]
    public $document = '';

    #[Validate('required|numeric|min:1')]
    public $amount = '';

    public $pixQrCode = null;
    public $pixPayload = null;
    public $isLoading = false;

    public function submit()
    {
        $this->validate();
        $this->isLoading = true;

        // dd($this->documentType, $this->document, $this->name, $this->amount);

        $asaasService = new AsaasApiService();
        try {
            $customer = $asaasService->createCustomer(
                name: $this->name,
                cpfCnpj: $this->document,
            );

            if (!$customer) {
                session()->flash('error', 'Erro ao criar cliente. Tente novamente.');
                $this->isLoading = false;
                return;
            }

            $billing = $asaasService->createBilling(
                customerId: $customer['id'],
                billingType: BillingType::PIX,
                value: $this->amount,
                dueDate: now()->addDay(),
            );

            if (!$billing) {
                session()->flash('error', 'Erro ao gerar cobrança. Tente novamente.');
                $this->isLoading = false;
                return;
            }

            $pixQrCodeData = $asaasService->getPixQrCode($billing['id']);

            if (!$pixQrCodeData) {
                session()->flash('error', 'Erro ao gerar QR Code. Tente novamente.');
                $this->isLoading = false;
                return;
            }

            $this->pixQrCode = $pixQrCodeData['encodedImage'];
            $this->pixPayload = $pixQrCodeData['payload'] ?? null;
            $this->isLoading = false;
        } catch (\Exception $e) {
            session()->flash('error', 'Erro ao processar doação: ' . $e->getMessage());
            $this->isLoading = false;
            return;
        }
    }

    public function render()
    {
        return view('livewire.create-donation');
    }
}
