<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\OrderEventNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class OrderNotificationService
{
    public function notifyCreated(int $orderId): void
    {
        $this->dispatch($orderId, [
            'subject' => 'Pesanan baru diterima',
            'message' => 'Pesanan Anda sudah dibuat dan sedang menunggu pembayaran/verifikasi.',
            'event' => 'created',
            'action_text' => 'Lihat Pesanan',
        ]);
    }

    public function notifyPaymentProofUploaded(int $orderId): void
    {
        $this->dispatch($orderId, [
            'subject' => 'Bukti pembayaran diunggah',
            'message' => 'Bukti pembayaran sudah diunggah dan menunggu verifikasi admin.',
            'event' => 'payment_proof_uploaded',
            'action_text' => 'Lihat Invoice',
        ]);
    }

    public function notifyPaymentApproved(int $orderId): void
    {
        $this->dispatch($orderId, [
            'subject' => 'Pembayaran diterima',
            'message' => 'Pembayaran untuk pesanan Anda telah diterima.',
            'event' => 'payment_approved',
            'action_text' => 'Lihat Pesanan',
        ]);
    }

    public function notifyPaymentRejected(int $orderId, ?string $reason = null): void
    {
        $this->dispatch($orderId, [
            'subject' => 'Pembayaran ditolak',
            'message' => $reason
                ? 'Pembayaran untuk pesanan Anda ditolak: ' . $reason
                : 'Pembayaran untuk pesanan Anda ditolak oleh admin.',
            'event' => 'payment_rejected',
            'action_text' => 'Lihat Pesanan',
        ]);
    }

    public function notifyShipped(int $orderId): void
    {
        $this->dispatch($orderId, [
            'subject' => 'Pesanan sedang dikirim',
            'message' => 'Pesanan Anda sudah dikirim dan dapat dipantau dengan nomor resi.',
            'event' => 'shipped',
            'action_text' => 'Lihat Tracking',
        ]);
    }

    public function notifyArrived(int $orderId): void
    {
        $this->dispatch($orderId, [
            'subject' => 'Pesanan sudah sampai tujuan',
            'message' => 'Pesanan Anda sudah sampai tujuan dan menunggu konfirmasi penerimaan.',
            'event' => 'arrived',
            'action_text' => 'Lihat Pesanan',
        ]);
    }

    public function notifyCompleted(int $orderId): void
    {
        $this->dispatch($orderId, [
            'subject' => 'Pesanan selesai',
            'message' => 'Pesanan Anda sudah selesai dan berhasil dikonfirmasi.',
            'event' => 'completed',
            'action_text' => 'Lihat Invoice',
        ]);
    }

    private function dispatch(int $orderId, array $payload): void
    {
        $order = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'users.name as buyer_name', 'users.email as buyer_email')
            ->where('orders.id', $orderId)
            ->first();

        if (!$order) {
            return;
        }

        $payload = array_merge($payload, [
            'order_id' => $order->id,
            'order_status' => $order->status,
            'payment_status' => $order->payment_status ?? null,
            'action_url' => route('orders.history') . '#' . $order->id,
        ]);

        $customer = User::find($order->user_id);
        $admins = User::where('role', 'admin')->get();
        $recipients = collect([$customer])->merge($admins)->filter()->unique('id');

        Notification::send($recipients, new OrderEventNotification($payload));
    }
}
