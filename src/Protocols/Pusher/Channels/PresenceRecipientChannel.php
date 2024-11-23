<?php

namespace Laravel\Reverb\Protocols\Pusher\Channels;

use Laravel\Reverb\Contracts\Connection;
use Laravel\Reverb\Loggers\Log;
use Laravel\Reverb\Protocols\Pusher\Channels\Concerns\InteractsWithPresenceChannels;

class PresenceRecipientChannel extends PrivateChannel
{
    use InteractsWithPresenceChannels;

    #[\Override]
    public function broadcast(array $payload, ?Connection $except = null): void
    {
        $recipientConnections = $this->getRecipientConnections();

        $message = json_encode($payload);

        Log::info('Broadcasting To', $this->name());
        Log::message($message);

        if ($except === null) {
            $message = json_encode($payload);

            Log::info('Broadcasting To', $this->name());
            Log::message($message);
            foreach ($recipientConnections as $connection) {
                $connection->send($message);
            }
            return;
        }


        if ($senderConnect = $this->connections()[$except->id()] ?? null) {
            $payload['user_id'] = (int)$senderConnect->data('user_id');
        }

        $message = json_encode($payload);

        Log::info('Broadcasting To', $this->name());
        Log::message($message);
        // Если except указан, отправляем всем, кроме него
        foreach ($recipientConnections as $connection) {
            if ($except->id() !== $connection->id()) {
                $connection->send($message);
            }
        }
    }

    private function getRecipientConnections() {
        return collect($this->connections->all())->filter(fn(ChannelConnection $connection) => $connection->data('user_info.is_recipient') === true);
    }
}
