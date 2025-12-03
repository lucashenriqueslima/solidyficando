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

    public FinancialMovement $financialMovement;

    public string $financialMovementCategoryId;

    public $pixQrCode = null;
    public $pixPayload = null;
    public $isLoading = false;
    public $qrCodeExpiresAt = null;
    public $showPaymentSuccessModal = false;

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

            $this->pixQrCode = $pixQrCodeData['encodedImage'];
            $this->pixPayload = $pixQrCodeData['payload'] ?? null;
            $this->qrCodeExpiresAt = now()->addMinutes(7)->toImmutable();
            $this->isLoading = false;

            $this->financialMovement = new FinancialMovement;

            $this->financialMovement->asaas_id = $billing['id'];
            $this->financialMovement->value = $this->amount;
            $this->financialMovement->description = "Doação de {$this->name} ({$this->document}) no valor de R$ {$this->amount}";
            $this->financialMovement->financial_movement_category_id = $this->financialMovementCategoryId;
            $this->financialMovement->flow_type = FinancialMovementFlowType::IN;
            $this->financialMovement->status = FinancialMovementStatus::PENDING;
            $this->financialMovement->due_date = now()->addDay();
            $this->financialMovement->save();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            $this->isLoading = false;
            return;
        }
    }

    public function refreshFinancialMovement()
    {
        $this->financialMovement->refresh();

        // Verifica se o pagamento foi confirmado
        if ($this->financialMovement->status === FinancialMovementStatus::PAID) {
            $this->showPaymentSuccessModal = true;
        }
    }

    public function checkExpiration()
    {
        if ($this->qrCodeExpiresAt && now()->greaterThan($this->qrCodeExpiresAt)) {
            $this->reset(['pixQrCode', 'pixPayload', 'qrCodeExpiresAt']);
            session()->flash('error', 'O QR Code do PIX expirou. Por favor, gere um novo código.');
            return true;
        }
        return false;
    }

    public function expireQrCode()
    {
        $this->reset(['pixQrCode', 'pixPayload', 'qrCodeExpiresAt']);
        session()->flash('error', 'O QR Code do PIX expirou. Por favor, gere um novo código.');
        return true;
    }

    public function resetForm()
    {
        $this->reset();
        $this->mount();
    }

    public function render()
    {
        return view('livewire.create-donation');
    }
}
