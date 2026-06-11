<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateAddress($request);

        $user = $request->user();
        $makeDefault = $request->boolean('is_default') || ! $user->addresses()->exists();

        if ($makeDefault) {
            $user->addresses()->update(['is_default' => false]);
        }

        $user->addresses()->create([
            ...$data,
            'is_default' => $makeDefault,
        ]);

        return back()->with('status', 'address-created');
    }

    public function update(Request $request, UserAddress $address): RedirectResponse
    {
        $this->authorizeAddress($request, $address);
        $data = $this->validateAddress($request);

        $makeDefault = $request->boolean('is_default');

        if ($makeDefault) {
            $request->user()->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update([
            ...$data,
            'is_default' => $makeDefault,
        ]);

        $this->ensureAtLeastOneDefault($request->user());

        return back()->with('status', 'address-updated');
    }

    public function destroy(Request $request, UserAddress $address): RedirectResponse
    {
        $this->authorizeAddress($request, $address);
        $wasDefault = $address->is_default;

        $address->delete();

        if ($wasDefault) {
            $this->ensureAtLeastOneDefault($request->user());
        }

        return back()->with('status', 'address-deleted');
    }

    public function setDefault(Request $request, UserAddress $address): RedirectResponse
    {
        $this->authorizeAddress($request, $address);

        $request->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return back()->with('status', 'address-defaulted');
    }

    private function validateAddress(Request $request): array
    {
        return $request->validate([
            'label' => ['required', 'string', 'max:100'],
            'recipient_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'address_line' => ['required', 'string', 'max:1000'],
            'is_default' => ['nullable', 'boolean'],
        ]);
    }

    private function authorizeAddress(Request $request, UserAddress $address): void
    {
        abort_unless($address->user_id === $request->user()->id, 403);
    }

    private function ensureAtLeastOneDefault($user): void
    {
        $addresses = $user->addresses()->orderByDesc('is_default')->orderByDesc('updated_at')->get();

        if ($addresses->isEmpty() || $addresses->contains(fn (UserAddress $address) => $address->is_default)) {
            return;
        }

        $addresses->first()?->update(['is_default' => true]);
    }
}
