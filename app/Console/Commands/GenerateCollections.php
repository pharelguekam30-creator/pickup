<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Models\SubscriptionCollection;
use App\Models\CollectionPlan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateCollections extends Command
{
    protected $signature = 'collections:generate';
    protected $description = 'Generate next month collections for active subscriptions';

    public function handle()
    {
        $daysMap = [
            'monday' => Carbon::MONDAY, 'tuesday' => Carbon::TUESDAY,
            'wednesday' => Carbon::WEDNESDAY, 'thursday' => Carbon::THURSDAY,
            'friday' => Carbon::FRIDAY, 'saturday' => Carbon::SATURDAY,
            'sunday' => Carbon::SUNDAY,
        ];

        $subscriptions = Subscription::with('plan')->where('status', 'active')->get();
        $generated = 0;

        foreach ($subscriptions as $subscription) {
            $plan = $subscription->plan;
            if (!$plan) continue;

            $lastCollection = $subscription->collections()->max('scheduled_date');
            $start = $lastCollection ? Carbon::parse($lastCollection)->addDay() : Carbon::parse($subscription->start_date);
            $end = Carbon::now()->addMonth();

            if ($start > $end) continue;

            $dayNumbers = collect($plan->collection_days)
                ->map(fn($d) => $daysMap[strtolower($d)] ?? null)
                ->filter();

            $current = $start->copy();
            while ($current <= $end) {
                $exists = $subscription->collections()->whereDate('scheduled_date', $current->toDateString())->exists();
                if (!$exists && $dayNumbers->contains($current->dayOfWeek)) {
                    SubscriptionCollection::create([
                        'subscription_id' => $subscription->id,
                        'scheduled_date' => $current->toDateString(),
                        'time_slot' => '08:00:00',
                        'status' => 'scheduled',
                    ]);
                    $generated++;
                }
                $current->addDay();
            }
        }

        $this->info("Generated $generated new collections.");
    }
}
