<?php

namespace Laravel\Reverb\Channels;

use Laravel\Reverb\Channels\Concerns\InteractsWithPrivateChannels;

class PrivateChannel extends Channel
{
    use InteractsWithPrivateChannels;
}
