<?php

namespace App\Tolerance;

use Tolerance\MessageProfile\Peer\ArbitraryPeer;
use Tolerance\MessageProfile\Peer\Resolver\PeerResolver;

final class CurrentPeerEnhancer implements PeerResolver
{
    /**
     * @var PeerResolver
     */
    private $decoratedPeerResolver;

    /**
     * @param PeerResolver $decoratedPeerResolver
     */
    public function __construct(PeerResolver $decoratedPeerResolver)
    {
        $this->decoratedPeerResolver = $decoratedPeerResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve()
    {
        $peer = $this->decoratedPeerResolver->resolve();

        return ArbitraryPeer::fromArray(array_merge($peer->getArray(), [
            'service' => getenv('TOLERANCE_SERVICE_NAME'),
            'container' => getenv('HOSTNAME'),
        ]));
    }
}
