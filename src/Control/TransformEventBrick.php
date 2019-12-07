<?php
/**
 * BSD 3-Clause License
 *
 * Copyright (c) 2019, TASoft Applications
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *  Redistributions of source code must retain the above copyright notice, this
 *   list of conditions and the following disclaimer.
 *
 *  Redistributions in binary form must reproduce the above copyright notice,
 *   this list of conditions and the following disclaimer in the documentation
 *   and/or other materials provided with the distribution.
 *
 *  Neither the name of the copyright holder nor the names of its
 *   contributors may be used to endorse or promote products derived from
 *   this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE
 * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
 * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

namespace Ikarus\SPS\Control;


use Ikarus\SPS\AbstractBrick;
use Ikarus\SPS\ActorBrickInterface;
use Ikarus\SPS\Event\DispatchedEventInterface;
use TASoft\EventManager\EventManager;

class TransformEventBrick extends AbstractBrick implements ActorBrickInterface
{
    /** @var array */
    private $sourceEventNames;

    public function __construct($sourceEventNames, $destinationEventNames)
    {
        $this->sourceEventNames = array_combine($sourceEventNames, $destinationEventNames);
    }

    public function getEventNames(): array
    {
        return array_keys( $this->sourceEventNames );
    }

    public function __invoke(string $eventName, DispatchedEventInterface $event, EventManager $eventManager, ...$arguments)
    {
        if($nextEvent = $this->sourceEventNames[ $eventName ] ?? NULL) {
            $eventManager->trigger( $nextEvent, $event, ...$arguments );
        } else {
            trigger_error("Could not transform event $eventName", E_USER_WARNING);
        }
    }

    public function getIdentifier(): string
    {
        return get_class($this);
    }
}