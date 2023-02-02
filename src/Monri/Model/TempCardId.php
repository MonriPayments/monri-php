<?php

namespace Monri\Model;

class TempCardId
{
    /**
     * @var string
     */
    private $timestamp;
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $digest;

    /**
     * @param string $timestamp
     * @param string $id
     * @param string $digest
     */
    public function __construct(string $timestamp, string $id, string $digest)
    {
        $this->timestamp = $timestamp;
        $this->id = $id;
        $this->digest = $digest;
    }

    /**
     * @return string
     */
    public function getTimestamp(): string
    {
        return $this->timestamp;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDigest(): string
    {
        return $this->digest;
    }

}
