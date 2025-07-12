<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessWhatsappQueue extends Command
{
    protected $signature = 'whatsapp:process';
    protected $description = 'Process WhatsApp queue and send confirmations';

    public function handle()
    {
        $pending = DB::table('whatsapp_queue')->where('status', 'pending')->get();
        foreach ($pending as $item) {
            // Simulate sending WhatsApp message
            $this->info('Sending WhatsApp message for order #' . $item->order_id . ': ' . $item->message);
            DB::table('whatsapp_queue')->where('id', $item->id)->update([
                'status' => 'sent',
                'updated_at' => now(),
            ]);
        }
        $this->info('WhatsApp queue processed.');
    }
} 