<?php

namespace YoastSEO_Vendor\GuzzleHttp\Exception;

use YoastSEO_Vendor\Psr\Http\Message\RequestInterface;
use YoastSEO_Vendor\Psr\Http\Message\ResponseInterface;
use YoastSEO_Vendor\GuzzleHttp\Promise\PromiseInterface;
use YoastSEO_Vendor\Psr\Http\Message\UriInterface;
/**
 * HTTP Request exception
 */
class RequestException extends \YoastSEO_Vendor\GuzzleHttp\Exception\TransferException
{
    /** @var RequestInterface */
    private $request;
    /** @var ResponseInterface */
    private $response;
    /** @var array */
    private $handlerContext;
    public function __construct($message, \YoastSEO_Vendor\Psr\Http\Message\RequestInterface $request, \YoastSEO_Vendor\Psr\Http\Message\ResponseInterface $response = null, \Exception $previous = null, array $handlerContext = [])
    {
        // Set the code of the exception if the response is set and not future.
        $code = $response && !$response instanceof \YoastSEO_Vendor\GuzzleHttp\Promise\PromiseInterface ? $response->getStatusCode() : 0;
        parent::__construct($message, $code, $previous);
        $this->request = $request;
        $this->response = $response;
        $this->handlerContext = $handlerContext;
    }
    /**
     * Wrap non-RequestExceptions with a RequestException
     *
     * @param RequestInterface $request
     * @param \Exception       $e
     *
     * @return RequestException
     */
    public static function wrapException(\YoastSEO_Vendor\Psr\Http\Message\RequestInterface $request, \Exception $e)
    {
        return $e instanceof \YoastSEO_Vendor\GuzzleHttp\Exception\RequestException ? $e : new \YoastSEO_Vendor\GuzzleHttp\Exception\RequestException($e->getMessage(), $request, null, $e);
    }
    /**
     * Factory method to create a new exception with a normalized error message
     *
     * @param RequestInterface  $request  Request
     * @param ResponseInterface $response Response received
     * @param \Exception        $previous Previous exception
     * @param array             $ctx      Optional handler context.
     *
     * @return self
     */
    public static function create(\YoastSEO_Vendor\Psr\Http\Message\RequestInterface $request, \YoastSEO_Vendor\Psr\Http\Message\ResponseInterface $response = null, \Exception $previous = null, array $ctx = [])
    {
        if (!$response) {
            return new self('Error completing request', $request, null, $previous, $ctx);
        }
        $level = (int) \floor($response->getStatusCode() / 100);
        if ($level === 4) {
            $label = 'Client error';
            $className = \YoastSEO_Vendor\GuzzleHttp\Exception\ClientException::class;
        } elseif ($level === 5) {
            $label = 'Server error';
            $className = \YoastSEO_Vendor\GuzzleHttp\Exception\ServerException::class;
        } else {
            $label = 'Unsuccessful request';
            $className = __CLASS__;
        }
        $uri = $request->getUri();
        $uri = static::obfuscateUri($uri);
        // Client Error: `GET /` resulted in a `404 Not Found` response:
        // <html> ... (truncated)
        $message = \sprintf('%s: `%s %s` resulted in a `%s %s` response', $label, $request->getMethod(), $uri, $response->getStatusCode(), $response->getReasonPhrase());
        $summary = static::getResponseBodySummary($response);
        if ($summary !== null) {
            $message .= ":\n{$summary}\n";
        }
        return new $className($message, $request, $response, $previous, $ctx);
    }
    /**
     * Get a short summary of the response
     *
     * Will return `null` if the response is not printable.
     *
     * @param ResponseInterface $response
     *
     * @return string|null
     */
    public static function getResponseBodySummary(\YoastSEO_Vendor\Psr\Http\Message\ResponseInterface $response)
    {
        $body = $response->getBody();
        if (!$body->isSeekable()) {
            return null;
        }
        $size = $body->getSize();
        if ($size === 0) {
            return null;
        }
        $summary = $body->read(120);
        $body->rewind();
        if ($size > 120) {
            $summary .= ' (truncated...)';
        }
        // Matches any printable character, including unicode characters:
        // letters, marks, numbers, punctuation, spacing, and separators.
        if (\preg_match('/[^\\pL\\pM\\pN\\pP\\pS\\pZ\\n\\r\\t]/', $summary)) {
            return null;
        }
        return $summary;
    }
    /**
     * Obfuscates URI if there is an username and a password present
     *
     * @param UriInterface $uri
     *
     * @return UriInterface
     */
    private static function obfuscateUri($uri)
    {
        $userInfo = $uri->getUserInfo();
        if (\false !== ($pos = \strpos($userInfo, ':'))) {
            return $uri->withUserInfo(\substr($userInfo, 0, $pos), '***');
        }
        return $uri;
    }
    /**
     * Get the request that caused the exception
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
    /**
     * Get the associated response
     *
     * @return ResponseInterface|null
     */
    public function getResponse()
    {
        return $this->response;
    }
    /**
     * Check if a response was received
     *
     * @return bool
     */
    public function hasResponse()
    {
        return $this->response !== null;
    }
    /**
     * Get contextual information about the error from the underlying handler.
     *
     * The contents of this array will vary depending on which handler you are
     * using. It may also be just an empty array. Relying on this data will
     * couple you to a specific handler, but can give more debug information
     * when needed.
     *
     * @return array
     */
    public function getHandlerContext()
    {
        return $this->handlerContext;
    }
}
