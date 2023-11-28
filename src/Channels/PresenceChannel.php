<?php

namespace Laravel\Reverb\Channels;

use Laravel\Reverb\Channels\Concerns\InteractsWithPresenceChannels;

class PresenceChannel extends PrivateChannel
{
    use InteractsWithPresenceChannels;
}
