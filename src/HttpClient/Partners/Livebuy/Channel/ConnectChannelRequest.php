<?php

namespace Cometcast\Openapi\HttpClient\Partners\Livebuy\Channel;

class ConnectChannelRequest implements \JsonSerializable
{
    /**
     * 頻道名稱
     * @var string
     */
    public $name;

    /**
     * 商家編號
     * @var string
     */
    public $storeId;

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
        return [
            'name' => $this->name,
            'store_id' => $this->storeId,
        ];
    }
}