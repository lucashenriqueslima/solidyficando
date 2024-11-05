<?php

namespace App\Filament\Pages\Auth;

use App\Filament\Helpers\FillamentHelper;
use App\Models\Company;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;


class Login extends \Filament\Pages\Auth\Login
{

    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            $this->getRateLimitedNotification($exception)?->send();

            return null;
        }

        $data = $this->form->getState();



        $company = $this->findCompanyByCredentials($this->getCredentialsFromFormData($data));

        if (!$company) {
            $this->throwFailureValidationException();
        }

        Filament::auth()->loginUsingId($company->id, $data['remember'] ?? false);

        $user = Filament::auth()->user();

        if (
            ($user instanceof FilamentUser) &&
            (! $user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }

    public function findCompanyByCredentials(array $credentials): ?Company
    {
        $mutatedFirstFourCpfNumbers = Str::substrReplace($credentials['cpf'], '.', 3, 0);

        $company = Company::with('president')
            ->where('cnpj', $credentials['cnpj'])
            ->whereHas('president', fn($query) => $query->where('cpf', 'like', "{$mutatedFirstFourCpfNumbers}%"))
            ->first();

        return $company;
    }

    /**
     * @return array<int | string, string | Form>
     */
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('cnpj')
            ->label('CNPJ da Instituição')
            ->mask('99.999.999/9999-99')
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('cpf')
            ->label('Os 4 primeiros números do CPF do Presidente da Instituição (apenas números)')
            ->hint(filament()->hasPasswordReset() ? new HtmlString(Blade::render('<x-filament::link :href="filament()->getRequestPasswordResetUrl()" tabindex="3"> {{ __(\'filament-panels::pages/auth/login.actions.request_password_reset.label\') }}</x-filament::link>')) : null)
            ->password()
            ->mask('9999')
            ->revealable(filament()->arePasswordsRevealable())
            ->autocomplete('current-password')
            ->maxLength(4)
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'cnpj' => $data['cnpj'],
            'cpf' => $data['cpf'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.cnpj' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
}
