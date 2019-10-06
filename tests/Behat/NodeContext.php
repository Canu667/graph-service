<?php
declare(strict_types=1);

namespace App\Tests\Behat;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use Imbo\BehatApiExtension\Context\ApiContext;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Psr7;

final class NodeContext extends ApiContext
{
    protected static $nodeId;

    /**
     * @Then I want to save the node id
     */
    public function saveNodeId()
    {
        $this->response->getBody()->rewind();
        self::$nodeId = json_decode($this->response->getBody()->getContents(), true)['id'];
    }

    /**
     * @When I request to remove the saved node id from the :path
     *
     * @param string $path
     *
     * @return ApiContext
     * @throws \Exception
     */
    public function removeSavedNode(string $path)
    {
        $this->setRequestPath($path . '/' . self::$nodeId);
        $this->setRequestMethod('DELETE');
        self::$nodeId = null;

        return $this->sendRequest();
    }

    /**
     * Manipulate the API client
     *
     * @param ClientInterface $client
     *
     * @return self
     */
    public function setClient(ClientInterface $client)
    {
        $stack = $client->getConfig('handler');
        $stack->push(Middleware::mapRequest(function (RequestInterface $request) {
            if (self::$nodeId !== null) {
                $body = json_decode($request->getBody()->getContents(), true);

                $body['id'] = self::$nodeId;

                return $request->withBody(Psr7\stream_for(json_encode($body)));
            }

            return $request;
        }));

        return parent::setClient($client);
    }
}
