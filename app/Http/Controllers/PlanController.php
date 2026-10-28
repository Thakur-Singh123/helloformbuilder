<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Stripe\Webhook;
use Carbon\Carbon;

class PlanController extends Controller
{
    public function upgrade()
    {
        if (auth()->check()) {
            auth()->user()->syncPlan();
        }
        return view('pricing');
    }

    public function checkoutPage()
{
    if (auth()->user()->isPro()) {
        return redirect()->route('templates.index');
    }
    return view('plans.checkout');  
}

    public function checkout()
    {
        $user   = auth()->user();
        $stripe = new StripeClient(config('services.stripe.secret'));

        $session = $stripe->checkout->sessions->create([
            'mode'                 => 'subscription',
            'line_items'           => [[
                'price'    => config('services.stripe.pro_price_id'),
                'quantity' => 1,
            ]],
            'customer_email'       => $user->email,
            'client_reference_id'  => $user->id,
            'success_url'          => route('plans.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'           => route('plans.upgrade'),
        ]);

        return redirect($session->url);
    }

    // public function success(Request $request)
    // {
    //     //Upgrade plan
    //     return redirect()->route('templates.index')
    //         ->with('success', 'Payment successful! Your Pro plan will be active shortly.');
    // }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()->route('plans.upgrade');
        }

        $stripe  = new StripeClient(config('services.stripe.secret'));
        $session = $stripe->checkout->sessions->retrieve($sessionId);

        // Security: payment complete ho aur session isi user ka ho
        if ($session->payment_status === 'paid'
            && (int) $session->client_reference_id === auth()->id()) {

            $sub    = $stripe->subscriptions->retrieve($session->subscription);
            $ts     = $sub->items->data[0]->current_period_end ?? $sub->current_period_end ?? null;
            $endsAt = $ts ? Carbon::createFromTimestamp($ts) : now()->addMonth();

            auth()->user()->update([
                'plan'                   => 'pro',
                'plan_ends_at'           => $endsAt,
                'stripe_customer_id'     => $session->customer,
                'stripe_subscription_id' => $session->subscription,
            ]);

            return view('plans.success', [
                'amount'   => number_format($session->amount_total / 100, 2),
                'currency' => strtoupper($session->currency),
                'ends_at'  => $endsAt->format('d M Y'),
            ]);
        }

            return view('plans.success', [
                'amount'   => number_format($session->amount_total / 100, 2),
                'currency' => strtoupper($session->currency),
            ]);
        

        return redirect()->route('plans.upgrade')
            ->with('error', 'Payment verify failed , Please try again.');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig     = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sig, config('services.stripe.webhook_secret'));
        } catch (\Exception $e) {
            return response('Invalid', 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $s    = $event->data->object;
                $user = User::find($s->client_reference_id);
                if ($user) {
                    $user->update([
                        'plan'                   => 'pro',
                        'stripe_customer_id'     => $s->customer,
                        'stripe_subscription_id' => $s->subscription,
                    ]);
                }
                break;

            case 'customer.subscription.deleted':
                $sub  = $event->data->object;
                $user = User::where('stripe_subscription_id', $sub->id)->first();
                if ($user) {
                    $user->update(['plan' => 'free']);
                }
                break;
        }

        return response('OK', 200);
    }
}