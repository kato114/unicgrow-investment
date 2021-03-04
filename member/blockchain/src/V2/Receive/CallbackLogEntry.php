<?php

namespace Blockchain\V2\Receive;

use DateTime;

/**
 * The callback log from the receive API.
 *
 * @author George Robinson <george.robinson@blockchain.com>
 */
class CallbackLogEntry
{
    /**
     * @var string
     */
    private $callback;

    /**
     * @var \DateTime
     */
    private $calledAt;

    /**
     * @var string
     */
    private $rawResponse;

    /**
     * @var int
     */
    private $responseCode;

    public function __construct($callback, DateTime $calledAt, $rawResponse, $responseCode)
    {
        $this->callback = $callback;
        $this->calledAt = $calledAt;
        $this->rawResponse = $rawResponse;
        $this->responseCode = $responseCode;
    }

    /**
     * Gets the callback URL.
     *
     * @return string
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Gets the called at timestamp.
     *
     * @return \DateTime
     */
    public function getCalledAt()
    {
        return $this->calledAt;
    }

    /**
     * Gets the raw HTTP response.
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->rawResponse;
    }

    /**
     * Gets the response code.
     *
     * @return int
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }
}
