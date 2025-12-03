<?php

namespace App\Livewire;

use App\Enums\BillingType;
use App\Enums\FinancialMovementFlowType;
use App\Enums\FinancialMovementStatus;
use App\Models\FinancialMovement;
use App\Models\FinancialMovementCategory;
use App\Services\Asaas\AsaasApiService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateDonation extends Component
{
    #[Validate('required|in:cpf,cnpj')]
    public string $documentType = 'cpf';

    #[Validate('required|min:3|max:255')]
    public string $name = '';

    #[Validate('required|string')]
    public string $document = '';

    #[Validate('required|numeric|min:1')]
    public $amount = '';

    public string $financialMovementCategoryId;

    public $pixQrCode = null;
    public $pixPayload = null;
    public $isLoading = false;

    public function mount()
    {
        $this->financialMovementCategoryId = FinancialMovementCategory::where('name', 'Contribuição Avulsa (PIX)')
            ->where('flow_type', 'in')
            ->value('id');
    }

    public function submit()
    {
        $this->validate();
        $this->isLoading = true;

        $asaasService = new AsaasApiService();

        try {
            $customer = $asaasService->createCustomer(
                name: $this->name,
                cpfCnpj: $this->document,
            );

            $billing = $asaasService->createBilling(
                customerId: $customer['id'],
                billingType: BillingType::PIX,
                value: $this->amount,
                dueDate: now()->addDay(),
            );

            $pixQrCodeData = $asaasService->getPixQrCode($billing['id']);

            if (!$pixQrCodeData) {
                session()->flash('error', 'Erro ao gerar QR Code. Tente novamente.');
                $this->isLoading = false;
                return;
            }

            $this->pixQrCode = $pixQrCodeData['encodedImage'];
            $this->pixPayload = $pixQrCodeData['payload'] ?? null;
            $this->isLoading = false;

            $financialMovement = new FinancialMovement;

            $financialMovement->asaas_id = $billing['id'];
            $financialMovement->value = $this->amount;
            $financialMovement->description = "Doação de {$this->name} ({$this->document}) no valor de R$ {$this->amount}";
            $financialMovement->financial_movement_category_id = $this->financialMovementCategoryId;
            $financialMovement->flow_type = FinancialMovementFlowType::IN;
            $financialMovement->status = FinancialMovementStatus::PENDING;
            $financialMovement->due_date = now()->addDay();
            $financialMovement->save();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            $this->isLoading = false;
            return;
        }
    }

    public function render()
    {
        return view('livewire.create-donation');
    }
}
