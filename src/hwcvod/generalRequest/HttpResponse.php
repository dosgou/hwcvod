<?php
namespace hwcvod\generalRequest;

class HttpResponse
{
    private $status;

    private $body;

    private $header;

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status  = $status;
    }

    public function getHeader()
    {
        return $this->header;
    }

    public function setHeader($header)
    {
        $this->header = $header;
    }

    public function isSuccess()
    {
        if (200 <= $this->status) {
            return true;
        }
        return false;
    }

}
