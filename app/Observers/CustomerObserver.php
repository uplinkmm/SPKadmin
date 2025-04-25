<?php

namespace App\Observers;

use App\Models\Customer;

class CustomerObserver
{
    /**
     * Handle the Customer "created" event.
     */
    public function created(Customer $customer): void
    {
        //
        // $customer->user_name = 'user_' . $customer->id;
        // $customer->save(); // Save the updated user_name
        do {
            $userName = 'user_' . $customer->id . '_' . mt_rand(1000, 9999);
        } while (\App\Models\Customer::where('user_name', $userName)->exists());
        $customer->user_name = $userName;
        $customer->save();
    }

    /**
     * Handle the Customer "updated" event.
     */
    public function updated(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "deleted" event.
     */
    public function deleted(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "restored" event.
     */
    public function restored(Customer $customer): void
    {
        //
    }

    /**
     * Handle the Customer "force deleted" event.
     */
    public function forceDeleted(Customer $customer): void
    {
        //
    }
}
