<?php

namespace App\Http\Controllers;

use App\Models\GiftCard;
use App\Utils\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GiftCardController extends Controller
{
    public function index($id)
    {

        return Inertia::render('GiftCard/Show', [
            'settings' => Settings::get(),
            'giftcard' => $id
        ]);
    }
    public function redeem(Request $request)
    {
        $code = $request->input('id');
        $gift = GiftCard::with('plan')->where('code', $code)->first();

        if (! $gift) {
            return back()->withErrors([
                'giftcard' => 'GiftCard não encontrado.',
            ]);
        }

        $plan = $gift->plan;

        if (! $gift->isValidToUse()) {
            return back()->withErrors([
                'giftcard' => 'GiftCard expirado, inativo ou sem usos disponíveis.',
            ]);
        }

        $user = $request->user();

        if ($gift->owner_user_id && $gift->owner_user_id !== $user->id) {
            return back()->withErrors([
                'giftcard' => 'Você não tem permissão para usar este GiftCard.',
            ]);
        }

        $gift->usage_count += 1;
        if ($gift->usage_count >= $gift->usage_limit) {
            $gift->active = false;
        }
        $gift->save();

        $now = Carbon::now();
        $subscription_end = $user->subscription_end ? Carbon::parse($user->subscription_end) : null;
        if (is_null($subscription_end) || $subscription_end->isPast()) {
            $user->subscription_start = $now;
            $user->subscription_end   = $now->clone()->addDays((float) $plan->days);
        } else {
            $user->subscription_end = $subscription_end->addDays((float) $plan->days);
        }

        $user->save();

        return redirect()->back()->with('success', 'GiftCard resgatado com sucesso!');
    }
}
