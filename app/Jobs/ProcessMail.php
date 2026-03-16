<?php

namespace App\Jobs;

use App\Traits\HandlesMailPreparation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMail implements ShouldQueue
{
  use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, HandlesMailPreparation;

  protected $mailData;
  protected $order;
  protected $message;

  /**
   * Create a new job instance.
   *
   * @return void
   */
  public function __construct($mailData, $order, $message)
  {
    $this->mailData = $mailData;
    $this->order = $order;
    $this->message = $message;
  }

  /**
   * Execute the job.
   *
   * @return void
   */
  public function handle()
  {
    $this->sendMail($this->mailData, $this->order, $this->message);
  }
}
