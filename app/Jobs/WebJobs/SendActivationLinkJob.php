<?php

namespace App\Jobs\WebJobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\WebModels\Admin;
use App\Mail\WebMail\AdminActivationMail;
use Illuminate\Support\Facades\Mail;

class SendActivationLinkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $admin;
    protected $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Admin $admin, $url)
    {
        $this->admin = $admin;
        $this->url = $url;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Mail::to($this->admin->email)->send(new AdminActivationMail($this->admin->firstname. ' '. $this->admin->lastname, $this->admin->activation_code, $this->url));
        } catch (\Exception $e) {
            logger('Sending Admin Activation Link Failed. Reason : ' . $e->getMessage());
        }
    }
}
