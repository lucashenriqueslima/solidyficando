<?php

namespace App\Http\Responses;



use App\Filament\App\Resources\PersonResource;
use App\Filament\Customer\Resources\MyOrdersResource;
use App\Filament\Resources\CompanyResource;
use App\Filament\Resources\OrderResource;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse extends \Filament\Http\Responses\Auth\LoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        // You can use the Filament facade to get the current panel and check the ID
        if (Filament::getCurrentPanel()->getId() === 'admin') {
            return redirect()->to(CompanyResource::getUrl('index'));
        }

        if (Filament::getCurrentPanel()->getId() === 'app') {
            return redirect()->to(PersonResource::getUrl('index'));
        }

        return parent::toResponse($request);
    }
}
